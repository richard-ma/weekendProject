from src.mask import *
from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    filename = "maze_test.png"
    mask = Mask.from_png(filename)
    grid = MaskedGrid(mask)
    RecursiveBacktracker().on(grid)

    filename = "image_mask.png"
    grid.to_png(filename)