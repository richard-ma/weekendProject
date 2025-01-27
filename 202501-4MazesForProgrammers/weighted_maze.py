from src.grid import *
from src.recursive_backtracker import *


if __name__ == "__main__":
    grid = WeightedGrid(10, 10)
    RecursiveBacktracker().on(grid)
    grid.braid(0.5) # 剔除一半死角（编排迷宫）
    start, finish = grid[0][0], grid[-1][-1]
    
    grid.set_distances(start.distances().path_to(grid[-1][-1]))
    filename = "original.png"
    grid.to_png(filename)

    lava = random.choice(grid._distances.cells())
    lava._weight = 50
    
    grid.set_distances(start.distances().path_to(finish))
    filename = "rerouted.png"
    grid.to_png(filename)