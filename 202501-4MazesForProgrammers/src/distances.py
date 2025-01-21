class Distances:
    def __init__(self, root):
        self.root = root
        self._cells = dict()
        self._cells[self.root] = 0

    def __getitem__(self, cell):
        return self._cells[cell] if cell in self._cells.keys() else None

    def __setitem__(self, cell, distance):
        self._cells[cell] = distance

    def cells(self):
        return list(self._cells.keys())

    def path_to(self, goal):
        current = goal

        breadcrumbs = Distances(self.root)
        breadcrumbs[current] = self._cells[current]

        while current != self.root:
            for neighbor in current.links():
                if self._cells[neighbor] < self._cells[current]:
                    breadcrumbs[neighbor] = self._cells[neighbor]
                    current = neighbor
                    break
        
        return breadcrumbs

    def max(self):
        max_distance = 0
        max_cell = self.root
        
        for cell, distance in self._cells.items():
            if distance > max_distance:
                max_cell = cell
                max_distance = distance
        
        return (max_cell, max_distance)