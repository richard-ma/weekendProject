from src.mask import *
from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    mask = Mask.from_text("mask.txt")
    grid = MaskedGrid(mask)
    RecursiveBacktracker().on(grid)

    filename = "masked.png"
    grid.to_png(filename)