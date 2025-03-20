import math
import random
from src.cell import *

class Grid:
    def __init__(self, rows: int, columns: int):
        self._rows = rows
        self._columns = columns

        self._grid = []
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                line.append(Cell())
            self._grid.append(line)

    def __getitem__(self, idx):
        return self._grid[idx]

    def each_row(self):
        for row in self._grid:
            yield row
            
    def each_cell(self):
        for row in self._grid:
            for cell in row:
                if cell:
                    yield cell

    def contents_of(self, cell):
        return cell.value() 

    def __str__(self):
        output = "+" + "---+" * self._columns + "\n"
        
        for row in self.each_row():
            top = "|"
            bottom = "+"
            
            for cell in row:
                body = " %d " % (self.contents_of(cell))
                east_boundary = "|"
                top = top + body + east_boundary

                south_boundary = "---"
                corner = "+"
                bottom = bottom + south_boundary + corner
            
            output = output + top + "\n"
            output = output + bottom + "\n"
        
        return output
