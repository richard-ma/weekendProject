#!/usr/bin/env python3

def toStr(n, base):
    convertString = "0123456789ABCDEF"
    if n < base:
        return convertString[n]
    else:
        return toStr(n//base, base) + convertString[n%base]

from ..linear_structures.linear_stack import Stack


rStack = Stack()

def stackToStr(n, base):
    convertString = "0123456789ABCDEF"
    while n > 0:
        if n < base:
            rStack.push(convertString[n])
        else:
            rStack.push(convertString[n % base])
        n = n // base
    res = ""
    while not rStack.isEmpty():
        res += str(rStack.pop())
    
    return res


if __name__ == "__main__":
    print(toStr(1453, 16))
    print(stackToStr(1453, 16))