from src.aldous_broder import *
from src.grid import *


if __name__ == "__main__":
    times = 6
    for n in range(times):
        grid = ColoredGrid(20, 20)
        AldousBroder().on(grid)
        
        middle = grid[grid._rows//2][grid._columns//2]
        grid.set_distances(middle.distances())
        
        filename = "aldous_broder_colored_%02d.png" % (n)
        grid.to_png(filename)