TILESIZE = 48
WIDTH = TILESIZE * 11
HEIGHT = TILESIZE * 9


dirs = {
    "east": (1, 0),
    "west": (-1, 0),
    "north": (0, -1),
    "south": (0, 1),
    "none": (0, 0),
}

level = 1
finished = False
gameover = False

def initlevel(mapdata):
    global walls, floors, boxes, targets, player
    walls = []
    floors = []
    boxes = []
    targets = []
    
    for row in range(len(mapdata)):
        for col in range(len(mapdata[row])):
            tile = mapdata[row][col]
            x = col * TILESIZE
            y = row * TILESIZE
            if tile >= '0' and tile != '1':
                floors.append(Actor("pushbox_floor", topleft=(x, y)))

            if tile == '1':
                walls.append(Actor("pushbox_wall", topleft=(x, y)))
            elif tile == '2':
                box = Actor("pushbox_box", topleft=(x, y))
                box.placed = False
                boxes.append(box)
            elif tile == '3':
                player = Actor("pushbox_right", topleft=(x, y))
            elif tile == '4':
                targets.append(Actor("pushbox_target", topleft=(x, y)))
            elif tile == '6':
                targets.append(Actor("pushbox_target", topleft=(x, y)))
                box = Actor("pushbox_box", topleft=(x, y))
                box.placed = True
                boxes.append(box)

def loadfile(file):
    mapfile = open(file, 'r')
    map_array = []
    while True:
        line = mapfile.readline()
        if line == "":
            break
        line = line.replace("\n", "")
        line = line.replace(" ", "")
        map_array.append(line.split(","))
    mapfile.close()
    return map_array

def loadmap(level):
    try:
        mapdata = loadfile("maps/map" + str(level) + ".txt")
    except FileNotFoundError:
        global gameover
        gameover = True
    else:
        initlevel(mapdata)

loadmap(level)

def levelup():
    for box in boxes:
        if not box.placed:
            return False
    return True

def setlevel():
    global finished, level
    finished = False
    level += 1
    loadmap(level)

def update():
    global finished
    if finished:
        return
    if levelup():
        finished = True
        sounds.win.play()
        clock.schedule(setlevel, 5)

def on_key_down(key):
    if key == keys.R:
        loadmap(level)
        return
    
    if key == keys.RIGHT:
        player.direction = "east"
        player.image = "pushbox_right"
    elif key == keys.LEFT:
        player.direction = "west"
        player.image = "pushbox_left"
    elif key == keys.UP:
        player.direction = "north"
        player.image = "pushbox_up"
    elif key == keys.DOWN:
        player.direction = "south"
        player.image = "pushbox_down"
    else:
        player.direction = "none"
    player_move()
    player_collision()

def player_move():
    player.oldx = player.x
    player.oldy = player.y
    dx, dy = dirs[player.direction]
    player.x += dx * TILESIZE
    player.y += dy * TILESIZE

def player_collision():
    if player.collidelist(walls) != -1:
        player.x = player.oldx
        player.y = player.oldy
        return
    index = player.collidelist(boxes)
    if index == -1:
        return
    box = boxes[index]
    if box_collision(box) == True:
        box.x = box.oldx
        box.y = box.oldy
        player.x = player.oldx
        player.y = player.oldy
        return
    sounds.fall.play()

def box_collision(box):
    box.oldx = box.x
    box.oldy = box.y
    dx, dy = dirs[player.direction]
    box.x += dx * TILESIZE
    box.y += dy * TILESIZE
    if box.collidelist(walls) != -1:
        return True
    for bx in boxes:
        if box == bx:
            continue
        if box.colliderect(bx):
            return True
    check_target(box)
    return False

def check_target(box):
    if box.collidelist(targets) != -1:
        box.image = "pushbox_box_hit"
        box.placed = True
    else:
        box.image = "pushbox_box"
        box.placed = False

def draw():
    screen.fill((200, 255, 255))

    if gameover:
        screen.draw.text("Congratulations!", center=(WIDTH//2, HEIGHT//2), fontsize=40, color="blue")
        return
    
    for floor in floors:
        floor.draw()
    for target in targets:
        target.draw()
    for wall in walls:
        wall.draw()
    for box in boxes:
        box.draw()
    player.draw()
    screen.draw.text("Level: " + str(level), topleft=(20, 20), fontsize=30, color="black")  
    if finished:
        screen.draw.text("Level Up!", center=(WIDTH//2, HEIGHT//2), fontsize=60, color="red")