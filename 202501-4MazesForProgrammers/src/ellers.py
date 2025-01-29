import random


class Ellers:
    class RowState:
        def __init__(self, columns, starting_set=0):
            self._columns = columns
            self._cells_in_set = {}
            self._set_for_cell = [None] * self._columns
            self._next_set = starting_set

        def record(self, s, cell):
            self._set_for_cell[cell._column] = s
            
            if s not in self._cells_in_set.keys():
                self._cells_in_set[s] = []
            self._cells_in_set[s].append(cell)

        def set_for(self, cell):
            if self._set_for_cell[cell._column] is None:
                self.record(self._next_set, cell)
                self._next_set += 1
            
            return self._set_for_cell[cell._column]

        def merge(self, winner, loser):
            for cell in self._cells_in_set[loser]:
                self._set_for_cell[cell._column] = winner
                self._cells_in_set[winner].append(cell)

            del self._cells_in_set[loser]

        def next_row_state(self):
            return Ellers.RowState(columns=self._columns, starting_set=self._next_set)

        def each_set(self):
            for set, cells in self._cells_in_set.items():
                yield (set, cells)
            return self

    def on(self, grid):
        row_state = Ellers.RowState(grid._columns)
        
        for row in grid.each_row():
            for cell in row:
                if cell._west is None:
                    continue # 跳过最西边的单元格

                now_set = row_state.set_for(cell)
                prior_set = row_state.set_for(cell._west)
                
                should_link = now_set != prior_set and \
                    (cell._south is None or random.randrange(2) == 0)
                
                if should_link:
                    cell.link(cell._west)
                    row_state.merge(prior_set, now_set)

            if row[0]._south is not None:
                next_row = row_state.next_row_state()
                
                for set, l in row_state.each_set():
                    random.shuffle(l)
                    
                    for index, cell in enumerate(l):
                        if index == 0 or random.randrange(3) == 0:
                            cell.link(cell._south)
                            next_row.record(row_state.set_for(cell), cell._south)

            row_state = next_row