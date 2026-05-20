import random


class AldousBroder:
    def __init__(self):
        pass

    def on(self, grid):
        cell = grid.random_cell()
        unvisited = grid.size() - 1
        
        while unvisited > 0:
            neighbor = random.choice(cell.neighbors())

            if len(neighbor.links()) == 0: # 当所有邻居都有连接时，当前单元格为死胡同
                cell.link(neighbor)
                unvisited -= 1 # 保证会访问每一个单元格
            
            cell = neighbor