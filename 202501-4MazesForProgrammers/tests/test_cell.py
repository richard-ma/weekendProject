import unittest
from src.cell import *


class TestCell(unittest.TestCase):
    def setUp(self):
        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_init(self):
        c1 = Cell(1, 2)
        c2 = Cell(2, 3)