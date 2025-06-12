import pygame
import random


if __name__ == "__main__":
    pygame.init()
    screen = pygame.display.set_mode((800, 600))
    pygame.display.set_caption("Reaction Timer")
    clock = pygame.time.Clock()

    # Load images
    red_image = pygame.image.load("red.png")
    green_image = pygame.image.load("green.png")

    # Set initial state
    current_image = None
    start_time = None
    reaction_time = None

    running = True
    while running:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False

            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_ESCAPE:
                    running = False

                if current_image is not None and event.key == pygame.K_SPACE:
                    reaction_time = pygame.time.get_ticks() - start_time
                    print(f"Your reaction time is {reaction_time} ms")
                    current_image = None

        # Clear the screen
        screen.fill((255, 255, 255))

        # Draw the current image if it exists
        if current_image is not None:
            screen.blit(current_image, (400, 300))

        # Update the display
        pygame.display.flip()
        clock.tick(60)

        # Randomly show red or green image after a delay
        if current_image is None:
            start_time = pygame.time.get_ticks()
            current_image = red_image if random.randint(0, 1) == 0 else green_image

    pygame.quit()