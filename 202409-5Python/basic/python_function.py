#!/usr/bin/env python3

# 牛顿法求平方根 导数
def squareroot(n):
    root = n / 2
    for k in range(20):
        root = (1/2) * (root + (n / root))

    return root

print(squareroot(4))
print(squareroot(16))
print(squareroot(25))