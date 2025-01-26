from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    grid = HexGrid(10, 10)
    RecursiveBacktracker().on(grid)

    filename = "hex.png"
    grid.to_png(filename)