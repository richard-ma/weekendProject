#!/usr/bin/env python3

class Deque:
    def __init__(self):
        self._data = list()

    def isEmpty(self):
        return len(self._data) == 0
    
    def size(self):
        return len(self._data)

    def addRear(self, val):
        self._data.append(val)
    
    def addFront(self, val):
        self._data.insert(0, val)

    def removeRear(self):
        return self._data.pop()
    
    def removeFront(self):
        return self._data.pop(0)

# Palindrome-Checker
def palchecker(aString):
    chardeque = Deque()

    for ch in aString:
        chardeque.addRear(ch)

    stillEqual = True

    while chardeque.size() > 1 and stillEqual:
        first = chardeque.removeFront()
        last = chardeque.removeRear()
        if first != last:
            stillEqual = False
            break
    
    return stillEqual


if __name__ == "__main__":
    d=Deque()

    print(d.isEmpty())
    d.addRear(4)
    d.addRear('dog')
    d.addFront('cat')
    d.addFront(True)
    print(d.size())
    print(d.isEmpty())
    d.addRear(8.4)
    print(d.removeRear())
    print(d.removeFront())

    print(palchecker("lsdkjfskf"))
    print(palchecker("radar"))