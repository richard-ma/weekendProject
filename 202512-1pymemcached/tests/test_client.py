# tests/test_memcached_server.py
import unittest
import memcache
import time

class TestMemcachedServer(unittest.TestCase):
    """Memcached协议Server的单元测试用例（基于python-memcached客户端）"""
    
    @classmethod
    def setUpClass(cls):
        """所有测试用例执行前初始化：连接Memcached Server"""
        # 连接本地Memcached Server（默认127.0.0.1:11211）
        try:
            cls.mc = memcache.Memcache(
                ['127.0.0.1:11211'],
                debug=0,  # 关闭debug输出，避免干扰测试
                timeout=5  # 连接超时5秒
            )
            # 验证连接是否成功
            cls.mc.set('_test_ping', 'pong', time=1)
            cls.assertEqual(cls.mc.get('_test_ping'), 'pong')
            cls.mc.delete('_test_ping')
        except Exception as e:
            raise RuntimeError(
                "无法连接Memcached Server，请先启动src/memcached_server.py！"
                f"错误信息：{e}"
            )

    @classmethod
    def tearDownClass(cls):
        """所有测试用例执行后清理：关闭客户端连接"""
        # memcache.Client无显式关闭方法，清空测试数据即可
        cls.mc.flush_all()  # 清空所有缓存数据

    def setUp(self):
        """每个测试用例执行前：清空测试数据，避免干扰"""
        self.test_key = 'test_key_' + str(int(time.time() * 1000))  # 唯一测试Key
        self.test_value = 'test_value_123456'

    def test_set_and_get(self):
        """测试set + get指令：新增键值对、覆盖键值对"""
        # 1. 测试新增键值对
        set_result = self.mc.set(self.test_key, self.test_value)
        self.assertTrue(set_result, "set指令执行失败")
        
        get_result = self.mc.get(self.test_key)
        self.assertEqual(
            get_result, self.test_value,
            f"get指令返回值错误：预期{self.test_value}，实际{get_result}"
        )

        # 2. 测试覆盖已有键值对
        new_value = 'new_test_value_789'
        set_result = self.mc.set(self.test_key, new_value)
        self.assertTrue(set_result, "覆盖set指令执行失败")
        
        get_result = self.mc.get(self.test_key)
        self.assertEqual(
            get_result, new_value,
            f"覆盖后get指令返回值错误：预期{new_value}，实际{get_result}"
        )

    def test_get_nonexistent_key(self):
        """测试get指令：获取不存在的键返回None"""
        nonexistent_key = 'nonexistent_key_' + str(int(time.time() * 1000))
        get_result = self.mc.get(nonexistent_key)
        self.assertIsNone(
            get_result,
            f"获取不存在的键未返回None，实际返回：{get_result}"
        )

    def test_delete(self):
        """测试delete指令：删除存在的键、删除不存在的键"""
        # 1. 测试删除存在的键
        self.mc.set(self.test_key, self.test_value)
        delete_result = self.mc.delete(self.test_key)
        self.assertTrue(delete_result, "删除存在的键失败")
        
        get_result = self.mc.get(self.test_key)
        self.assertIsNone(
            get_result,
            f"删除后get仍返回值：{get_result}"
        )

        # 2. 测试删除不存在的键（应返回False，无异常）
        nonexistent_key = 'nonexistent_key_' + str(int(time.time() * 1000))
        try:
            delete_result = self.mc.delete(nonexistent_key)
            self.assertFalse(
                delete_result,
                f"删除不存在的键应返回False，实际返回：{delete_result}"
            )
        except Exception as e:
            self.fail(f"删除不存在的键时抛出异常：{e}")

    def test_flush_all(self):
        """测试flush_all指令：清空所有缓存数据"""
        # 先写入多条测试数据
        keys = [f'flush_test_key_{i}' for i in range(3)]
        values = [f'flush_test_value_{i}' for i in range(3)]
        for k, v in zip(keys, values):
            self.mc.set(k, v)
        
        # 验证写入成功
        for k, v in zip(keys, values):
            self.assertEqual(self.mc.get(k), v, f"flush前{k}值错误")
        
        # 执行flush_all
        flush_result = self.mc.flush_all()
        self.assertTrue(flush_result, "flush_all指令执行失败")
        
        # 验证所有数据被清空
        for k in keys:
            self.assertIsNone(self.mc.get(k), f"flush后{k}未被清空")

    def test_stats(self):
        """测试stats指令：获取服务器状态（基础验证）"""
        # memcache.Client的stats方法返回字典格式的状态数据
        stats = self.mc.get_stats()
        # get_stats返回[(server, stats_dict), ...]，取第一个服务器的状态
        self.assertTrue(len(stats) > 0, "stats指令返回空")
        
        server, stats_dict = stats[0]
        self.assertEqual(server, '127.0.0.1:11211', "stats返回的服务器地址错误")
        # 验证核心状态字段存在
        self.assertIn('curr_connections', stats_dict, "stats缺少curr_connections字段")
        self.assertIn('total_keys', stats_dict, "stats缺少total_keys字段")

if __name__ == '__main__':
    # 运行所有测试用例，显示详细结果
    unittest.main(verbosity=2)
