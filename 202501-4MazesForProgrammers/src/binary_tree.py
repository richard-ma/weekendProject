import random
from src.cell import *
from src.grid import *


class BinaryTree:
    def __init__(self):
        pass

    def on(self, grid):
        for cell in grid.each_cell():
            neighbors = list()
            neighbors += [cell._north] if cell._north else []
            neighbors += [cell._east] if cell._east else []
            
            neighbor = random.choice(neighbors) if neighbors else None

            if neighbor:
                cell.link(neighbor)

# 上边和右边的墙随机打通，生成迷宫
# 起点在左下角，终点在右上角
# 缺点：第一行和最后一列永远是通路，缺乏变化，迷宫的复杂度较低