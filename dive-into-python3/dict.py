#!/usr/bin/env python
# encoding: utf-8

import unittest
from copy import copy

class TestDict(unittest.TestCase):

    def setUp(self):
        self.a_dict = {
                'one': 1,
                'two': 2,
                'three': 3,
                }

    def tearDown(self):
        pass

    def test_change_value(self):
        a_dict = copy(self.a_dict)
        a_dict['one'] = 3
        self.assertEqual(
                3,
                a_dict['one'])

    # 当key不存在时使用默认值
    def test_get_value_with_default(self):
        self.assertEqual(
                1,
                self.a_dict.get('one'))
        # a_dict don't have key 'four'
        a_dict = copy(self.a_dict)
        self.assertEqual(
                4,
                a_dict.get('four', 4)) # use default value 4
        a_dict['four'] = 55 # add 'four' to a_dict
        self.assertEqual(
                55,
                a_dict.get('four', 4)) # use a_dict['four'] value 55

    def test_get_keys(self):
        self.assertEqual(
                ['one', 'two', 'three'],
                list(self.a_dict.keys())) # muse convert to list


if __name__ == '__main__':
    unittest.main()
