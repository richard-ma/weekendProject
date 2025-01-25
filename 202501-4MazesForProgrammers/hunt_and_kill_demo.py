from src.grid import *
from src.hunt_and_kill import *


if __name__ == "__main__":
    grid = Grid(20, 20)
    HuntAndKill().on(grid)
    
    filename = "hunt_and_kill.png"
    grid.to_png(filename)