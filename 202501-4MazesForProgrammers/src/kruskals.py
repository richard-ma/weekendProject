import random


class Kruskals:
    class State:
        def __init__(self, grid):
            self._grid = grid
            self._neighbors = []
            self._set_for_cell = {}
            self._cells_in_set = {}
            
            for cell in self._grid.each_cell():
                s = len(self._set_for_cell)
                self._set_for_cell[cell] = s
                self._cells_in_set[s] = [cell]

                if cell._south is not None:
                    self._neighbors.append([cell, cell._south])
                if cell._east is not None:
                    self._neighbors.append([cell, cell._east])

        # add UnderCells to State
        # call after grid.tunnel_under(cell)
        def update_under_cells(self):
            for cell in self._grid._under_cells:
                s = len(self._set_for_cell)
                self._set_for_cell[cell] = s
                self._cells_in_set[s] = [cell]

                if cell._south is not None:
                    self._neighbors.append([cell, cell._south])
                if cell._east is not None:
                    self._neighbors.append([cell, cell._east])

        def can_merge(self, left, right):
            return self._set_for_cell[left] != self._set_for_cell[right]

        def merge(self, left, right):
            left.link(right)

            winner = self._set_for_cell[left]
            loser = self._set_for_cell[right]
            if loser in self._cells_in_set.keys():
                losers = self._cells_in_set[loser]
            else:
                losers = [right]

            for cell in losers:
                self._cells_in_set[winner].append(cell)
                self._set_for_cell[cell] = winner
            
            # self._cells_in_set.remove(loser)
            self._cells_in_set = {k: v for k, v in self._cells_in_set.items() if v != loser}

        def add_crossing(self, cell):
            if len(cell.links()) > 0 or (not self.can_merge(cell._east, cell._west)) or (not self.can_merge(cell._north, cell._south)):
                return False

            self._neighbors = [[left, right] for left, right in self._neighbors if (not left == cell) and (not right == cell)]

            if random.randrange(2) == 0:
                self.merge(cell._west, cell)
                self.merge(cell, cell._east)

                self._grid.tunnel_under(cell)
                self.update_under_cells()
                self.merge(cell._north, cell._north._south)
                self.merge(cell._south, cell._south._north)
            else:
                self.merge(cell._north, cell)
                self.merge(cell, cell._south)

                self._grid.tunnel_under(cell)
                self.update_under_cells()
                self.merge(cell._west, cell._west._east)
                self.merge(cell._east, cell._east._west)

            return True

    def on(self, grid, state=None):
        if state is None:
            state = self.State(grid)

        neighbors = state._neighbors
        random.shuffle(neighbors)

        while len(neighbors) > 0:
            left, right = neighbors.pop()
            if state.can_merge(left, right):
                state.merge(left, right)