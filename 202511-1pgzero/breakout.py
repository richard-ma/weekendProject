import random

WIDTH = 640
HEIGHT = 400

ball = Actor('breakout_ball', (WIDTH // 2, HEIGHT - 47))
pad = Actor('breakout_paddle', (WIDTH // 2, HEIGHT - 30))
pad.speed = 5

BRICK_W = 80
BRICK_H = 20
bricks = []
for i in range(5):
    for j in range(WIDTH // BRICK_W):
        brick = Actor('breakout_brick')
        brick.left = j * BRICK_W
        brick.top = 30 + i * BRICK_H
        bricks.append(brick)

started = False

score = 0
lives = 3

win = False
lost = False

def check_game_over():
    global win, lost
    if len(bricks) == 0:
        sounds.win.play()
        win = True
    elif lives <= 0:
        sounds.fail.play()
        lost = True

def ball_move():
    global started
    global lives
    if not started:
        if keyboard.space:
            dir = 1 if random.choice([0, 1]) else -1
            ball.vx = dir * 3
            ball.vy = -3
            started = True
        else:
            ball.x = pad.x
            ball.bottom = pad.top
            return
    ball.x += ball.vx
    ball.y += ball.vy

    if ball.left < 0 or ball.right > WIDTH:
        ball.vx = -ball.vx
    if ball.top < 0:
        ball.vy = -ball.vy
    elif ball.top > HEIGHT:
        started = False
        lives -= 1

    sounds.miss.play()
    
def collision_ball_pad():
    if not ball.colliderect(pad):
        return
    if ball.y < pad.y:
        ball.vy = -abs(ball.vy)
        sounds.bounce.play()
    if ball.x < pad.x:
        ball.vx = -abs(ball.vx)
    else:
        ball.vx = abs(ball.vx)

def collision_ball_bricks():
    global score
    n = ball.collidelist(bricks)
    if n == -1:
        return
    brick = bricks[n]
    bricks.remove(brick)
    sounds.collide.play()
    score += 10

    if brick.left < ball.x < brick.right:
        ball.vy = -ball.vy
    elif ball.x <= brick.left:
        if ball.vx > 0:
            ball.vx = -ball.vx
        else:
            ball.vy = -ball.vy
    elif ball.x >= brick.right:
        if ball.vx < 0:
            ball.vx = -ball.vx
        else:
            ball.vy = -ball.vy

def pad_move():
    if keyboard.left and pad.left > 0:
        pad.left -= pad.speed
    if keyboard.right and pad.right < WIDTH:
        pad.right += pad.speed

def update():
    check_game_over()
    pad_move()
    ball_move()
    collision_ball_pad()
    collision_ball_bricks()

def draw():
    screen.fill((255, 255, 255))
    for brick in bricks:
        brick.draw()
    ball.draw()
    pad.draw()
    screen.draw.text(f'Score: {score}', (10, 10), color='black')
    screen.draw.text(f'Lives: {lives}', (WIDTH - 100, 10), color='black')

    if win:
        screen.draw.text('YOU WIN!', center=(WIDTH // 2, HEIGHT // 2), fontsize=60, color='green')
    elif lost:
        screen.draw.text('GAME OVER', center=(WIDTH // 2, HEIGHT // 2), fontsize=60, color='red')