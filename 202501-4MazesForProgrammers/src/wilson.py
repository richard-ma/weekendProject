import random


class Wilsons:
    def __init__(self):
        pass
    
    def on(self, grid):
        unvisited = []
        for cell in grid.each_cell():
            unvisited.append(cell)

        first = random.choice(unvisited)
        unvisited.remove(first)

        while len(unvisited) > 0:
            cell = random.choice(unvisited)
            path = [cell]
            
            while cell in unvisited:
                cell = random.choice(cell.neighbors())
                try:
                    position = path.index(cell)
                except ValueError:
                    position = None
                if position is None:
                    path.append(cell)
                else:
                    path = path[:position+1] # delete circle

            for index in range(len(path)-1): # connect all cells in path
                path[index].link(path[index+1])
                unvisited.remove(path[index])