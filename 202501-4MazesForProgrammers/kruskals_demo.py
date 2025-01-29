from src.kruskals import *
from src.grid import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    Kruskals().on(grid)

    filename = "kruskals.png"
    grid.to_png(filename)