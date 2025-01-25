from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    grid = Grid(5, 5)
    grid[0][0]._east._west = None
    grid[0][0]._south._north = None

    grid[4][4]._west._east = None
    grid[4][4]._north._south = None

    RecursiveBacktracker().on(grid, start_at=grid[1][1])
    print(grid)