from src.grid import *
from src.binary_tree import *
from src.sidewinder import *
from src.aldous_broder import *
from src.wilson import *
from src.hunt_and_kill import *


if __name__ == "__main__":
    algorithms = [BinaryTree(), Sidewinder(), AldousBroder(), Wilsons(), HuntAndKill()]
    
    tries = 100
    size = 20
    
    averages = {}
    for algorithm in algorithms:
        print("running %s" % (algorithm.__class__.__name__))

        deadend_counts = []
        for _ in range(tries):
            grid = Grid(size, size)
            algorithm.on(grid)
            deadend_counts.append(len(grid.deadends()))
        averages[algorithm] = sum(deadend_counts) / len(deadend_counts)
    
    total_cells = size ** 2
    print("Average dead-ends per %d*%d maze (%d cells)" % (size, size, total_cells))

    sorted_algorithms = {key: value for key, value in sorted(averages.items(), key=lambda item: item[1], reverse=True)}

    for algorithm in sorted_algorithms:
        percentage = averages[algorithm] * 100.0 / total_cells
        print("%14s : %3d/%3d (%d%%)" % (algorithm.__class__.__name__, averages[algorithm], total_cells, percentage))
