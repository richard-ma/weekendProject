#!/usr/bin/env python3

class Stack:
    def __init__(self) -> None:
        self._data = list()

    def isEmpty(self):
        return len(self._data) == 0

    def push(self, value):
        self._data.append(value)

    def pop(self):
        return self._data.pop()

    def peek(self):
        return self._data[-1]

    def size(self):
        return len(self._data)
        

if __name__ == "__main__":
    s=Stack()

    print(s.isEmpty())
    s.push(4)
    s.push('dog')
    print(s.peek())
    s.push(True)
    print(s.size())
    print(s.isEmpty())
    s.push(8.4)
    print(s.pop())
    print(s.pop())
    print(s.size())
