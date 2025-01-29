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

    def on(self, grid, state=None):
        if state is None:
            state = self.State(grid)

        neighbors = state._neighbors
        random.shuffle(neighbors)

        while len(neighbors) > 0:
            left, right = neighbors.pop()
            if state.can_merge(left, right):
                state.merge(left, right)