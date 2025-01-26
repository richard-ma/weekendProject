from src.distances import *


class Cell:
    def __init__(self, row: int, column: int):
        self._row = row
        self._column = column

        self._north = None
        self._south = None
        self._east = None
        self._west = None

        self._links = dict()

    def link(self, cell, bidi=True):
        self._links[cell] = True
        if bidi:
            cell.link(self, False)

    def unlink(self, cell, bidi=True):
        del self._links[cell]
        if bidi:
            cell.unlink(self, False)

    def links(self):
        return list(self._links.keys())

    def is_linked(self, cell):
        return cell in self._links.keys()

    def neighbors(self):
        ret = list()
        ret += [self._north] if self._north else []
        ret += [self._south] if self._south else []
        ret += [self._east] if self._east else []
        ret += [self._west] if self._west else []
        
        return ret

    def distances(self):
        distances = Distances(self)
        frontier = [self]

        while len(frontier) > 0:
            new_frontier = []
            for cell in frontier:
                for linked in cell.links():
                    if distances[linked] is not None:
                        continue
                    distances[linked] = distances[cell] + 1
                    new_frontier.append(linked)
            frontier = new_frontier

        return distances


class PolarCell(Cell):
    def __init__(self, row, column):
        super().__init__(row, column)
        self._cw = None
        self._ccw = None
        self._inward = None
        self._outward = []

    def neighbors(self):
        l = []

        if self._cw is not None:
            l.append(self._cw)
        if self._ccw is not None:
            l.append(self._ccw)
        if self._inward is not None:
            l.append(self._inward)
        l += self._outward
        
        return l

        
class HexCell(Cell):
    def __init__(self, row, column):
        super().__init__(row, column)
        self._northeast = None
        self._northwest = None
        self._southeast = None
        self._southwest = None
        
    def neighbors(self):
        l = []

        if self._north is not None:
            l.append(self._north)
        if self._northeast is not None:
            l.append(self._northeast)
        if self._northwest is not None:
            l.append(self._northwest)
        if self._south is not None:
            l.append(self._south)
        if self._southeast is not None:
            l.append(self._southeast)
        if self._southwest is not None:
            l.append(self._southwest)

        return l