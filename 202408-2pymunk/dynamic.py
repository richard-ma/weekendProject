import pymunk

space = pymunk.Space()
space.gravity = (0, 1000)
'''
设置重力
'''

body = pymunk.Body()
body.position = (100, 100)

shape = pymunk.Circle(body, 20)
shape.mass = 1

space.add(body, shape)

if __name__ == "__main__":
    import util
    util.run(space)