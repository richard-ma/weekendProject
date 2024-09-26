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

# 栈应用：括号匹配检测
def parChecker(symbolString):
    s = Stack()
    balanced = True # 标记括号是否匹配
    index = 0
    while index < len(symbolString) and balanced:
        symbol = symbolString[index]
        if symbol in "([{":
            s.push(symbol)
        else:
            if s.isEmpty():
                balanced = False
            else:
                top = s.pop()
                if not matches(top, symbol):
                    balanced = False
        
        index += 1
    
    if balanced and s.isEmpty(): # 如果栈中有剩余括号，就说明没有完全匹配
        return True
    else:
        return False

def matches(open, close):
    opens = "([{"
    closers = ")]}"
    return opens.index(open) == closers.index(close)
        

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


    print(parChecker('((()))'))
    print(parChecker('((())'))

    print(parChecker('{({([][])}())}'))
    print(parChecker('[{()]'))