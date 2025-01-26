from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    grid = PolarGrid(8, 8)
    RecursiveBacktracker().on(grid)

    filename = "polar.png"
    grid.to_png(filename)