from src.kruskals import *
from src.grid import *


class SimpleOverCell(OverCell):
    def __init__(self, row, column, grid):
        super().__init__(row, column, grid)
        
    def neighbors(self):
        l = []
        
        if self._north is not None:
            l.append(self._north)
        if self._south is not None:
            l.append(self._south)
        if self._east is not None:
            l.append(self._east)
        if self._west is not None:
            l.append(self._west)

        return l

        
class PreconfiguredGrid(WeaveGrid):
    def __init__(self, rows, columns):
        super().__init__(rows, columns)
        
    def prepare_grid(self):
        ret = list()
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                line.append(SimpleOverCell(r, c, self))
            ret.append(line)
        return ret


if __name__ == "__main__":
    grid = PreconfiguredGrid(20, 20)
    state = Kruskals.State(grid)

    for _ in range(grid.size()):
        row = random.randrange(1, grid._rows - 1)
        column = random.randrange(1, grid._columns - 1)
        state.add_crossing(grid[row][column])

    Kruskals().on(grid, state)

    filename = "kruskals_weave.png"
    grid.to_png(filename, inset=0.2)