import random
from PIL import Image, ImageColor


class Mask:
    def __init__(self, rows, columns):
        self._rows = rows
        self._columns = columns
        self._bits = [[True for _ in range(self._rows)] for _ in range(self._columns)] # 用于记录位置是否启用
        
    def __setitem__(self, pos, is_on):
        self._bits[pos[0]][pos[1]] = is_on
    
    def __getitem__(self, pos):
        if 0 <= pos[0] < self._rows and 0 <= pos[1] < self._columns:
            return self._bits[pos[0]][pos[1]]
        else:
            return False

    def count(self):
        count = 0
        for row in range(self._rows):
            for column in range(self._columns):
                if self._bits[row][column]:
                    count += 1
        return count

    def random_location(self):
        while True:
            row = random.randrange(0, self._rows)
            col = random.randrange(0, self._columns)
            
            if self._bits[row][col]:
                return (row, col)

    @classmethod
    def from_text(cls, filename):
        lines = []
        with open(filename, 'r+') as f:
            lines = [s.strip() for s in f.readlines()]
        while len(lines[-1]) < 1:
            lines.pop()

        rows = len(lines)
        columns = len(lines[0])
        mask = cls(rows, columns)
        
        for row in range(mask._rows):
            for col in range(mask._columns):
                if lines[row][col] == 'x':
                    mask[row, col] = False
                else:
                    mask[row, col] = True
        
        return mask

    @classmethod
    def from_png(cls, filename):
        image = Image.open(filename)
        mask = cls(image.height, image.width)
        
        for row in range(mask._rows):
            for col in range(mask._columns):
                if image.getpixel((col, row)) == (0, 0, 0):
                    mask[row, col] = False
                else:
                    mask[row, col] = True
        
        return mask