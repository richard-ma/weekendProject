import random
from src.cell import *
from src.grid import *


class BinaryTree:
    def __init__(self):
        pass

    def on(self, grid):
        for cell in grid.each_cell():
            neighbors = list()
            neighbors += cell._north if cell._north else []
            neighbors += cell._east if cell._east else []
            
            neighbor = random.choice(neighbors)

            if neighbor:
                cell.link(neighbor)