from src.recursive_backtracker import *
from src.grid import *


if __name__ == "__main__":
    grid = WeaveGrid(20, 20)
    RecursiveBacktracker().on(grid)

    filename = "weave_maze.png"
    grid.to_png(filename)