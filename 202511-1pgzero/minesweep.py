import random

ROWS = 15
COLS = 15
SIZE = 25
WIDTH = COLS * SIZE
HEIGHT = ROWS * SIZE

BOMBS = 20

blocks = list()
for i in range(ROWS):
    for j in range(COLS):
        block = Actor("minesweep_block")
        block.left = j * SIZE
        block.top = i * SIZE
        block.isbomb = False
        block.isflag = False
        block.isopen = False
        blocks.append(block)

random.shuffle(blocks)

for i in range(BOMBS):
    blocks[i].isbomb = True

failed = False
finished = False

def draw():
    for block in blocks:
        block.draw()
    if failed:
        screen.draw.text("Game Over", center=(WIDTH // 2, HEIGHT // 2), fontsize=100, color="red")
    if finished:
        screen.draw.text("You Win!", center=(WIDTH // 2, HEIGHT // 2), fontsize=100, color="green")

def on_mouse_down(pos, button):
    for block in blocks:
        if block.collidepoint(pos) and not block.isopen:
            if button == mouse.RIGHT:
                set_flag(block)
            elif button == mouse.LEFT and not block.isflag:
                if block.isbomb:
                    blow_up()
                else:
                    open_block(block)

def set_flag(block):
    if not block.isflag:
        block.image = "minesweep_flag"
        block.isflag = True
    else:
        block.image = "minesweep_block"
        block.isflag = False
        
def blow_up():
    global failed
    failed = True
    sounds.bomb.play()
    for i in range(BOMBS):
        blocks[i].image = "minesweep_bomb"

def open_block(block):
    block.isopen = True
    bomb_count = get_bomb_count(block)
    block.image = "minesweep_number" + str(bomb_count)
    if bomb_count != 0:
        return
    for neighbour in get_neighbours(block):
        if not neighbour.isopen and not neighbour.isbomb:
            open_block(neighbour)

def get_neighbours(bk):
    neighbours = list()
    for block in blocks:
        if block.isopen:
            continue
        if block.x == bk.x - SIZE and block.y == bk.y \
            or block.x == bk.x + SIZE and block.y == bk.y \
            or block.x == bk.x and block.y == bk.y - SIZE \
            or block.x == bk.x and block.y == bk.y + SIZE \
            or block.x == bk.x - SIZE and block.y == bk.y - SIZE \
            or block.x == bk.x - SIZE and block.y == bk.y + SIZE \
            or block.x == bk.x + SIZE and block.y == bk.y - SIZE \
            or block.x == bk.x + SIZE and block.y == bk.y + SIZE:
            neighbours.append(block)
    return neighbours

def get_bomb_count(bk):
    count = 0
    for block in get_neighbours(bk):
        if block.isbomb:
            count += 1
    return count

def update():
    global finished

    if finished or failed:
        return

    for block in blocks:
        if not block.isbomb and not block.isopen:
            return

    finished = True
    sounds.win.play()