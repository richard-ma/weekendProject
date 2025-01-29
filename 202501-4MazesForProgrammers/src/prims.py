import random


class SimplifiedPrims:
    def on(self, grid, start_at=None):
        if start_at is None:
            start_at = grid.random_cell()
            
        active = []
        active.append(start_at)

        while len(active) > 0:
            cell = random.choice(active)
            availabe_neighbors = [n for n in cell.neighbors() if len(n.links()) == 0]

            if len(availabe_neighbors) > 0:
                neighbor = random.choice(availabe_neighbors)
                cell.link(neighbor)
                active.append(neighbor)
            else:
                active.remove(cell)