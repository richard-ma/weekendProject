# src/memcached_server.py
import socket
import threading
import re
from typing import Tuple, Optional
from CachedData import CachedData  # 导入你的核心缓存类

class MemcachedServer:
    def __init__(self, host: str = '127.0.0.1', port: int = 11211):
        """
        初始化Memcached协议服务器
        :param host: 绑定地址
        :param port: 绑定端口（默认Memcached端口11211）
        """
        self.host = host
        self.port = port
        self.server_socket = None
        self.cache = CachedData()  # 核心缓存实例
        self.is_running = False
        self.lock = threading.RLock()  # 多线程安全锁
        self.client_count = 0  # 统计当前连接数

    def _parse_command(self, data: bytes) -> Tuple[str, list]:
        """
        解析客户端指令（简化版，仅处理核心指令）
        :param data: 客户端发送的字节数据
        :return: (指令名, 指令参数列表)
        """
        # 解码并去除首尾空白（\r\n）
        cmd_str = data.decode('utf-8').strip()
        if not cmd_str:
            return ('invalid', [])
        
        # 拆分指令（支持空格分隔）
        parts = re.split(r'\s+', cmd_str)
        cmd = parts[0].lower()  # 指令转小写
        args = parts[1:] if len(parts) > 1 else []
        return (cmd, args)

    def _handle_set(self, args: list, client_socket: socket.socket) -> None:
        """处理set指令：set key 0 0 val_len\r\nval\r\n"""
        if len(args) < 4:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        key = args[0]
        # 跳过flags(1)、expire(2)，取value长度(3)
        try:
            val_len = int(args[3])
        except ValueError:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        # 读取value（需读取 val_len + 2 字节，包含最后的\r\n）
        try:
            val_data = client_socket.recv(val_len + 2)
            if len(val_data) < val_len:
                client_socket.sendall(b'ERROR\r\n')
                return
            # 提取value（去掉最后的\r\n）
            value = val_data[:val_len].decode('utf-8')
        except Exception:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        # 写入缓存
        with self.lock:
            self.cache.set(key, value)
        client_socket.sendall(b'STORED\r\n')

    def _handle_get(self, args: list, client_socket: socket.socket) -> None:
        """处理get指令：get key"""
        if len(args) < 1:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        key = args[0]
        with self.lock:
            value = self.cache.get(key)
        
        if value is None:
            client_socket.sendall(b'END\r\n')
        else:
            # 响应格式：VALUE <key> <flags> <len>\r\n<val>\r\nEND\r\n
            val_len = len(value)
            resp = f'VALUE {key} 0 {val_len}\r\n{value}\r\nEND\r\n'
            client_socket.sendall(resp.encode('utf-8'))

    def _handle_delete(self, args: list, client_socket: socket.socket) -> None:
        """处理delete指令：delete key"""
        if len(args) < 1:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        key = args[0]
        with self.lock:
            exists = self.cache.exists(key)
            if exists:
                self.cache.delete(key)
                client_socket.sendall(b'DELETED\r\n')
            else:
                client_socket.sendall(b'NOT_FOUND\r\n')

    def _handle_exists(self, args: list, client_socket: socket.socket) -> None:
        """处理自定义exists指令：exists key"""
        if len(args) < 1:
            client_socket.sendall(b'ERROR\r\n')
            return
        
        key = args[0]
        with self.lock:
            if self.cache.exists(key):
                client_socket.sendall(b'EXISTS\r\n')
            else:
                client_socket.sendall(b'NOT_FOUND\r\n')

    def _handle_stats(self, client_socket: socket.socket) -> None:
        """处理stats指令：返回基础状态"""
        with self.lock:
            stats = [
                f'STAT pid {threading.get_ident()}',
                f'STAT curr_connections {self.client_count}',
                f'STAT total_keys {len(self.cache.keys())}',
                'END'
            ]
        resp = '\r\n'.join(stats) + '\r\n'
        client_socket.sendall(resp.encode('utf-8'))

    def _handle_client(self, client_socket: socket.socket, client_addr: Tuple[str, int]) -> None:
        """处理单个客户端连接（多线程）"""
        print(f'客户端连接：{client_addr}')
        with self.lock:
            self.client_count += 1
        
        try:
            while self.is_running:
                # 接收客户端数据（缓冲区1024字节）
                data = client_socket.recv(1024)
                if not data:
                    break  # 客户端关闭连接
                
                # 解析指令
                cmd, args = self._parse_command(data)
                
                # 处理不同指令
                if cmd == 'set':
                    self._handle_set(args, client_socket)
                elif cmd == 'get':
                    self._handle_get(args, client_socket)
                elif cmd == 'delete':
                    self._handle_delete(args, client_socket)
                elif cmd == 'exists':
                    self._handle_exists(args, client_socket)
                elif cmd == 'stats':
                    self._handle_stats(client_socket)
                elif cmd == 'quit':
                    break  # 退出连接
                else:
                    client_socket.sendall(b'ERROR\r\n')
        except Exception as e:
            print(f'客户端处理异常 {client_addr}：{e}')
        finally:
            # 关闭连接并更新统计
            client_socket.close()
            with self.lock:
                self.client_count -= 1
            print(f'客户端断开：{client_addr}')

    def start(self) -> None:
        """启动服务器"""
        # 创建TCP socket
        self.server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        # 复用地址和端口（避免重启时端口占用）
        self.server_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        self.server_socket.bind((self.host, self.port))
        self.server_socket.listen(5)  # 监听队列大小
        self.is_running = True
        
        print(f'Memcached协议服务器启动：{self.host}:{self.port}')
        print('支持指令：set/get/delete/exists/stats/quit')
        
        # 接受客户端连接（主线程）
        try:
            while self.is_running:
                client_socket, client_addr = self.server_socket.accept()
                # 启动子线程处理客户端
                client_thread = threading.Thread(
                    target=self._handle_client,
                    args=(client_socket, client_addr),
                    daemon=True  # 守护线程，主进程退出时自动结束
                )
                client_thread.start()
        except KeyboardInterrupt:
            print('\n服务器正在关闭...')
        finally:
            self.stop()

    def stop(self) -> None:
        """停止服务器"""
        self.is_running = False
        if self.server_socket:
            self.server_socket.close()
        print('服务器已关闭')

if __name__ == '__main__':
    # 启动服务器（默认127.0.0.1:11211）
    server = MemcachedServer()
    server.start()
