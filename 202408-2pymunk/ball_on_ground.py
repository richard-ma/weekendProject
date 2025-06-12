import pymunk

space = pymunk.Space()
space.gravity = (0, 100)

circle_body = pymunk.Body()
circle_body.position = (100, 100)
circle_shape = pymunk.Circle(circle_body, 20)
circle_shape.mass = 1
space.add(circle_body, circle_shape)

ground_body = pymunk.Body(body_type=pymunk.Body.STATIC)
ground_body.position = (0, 500)
ground_shape = pymunk.Segment(ground_body, (0, 0), (1000, 0), 1)
ground_shape.mass = 1
space.add(ground_body, ground_shape)

circle_shape.elasticity = 0.8
ground_shape.elasticity = 1 


if __name__ == "__main__":
    import util
    util.run(space)