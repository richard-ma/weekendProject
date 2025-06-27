import pygame as pg
import random

WIDTH = 800
HEIGHT = 600

class Ball:
    """A class representing a ball in the game."""

    def __init__(self, x, y, radius, dx=1, dy=1, color=(255, 255, 255)):
        self.x = x
        self.y = y
        self.radius = radius
        self.dx = dx 
        self.dy = dy 
        self.color = color  # Default color white

    def draw(self, screen):
        """Draw the ball on the given screen."""
        pg.draw.circle(screen, self.color, (self.x, self.y), self.radius)

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
    pause_flg = False  # Flag to control pause state

    balls_number = 100 # Number of balls to create
    balls = []  # List to hold multiple balls if needed
    for i in range(balls_number):
        # Create multiple balls with random positions and velocities
        x = random.randint(20, WIDTH - 20)
        y = random.randint(20, HEIGHT - 20)
        radius = 10
        dx = random.choice(range(-5, 6))  # Random horizontal velocity
        dy = random.choice(range(-5, 6))  # Random vertical velocity
        if dx == 0 and dy == 0:
            dx = 3
            dy = 3
        color = (0, random.randint(0, 255), random.randint(0, 255))  # Random color
        balls.append(Ball(x, y, radius, dx, dy, color))

    while running:
        for event in pg.event.get():
            if event.type == pg.QUIT:
                running = False
            elif event.type == pg.KEYDOWN:
                if event.key == pg.K_ESCAPE:  # Exit on ESC key press
                    running = False
                elif event.key == pg.K_SPACE: # Toggle pause on space key press
                    pause_flg = not pause_flg
                    
        screen.fill((0, 0, 0))  # Clear the screen with black
        for ball in balls:
            if not pause_flg:
                ball.update()  # Update the ball's position
            ball.draw(screen)  # Call the draw function to draw the ball
        pg.display.flip()  # Update the display
        
        clock.tick(60)  # Limit to 60 frames per second
    
    pg.quit()