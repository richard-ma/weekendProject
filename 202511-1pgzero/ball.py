import random

WIDTH = 500
HEIGHT = 300

NUM = 10
balls = list()

for i in range(NUM):
    ball = Actor("breakout_ball", (200, 100))
    ball.x = random.randint(0, WIDTH)
    ball.y = random.randint(0, HEIGHT)
    ball.dx = 5
    ball.dy = 5
    balls.append(ball)

def draw():
    screen.fill((255, 255, 255))
    for ball in balls:
        ball.draw()

def update():
    for ball in balls:
        ball.x += ball.dx
        ball.y += ball.dy
        if ball.right > WIDTH or ball.left < 0:
            ball.dx = -ball.dx
        if ball.bottom > HEIGHT or ball.top < 0:
            ball.dy = -ball.dy