from src.cell import *
from src.grid import *

if __name__ == "__main__":
    grid = Grid(5, 5)
    grid.load("tests/fixtures/grid_line.txt")
    print(grid)