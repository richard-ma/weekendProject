#!/usr/bin/env python
# encoding: utf-8

import unittest
from copy import copy

class TestList(unittest.TestCase):

    """Test case docstring."""

    def setUp(self):
        self.a_list = list(range(5))
        self.a_list_length = len(self.a_list)

    def tearDown(self):
        pass

    def test_slice(self):
        self.assertEqual(
                [1, 2],
                self.a_list[1:3])
        self.assertEqual(
                [1, 2, 3],
                self.a_list[1:-1])
        self.assertEqual(
                [0, 1, 2],
                self.a_list[0:3])
        self.assertEqual(
                [0, 1, 2],
                self.a_list[:3])
        self.assertEqual(
                [3, 4],
                self.a_list[3:])
        self.assertEqual(
                [0, 1, 2, 3, 4],
                self.a_list[:])

        # even item
        self.assertEqual(
                [0, 2, 4],
                self.a_list[0::2])
        # odd item
        self.assertEqual(
                [1, 3],
                self.a_list[1::2])

        # reverse list
        self.assertEqual(
                [4, 3, 2, 1, 0],
                self.a_list[::-1])

    def test_add(self):
        a_list = copy(self.a_list) # reset a_list

        self.assertEqual(
            [0, 1, 2, 3, 4, 5, 6],
            self.a_list + [5, 6])

        a_list = copy(self.a_list) # reset a_list
        a_list.append(True)
        self.assertEqual(
                [0, 1, 2, 3, 4, True],
                a_list)

        a_list = copy(self.a_list) # reset a_list
        a_list.extend(['four', 'omega'])
        self.assertEqual(
                [0, 1, 2, 3, 4, 'four', 'omega'],
                a_list)

        a_list = copy(self.a_list) # reset a_list
        a_list.insert(0, 'ooo')
        self.assertEqual(
                ['ooo', 0, 1, 2, 3, 4],
                a_list)

    def test_search_and_count(self):
        a_list = ['a', 'b', 'new', 'mpilgrim', 'new']
        self.assertEqual(
                3,
                a_list.index('mpilgrim'))
        self.assertEqual(
                2,
                a_list.count('new'))

    def test_remove(self):
        a_list = copy(self.a_list) # reset a_list
        del a_list[1]
        self.assertEqual(
                self.a_list_length - 1,
                len(a_list))

        a_list = copy(self.a_list) # reset a_list
        a_list.remove(3)
        self.assertEqual(
                self.a_list_length - 1,
                len(a_list))

        a_list = copy(self.a_list) # reset a_list
        item = a_list.pop()
        self.assertEqual(
                self.a_list[-1],
                item)
        self.assertEqual(
                self.a_list_length - 1,
                len(a_list))

        a_list = copy(self.a_list) # reset a_list
        item = a_list.pop(1)
        self.assertEqual(
                self.a_list[1],
                item)
        self.assertEqual(
                self.a_list_length - 1,
                len(a_list))

    def test_list_as_stack(self):
        a_list = copy(self.a_list) # reset a_list

        item = 'new'
        # push
        a_list.append(item)
        self.assertEqual(
                self.a_list_length + 1,
                len(a_list))
        #pop
        t = a_list.pop()
        self.assertEqual(
                self.a_list_length,
                len(a_list))
        self.assertEqual(t, item)

    def test_list_as_queue(self):
        a_list = copy(self.a_list) # reset a_list

        item = 'new'
        # push
        a_list.append(item)
        self.assertEqual(
                self.a_list_length + 1,
                len(a_list))
        #pop
        t = a_list.pop(0)
        self.assertEqual(
                self.a_list_length,
                len(a_list))
        self.assertEqual(t, self.a_list[0])


if __name__ == '__main__':
    unittest.main()
