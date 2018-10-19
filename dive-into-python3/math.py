#!/usr/bin/env python
# encoding: utf-8

import math

import unittest

class TestMath(unittest.TestCase):

    """Test case docstring."""

    def setUp(self):
        pass

    def tearDown(self):
        pass

    def test_name(self):
        self.assertEqual(3.141592653589793, math.pi)
        self.assertEqual(1.0, math.sin(math.pi / 2))
        self.assertEqual(0.9999999999999999, math.tan(math.pi / 4))

if __name__ == '__main__':
    unittest.main()
