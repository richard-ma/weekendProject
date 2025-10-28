SIZE = 15
WIDTH = SIZE * 30
HEIGHT = SIZE * 30

snake_head = Actor("snake_head", (WIDTH // 2, HEIGHT // 2))

direction = "east"
counter = 0

def draw():
    screen.fill((255, 255, 255))
    snake_head.draw()

def check_keys():
    global direction
    if keyboard.up and direction != "south":
        snake_head.y -= SIZE
    elif key == keys.DOWN and direction != "north":
        snake_head.y += SIZE
    elif key == keys.LEFT and direction != "east":
        snake_head.x -= SIZE
    elif key == keys.RIGHT and direction != "west":
        snake_head.x += SIZE

def update():
    check_keys()
    update_snake()

def update_snake():
    global counter
    counter += 1
    if counter < 10:
        return
    else:
        counter = 0

    if direction == "east":
        snake_head.x += SIZE
    elif direction == "west":
        snake_head.x -= SIZE
    elif direction == "north":
        snake_head.y -= SIZE
    elif direction == "south":
        snake_head.y += SIZE