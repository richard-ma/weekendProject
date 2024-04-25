#!/usr/bin/env python3

# pip install numpy
import numpy as np
# sudo apt-get install portaudio19-dev python3-all-dev
# pip install pyaudio
import pyaudio
# pip install pygame
import pygame

p = pyaudio.PyAudio()

stream = p.open(
    format=pyaudio.paInt16,
    channels=1, 
    rate=44100,
    input=True,
    frames_per_buffer=1024
)

pygame.init()
white = (255, 255, 255)
black = (0, 0, 0)
green = (0, 255, 0)
blue = (0, 0, 128)

x = 800
y = 800

display_surface = pygame.display.set_mode((x, y))
pygame.display.set_caption("Show")
font = pygame.font.Font(pygame.font.get_default_font(), 128)
text = font.render("NOISE!", True, green, blue)
textRect = text.get_rect()
textRect.center = (x // 2, y // 2)

loop = True
while loop:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            loop = False
    data = np.fromstring(stream.read(1024), dtype=np.int16)

    volume = np.max(data)
    print(volume)

    display_surface.fill(black)
    if volume > 8000:
        display_surface.blit(text, textRect)

    pygame.display.flip()

pygame.quit()
stream.stop_stream()
stream.close()
p.terminate()