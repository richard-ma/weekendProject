from src.aldous_broder import *
from src.grid import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    AldousBroder().on(grid)
    
    filename = "aldous_broder.png"
    grid.to_png(filename)