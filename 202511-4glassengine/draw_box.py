from glass_engine import *
from glass_engine.Geometries import *
from glass_engine.Lights import *

# 创建空场景
scene = Scene()
# 添加几何体
box = Box(Lx=0.3, Ly=0.3, Lz=1)
box.position.z = 1
scene.add(box)
# 添加地板
floor = Floor()
scene.add(floor)
# 添加光源
light = DirLight()
light.pitch = -45
light.yaw = 45
scene.add(light)
# 添加相机
camera = Camera()
camera.position.z = 1.7
camera.position.y = -5
camera.pitch = -15
scene.add(camera)

camera.screen.show()