from math import *

def add(*vectors):
    return tuple(map(sum, zip(*vectors)))

def scale(scalar, vector):
    return tuple(coord * scalar for coord in vector)

def length(vector):
    return sqrt(sum(coord ** 2 for coord in vector))

def subtract(v1, v2):
    return tuple((v1[0]-v2[0], v1[1]-v2[1], v1[2]-v2[2]))

def distance(v1, v2):
    return length(subtract(v1, v2))

# angle是弧度值 360 = 2*pi
def to_cartesian(polar_vector):
    length, angle = polar_vector[0], polar_vector[1]
    return (length * cos(angle), length * sin(angle))

def to_polar(vector):
    x, y = vector[0], vector[1]
    angle = atan2(y, x)
    return (length(vector), angle)

def dot(u, v):
    return sum(coord1 * coord2 for coord1, coord2 in zip(u, v))

def angle_between(v1, v2):
    return acos(
        dot(v1, v2) / (length(v1) * length(v2))
    )

def component(v, direction):
    return (dot(v, direction) / length(direction))

def cross(u, v):
    ux, uy, uz = u
    vx, vy, vz = v
    return (uy*vz-uz*vy, uz*vx-ux*vz, ux*vy-uy*vx)

def vector_to_2d(v):
    return (component(v, right), component(v, top))

def face_to_2d(face):
    return [vector_to_2d(vertex) for vertex in face]

def unit(v):
    return scale(1./length(v), v) # 将向量根据其长度缩放成单位1向量

def normal(face):
    return (cross(subtract(face[1], face[0]), subtract(face[2], face[0])))

def compose(*args):
    def new_function(input_value):
        state = input_value
        for f in reversed(args):
            state = f(state)
        return state
    return new_function

def linear_combination(scalars, *vectors):
    scaled = [scale(s, v) for s, v in zip(scalars, vectors)]
    return add(*scaled)

def transform_standard_basis(transform):
    return (transform((1, 0, 0)), transform((0, 1, 0)), transform((0, 0, 1)))

def multiply_matrix_vector(matrix, vector):
    return linear_combination(vector, *zip(*matrix))

def multiply_matrix_vector3(matrix, vector): # 同multiply_matrix_vector
    return tuple(
        dot(row, vector) for row in matrix
    )

def matrix_multiply(a, b):
    return tuple(
        tuple(dot(row, col) for col in zip(*b)) for row in a
    )

def infer_matrix(n, transformation):
    def standard_basis_vector(i):
        return tuple(1 if i == j else 0 for j in range(n))
    standard_basis = [standard_basis_vector(i) for i in range(n)]

    cols = [transformation(v) for v in standard_basis]

    return tuple(zip(*cols))

def transpose(matrix):
    return tuple(zip(matrix))