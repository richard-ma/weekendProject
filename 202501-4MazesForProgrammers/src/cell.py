class Cell:
    def __init__(self, row: int, column: int):
        self._row = row
        self._column = column

        self._north = list()
        self._south = list()
        self._east = list()
        self._west = list()

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

    def is_links(self, cell):
        return cell in self._links.keys()

    def neighbors(self):
        ret = list()
        ret += self._north
        ret += self._south
        ret += self._east
        ret += self._west
        
        return ret