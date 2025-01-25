import random


class RecursiveBacktracker:
    def __init__(self):
        pass
    
    def on(self, grid, start_at=None):
        if start_at is None:
            start_at = grid.random_cell()
            
        stack = []
        stack.append(start_at)
        
        while len(stack) > 0:
            current = stack[-1]
            neighbors = [n for n in current.neighbors() if len(n.links()) == 0]

            if len(neighbors) == 0:
                stack.pop()
            else:
                neighbor = random.choice(neighbors)
                current.link(neighbor)
                stack.append(neighbor)