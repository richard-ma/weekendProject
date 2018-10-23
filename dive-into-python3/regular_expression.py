#!/usr/bin/env python
# encoding: utf-8

import unittest
import re

class TestRegularExpression(unittest.TestCase):

    """Test case docstring."""

    def setUp(self):
        pass

    def tearDown(self):
        pass

    # 字符串替换
    def test_replace(self):
        s = '100 ROAD'
        self.assertEqual(
                '100 RD.',
                s.replace('ROAD', 'RD.'))

    # 使用re.sub字符串替换
    def test_re_sub(self):
        s = '100 ROAD'
        self.assertEqual(
                '100 RD.',
                re.sub('ROAD$', 'RD.', s))
        # \b 表示空白字符
        self.assertEqual(
                '100 BROAD',
                re.sub('\\bROAD$', 'RD.', '100 BROAD'))
        self.assertEqual(
                '100 BROAD',
                re.sub(r'\bROAD$', 'RD.', '100 BROAD'))
        self.assertEqual(
                '100 BROAD RD. 3',
                re.sub(r'\bROAD\b', 'RD.', '100 BROAD ROAD 3'))



if __name__ == '__main__':
    unittest.main()
