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
            
            cell = neighbor # 无论是否连接，都会访问邻居单元格，因此会访问每一个单元格
            # 这种算法的效率很低，因为它可能会多次访问同一个单元格，直到所有单元格都被访问过一次为止。
            # 但是它的优点是实现简单，并且生成的迷宫具有很好的随机性和均匀性。
            # 这种算法的平均时间复杂度是 O(n^2)，其中 n 是单元格的数量。
            # 这是因为在最坏的情况下，算法可能会多次访问同一个单元格，直到所有单元格都被访问过一次为止。