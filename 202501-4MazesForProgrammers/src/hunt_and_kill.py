import random

class HuntAndKill:
    def __init__(self):
        pass

    def on(self, grid):
        current = grid.random_cell() # 随机选择起点
        
        while current is not None:
            unvisited_neighbors = [n for n in current.neighbors() if len(n.links()) == 0]

            if len(unvisited_neighbors) > 0: # kill
                neighbor = random.choice(unvisited_neighbors)
                current.link(neighbor)
                current = neighbor
            else: # hunt
                current = None
                for cell in grid.each_cell():
                    visited_neighbors = [n for n in cell.neighbors() if len(n.links()) > 0]
                    
                    if len(cell.links()) == 0 and len(visited_neighbors) > 0:
                        current = cell
                        neighbor = random.choice(visited_neighbors)
                        current.link(neighbor)
                        break