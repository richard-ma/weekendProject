import pygame as pg
import random

WIDTH = 800
HEIGHT = 600


if  __name__ == "__main__":
    pg.init()
    screen = pg.display.set_mode((800, 600))
    pg.display.set_caption("Ball Game")
    
    clock = pg.time.Clock()
    running = True

    while running:
        for event in pg.event.get():
            if event.type == pg.QUIT:
                running = False
            elif event.type == pg.KEYDOWN:
                if event.key == pg.K_ESCAPE:  # Exit on ESC key press
                    running = False
                    
        screen.fill((0, 0, 0))  # Clear the screen with black
        pg.display.flip()  # Update the display
        
        clock.tick(60)  # Limit to 60 frames per second
    
    pg.quit()