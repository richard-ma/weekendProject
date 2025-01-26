import math
import random
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
    def __init__(self, rows):
        super().__init__(rows, 1)

    def __getitem__(self, idx):
        if type(idx) in (tuple, list):
            row, col = idx
            if 0 <= row < self._rows:
                return self._grid[row][col%len(self._grid[row])]
            else:
                return None
        else:
            return self._grid[idx]
    
    def prepare_grid(self):
        rows = []
        
        row_height = 1.0 / self._rows
        rows.append([PolarCell(0, 0)]) # row[0]

        for row in range(1, self._rows):
            radius = float(row) / self._rows
            circumference = 2 * math.pi * radius
            
            previous_count = len(rows[row-1])
            estimated_cell_width = circumference / previous_count
            ratio = round(estimated_cell_width / row_height)

            cells = previous_count * ratio
            rows.append([PolarCell(row, col) for col in range(cells)])
        
        return rows

    def configure_cells(self):
        for cell in self.each_cell():
            row, col = cell._row, cell._column
            
            if row > 0:
                cell._cw = self[row, col+1]
                cell._ccw = self[row, col-1]

                ratio = len(self._grid[row]) / len(self._grid[row-1])
                parent = self._grid[row-1][int(col/ratio)]
                parent._outward.append(cell)
                cell._inward = parent

    def random_cell(self):
        row = random.randrange(0, self._rows)
        col = random.randrange(0, len(self._grid[row]))
        return self._grid[row][col]
    
    def to_png(self, filename, cell_size=10, wall_width=2):
        img_size = 2 * self._rows * cell_size
        
        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_size+1, img_size+1), color=background)
        draw = ImageDraw.Draw(img)
        center = img_size // 2
        
        for cell in self.each_cell():
            if cell._row == 0:
                continue

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

            if not cell.is_linked(cell._inward):
                draw.line((ax, ay, cx, cy), fill=wall, width=wall_width)
            if not cell.is_linked(cell._cw):
                draw.line((cx, cy, dx, dy), fill=wall, width=wall_width)

            draw.ellipse((0, 0, img_size, img_size), outline=wall, width=wall_width)

        img.save(filename)

        
class HexGrid(Grid):
    def prepare_grid(self):
        ret = list()
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                line.append(HexCell(r, c))
            ret.append(line)
        return ret

    def configure_cells(self):
        for cell in self.each_cell():
            row, col = cell._row, cell._column

            if col % 2 == 0:
                north_diagonal = row - 1
                south_diagonal = row
            else:
                north_diagonal = row
                south_diagonal = row + 1
            
            cell._northwest = self[north_diagonal, col-1]
            cell._north = self[row-1, col]
            cell._northeast = self[north_diagonal, col+1]
            cell._southwest = self[south_diagonal, col-1]
            cell._south= self[row+1, col]
            cell._southeast = self[south_diagonal, col+1]

    def to_png(self, filename, cell_size=10, wall_width=2):
        a_size = cell_size / 2.0
        b_size = cell_size * math.sqrt(3) / 2.0
        width = cell_size * 2
        height = b_size * 2

        img_width = int(3 * a_size * self._columns + a_size + 0.5)
        img_height = int(height * self._rows + b_size + 0.5)

        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_width+1, img_height+1), color=background)
        draw = ImageDraw.Draw(img)
        
        for mode in ['backgrounds', 'walls']:
            for cell in self.each_cell():
                cx = cell_size + 3 * cell._column * a_size
                cy = b_size + cell._row * height
                cy += b_size if cell._column % 2 == 1 else 0

                # f/n: far near
                # n/s/w/e: north south west east
                x_fw = int(cx - cell_size)
                x_nw = int(cx - a_size)
                x_ne = int(cx + a_size)
                x_fe = int(cx + cell_size)

                # m: middle
                y_n = int(cy - b_size)
                y_m = int(cy)
                y_s = int(cy + b_size)

                if mode == 'backgrounds':
                    color = self.background_color_for(cell)
                    if color is not None:
                        points = [
                            [x_fw, y_m], [x_nw, y_n], [x_ne, y_n],
                            [x_fe, y_m], [x_ne, y_s], [x_nw, y_s]
                        ]
                        draw.polygon(points, fill=color)
                else:
                    if cell._southwest is None:
                        draw.line((x_fw, y_m, x_nw, y_s), fill=wall, width=wall_width)
                    if cell._northwest is None:
                        draw.line((x_fw, y_m, x_nw, y_n), fill=wall, width=wall_width)
                    if cell._north is None:
                        draw.line((x_nw, y_n, x_ne, y_n), fill=wall, width=wall_width)

                    if not cell.is_linked(cell._northeast):
                        draw.line((x_ne, y_n, x_fe, y_m), fill=wall, width=wall_width)
                    if not cell.is_linked(cell._southeast):
                        draw.line((x_fe, y_m, x_ne, y_s), fill=wall, width=wall_width)
                    if not cell.is_linked(cell._south):
                        draw.line((x_ne, y_s, x_nw, y_s), fill=wall, width=wall_width)
                    
        img.save(filename)


class TriangleGrid(Grid):
    def __init__(self, rows, columns):
        super().__init__(rows, columns)
        
    def prepare_grid(self):
        ret = list()
        for r in range(self._rows):
            line = list()
            for c in range(self._columns):
                line.append(TriangleCell(r, c))
            ret.append(line)
        return ret

    def configure_cells(self):
        for cell in self.each_cell():
            row, col = cell._row, cell._column
            
            cell._west = self[row, col - 1]
            cell._east = self[row, col + 1]

            if cell.upright():
                cell._south = self[row + 1, col]
            else:
                cell._north = self[row - 1, col]

    def to_png(self, filename, cell_size=10, wall_width=2):
        half_width = cell_size / 2.0
        height = cell_size * math.sqrt(3) / 2.0
        half_height = height / 2.0
        
        img_width = int(cell_size * (self._columns + 1) / 2.0)
        img_height = int(height * self._rows)

        background = 'white'
        wall = 'black'

        img = Image.new('RGB', (img_width+1, img_height+1), color=background)
        draw = ImageDraw.Draw(img)
        
        for mode in ['backgrounds', 'walls']:
            for cell in self.each_cell():
                cx = half_width + cell._column * half_width
                cy = half_height + cell._row * height

                west_x = int(cx - half_width)
                mid_x = int(cx)
                east_x = int(cx + half_width)

                if cell.upright():
                    apex_y = int(cy - half_height)
                    base_y = int(cy + half_height)
                else:
                    apex_y = int(cy + half_height)
                    base_y = int(cy - half_height)


                if mode == 'backgrounds':
                    color = self.background_color_for(cell)
                    if color is not None:
                        points = [
                            [west_x, base_y], [mid_x, apex_y], [east_x, base_y]
                        ]
                        draw.polygon(points, fill=color)
                else:
                    if cell._west is None:
                        draw.line((west_x, base_y, mid_x, apex_y), fill=wall, width=wall_width)
                    if not cell.is_linked(cell._east):
                        draw.line((east_x, base_y, mid_x, apex_y), fill=wall, width=wall_width)

                    no_south = cell.upright() and cell._south is None
                    not_linked = (not cell.upright()) and (not cell.is_linked(cell._north))
                    if no_south or not_linked:
                        draw.line((east_x, base_y, west_x, base_y), fill=wall, width=wall_width)
                    
        img.save(filename)