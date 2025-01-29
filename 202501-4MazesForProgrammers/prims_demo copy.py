from src.prims import *
from src.grid import *


if __name__ == "__main__":
    grid = ColoredGrid(20, 20)
    start_at = grid.random_cell()
    Prims().on(grid, start_at=start_at)

    grid.set_distances(start_at.distances())

    filename = "prims.png"
    grid.to_png(filename)