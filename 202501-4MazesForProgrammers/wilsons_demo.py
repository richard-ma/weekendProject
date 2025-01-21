from src.wilson import *
from src.grid import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    Wilsons().on(grid)

    filename = "wilsons.png"
    grid.to_png(filename)