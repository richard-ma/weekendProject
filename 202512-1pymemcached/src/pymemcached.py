import socketserver
import threading

class CachedData:
    def __init__(self):
        self.store = dict()

    def set(self, key, value):
        self.store[key] = value

    def get(self, key):
        return self.store.get(key, None)

# 定义请求处理器类，继承自 BaseRequestHandler
class MyRequestHandler(socketserver.BaseRequestHandler):
    def handle(self):
        # self.request 是客户端的连接套接字
        # self.client_address 是客户端的地址 (ip, port)
        print(f"客户端 {self.client_address} 已连接。当前线程: {threading.current_thread().name}")

        # 与客户端进行通信
        while True:
            try:
                # 接收客户端发送的数据
                data = self.request.recv(1024).strip()
                if not data:
                    # 如果接收到空数据，表示客户端断开连接
                    print(f"客户端 {self.client_address} 已断开。")
                    break

                # 打印接收到的消息
                print(f"收到 {self.client_address}: {data.decode('utf-8')}")

                # 向客户端发送响应
                response = f"服务器收到: {data.decode('utf-8')}"
                self.request.sendall(response.encode('utf-8'))

            except ConnectionResetError:
                # 处理客户端异常断开连接
                print(f"客户端 {self.client_address} 异常断开。")
                break
            except Exception as e:
                print(f"处理请求时发生错误: {e}")
                break

        # 关闭客户端连接
        self.request.close()

# 创建服务器实例
if __name__ == "__main__":
    HOST, PORT = "localhost", 9999 

    # 使用 ThreadingTCPServer 来支持多客户端并发
    # 这会为每个连接创建一个新线程
    server = socketserver.ThreadingTCPServer((HOST, PORT), MyRequestHandler)

    # 可选：设置守护线程，这样主程序退出时服务器线程会自动结束
    server.daemon_threads = True

    # 可选：设置服务器地址重用，避免频繁重启时端口被占用
    server.allow_reuse_address = True

    print(f"服务器启动在 {HOST}:{PORT}，等待客户端连接...")

    try:
        # 服务器开始监听并处理请求
        server.serve_forever()
    except KeyboardInterrupt:
        print("\n服务器正在关闭...")
        server.shutdown()
        server.server_close()
        print("服务器已关闭。")