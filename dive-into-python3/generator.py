#!/usr/bin/env python
# encoding: utf-8

import unittest

class TestGenerator(unittest.TestCase):

    def setUp(self):
        pass

    def tearDown(self):
        pass

    # 斐波那契数列生成器
    def test_fib_generator(self):
        def fib(max):
            a, b = 0, 1
            while a < max:
                yield a
                a, b = b, a+b

        self.assertEqual(
                [0, 1, 1, 2, 3, 5, 8],
                list(fib(10)))

if __name__ == '__main__':
    unittest.main()
