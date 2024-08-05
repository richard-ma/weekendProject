import pymunk

space = pymunk.Space()

body = pymunk.Body(body_type=pymunk.Body.STATIC)
body.position = (100, 100)

shape = pymunk.Circle(body, 20)
shape.mass = 1

space.add(body, shape)

if __name__ == "__main__":
    import util
    util.run(space)