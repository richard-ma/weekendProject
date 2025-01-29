import random


class GrowingTree:
    def on(self, grid, choose_active_func, start_at=None):
        if start_at is None:
            start_at = grid.random_cell()
            
        active = []
        active.append(start_at)

        while len(active) > 0:
            cell = choose_active_func(active)
            availabe_neighbors = [n for n in cell.neighbors() if len(n.links()) == 0]

            if len(availabe_neighbors) > 0:
                neighbor = random.choice(availabe_neighbors)
                cell.link(neighbor)
                active.append(neighbor)
            else:
                active.remove(cell)