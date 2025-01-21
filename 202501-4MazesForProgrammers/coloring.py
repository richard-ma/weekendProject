from src.grid import *
from src.binary_tree import *


if __name__ == "__main__":
    grid = ColoredGrid(25, 25)
    BinaryTree().on(grid)
    
    start = grid[grid._rows // 2][grid._columns // 2]
    grid.set_distances(start.distances())
    
    filename = "colorized.png"
    grid.to_png(filename)