from src.grid import *
from src.binary_tree import *


if __name__ == "__main__":
    grid = DistanceGrid(5, 5)
    BinaryTree().on(grid)
    
    start = grid[0][0]
    
    distances = start.distances()
    new_start, distance = distances.max()
    
    new_distances = new_start.distances()
    goal, distances = new_distances.max()
    
    grid._distances = new_distances.path_to(goal)
    print(grid)