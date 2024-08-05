import pymunk

space = pymunk.Space()
space.damping = 0.8
'''
设置空气阻力，默认为1即不受空气阻力
0.8表示每秒运动损失20%的速度
'''

body = pymunk.Body()
body.position = (100, 100)
body.velocity = (50, 0)

shape = pymunk.Circle(body, 20)
shape.mass = 1

space.add(body, shape)

if __name__ == "__main__":
    import util
    util.run(space)