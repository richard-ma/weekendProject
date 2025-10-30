import random

WIDTH = 138 * 4
HEIGHT = 396

SPEED = 3

backgrounds = list()
for i in range(5):
    backimage = Actor("flappybird_background", topleft=(i * 138, 0))
    backgrounds.append(backimage)

ground = Actor("flappybird_ground", bottomleft=(0, HEIGHT))

pipe_top = Actor("flappybird_top_pipe")
pipe_bottom = Actor("flappybird_bottom_pipe")
GAP = 150

bird = Actor("flappybird1", center=(WIDTH // 2, HEIGHT // 2))
bird.vy = 0
bird.dead = False

GRAVITY = 0.2
FLAP_VELOCITY = -5

anim_counter = 0

score = 0
score_flag = False

gui_title = Actor("flappybird_title", center=(WIDTH // 2, 72))
gui_ready = Actor("flappybird_get_ready", center=(WIDTH // 2, 204))
gui_start = Actor("flappybird_start_button", center=(WIDTH // 2, 345))
gui_over = Actor("flappybird_game_over", center=(WIDTH // 2, HEIGHT // 2))

started = False

def reset_pipes():
    global score_flag
    score_flag = True
    pipe_top.bottom = random.randint(50, 150)
    pipe_bottom.top = pipe_top.bottom + GAP
    pipe_top.left = WIDTH
    pipe_bottom.left = WIDTH

def check_collision():
    if bird.colliderect(pipe_top) or bird.colliderect(pipe_bottom):
        sounds.collide.play()
        music.stop()
        bird.dead = True
    elif bird.colliderect(ground):
        sounds.fail.play()
        music.stop()
        bird.dead = True

def animation():
    global anim_counter
    anim_counter += 1
    if anim_counter == 2:
        bird.image = "flappybird1"
    elif anim_counter == 4:
        bird.image = "flappybird2"
    elif anim_counter == 6:
        bird.image = "flappybird3"
    elif anim_counter == 8:
        bird.image = "flappybird2"
        anim_counter = 0

def fly():
    global score, score_flag
    if score_flag and bird.x > pipe_top.right:
        score += 1
        score_flag = False
    bird.vy += GRAVITY
    bird.y += bird.vy
    if bird.top < 0:
        bird.top = 0

def on_mouse_down(pos):
    global started
    if bird.dead:
        return
    if started:
        bird.vy = FLAP_VELOCITY
        sounds.flap.play()
        return
    if gui_start.collidepoint(pos):
        started = True
        reset_pipes()
        music.play("flappybird")

def update_pipes():
    pipe_top.x -= SPEED
    pipe_bottom.x -= SPEED
    if pipe_top.right < 0:
        reset_pipes()

def update_background():
    for backimage in backgrounds:
        backimage.x -= SPEED
        if backimage.right < 0:
            backimage.left = WIDTH

def update_ground():
    ground.x -= SPEED
    if ground.right < WIDTH:
        ground.left = 0

def update():
    if not started or bird.dead:
        return
    update_background()
    update_ground()
    update_pipes()
    fly()
    animation()
    check_collision()

def draw():
    screen.fill((255, 255, 255))
    for backimage in backgrounds:
        backimage.draw()

    if not started:
        gui_title.draw()
        gui_ready.draw()
        gui_start.draw()
        return
    
    ground.draw()
    pipe_top.draw()
    pipe_bottom.draw()
    bird.draw()

    screen.draw.text(str(score), topleft=(30, 30), fontsize=60, color="white")

    if bird.dead:
        gui_over.draw()