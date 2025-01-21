from src.grid import *
from src.binary_tree import *
from src.sidewinder import *


if __name__ == "__main__":
    row, col = 30, 30
    grid = Grid(row, col)
    binaryTree = BinaryTree()
    binaryTree.on(grid)
    grid.to_png("on_binary_tree.png")

    grid = Grid(row, col)
    sidewinder = Sidewinder()
    sidewinder.on(grid)
    grid.to_png("on_sidewinder.png")