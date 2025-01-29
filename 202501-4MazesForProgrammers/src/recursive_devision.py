import random


class RecursiveDivision:
    def on(self, grid, min_height=1, min_width=1, p=0):
        self._grid = grid
        self._min_height = min_height
        self._min_width = min_width
        self._p = p
        
        for cell in self._grid.each_cell():
            for n in cell.neighbors():
                cell.link(n, False) # 做单向链接
        
        self.divide(0, 0, self._grid._rows, self._grid._columns)

    def divide(self, row, column, height, width):
        if height <= 1 or width <= 1 or \
            (height < self._min_height and width < self._min_width and random.random() < self._p):
            return
        
        if height > width:
            self.divide_horizontally(row, column, height, width)
        else:
            self.divide_vertically(row, column, height, width)

    def divide_horizontally(self, row, column, height, width):
        divide_south_of = random.randrange(height-1)
        passage_at = random.randrange(width)

        for x in range(width):
            if passage_at == x:
                continue
            
            cell = self._grid[row + divide_south_of, column + x]
            cell.unlink(cell._south)

        self.divide(row, column, divide_south_of + 1, width)
        self.divide(row + divide_south_of + 1, column, height - divide_south_of - 1, width)

    def divide_vertically(self, row, column, height, width):
        divide_east_of = random.randrange(width-1)
        passage_at = random.randrange(height)

        for y in range(height):
            if passage_at == y:
                continue
            
            cell = self._grid[row + y, column + divide_east_of]
            cell.unlink(cell._east)

        self.divide(row, column, height, divide_east_of + 1)
        self.divide(row, column + divide_east_of + 1, height, width - divide_east_of - 1)