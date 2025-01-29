from src.grid import *
from src.recursive_devision import *


def draw_maze(png_filename, p=0, min_height=5, min_width=5):
    grid = ColoredGrid(25, 25)
    RecursiveDivision().on(grid, min_height=min_height, min_width=min_width, p=p)
    
    start = grid[grid._rows // 2][grid._columns // 2]
    grid.set_distances(start.distances())
    
    grid.to_png(png_filename)


if __name__ == "__main__":
    draw_maze("recursive_division.png")
    draw_maze("recursive_division_with_rooms.png", p=0.25, min_height=5, min_width=5)