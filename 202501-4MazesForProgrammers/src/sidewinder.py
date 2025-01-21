import random
from src.grid import *


class Sidewinder:
    def __init__(self):
        pass
    
    def on(self, grid):
        for row in grid.each_row():
            run = [] # 铺展

            for cell in row:
                run.append(cell)

                at_eastern_boundary = cell._east is None
                at_northern_boundary = cell._north is None

                should_close_out = at_eastern_boundary or ((not at_northern_boundary) and random.randint(0, 2) == 0)

                if should_close_out:
                    member = random.choice(run)
                    if member._north:
                        member.link(member._north)
                    run.clear()
                else:
                    cell.link(cell._east)