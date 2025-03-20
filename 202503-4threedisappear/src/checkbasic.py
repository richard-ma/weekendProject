from src.grid import *


class BasicCheck:
    def __init__(self):
        pass
    
    def on(self, grid: Grid, current: tuple):
        r, c = current
        v = grid[r][c].value()

        ret = [current]

        # South
        dr, dc = r+1, c
        while dr < grid._rows and grid[dr][dc].value() == v:
            ret.append((dr, dc))
            dr += 1
        # North
        dr, dc = r-1, c
        while dr >= 0 and grid[dr][dc].value() == v:
            ret.append((dr, dc))
            dr -= 1
        # East
        dr, dc = r, c+1
        while dc < grid._columns and grid[dr][dc].value() == v:
            ret.append((dr, dc))
            dc += 1
        # West
        dr, dc = r, c-1
        while dc >= 0 and grid[dr][dc].value() == v:
            ret.append((dr, dc))
            dc -= 1

        return ret