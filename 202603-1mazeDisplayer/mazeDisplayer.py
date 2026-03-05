import pygame
import random
import sys
import os

# 初始化Pygame
pygame.init()

# 常量定义
WIDTH, HEIGHT = 800, 600
CELL_SIZE = 20  # 每个迷宫格子的大小
GRID_WIDTH = (WIDTH - 200) // CELL_SIZE  # 迷宫区域宽度（留出右侧控制面板）
GRID_HEIGHT = HEIGHT // CELL_SIZE        # 迷宫区域高度

# 颜色定义
BLACK = (0, 0, 0)
WHITE = (255, 255, 255)
GRAY = (200, 200, 200)
RED = (255, 0, 0)
GREEN = (0, 255, 0)
BLUE = (100, 149, 237)
HOVER_BLUE = (135, 180, 240)
YELLOW = (255, 255, 0)

# 设置窗口
screen = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("迷宫显示软件 - 无Tkinter依赖")

# 字体设置
font = pygame.font.Font(None, 24)
title_font = pygame.font.Font(None, 36)

# 文件输入框状态
input_active = False
input_text = ""
input_rect = pygame.Rect(GRID_WIDTH * CELL_SIZE + 20, 260, 160, 30)

class Maze:
    def __init__(self, width, height):
        self.width = width
        self.height = height
        # 初始化迷宫网格：True表示有墙，False表示无墙
        self.grid = [[True for _ in range(width)] for _ in range(height)]
        self.start = (0, 0)
        self.end = (width-1, height-1)
        
    def reset(self):
        """重置迷宫（全为墙壁）"""
        self.grid = [[True for _ in range(self.width)] for _ in range(self.height)]
        
    def generate_maze(self):
        """使用深度优先搜索生成随机迷宫"""
        self.reset()
        
        # 起始点设为通路
        self.grid[self.start[1]][self.start[0]] = False
        
        # 深度优先搜索栈
        stack = [self.start]
        visited = set([self.start])
        
        # 四个方向：上、右、下、左
        directions = [(-2, 0), (2, 0), (0, -2), (0, 2)]
        
        while stack:
            current_x, current_y = stack[-1]
            
            # 寻找未访问的邻居
            neighbors = []
            for dx, dy in directions:
                nx, ny = current_x + dx, current_y + dy
                if (0 <= nx < self.width and 
                    0 <= ny < self.height and 
                    (nx, ny) not in visited):
                    neighbors.append((nx, ny))
            
            if neighbors:
                # 随机选择一个邻居
                next_x, next_y = random.choice(neighbors)
                visited.add((next_x, next_y))
                
                # 打通当前格子和邻居之间的墙
                mid_x = (current_x + next_x) // 2
                mid_y = (current_y + next_y) // 2
                self.grid[next_y][next_x] = False
                self.grid[mid_y][mid_x] = False
                
                stack.append((next_x, next_y))
            else:
                # 回溯
                stack.pop()
        
        # 确保终点是通路
        self.grid[self.end[1]][self.end[0]] = False

    def toggle_wall(self, x, y):
        """切换指定位置的墙壁状态（绘制/擦除）"""
        if 0 <= x < self.width and 0 <= y < self.height:
            self.grid[y][x] = not self.grid[y][x]
    
    def load_from_text_file(self, file_path):
        """从文本文件加载迷宫
        规则：空格 = 通路(False)，-|+ = 墙壁(True)
        """
        try:
            # 处理相对路径/绝对路径
            if not os.path.isabs(file_path):
                file_path = os.path.join(os.getcwd(), file_path)
            
            with open(file_path, 'r', encoding='utf-8') as f:
                lines = [line.rstrip('\n') for line in f.readlines()]
            
            # 获取文本迷宫的尺寸
            maze_height = len(lines)
            maze_width = max(len(line) for line in lines) if maze_height > 0 else 0
            
            # 限制迷宫尺寸不超过显示区域
            maze_width = min(maze_width, GRID_WIDTH)
            maze_height = min(maze_height, GRID_HEIGHT)
            
            # 重置并调整迷宫尺寸
            self.width = maze_width
            self.height = maze_height
            self.grid = [[True for _ in range(maze_width)] for _ in range(maze_height)]
            
            # 解析每一行的字符
            for y in range(maze_height):
                if y >= len(lines):
                    break
                line = lines[y]
                for x in range(maze_width):
                    if x >= len(line):
                        continue
                    char = line[x]
                    # 空格表示通路，-|+表示墙壁
                    if char == ' ':
                        self.grid[y][x] = False
                    elif char in '-|+':
                        self.grid[y][x] = True
            
            # 更新起点和终点
            self.start = (0, 0)
            self.end = (self.width-1, self.height-1)
            
            # 确保起点和终点是通路（如果被墙壁覆盖）
            self.grid[self.start[1]][self.start[0]] = False
            self.grid[self.end[1]][self.end[0]] = False
            
            return True
        except FileNotFoundError:
            print(f"错误：找不到文件 '{file_path}'")
            return False
        except Exception as e:
            print(f"加载迷宫文件失败: {e}")
            return False

    def draw(self, surface):
        """绘制迷宫"""
        # 绘制迷宫背景
        surface.fill(WHITE)
        
        # 绘制迷宫格子
        for y in range(self.height):
            for x in range(self.width):
                rect = pygame.Rect(
                    x * CELL_SIZE, 
                    y * CELL_SIZE, 
                    CELL_SIZE, 
                    CELL_SIZE
                )
                if self.grid[y][x]:
                    pygame.draw.rect(surface, BLACK, rect)
                else:
                    pygame.draw.rect(surface, WHITE, rect)
                    pygame.draw.rect(surface, GRAY, rect, 1)
        
        # 标记起点和终点
        start_rect = pygame.Rect(
            self.start[0] * CELL_SIZE, 
            self.start[1] * CELL_SIZE, 
            CELL_SIZE, 
            CELL_SIZE
        )
        end_rect = pygame.Rect(
            self.end[0] * CELL_SIZE, 
            self.end[1] * CELL_SIZE, 
            CELL_SIZE, 
            CELL_SIZE
        )
        pygame.draw.rect(surface, GREEN, start_rect)
        pygame.draw.rect(surface, RED, end_rect)
        
        # 绘制控制面板分隔线
        pygame.draw.line(surface, BLACK, (GRID_WIDTH * CELL_SIZE, 0), 
                         (GRID_WIDTH * CELL_SIZE, HEIGHT), 2)

def draw_controls(surface, maze):
    """绘制控制面板"""
    # 控制面板背景
    control_rect = pygame.Rect(GRID_WIDTH * CELL_SIZE, 0, 200, HEIGHT)
    pygame.draw.rect(surface, BLUE, control_rect)
    
    # 标题
    title_text = title_font.render("迷宫控制", True, WHITE)
    surface.blit(title_text, (GRID_WIDTH * CELL_SIZE + 20, 20))
    
    # 按钮样式
    button_color = (70, 130, 180)
    button_hover_color = HOVER_BLUE
    
    # 生成迷宫按钮
    generate_rect = pygame.Rect(GRID_WIDTH * CELL_SIZE + 20, 80, 160, 40)
    mouse_pos = pygame.mouse.get_pos()
    if generate_rect.collidepoint(mouse_pos):
        pygame.draw.rect(surface, button_hover_color, generate_rect)
    else:
        pygame.draw.rect(surface, button_color, generate_rect)
    generate_text = font.render("Generate Random Maze", True, WHITE)
    surface.blit(generate_text, (GRID_WIDTH * CELL_SIZE + 40, 90))
    
    # 重置迷宫按钮
    reset_rect = pygame.Rect(GRID_WIDTH * CELL_SIZE + 20, 140, 160, 40)
    if reset_rect.collidepoint(mouse_pos):
        pygame.draw.rect(surface, button_hover_color, reset_rect)
    else:
        pygame.draw.rect(surface, button_color, reset_rect)
    reset_text = font.render("Reset Maze", True, WHITE)
    surface.blit(reset_text, (GRID_WIDTH * CELL_SIZE + 60, 150))
    
    # 加载文件按钮
    load_rect = pygame.Rect(GRID_WIDTH * CELL_SIZE + 20, 200, 160, 40)
    if load_rect.collidepoint(mouse_pos):
        pygame.draw.rect(surface, button_hover_color, load_rect)
    else:
        pygame.draw.rect(surface, button_color, load_rect)
    load_text = font.render("Load Text Maze", True, WHITE)
    surface.blit(load_text, (GRID_WIDTH * CELL_SIZE + 40, 210))
    
    # 文件路径输入框
    input_color = YELLOW if input_active else WHITE
    pygame.draw.rect(surface, input_color, input_rect, 2)
    input_surface = font.render(input_text, True, WHITE)
    surface.blit(input_surface, (input_rect.x + 5, input_rect.y + 5))
    
    # 操作说明
    instructions = [
        "Operation Instructions:",
        "• Left Mouse Button: Draw Walls",
        "• Right Mouse Button: Erase Walls",
        "• Enter File Path and Click Load",
        "• Text Rules: Space=Path, -=Wall"
    ]
    
    y_offset = 300
    for text in instructions:
        instruction_text = font.render(text, True, WHITE)
        surface.blit(instruction_text, (GRID_WIDTH * CELL_SIZE + 20, y_offset))
        y_offset += 30
    
    # 显示迷宫尺寸信息
    size_text = font.render(f"迷宫尺寸：{maze.width} × {maze.height}", True, WHITE)
    surface.blit(size_text, (GRID_WIDTH * CELL_SIZE + 20, HEIGHT - 40))

def main():
    """主函数"""
    global input_active, input_text
    
    # 创建迷宫实例
    maze = Maze(GRID_WIDTH, GRID_HEIGHT)
    
    # 时钟（控制帧率）
    clock = pygame.time.Clock()
    
    running = True
    while running:
        # 处理事件
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False
            
            # 鼠标点击事件
            elif event.type == pygame.MOUSEBUTTONDOWN:
                x, y = pygame.mouse.get_pos()
                
                # 点击输入框激活/取消
                if input_rect.collidepoint(x, y):
                    input_active = not input_active
                else:
                    input_active = False
                
                # 检查是否点击了控制面板按钮
                if x > GRID_WIDTH * CELL_SIZE:
                    # 生成迷宫按钮
                    if 80 <= y <= 120:
                        # 重置为默认尺寸再生成
                        maze.width = GRID_WIDTH
                        maze.height = GRID_HEIGHT
                        maze.generate_maze()
                    # 重置迷宫按钮
                    elif 140 <= y <= 180:
                        maze.reset()
                    # 加载文件按钮
                    elif 200 <= y <= 240 and input_text.strip():
                        maze.load_from_text_file(input_text.strip())
                else:
                    # 转换为迷宫格子坐标
                    cell_x = x // CELL_SIZE
                    cell_y = y // CELL_SIZE
                    
                    # 左键绘制墙壁，右键擦除墙壁
                    if event.button == 1:  # 左键
                        if 0 <= cell_x < maze.width and 0 <= cell_y < maze.height:
                            maze.grid[cell_y][cell_x] = True
                    elif event.button == 3:  # 右键
                        if 0 <= cell_x < maze.width and 0 <= cell_y < maze.height:
                            maze.grid[cell_y][cell_x] = False
            
            # 键盘输入（文件路径）
            elif event.type == pygame.KEYDOWN:
                if input_active:
                    if event.key == pygame.K_RETURN:
                        input_active = False
                    elif event.key == pygame.K_BACKSPACE:
                        input_text = input_text[:-1]
                    elif event.key == pygame.K_DELETE:
                        input_text = ""
                    else:
                        # 只允许输入文件路径相关字符
                        if event.unicode and event.unicode not in ['\t', '\n']:
                            input_text += event.unicode
            
            # 鼠标拖动（按住鼠标绘制/擦除）
            elif event.type == pygame.MOUSEMOTION:
                if pygame.mouse.get_pressed()[0] or pygame.mouse.get_pressed()[2]:
                    x, y = pygame.mouse.get_pos()
                    if x < GRID_WIDTH * CELL_SIZE:
                        cell_x = x // CELL_SIZE
                        cell_y = y // CELL_SIZE
                        if 0 <= cell_x < maze.width and 0 <= cell_y < maze.height:
                            if pygame.mouse.get_pressed()[0]:
                                maze.grid[cell_y][cell_x] = True
                            else:
                                maze.grid[cell_y][cell_x] = False
        
        # 绘制界面
        maze.draw(screen)
        draw_controls(screen, maze)
        
        # 更新显示
        pygame.display.flip()
        
        # 控制帧率为60 FPS
        clock.tick(60)
    
    # 退出程序
    pygame.quit()
    sys.exit()

if __name__ == "__main__":
    main()
