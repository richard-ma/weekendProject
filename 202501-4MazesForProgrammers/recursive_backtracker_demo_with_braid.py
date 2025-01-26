from src.recursive_backtracker import *
from src.grid import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    RecursiveBacktracker().on(grid)
    grid.braid()

    filename = "recursive_backtracker_with_braid.png"
    grid.to_png(filename)