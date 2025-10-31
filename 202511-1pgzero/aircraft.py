import random
import math

WIDTH = 480
HEIGHT = 680

backgrounds = list()
backgrounds.append(Actor("warplanes_background", topleft=(0, 0)))
backgrounds.append(Actor("warplanes_background", bottomleft=(0, 0)))

hero = Actor("warplanes_hero1", center=(WIDTH // 2, HEIGHT - 50))
hero.speed = 5
hero.animcount = 0
hero.power = False
hero.score = 0
hero.live = 3
hero.unattack = False
hero.ukcount = 0

gameover = False

bullets = list()

powers = list()

tweens = ["linear", "accelerate", "decelerate", "in_elastic", "out_elastic", "bounce_start_end"]
enemies = list()

music.play("warplanes")

def draw_hud():
    for i in range(hero.live):
        screen.blit("warplanes_live", (i * 35, HEIGHT - 35))
    screen.draw.text(f"Score: {hero.score}", topleft=(20, 20), fontsize=25)

def update_enemy():
    for enemy in enemies:
        if enemy.top >= HEIGHT:
            enemies.remove(enemy)
            continue
        n = enemy.collidelist(bullets)
        if n != -1:
            enemies.remove(enemy)
            bullets.remove(bullets[n])
            sounds.shooted.play()
            hero.score += 200 if enemy.image == "warplanes_enemy2" else 100
        if enemy.colliderect(hero) and not hero.unattack:
            hero.live -= 1
            if hero.live > 0:
                hero.unattack = True
                hero.ukcount = 100
                enemies.remove(enemy)
                sounds.shooted.play()
            else:
                sounds.gameover.play()
                global gameover
                gameover = True

def spawn_enemy():
    origin_x = random.randint(50, WIDTH)
    target_x = random.randint(50, WIDTH)
    tn = random.choice(tweens)
    dn = random.randint(3, 6)
    enemy = Actor("warplanes_enemy1", bottomright=(origin_x, 0))
    if random.randint(1, 100) < 20:
        enemy.image = "warplanes_enemy2"
    enemies.append(enemy)
    animate(enemy, duration=dn, tween=tn, topright=(target_x, HEIGHT))

clock.schedule_interval(spawn_enemy, 1.0)

def update_powerup():
    if random.randint(1, 1000) < 5:
        x = random.randint(50, WIDTH)
        powerup =  Actor("warplanes_powerup",bottomright=(x, 0))
        powers.append(powerup)

    for powerup in powers:
        powerup.y += 2
        if powerup.top > HEIGHT:
            powers.remove(powerup)
        elif hero.colliderect(powerup):
            hero.power = True
            powers.remove(powerup)
            clock.schedule(powerdown, 5)

def powerdown():
    hero.power = False

def shoot():
    sounds.bullet.play()
    bullets.append(Actor("warplanes_bullet", center=(hero.x, hero.top)))

    if hero.power:
        leftbullet = Actor("warplanes_bullet", midbottom=(hero.x, hero.top))
        leftbullet.angle = 15
        bullets.append(leftbullet)
        rightbullet = Actor("warplanes_bullet", midbottom=(hero.x, hero.top))
        rightbullet.angle = -15
        bullets.append(rightbullet)

def update_bullets():
    for bullet in bullets:
        theta = math.radians(bullet.angle + 90)
        bullet.x += 10 * math.cos(theta)
        bullet.y -= 10 * math.sin(theta)
        if bullet.bottom < 0:
            bullets.remove(bullet)

def draw_hero():
    if hero.unattack:
        if hero.ukcount % 5 == 0:
            return
    hero.draw()

def draw():
    if gameover:
        screen.blit("warplanes_gameover", (0, 0))
        return
    
    for backimage in backgrounds:
        backimage.draw()
    
    for bullet in bullets:
        bullet.draw()

    for powerup in powers:
        powerup.draw()

    for enemy in enemies:
        enemy.draw()

    draw_hero()
    draw_hud()

def update_hero():
    move_hero()
    hero.animcount = (hero.animcount + 1) % 20
    if hero.animcount == 0:
        hero.image = "warplanes_hero1"
    elif hero.animcount == 10:
        hero.image = "warplanes_hero2"

    if hero.unattack:
        hero.ukcount -= 1
        if hero.ukcount <= 0:
            hero.unattack = False
            hero.ukcount = 100

def move_hero():
    if keyboard.left and hero.left > 0:
        hero.x -= hero.speed
    if keyboard.right and hero.right < WIDTH:
        hero.x += hero.speed
    if keyboard.up and hero.top > 0:
        hero.y -= hero.speed
    if keyboard.down and hero.bottom < HEIGHT:
        hero.y += hero.speed
    if keyboard.space:
        clock.schedule_unique(shoot, 0.1)

def update_background():
    for backimage in backgrounds:
        backimage.y += 2
        if backimage.top > HEIGHT:
            backimage.bottom = 0

def update():
    if gameover:
        clock.unschedule(spawn_enemy)
        return
    update_background()
    update_hero()
    update_bullets()
    update_powerup()
    update_enemy()