import unittest
from src.sidewinder import *
from src.grid import *


class TestSidewinder(unittest.TestCase):
    def setUp(self):
        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_binary_tree_on(self):
        g = Grid(4, 4)

        link_count = 0
        for cell in g.each_cell():
            if len(cell.links()) > 0:
                link_count += len(cell.links())
        self.assertEqual(link_count, 0)

        sw = Sidewinder()
        sw.on(g)

        link_count = 0
        for cell in g.each_cell():
            if len(cell.links()) > 0:
                link_count += len(cell.links())

        self.assertGreater(link_count, 0)
        # print(g) # show grid
        # g.to_png() # test draw to png