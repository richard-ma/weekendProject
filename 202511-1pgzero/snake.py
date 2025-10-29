import random

SIZE = 15
WIDTH = SIZE * 30
HEIGHT = SIZE * 30

snake_head = Actor("snake_head", (WIDTH // 2, HEIGHT // 2))
length = 1
body = list()

food = Actor("snake_food")
food.x = random.randint(2, WIDTH // SIZE - 2) * SIZE
food.y = random.randint(2, HEIGHT // SIZE - 2) * SIZE

direction = "east"
snake_head.angle = 180
dirs = {"east": (1, 0), "north": (0, -1), "west": (-1, 0), "south": (0, 1)}

counter = 0
finished = False

def draw():
    screen.fill((255, 255, 255))

    if finished:
        screen.draw.text("Game Over", center=(WIDTH // 2, HEIGHT // 2), fontsize=60, color="red")

    for b in body:
        b.draw()

    snake_head.draw()
    food.draw()

def check_keys():
    global direction
    if keyboard.up and direction != "south":
        direction = "north"
        snake_head.angle = 90
    elif keyboard.down and direction != "north":
        direction = "south"
        snake_head.angle = -90
    elif keyboard.left and direction != "east":
        direction = "west"
        snake_head.angle = 180
    elif keyboard.right and direction != "west":
        direction = "east"
        snake_head.angle = 0

def update():
    if finished:
        return
    check_game_over()
    check_keys()
    eat_food()
    update_snake()

def check_game_over():
    global finished

    if (snake_head.x < 0 or snake_head.x >= WIDTH or
        snake_head.y < 0 or snake_head.y >= HEIGHT):
        sounds.fail.play()
        finished = True
        
    for n in range(len(body) - 1):
        if (body[n].x == snake_head.x and
            body[n].y == snake_head.y):
            sounds.fail.play()
            finished = True

def update_snake():
    global counter
    counter += 1
    if counter < 10:
        return
    else:
        counter = 0

    dx,dy = dirs[direction]
    snake_head.x += dx * SIZE
    snake_head.y += dy * SIZE

    if len(body) == length:
        body.remove(body[0])
    body.append(Actor("snake_body", (snake_head.x, snake_head.y)))

def eat_food():
    global length
    if food.x == snake_head.x and food.y == snake_head.y:
        sounds.eat.play()
        length += 1
        food.x = random.randint(2, WIDTH // SIZE - 2) * SIZE
        food.y = random.randint(2, HEIGHT // SIZE - 2) * SIZE