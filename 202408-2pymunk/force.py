import pymunk

space = pymunk.Space()

body = pymunk.Body()
body.position = (100, 100)

shape = pymunk.Circle(body, 20)
shape.mass = 1

space.add(body, shape)

'''
给物体施加力
'''
def func():
    body.apply_force_at_local_point((10, 5), (0, 0))

if __name__ == "__main__":
    import util
    util.run(space, func)