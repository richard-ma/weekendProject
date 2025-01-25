import random


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