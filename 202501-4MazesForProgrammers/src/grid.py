from random import randint
from cell import *

class Grid:
    def __init__(self, rows: int, columns: int):
        self._rows = rows
        self._columns = columns

        self._grid = self.prepare_grid() # 2D Array
        self.configure_cells()

    def prepare_grid(self):
        ret = list()
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                line.append(Cell(r, c))
            ret.append(line)
        return ret
    
    def configure_cells(self):
        for line in self._grid:
            for cell in line:
                row, col = cell._row, cell._column
                cell._north = self[row-1][col] if row-1 >= 0 else None
                cell._south = self[row+1][col] if row+1 < self._rows else None
                cell._east = self[row][col+1] if col+1 < self._columns else None
                cell._west = self[row][col-1] if col-1 >= 0 else None

    def __getitem__(self, idx):
        return self._grid[idx]

    def random_cell(self):
        r_row, r_col = randint(0, self._rows-1), randint(0, self._columns-1)
        return self[r_row][r_col]

    def size(self):
        return self._rows * self._columns

    def each_row(self):
        for row in self._grid:
            yield row
            
    def each_cell(self):
        for row in self._grid:
            for cell in row:
                if cell:
                    yield cell


if __name__ == "__main__":
    g = Grid(4, 4)
    print(g[0][0]._north)
    print(g[0][0]._west)
    print(g[0][0]._south._row, g[0][0]._south._column)
    print(g[0][0]._east._row, g[0][0]._east._column)
    print(g.random_cell())