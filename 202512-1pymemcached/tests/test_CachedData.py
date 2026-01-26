# tests/test_CachedData.py
import unittest

# 从src目录导入CachedData类
from src.CachedData import CachedData

class TestCachedData(unittest.TestCase):
    """CachedData类的单元测试用例，覆盖所有核心方法"""

    def setUp(self):
        """每个测试方法执行前初始化全新的CachedData实例，避免用例间干扰"""
        self.cache = CachedData()

    def test_set_and_get(self):
        """测试set和get方法：新增键值对、覆盖已有键值对"""
        # 测试1：新增键值对
        self.cache.set("user:1001", {"name": "zhangsan", "age": 25})
        self.assertEqual(
            self.cache.get("user:1001"),
            {"name": "zhangsan", "age": 25},
            "新增键值对后get返回值不符"
        )

        # 测试2：覆盖已有键值对
        self.cache.set("user:1001", {"name": "lisi", "age": 30})
        self.assertEqual(
            self.cache.get("user:1001"),
            {"name": "lisi", "age": 30},
            "覆盖键值对后get返回值不符"
        )

    def test_get_nonexistent_key(self):
        """测试get方法：获取不存在的键返回None"""
        self.assertIsNone(
            self.cache.get("non_exist_key"),
            "获取不存在的键未返回None"
        )

    def test_delete(self):
        """测试delete方法：删除存在的键、删除不存在的键（无报错）"""
        # 测试1：删除存在的键
        self.cache.set("order:2001", 99.9)
        self.cache.delete("order:2001")
        self.assertFalse(
            self.cache.exists("order:2001"),
            "删除存在的键后exists仍返回True"
        )

        # 测试2：删除不存在的键（验证不抛出异常）
        try:
            self.cache.delete("non_exist_key")
        except Exception as e:
            self.fail(f"删除不存在的键时抛出异常：{e}")

    def test_exists(self):
        """测试exists方法：检查存在/不存在的键返回正确布尔值"""
        self.cache.set("product:3001", "phone")
        # 测试1：存在的键返回True
        self.assertTrue(
            self.cache.exists("product:3001"),
            "存在的键exists返回False"
        )
        # 测试2：不存在的键返回False
        self.assertFalse(
            self.cache.exists("product:9999"),
            "不存在的键exists返回True"
        )

    def test_keys(self):
        """测试keys方法：空缓存返回空列表、多键缓存返回所有键的列表"""
        # 测试1：空缓存返回空列表
        self.assertEqual(
            self.cache.keys(),
            [],
            "空缓存时keys未返回空列表"
        )

        # 测试2：多键缓存返回所有键（排序后验证，避免字典无序问题）
        test_keys = ["a", "b", "c"]
        test_values = [1, 2, 3]
        for k, v in zip(test_keys, test_values):
            self.cache.set(k, v)
        
        self.assertEqual(
            sorted(self.cache.keys()),
            sorted(test_keys),
            "多键缓存时keys返回的键列表不完整"
        )

    def test_mixed_operations(self):
        """测试混合操作：综合验证set/delete/get/exists/keys的逻辑一致性"""
        # 初始化数据
        self.cache.set("k1", "v1")
        self.cache.set("k2", "v2")

        # 验证初始状态
        self.assertEqual(len(self.cache.keys()), 2, "初始键数量不符")
        self.assertTrue(self.cache.exists("k1"), "初始状态k1应存在")

        # 删除k1后验证
        self.cache.delete("k1")
        self.assertEqual(len(self.cache.keys()), 1, "删除k1后键数量不符")
        self.assertIsNone(self.cache.get("k1"), "删除k1后get应返回None")

        # 新增k3后验证
        self.cache.set("k3", "v3")
        self.assertEqual(len(self.cache.keys()), 2, "新增k3后键数量不符")
        self.assertEqual(self.cache.get("k3"), "v3", "新增k3后get返回值不符")

if __name__ == '__main__':
    # 运行所有测试用例，显示详细执行结果
    unittest.main(verbosity=2)
