import unittest
from src.cell import *


class TestCell(unittest.TestCase):
    def setUp(self):
        self.c1 = Cell(1, 2)
        self.c2 = Cell(2, 3)

        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_init(self):
        cell = Cell(1, 2)

    def test_set_and_get(self):
        self.assertEqual(self.c1._row, 1)
        self.assertEqual(self.c1._column, 2)
        self.assertEqual(self.c2._row, 2)
        self.assertEqual(self.c2._column, 3)

    def test_link_and_unlink(self):
        self.assertFalse(self.c1.is_linked(self.c2))
        self.assertFalse(self.c2.is_linked(self.c1))

        self.c1.link(self.c2)
        self.assertTrue(self.c1.is_linked(self.c2))
        self.assertTrue(self.c2.is_linked(self.c1))

        self.c1.unlink(self.c2)
        self.assertFalse(self.c1.is_linked(self.c2))
        self.assertFalse(self.c2.is_linked(self.c1))

        self.c1.link(self.c2)
        self.c2.unlink(self.c1)
        self.assertFalse(self.c1.is_linked(self.c2))
        self.assertFalse(self.c2.is_linked(self.c1))

    def test_links(self):
        self.c1.link(self.c2)
        self.assertTrue(self.c2 in self.c1.links())
        self.assertTrue(self.c1 in self.c2.links())