import math
import random
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
                if cell is not None:
                    row, col = cell._row, cell._column
                    cell._north = self[row-1][col] if row-1 >= 0 else None
                    cell._south = self[row+1][col] if row+1 < self._rows else None
                    cell._east = self[row][col+1] if col+1 < self._columns else None
                    cell._west = self[row][col-1] if col-1 >= 0 else None

    def __getitem__(self, idx):
        if type(idx) in (tuple, list):
            row, col = idx
            if 0 <= row < self._rows and 0 <= col < self._columns:
                return self._grid[row][col]
            else:
                return None
        else:
            return self._grid[idx]

    def random_cell(self):
        r_row, r_col = random.randint(0, self._rows-1), random.randint(0, self._columns-1)
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

    def contents_of(self, cell):
        return " "

    def __str__(self):
        output = "+" + "---+" * self._columns + "\n"
        
        for row in self.each_row():
            top = "|"
            bottom = "+"
            
            for cell in row:
                cell = cell if cell else Cell(-1, -1)
                body = " %c " % (self.contents_of(cell))
                east_boundary = " " if (cell.is_linked(cell._east)) else "|"
                top = top + body + east_boundary

                south_boundary = "   " if (cell.is_linked(cell._south)) else "---"
                corner = "+"
                bottom = bottom + south_boundary + corner
            
            output = output + top + "\n"
            output = output + bottom + "\n"
        
        return output

    def to_png(self, filename, cell_size=10, inset=0, wall_width=2):
        img_width = cell_size * self._columns
        img_height = cell_size * self._rows
        inset = int(cell_size * inset)

        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_width+1, img_height+1), color=background)
        draw = ImageDraw.Draw(img)

        for mode in ['backgrounds', 'walls']:
            for cell in self.each_cell():
                x = cell._column * cell_size
                y = cell._row * cell_size

                if inset > 0:
                    self.to_png_with_inset(draw, cell, mode, cell_size, wall, x, y, inset, wall_width)
                else:
                    self.to_png_without_inset(draw, cell, mode, cell_size, wall, x, y, wall_width)

        img.save(filename)

    def to_png_without_inset(self, draw, cell, mode, cell_size, wall, x, y, wall_width):
        x1, y1 = x, y
        x2 = x1 + cell_size
        y2 = y1 + cell_size

        if mode == 'backgrounds':
            color = self.background_color_for(cell)
            if color is not None:
                draw.rectangle((x1, y1, x2, y2), fill=color)
        else:
            if cell._north is None:
                draw.line((x1, y1, x2, y1), fill=wall, width=wall_width)
            if cell._west is None:
                draw.line((x1, y1, x1, y2), fill=wall, width=wall_width)

            if not cell.is_linked(cell._east):
                draw.line((x2, y1, x2, y2), fill=wall, width=wall_width)
            if not cell.is_linked(cell._south):
                draw.line((x1, y2, x2, y2), fill=wall, width=wall_width)

    def cell_coordinates_with_inset(self, x, y, cell_size, inset):
        x1, x4 = x, x + cell_size
        x2 = x1 + inset
        x3 = x4 - inset

        y1, y4, = y, y + cell_size
        y2 = y1 + inset
        y3 = y4 - inset

        return [x1, x2, x3, x4, y1, y2, y3, y4]

    def to_png_with_inset(self, draw, cell, mode, cell_size, wall, x, y, inset, wall_width):
        x1, x2, x3, x4, y1, y2, y3, y4 = self.cell_coordinates_with_inset(x, y, cell_size, inset)

        if mode == 'backgrounds':
            color = self.background_color_for(cell)
            if color is not None:
                draw.rectangle((x2, y2, x3, y3), fill=color)
        else:
            if cell.is_linked(cell._north):
                draw.line((x2, y1, x2, y2), fill=wall, width=wall_width)
                draw.line((x3, y1, x3, y2), fill=wall, width=wall_width)
            else:
                draw.line((x2, y2, x3, y2), fill=wall, width=wall_width)

            if cell.is_linked(cell._south):
                draw.line((x2, y3, x2, y4), fill=wall, width=wall_width)
                draw.line((x3, y3, x3, y4), fill=wall, width=wall_width)
            else:
                draw.line((x2, y3, x3, y3), fill=wall, width=wall_width)

            if cell.is_linked(cell._west):
                draw.line((x1, y2, x2, y2), fill=wall, width=wall_width)
                draw.line((x1, y3, x2, y3), fill=wall, width=wall_width)
            else:
                draw.line((x2, y2, x2, y3), fill=wall, width=wall_width)

            if cell.is_linked(cell._east):
                draw.line((x3, y2, x4, y2), fill=wall, width=wall_width)
                draw.line((x3, y3, x4, y3), fill=wall, width=wall_width)
            else:
                draw.line((x3, y2, x3, y3), fill=wall, width=wall_width)

    def background_color_for(self, cell):
        return None

    def deadends(self): # 死角计数
        l = []
        for cell in self.each_cell():
            if len(cell.links()) == 1:
                l.append(cell)
        return l

    def braid(self, p=1.0):
        deadends = self.deadends()
        random.shuffle(deadends)
        for cell in deadends:
            if len(cell.links()) != 1 or random.random() > p:
                continue
            
            neighbors = [n for n in cell.neighbors() if not cell.is_linked(n)]
            best = [n for n in neighbors if len(n.links()) == 1]
            best = neighbors if len(best) == 0 else best
            
            neighbor = random.choice(best)
            cell.link(neighbor)