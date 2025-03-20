from src.grid import *
from src.checkbasic import *

import unittest


class TestBasicCheck(unittest.TestCase):
    def setUp(self):
        self._d = {
            'tests/fixtures/grid_cross_left.txt':      3,
            'tests/fixtures/grid_cross_right.txt':     3,
            'tests/fixtures/grid_cross.txt':           5,
            'tests/fixtures/grid_horizon.txt':         3,
            'tests/fixtures/grid_vertical.txt':        3,
            'tests/fixtures/grid_horizon_long.txt':    5,
            'tests/fixtures/grid_vertical_long.txt':   5,
            'tests/fixtures/grid_L_bottom_left.txt':   3,
            'tests/fixtures/grid_L_bottom_right.txt':  3,
            'tests/fixtures/grid_L_top_left.txt':      3,
            'tests/fixtures/grid_L_top_right.txt':     3,
            'tests/fixtures/grid_T_bottom.txt':        5,
            'tests/fixtures/grid_T_top.txt':           5,
        }
    
    def tearDown(self):
        return super().tearDown()
    
    def test_basic_check(self):
        grid = Grid(5, 5)

        for filename, answer in self._d.items():
            grid.load(filename)
            ret = BasicCheck().on(grid, (2, 2))
            self.assertEqual(len(ret), answer, f'filename: {filename} ret: {ret} answer: {answer}')