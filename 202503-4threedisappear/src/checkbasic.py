from src.grid import *


class BasicCheck:
    def __init__(self):
        pass
    
    def on(self, grid: Grid, current: tuple):
        y, x = current
        v = grid[x][y].value()

        ret = [current]

        dx, dy = x+1, y
        while dx < grid._columns-1 and grid[dx][dy].value() == v:
            ret.append((dx, dy))
            dx += 1
        dx, dy = x-1, y
        while dx > 0 and grid[dx][dy].value() == v:
            ret.append((dx, dy))
            dx -= 1
        dx, dy = x, y+1
        while dy < grid._rows-1 and grid[dx][dy].value() == v:
            ret.append((dx, dy))
            dy += 1
        dx, dy = x, y-1
        while dy > 0 and grid[dx][dy].value() == v:
            ret.append((dx, dy))
            dy -= 1

        return ret