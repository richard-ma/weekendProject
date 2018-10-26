#!/usr/bin/env python
# encoding: utf-8

import unittest

class Fib(object):

    def __init__(self, max):
        self.max = max

    # 每次迭代的初始化
    def __iter__(self):
        self.a = 0
        self.b = 1
        return self

    # 获得下一个元素，直到Stopiteration产生
    def __next__(self):
        fib = self.a
        if fib > self.max:
            raise StopIteration()

        self.a, self.b = self.b, self.a + self.b
        return fib

class TestFib(unittest.TestCase):

    def setUp(self):
        pass

    def tearDown(self):
        pass

    def test_iterator(self):
        f = Fib(10)
        ans = []
        for item in f:
            ans.append(item)

        self.assertEqual(
                [0, 1, 1, 2, 3, 5, 8],
                ans)

        ans = []
        f.max = 5
        for item in f:
            ans.append(item)

        self.assertEqual(
                [0, 1, 1, 2, 3, 5],
                ans)

if __name__ == '__main__':
    unittest.main()
