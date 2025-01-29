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


class TriangleCell(Cell):
    def __init__(self, row, column):
        super().__init__(row, column)
        
    def upright(self):
        return (self._row + self._column) % 2 == 0

    def neighbors(self):
        l = []
        
        if self._west is not None:
            l.append(self._west)
        if self._east is not None:
            l.append(self._east)
        
        if (not self.upright()) and (self._north is not None):
            l.append(self._north)
        if (self.upright()) and (self._south is not None):
            l.append(self._south)

        return l


class WeightedCell(Cell):
    def __init__(self, row, column):
        super().__init__(row, column)
        self._weight = 1

    def distances(self):
        weights = Distances(self)
        pending = [self]

        while len(pending) > 0:
            pending.sort(key=lambda x: x._weight) # ASCENDING ORDER
            cell = pending.pop(0)

            for neighbor in cell.links():
                total_weight = weights[cell] + neighbor._weight
                if (weights[neighbor] is None) or (total_weight < weights[neighbor]):
                    pending.append(neighbor)
                    weights[neighbor] = total_weight

        return weights


class OverCell(Cell):
    def __init__(self, row, column, grid):
        super().__init__(row, column)
        self._grid = grid

    def neighbors(self):
        l = super().neighbors()
        if self.can_tunnel_north():
            l.append(self._north._north)
        if self.can_tunnel_south():
            l.append(self._south._south)
        if self.can_tunnel_east():
            l.append(self._east._east)
        if self.can_tunnel_west():
            l.append(self._west._west)
        
        return l

    def can_tunnel_north(self):
        return (self._north is not None) and (self._north._north is not None) and self._north.horizontal_passage()

    def can_tunnel_south(self):
        return (self._south is not None) and (self._south._south is not None) and self._south.horizontal_passage()

    def can_tunnel_east(self):
        return (self._east is not None) and (self._east._east is not None) and self._east.vertical_passage()

    def can_tunnel_west(self):
        return (self._west is not None) and (self._west._west is not None) and self._west.vertical_passage()

    def horizontal_passage(self):
        return self.is_linked(self._east) and self.is_linked(self._west) and \
            (not self.is_linked(self._north)) and (not self.is_linked(self._south))

    def vertical_passage(self):
        return self.is_linked(self._north) and self.is_linked(self._south) and \
            (not self.is_linked(self._east)) and (not self.is_linked(self._west))

    def link(self, cell, bidi=True):
        if self._north is not None and self._north == cell._south:
            neighbor = self._north
        elif self._south is not None and self._south == cell._north:
            neighbor = self._south
        elif self._east is not None and self._east == cell._west:
            neighbor = self._east
        elif self._west is not None and self._west == cell._east:
            neighbor = self._west
        else:
            neighbor = None

        if neighbor is not None:
            self._grid.tunnel_under(neighbor)
        else:
            super().link(cell, bidi)


class UnderCell(Cell):
    def __init__(self, over_cell):
        super().__init__(over_cell._row, over_cell._column)

        if over_cell.horizontal_passage():
            self._north = over_cell._north
            over_cell._north._south = self
            self._south = over_cell._south
            over_cell._south._north = self
            self.link(self._north)
            self.link(self._south)
        else:
            self._east = over_cell._east
            over_cell._east._west = self
            self._west = over_cell._west
            over_cell._west._east = self
            self.link(self._east)
            self.link(self._west)

    def horizontal_passage(self):
        return self._east is not None or self._west is not None
    
    def vertical_passage(self):
        return self._north is not None or self._south is not None