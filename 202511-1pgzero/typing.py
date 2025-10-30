import random
import time

WIDTH = 640
HEIGHT = 400

MAX_NUM = 5
balloons = list()
balloon_queue = list()
score = 0

start_time = time.perf_counter()  # seconds
left_time = 60

win = False
lose = False

def check_game_over():
    global win, lose
    if left_time <= 0:
        sounds.fail.play()
        lose = True
    elif score >= 20:
        sounds.win.play()
        win = True

def count_time():
    global left_time
    play_time = int(time.perf_counter() - start_time)
    left_time = 60 - play_time

def random_location():
    min_dx = 0
    while min_dx < 20:
        min_dx = WIDTH
        x = random.randint(20, WIDTH - 20)
        for balloon in balloons:
            dx = abs(balloon.x - x)
            min_dx = min(dx, min_dx)
    return x

def random_velocity():
    n = random.randint(1, 100)
    if n <= 5:
        velocity = -5
    elif n <= 25:
        velocity = -4
    elif n <= 75:
        velocity = -3
    elif n <= 95:
        velocity = -2
    else:
        velocity = -1
    return velocity

def random_char():
    charset = set()
    for balloon in balloons:
        charset.add(balloon.char)
    ch = chr(random.randint(65, 90))  # A-Z
    while ch in charset:
        ch = chr(random.randint(65, 90))
    return ch

def add_balloon():
    if len(balloons) < MAX_NUM:
        balloon = Actor("typing_balloon", (WIDTH // 2, HEIGHT))
        balloon.char = random_char()
        balloon.x = random_location()
        balloon.vy = random_velocity()
        balloon.typed = False
        balloons.append(balloon)

def remove_balloon():
    sounds.eat.play()
    balloon = balloons.pop(0)
    if balloon in balloons:
        balloons.remove(balloon)

def update_balloons():
    for balloon in balloons:
        balloon.y += balloon.vy
        if balloon.y < 0:
            balloons.remove(balloon)

def draw():
    screen.fill((255, 255, 255))
    for balloon in balloons:
        balloon.draw()
        if balloon.typed:
            color = "white"
        else:
            color = "black"
        screen.draw.text(balloon.char, center=balloon.center, color=color)
    screen.draw.text(f"Score: {score}", bottomleft=(10, HEIGHT - 10), color="black")
    screen.draw.text(f"Time: {left_time}", bottomleft=(WIDTH - 80, HEIGHT - 10), color="black")
    if win:
        screen.draw.text("You Win!", center=(WIDTH // 2, HEIGHT // 2), fontsize=60, color="green")
    elif lose:
        screen.draw.text("Game Over", center=(WIDTH // 2, HEIGHT // 2), fontsize=60, color="red")

def update():
    if win or lose:
        return
    if len(balloons) < MAX_NUM:
        add_balloon()
    update_balloons()
    check_game_over()
    count_time()

def on_key_down(key):
    global score
    for balloon in balloons:
        if str(key) == "keys." + balloon.char:
            balloon.typed = True
            balloon_queue.append(balloon)
            score += 1
            clock.schedule(remove_balloon, 0.3)
            break