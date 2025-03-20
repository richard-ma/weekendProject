from src.cell import *
from src.grid import *
from src.checkbasic import *

if __name__ == "__main__":
    grid = Grid(5, 5)
    grid.load("tests/fixtures/grid_line.txt")
    print(grid)

    row, col = 2, 2
    print(row, col, grid[row][col].value())
    check = BasicCheck().on(grid, (row, col))
    print(check)