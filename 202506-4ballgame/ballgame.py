import pygame as pg
import random

WIDTH = 800
HEIGHT = 600

class Ball:
    """A class representing a ball in the game."""

    def __init__(self, x, y, radius, dx=1, dy=1):
        self.x = x
        self.y = y
        self.radius = radius
        self.dx = dx 
        self.dy = dy 

    def draw(self, screen):
        """Draw the ball on the given screen."""
        pg.draw.circle(screen, (255, 255, 255), (self.x, self.y), self.radius)

    def update(self):
        """Update the game state."""
        # Here you can add logic to update the ball's position or handle input
        if self.x + self.radius >= WIDTH or self.x - self.radius <= 0:
            self.dx = -self.dx  # Reverse direction if hitting the left or right wall
        if self.y + self.radius >= HEIGHT or self.y - self.radius <= 0:
            self.dy = -self.dy  # Reverse direction if hitting the top or bottom wall
        self.x += self.dx
        self.y += self.dy

if  __name__ == "__main__":
    pg.init()
    screen = pg.display.set_mode((800, 600))
    pg.display.set_caption("Ball Game")
    
    clock = pg.time.Clock()
    running = True

    dx = random.choice([-3, 3])  # Randomly choose initial direction
    dy = random.choice([-3, 3])  # Randomly choose initial direction
    ball = Ball(400, 300, 10, dx, dy)

    while running:
        for event in pg.event.get():
            if event.type == pg.QUIT:
                running = False
        
        screen.fill((0, 0, 0))  # Clear the screen with black
        ball.update() # Update the ball's position
        ball.draw(screen)  # Call the draw function to draw the ball
        pg.display.flip()  # Update the display
        
        clock.tick(60)  # Limit to 60 frames per second
    
    pg.quit()