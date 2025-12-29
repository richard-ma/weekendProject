import pygame
import time
import threading

# 初始化Pygame
pygame.init()
SCREEN_WIDTH = 800
SCREEN_HEIGHT = 600
screen = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT))
pygame.display.set_caption("交通信号灯模拟")

# 定义颜色
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)
RED = (255, 0, 0)
GREEN = (0, 255, 0)
YELLOW = (255, 255, 0)

# 定义交通信号灯类
class TrafficLight:
    def __init__(self):
        self.color = GREEN

    def change_color(self):
        while True:
            self.color = RED
            time.sleep(5) # 红灯持续5秒
            self.color = GREEN
            time.sleep(5) # 绿灯持续5秒

# 定义行人类
class Pedestrian:
    def __init__(self):
        self.position = (100, 300)

    def move(self):
        while True:
            time.sleep(2) # 模拟行人移动逻辑

# 定义车辆类
class Car:
    def __init__(self):
        self.position = (700, 300)

    def move(self):
        while True:
            time.sleep(1) # 模拟车辆移动逻辑

# 绘制函数
def draw():
    screen.fill(WHITE)
    pygame.draw.circle(screen, traffic_light.color, (SCREEN_WIDTH // 2, SCREEN_HEIGHT // 2 - 100), 50) # 信号灯
    pygame.draw.rect(screen, BLACK, (pedestrian.position[0], pedestrian.position[1], 20, 20)) # 行人
    pygame.draw.rect(screen, BLACK, (car.position[0], car.position[1], 40, 20)) # 车辆
    pygame.display.flip()

# 实例化对象
traffic_light = TrafficLight()
pedestrian = Pedestrian()
car = Car()

# 创建并启动线程
traffic_thread = threading.Thread(target=traffic_light.change_color)
pedestrian_thread = threading.Thread(target=pedestrian.move)
car_thread = threading.Thread(target=car.move)

traffic_thread.start()
pedestrian_thread.start()
car_thread.start()

# 游戏循环
clock = pygame.time.Clock()
running = True
while running:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False
    draw()
    clock.tick(60)

pygame.quit()