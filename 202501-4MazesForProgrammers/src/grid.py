import math
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
                if cell is not None:
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

    def to_png(self, filename, cell_size=10, wall_width=2):
        img_width = cell_size * self._columns
        img_height = cell_size * self._rows

        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_width+1, img_height+1), color=background)
        draw = ImageDraw.Draw(img)

        for mode in ['backgrounds', 'walls']:
            for cell in self.each_cell():
                x1 = cell._column * cell_size
                y1 = cell._row * cell_size
                x2 = (cell._column + 1) * cell_size
                y2 = (cell._row + 1) * cell_size

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

        img.save(filename)

    def background_color_for(self, cell):
        return None

    def deadends(self): # 死角计数
        l = []
        for cell in self.each_cell():
            if len(cell.links()) == 1:
                l.append(cell)
        return l


class DistanceGrid(Grid):
    def __init__(self, rows, columns):
        super().__init__(rows, columns)
        self._distances = None

    # change int value to string charactor
    def __i2c(self, i: int):
        charset = "0123456789abcdefghijklmnopqrstuvwxyz"
        return charset[i]
        
    def contents_of(self, cell):
        if self._distances and self._distances[cell] is not None:
            return self.__i2c(self._distances[cell])
        else:
            return super().contents_of(cell)


class ColoredGrid(Grid):
    def __init__(self, rows, columns):
        super().__init__(rows, columns)
        self._distances = None
        self._maximum = None

    def set_distances(self, distances):
        self._distances = distances
        farthest, self._maximum = distances.max()

    def background_color_for(self, cell):
        if not cell in self._distances.cells():
            return None
        distance = self._distances[cell]
        intensity = float(self._maximum - distance) / self._maximum
        dark = round(255 * intensity)
        bright = 128 + round(127 * intensity)
        return (dark, bright, dark)


class MaskedGrid(Grid):
    def __init__(self, mask):
        self._mask = mask
        super().__init__(self._mask._rows, self._mask._columns)

    def prepare_grid(self):
        ret = list()
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                if self._mask[r, c]:
                    line.append(Cell(r, c))
                else:
                    line.append(None)
            ret.append(line)
        return ret

    def random_cell(self):
        row, col = self._mask.random_location()
        return self[row][col]

    def size(self):
        return self._mask.count()


class PolarGrid(Grid):
    def to_png(self, filename, cell_size=10, wall_width=2):
        img_size = 2 * self._rows * cell_size
        
        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_size+1, img_size+1), color=background)
        draw = ImageDraw.Draw(img)
        center = img_size // 2
        
        for cell in self.each_cell():
            theta = 2 * math.pi / len(self._grid[cell._row])
            inner_radius = cell._row * cell_size
            outer_radius = (cell._row + 1) * cell_size
            theta_ccw = cell._column * theta
            theta_cw = (cell._column + 1) * theta

            ax = center + int(inner_radius * math.cos(theta_ccw))
            ay = center + int(inner_radius * math.sin(theta_ccw))
            bx = center + int(outer_radius * math.cos(theta_ccw))
            by = center + int(outer_radius * math.sin(theta_ccw))
            cx = center + int(inner_radius * math.cos(theta_cw))
            cy = center + int(inner_radius * math.sin(theta_cw))
            dx = center + int(outer_radius * math.cos(theta_cw))
            dy = center + int(outer_radius * math.sin(theta_cw))

            if not cell.is_linked(cell._north):
                draw.line((ax, ay, cx, cy), fill=wall, width=wall_width)
            if not cell.is_linked(cell._east):
                draw.line((cx, cy, dx, dy), fill=wall, width=wall_width)

            draw.ellipse((0, 0, img_size, img_size), outline=wall, width=wall_width)

        img.save(filename)