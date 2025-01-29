import random
from src.growing_tree import *
from src.grid import *


def growing_tree(choose_active_func, png_filename):
    grid = ColoredGrid(20, 20)
    start_at = grid.random_cell()
    GrowingTree().on(grid, choose_active_func=choose_active_func, start_at=start_at)

    grid.set_distances(start_at.distances())

    grid.to_png(png_filename)


if __name__ == "__main__":
    growing_tree(lambda active: random.choice(active), "growing_tree_random.png")
    growing_tree(lambda active: active[-1], "growing_tree_last.png")
    growing_tree(lambda active: active[-1] if random.randrange(2) == 0 else random.choice(active), "growing_tree_mix.png")