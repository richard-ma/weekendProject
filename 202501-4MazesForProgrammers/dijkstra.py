from src.binary_tree import *
from src.grid import *


if __name__ == "__main__":
    grid = DistanceGrid(5, 5)
    BinaryTree().on(grid)

    start = grid[0][0]
    distances = start.distances()
    grid._distances = distances
    
    print(grid)