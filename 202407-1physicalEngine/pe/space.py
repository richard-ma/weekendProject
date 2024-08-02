import numpy as np
from body import Body


class Space:
    def __init__(self):
        self._bodies = list()

    def append(self, body: Body):
        self._bodies.append(body)

    def run(self, t):
        for body in self._bodies:
            # v1 = v0 + a * t
            body._v = body._a * t + body._v
            # p1 = p0 + v * t
            body._p = body._v * t + body._p
            body._a = np.zeros(3)

if __name__ == "__main__":
    b = Body(1.)
    F = np.array([1., 0., 0.])
    zero_F = np.array([0., 0., 0.])

    s = Space()
    s.append(b)

    for time in range(100):
        s.run(1)
        for body in s._bodies:
            if time < 5:
                b.force(F)
            elif time == 5:
                b.force(zero_F)
            if time >= 95:
                b.force(-F)
            print(time, body.get_position())
