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