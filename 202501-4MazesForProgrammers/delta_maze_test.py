from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    grid = TriangleGrid(10, 17)
    RecursiveBacktracker().on(grid)

    filename = "delta.png"
    grid.to_png(filename)