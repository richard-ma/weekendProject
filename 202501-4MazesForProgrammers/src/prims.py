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


class Prims:
    def on(self, grid, start_at=None):
        if start_at is None:
            start_at = grid.random_cell()
            
        active = []
        active.append(start_at)
        costs = {}
        for cell in grid.each_cell():
            costs[cell] = random.randrange(100) # 0 ~ 99

        while len(active) > 0:
            # key(cell) = min(costs, key=costs.get) # Finding the Key with the Smallest Value
            active_costs = {k: v for k, v in costs.items() if k in active}
            cell = min(active_costs, key=active_costs.get)
            availabe_neighbors = [n for n in cell.neighbors() if len(n.links()) == 0]

            if len(availabe_neighbors) > 0:
                availabe_neighbors_costs = {k: v for k, v in costs.items() if k in availabe_neighbors}
                neighbor = min(availabe_neighbors_costs, key=availabe_neighbors_costs.get)
                cell.link(neighbor)
                active.append(neighbor)
            else:
                active.remove(cell)
        