import random


class RecursiveBacktracker:
    def __init__(self):
        pass
    
    def on(self, grid, start_at=None):
        if start_at is None:
            start_at = grid.random_cell() # 从随机单元格开始生成迷宫
            
        stack = []
        stack.append(start_at)
        
        while len(stack) > 0:
            current = stack[-1]
            neighbors = [n for n in current.neighbors() if len(n.links()) == 0] # 获取当前单元格的所有未连接的邻居单元格

            if len(neighbors) == 0: # 如果所有邻居都有连接，说明当前单元格以处理完毕
                stack.pop() # 回退到上一个单元格，继续处理其他邻居
            else:
                neighbor = random.choice(neighbors)
                current.link(neighbor) # 连接当前单元格和随机选择的邻居单元格
                stack.append(neighbor) # 将随机选择的邻居单元格加入栈中，继续处理这个单元格的邻居