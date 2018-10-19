#!/usr/bin/env python
# encoding: utf-8

from copy import copy
import unittest

class TestSet(unittest.TestCase):

    """Test case docstring."""

    def setUp(self):
        self.a_set = {1, 2, 3}
        self.a_set_length = len(self.a_set)
        self.b_set = {2, 4, 6}
        self.b_set_length = len(self.b_set)

    def tearDown(self):
        pass

    def test_add(self):
        a_set = copy(self.a_set) # reset a_set
        a_set.add(8)
        self.assertEqual(
                self.a_set_length + 1,
                len(a_set))
        self.assertTrue(8 in a_set)

        # add twice
        a_set = copy(self.a_set) # reset a_set
        a_set.add(9)
        a_set.add(9) # added twice
        self.assertEqual(
                self.a_set_length + 1,
                len(a_set))
        self.assertTrue(9 in a_set)

        # use update to add more elements
        a_set = copy(self.a_set) # reset a_set
        b_set = copy(self.b_set) # reset b_set
        a_set.update(b_set) # A set or a list will fit for b_set position.
        self.assertEqual(
                self.a_set_length + self.b_set_length - 1, # 2
                len(a_set))
        self.assertEqual(
                {1, 2, 3, 4, 6},
                a_set)

    def test_remove(self):
        # discard
        a_set = copy(self.a_set) # reset a_set
        a_set.discard(2)
        self.assertEqual(
                self.a_set_length - 1,
                len(a_set))
        # discard not exsist element
        a_set = copy(self.a_set) # reset a_set
        a_set.discard(2)
        a_set.discard(2) # 2 is not exsist
        self.assertEqual(
                self.a_set_length - 1,
                len(a_set))

        # remove
        a_set = copy(self.a_set) # reset a_set
        a_set.remove(2)
        self.assertEqual(
                self.a_set_length - 1,
                len(a_set))
        # remove element which is not exsist will raise KeyError exception
        a_set = copy(self.a_set) # reset a_set
        a_set.remove(2)
        with self.assertRaises(KeyError):
            a_set.remove(2) # 2 will raise KeyError exception.
        self.assertEqual(
                self.a_set_length - 1,
                len(a_set))

        # pop
        a_set = copy(self.a_set) # reset a_set
        a_set.pop()
        self.assertEqual(
                self.a_set_length - 1,
                len(a_set))

        # clear
        a_set = copy(self.a_set) # reset a_set
        a_set.clear()
        self.assertEqual(
                0,
                len(a_set))

    # 集合运算
    def test_operation(self):
        self.assertEqual(
                {1, 2, 3, 4, 6},
                self.a_set.union(self.b_set)) # 并集
        self.assertEqual(
                {2},
                self.a_set.intersection(self.b_set)) # 交集
        self.assertEqual(
                {1, 3},
                self.a_set.difference(self.b_set)) # 差集
        self.assertEqual(
                {1, 3, 4, 6},
                self.a_set.symmetric_difference(self.b_set))

    # 子集和超集判断
    def test_subset_and_superset(self):
        a_set = {1, 2}
        b_set = {1, 2, 3}
        self.assertTrue(a_set.issubset(b_set))
        self.assertFalse(b_set.issubset(a_set))
        self.assertFalse(a_set.issuperset(b_set))
        self.assertTrue(b_set.issuperset(a_set))


if __name__ == '__main__':
    unittest.main()
