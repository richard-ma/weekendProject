from random import randint
from PIL import Image, ImageDraw
from src.cell import *

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

    def __str__(self):
        output = "+" + "---+" * self._columns + "\n"
        
        for row in self.each_row():
            top = "|"
            bottom = "+"
            
            for cell in row:
                cell = cell if cell else Cell(-1, -1)
                body = "   "
                east_boundary = " " if (cell.is_linked(cell._east)) else "|"
                top = top + body + east_boundary

                south_boundary = "   " if (cell.is_linked(cell._south)) else "---"
                corner = "+"
                bottom = bottom + south_boundary + corner
            
            output = output + top + "\n"
            output = output + bottom + "\n"
        
        return output

    def to_png(self, filename, cell_size=10, wall_width=2):
        img_width = cell_size * self._columns
        img_height = cell_size * self._rows

        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_width+1, img_height+1), color=background)
        draw = ImageDraw.Draw(img)

        for cell in self.each_cell():
            x1 = cell._column * cell_size
            y1 = cell._row * cell_size
            x2 = (cell._column + 1) * cell_size
            y2 = (cell._row + 1) * cell_size

            if cell._north is None:
                draw.line((x1, y1, x2, y1), fill=wall, width=wall_width)
            if cell._west is None:
                draw.line((x1, y1, x1, y2), fill=wall, width=wall_width)

            if not cell.is_linked(cell._east):
                draw.line((x2, y1, x2, y2), fill=wall, width=wall_width)
            if not cell.is_linked(cell._south):
                draw.line((x1, y2, x2, y2), fill=wall, width=wall_width)

        img.save(filename)