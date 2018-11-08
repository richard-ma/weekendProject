#!/usr/bin/env python
# encoding: utf-8

# 双人五子棋游戏


class GoBang():
    def __init__(self):
        self.reset()

    def reset(self):
        self.chessboard = [] # 15 * 15
        for i in range(15):
            self.chessboard.append([0] * 15)
        self.turn = 1  # 白字为1, 黑子为-1，白方先行
        self.condition_to_win = 5  # 获胜条件

    # 落子
    def set(self, x, y):
        if self.chessboard[y][x] == 0:
            self.chessboard[y][x] = self.turn  # 落子

    # 换对方落子
    def take_turns(self):
        self.turn *= -1

    # 检查棋盘是否有一方获胜
    def check(self, x, y):
        v = [[1, 0], [0, 1], [1, 1], [1, -1]]

        for direction in v:
            counter = 1 # 落子位置有一个子，从1开始

            # 向量正向计数
            delta_x = x + direction[0]
            delta_y = y + direction[1]
            while delta_x < 15 and delta_y < 15 and self.chessboard[delta_y][delta_x] == self.turn:
                counter += 1
                delta_x = delta_x + direction[0]
                delta_y = delta_y + direction[1]

            # 向量反向计数
            delta_x = x - direction[0]
            delta_y = y - direction[1]
            while delta_x >= 0 and delta_y >= 0 and self.chessboard[delta_y][delta_x] == self.turn:
                counter += 1
                delta_x = delta_x - direction[0]
                delta_y = delta_y - direction[1]

            if counter == self.condition_to_win:
                return True  # 本方获胜

        return False  # 本方没有获胜

    # 输出棋盘状态
    def print_chessboard(self):
        for row in self.chessboard:
            print(' '.join(['-' if e == 0 else 'O' if e == 1 else 'X' for e in row]))


if __name__ == '__main__':
    gobang = GoBang()
    x = y = 0
    while True:
        x = input()
        y = input()
        x = int(x)
        y = int(y)

        gobang.set(x, y)
        if gobang.check(x, y) == True:
            break
        gobang.take_turns()
        gobang.print_chessboard()
    print("%s win" % (gobang.turn))
