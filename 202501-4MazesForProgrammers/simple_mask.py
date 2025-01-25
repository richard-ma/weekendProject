from src.mask import *
from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    mask = Mask(5, 5)
    mask[0, 0] = False
    mask[2, 2] = False
    mask[4, 4] = False

    grid = MaskedGrid(mask)
    RecursiveBacktracker().on(grid)
    
    print(grid)