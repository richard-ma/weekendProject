#!/usr/bin/env python3

set_a = {1, 2, 3}
set_b = {2, 3, 4}

print(1 in set_a) # True
print(1 in set_b) # False

# {1, 2, 3, 4}
print(set_a | set_b)
print(set_a.union(set_b))

# {2, 3}
print(set_a & set_b)
print(set_a.intersection(set_b))

# {1}
print(set_a - set_b)
print(set_a.difference(set_b))

print({1}.issubset({1, 2})) # True
print({1}.issubset({2, 4})) # False

set_a.add(4)
print(set_a) # {1, 2, 3, 4}
set_a.remove(4)
print(set_a) # {1, 2, 3}
print({1, 2}.pop()) # 1 随即移除一个元素
set_a.clear()
print(set_a) # set()