class Distances:
    def __init__(self, root):
        self.root = root
        self.cells = dict()
        self.cells[self.root] = 0

    def __getitem__(self, cell):
        return self.cells[cell] if cell in self.cells.keys() else None

    def __setitem__(self, cell, distance):
        self.cells[cell] = distance

    def cells(self):
        return list(self.cells.keys())