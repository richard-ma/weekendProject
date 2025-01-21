import unittest
from src.grid import *


class TestGrid(unittest.TestCase):
    def setUp(self):
        self.rows, self.cols = 4, 4
        self.g = Grid(self.rows, self.cols)
        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_init(self):
        g = Grid(4, 4)
        self.assertIsInstance(g, Grid)

    def test_size(self):
        self.assertEqual(self.g.size(), self.rows * self.cols)

    def test_random(self):
        r_cell1 = self.g.random_cell()

        self.assertIsInstance(r_cell1, Cell)
        self.assertEqual(r_cell1, self.g[r_cell1._row][r_cell1._column])

    def test_each_row(self):
        for idx, row in enumerate(self.g.each_row()):
            self.assertEqual(row, self.g[idx])

    def test_each_cell(self):
        for cell in self.g.each_cell():
            self.assertEqual(cell, self.g[cell._row][cell._column])

    def test_neighbors(self):
        cell_center = self.g[1][1]
        cell_north = self.g[0][1]
        cell_south = self.g[2][1]
        cell_east = self.g[1][2]
        cell_west = self.g[1][0]

        self.assertEqual(cell_center._north, cell_north)
        self.assertEqual(cell_center._south, cell_south)
        self.assertEqual(cell_center._east, cell_east)
        self.assertEqual(cell_center._west, cell_west)

        self.assertEqual(cell_center.neighbors(), [cell_north, cell_south, cell_east, cell_west])

        self.assertEqual(len(self.g[0][0].neighbors()), 2)
        self.assertEqual(len(self.g[0][1].neighbors()), 3)
        