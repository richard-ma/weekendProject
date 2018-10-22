#!/usr/bin/env python
# encoding: utf-8

import unittest
import copy

class TestListComprehension(unittest.TestCase):

    def setUp(self):
        self.a_list = list(range(5))

    def tearDown(self):
        pass

    # 映射出新list
    def test_map_to_new_list(self):
        self.assertEqual(
                [0, 2, 4, 6, 8],
                [item * 2 for item in self.a_list])

    # 获取全部文件的绝对路径
    def test_get_realpath_of_files(self):
        import os, glob
        os.chdir('/etc')
        self.assertTrue(
                '/etc/hosts' in [os.path.realpath(item) for item in glob.glob('*')])
        self.assertTrue(
                '/etc/grub.d' in [os.path.realpath(item) for item in glob.glob('*')])

    # dict解析
    def test_dict_comprehension(self):
        self.assertEqual(
                {0: 0, 1: 2, 2: 4, 3: 6, 4: 8},
                {item: item * 2 for item in self.a_list})

    # 交换字典的key与value
    def test_swap_key_and_value(self):
        a_dict = {item: item * 2 for item in self.a_list}
        # {0: 0, 1: 2, 2: 4, 3: 6, 4: 8}
        self.assertEqual(
                {0: 0, 2: 1, 4: 2, 6: 3, 8: 4},
                {value:key for key, value in a_dict.items()}) # .items()获取key，value对

    # 集合解析
    def test_set_comprehension(self):
        a_set = set(self.a_list)
        self.assertEqual(
                {0, 1, 4, 9, 16},
                {item ** 2 for item in a_set})


if __name__ == '__main__':
    unittest.main()
