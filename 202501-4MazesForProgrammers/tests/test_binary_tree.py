import unittest
from src.binary_tree import *
from src.grid import *


class TestBinaryTree(unittest.TestCase):
    def setUp(self):
        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_binary_tree_on(self):
        g = Grid(4, 4)
        bt = BinaryTree()
        bt.on(g)

        link_count = 0
        for cell in g.each_cell():
            if len(cell.links()) > 0:
                link_count += len(cell.links())

        self.assertGreater(link_count, 0)