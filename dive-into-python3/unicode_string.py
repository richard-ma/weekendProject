#!/usr/bin/env python
# encoding: utf-8

import unittest

class TestString(unittest.TestCase):

    """Test case docstring."""

    def setUp(self):
        pass

    def tearDown(self):
        pass

    # 显示有中文的字符串长度
    def test_len(self):
        s = "深入理解python"
        self.assertEqual(10, len(s))
        self.assertEqual("深", s[0])

    # 使用format格式化变量输出
    def test_format(self):
        username = 'mark'
        password = 'password'

        self.assertEqual(
                "mark's password is password",
                "{0}'s password is {1}".format(username, password))

    # 使用format格式化list输出
    def test_format_list(self):
        si_suffixes = ['KB', 'MB']
        self.assertEqual(
                "1000KB = 1MB",
                "1000{0[0]} = 1{0[1]}".format(si_suffixes))

    # 使用format格式化浮点数输出
    def test_format_float(self):
        self.assertEqual(
                '698.2 GB',
                '{0:.1f} {1}'.format(698.24, 'GB'))

    # 将多行文本转换为数组
    def test_splitlines(self):
        s = '''first line
        second line'''
        lines = s.splitlines()
        self.assertEqual(2, len(lines))
        self.assertEqual(
                'first line',
                lines[0])

    # 大小写转换
    def test_switch_case(self):
        lower = 'case'
        upper = 'CASE'

        self.assertEqual(lower, upper.lower())
        self.assertEqual(upper, lower.upper())

    # 字符计数
    def test_count(self):
        s = 'hoheoelolo'
        self.assertEqual(4, s.count('o'))

    # 将query string转换为dict
    def test_query_string_to_dict(self):
        query_string = 'user=hello&password=world'
        a_list = query_string.split('&')
        # dict([[1, 1], [2, 2]]) == {1: 1, 2: 2}
        a_dict = dict([v.split('=') for v in a_list])

        self.assertEqual(
                {'user': 'hello', 'password': 'world'},
                a_dict)

    # Byte
    def test_byte(self):
        byte_string = b'abcd'
        self.assertIsInstance(byte_string, bytes)
        self.assertEqual(97, byte_string[0])

        # length
        byte_string += b'\xff'
        self.assertEqual(5, len(byte_string))

        # bytearray 使用bytearray转换后的对象可以像list一样给元素赋值
        barr = bytearray(byte_string)
        barr[0] = 102
        byte_string = bytes(barr)
        # TODO testcase

    # 字符编码
    # 字符编码实际就是string和bytes对象之间的相互转换方法
    def test_coded(self):
        a_string = '深入python'
        byte_string = b'\xe6\xb7\xb1\xe5\x85\xa5python'
        self.assertEqual( # encode string -> bytes
                byte_string,
                a_string.encode('utf-8'))
        self.assertEqual( # decode bytes -> string
                a_string,
                byte_string.decode('utf-8'))


if __name__ == '__main__':
    unittest.main()
