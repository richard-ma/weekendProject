from src.recursive_backtracker import *
from src.grid import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    RecursiveBacktracker().on(grid)

    filename = "recursive_backtracker_with_inset.png"
    grid.to_png(filename, inset=0.1, wall_width=1)