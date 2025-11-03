import random

SIZE = 40
WIDTH = SIZE * 15
HEIGHT = SIZE * 15

board = [[" " for i in range(15)] for j in range(15)]
chesses = []
turn = 'b'
last_turn = 'w'

gameover = False

N = 0
W = 1
B = 2
S = 3

cdata = [# 一个棋子的情况
        [ N, N, N, S, B ], [ B, S, N, N, N ], [ N, N, N, S, B ], [ N, B, S, N, N ],		
        [ N, N, S, B, N ], [ N, N, B, S, N ], [ N, N, N, S, W ], [ W, S, N, N, N ],
        [ N, N, N, S, W ], [ N, W, S, N, N ], [ N, N, S, W, N ], [ N, N, W, S, N ],
        # 两个棋子的情况
        [ B, B, S, N, N ], [ N, N, S, B, B ], [ B, S, B, N, N ], [ N, N, B, S, B ],
        [ N, B, S, B, N ], [ N, B, B, S, N ], [ N, S, B, B, N ], [ W, W, S, N, N ],
        [ N, N, S, W, W ], [ W, S, W, N, N ], [ N, N, W, S, W ], [ N, W, S, W, N ],
        [ N, W, W, S, N ], [ N, S, W, W, N ],
        # 三个棋子的情况
        [ N, S, B, B, B ], [ B, B, B, S, N ], [ N, B, B, B, S ], [ N, B, S, B, B ],
        [ B, B, S, B, N ], [ N, S, W, W, W ], [ W, W, W, S, N ], [ N, W, W, W, S ],	
        [ N, W, S, W, W ], [ W, W, S, W, N ],
        # 四个棋子的情况
        [ S, B, B, B, B ], [ B, S, B, B, B ], [ B, B, S, B, B ], [ B, B, B, S, B ],
        [ B, B, B, B, S ], [ S, W, W, W, W ], [ W, S, W, W, W ], [ W, W, S, W, W ],
        [ W, W, W, S, W ], [ W, W, W, W, S ]]

AI_col = -1                     # 电脑下棋位置的列号
AI_row = -1                     # 电脑下棋位置的行号
max_level = -1                  # 走棋模式等级

def auto_match(row, col, level, dx, dy):
    global AI_col, AI_row, max_level
    col_sel = -1
    row_sel = -1
    isfind = True
    
    for j in range(5):
        cs = board[col + j * dx][row + j * dy]
        if cs == " ":
            if cdata[level][j] == S:
                col_sel = col + j * dx
                row_sel = row + j * dy
            elif cdata[level][j] != N:
                isfind = False
                break
        elif cs == 'b' and cdata[level][j] != B:
            isfind = False
            break
        elif cs == 'w' and cdata[level][j] != W:
            isfind = False
            break
    if isfind:
        max_level = level
        AI_col = col_sel
        AI_row = row_sel
        return True
    return False

def AI_play():
    global AI_row, AI_col, max_level
    AI_col = -1
    AI_row = -1
    max_level = -1
    for row in range(15):
        for col in range(15):
            for level in range(len(cdata)-1, -1, -1):
                if level <= max_level:
                    break
                if col + 4 < 15:
                    if auto_match(row, col, level, 1, 0):
                        break
                if row + 4 < 15:
                    if auto_match(row, col, level, 0, 1):
                        break
                if col - 4 >= 0 and row + 4 < 15:
                    if auto_match(row, col, level, -1, 1):
                        break
                if col + 4 < 15 and row + 4 < 15:
                    if auto_match(row, col, level, 1, 1):
                        break
    if AI_col != -1 and AI_row != -1:
        board[AI_col][AI_row] = 'w'
        return True
    
    while True:
        col = random.randint(0, 14)
        row = random.randint(0, 14)
        if board[col][row] == ' ':
            board[col][row] = 'w'
            AI_col = col
            AI_row = row
            return True
    return False

def update():
    global gameover
    if gameover:
        return
    if check_win():
        gameover = True
        if last_turn == 'b':
            sounds.win.play()
        else:
            sounds.fail.play()
        return
    if turn == 'w':
        if AI_play():
            chess = Actor("gobang_white", (AI_col * SIZE + 20, AI_row * SIZE + 20))
            chesses.append(chess)
            change_side()

def retract():
    if len(chesses) < 2:
        return
    for i in range(2):
        chess = chesses.pop()
        col = int(chess.x - 20) // SIZE
        row = int(chess.y - 20) // SIZE
        board[col][row] = " "

def draw_text():
    if not gameover:
        return
    if last_turn == 'b':
        text = 'You Win'
    else:
        text = 'You Lost'
    
    screen.draw.text(text, center=(WIDTH // 2, HEIGHT // 2), fontsize=100, color='red')

def on_mouse_down(pos, button):
    if gameover:
        return
    if button == mouse.LEFT:
        play(pos)
    elif button == mouse.RIGHT:
        retract()

def play(pos):
    col = pos[0] // SIZE
    row = pos[1] // SIZE
    if board[col][row] != " ":
        return
    if turn == 'b':
        chess = Actor("gobang_black", (col * SIZE + 20, row * SIZE + 20))
    else:
        chess = Actor("gobang_white", (col * SIZE + 20, row * SIZE + 20))
    chesses.append(chess)
    board[col][row] = turn
    change_side()

def draw_board():
    for i in range(15):
        screen.draw.line((20, SIZE * i + 20), (580, SIZE * i + 20), 'black')
        screen.draw.line((SIZE * i + 20, 20), (SIZE * i + 20, 580), 'black')

def draw_chess():
    for chess in chesses:
        chess.draw()

    if len(chesses) > 0:
        chess = chesses[-1]
        rect = Rect(chess.topleft, (36, 36))
        screen.draw.rect(rect, (255, 255, 255))

def change_side():
    global turn, last_turn
    last_turn = turn
    if turn == 'b':
        turn = 'w'
    else:
        turn = 'b'

def draw():
    screen.fill((210, 180, 140))
    draw_board()
    draw_chess()
    draw_text()

def check_win():
    a = last_turn
    for i in range(11):
        for j in range(11):
            if board[i][j] == a and board[i+1][j+1] == a and board[i+2][j+2] == a \
                and board[i+3][j+3] == a and board[i+4][j+4] == a:
                    return True
    for i in range(11):
        for j in range(4, 15):
            if board[i][j] == a and board[i+1][j-1] == a and board[i+2][j-2] == a \
                and board[i+3][j-3] == a and board[i+4][j-4] == a:
                    return True
    for i in range(15):
        for j in range(11):
            if board[i][j] == a and board[i][j+1] == a and board[i][j+2] == a \
                and board[i][j+3] == a and board[i][j+4] == a:
                    return True
    for i in range(11):
        for j in range(15):
            if board[i][j] == a and board[i+1][j] == a and board[i+2][j] == a \
                and board[i+3][j] == a and board[i+4][j] == a:
                    return True
    return False