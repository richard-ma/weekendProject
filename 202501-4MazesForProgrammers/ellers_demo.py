from src.ellers import *
from src.sidewinder import *
from src.grid import *


def draw_colored_maze(algrithm_obj, png_filename):
    grid = ColoredGrid(20, 20)
    start_at = grid[grid._rows - 1][grid._columns // 2]
    algrithm_obj.on(grid)

    grid.set_distances(start_at.distances())

    grid.to_png(png_filename)


if __name__ == "__main__":
    draw_colored_maze(Ellers(), "ellers_colored.png")
    draw_colored_maze(Sidewinder(), "sidewinder_colored.png")