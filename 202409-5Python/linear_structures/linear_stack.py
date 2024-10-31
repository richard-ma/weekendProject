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


# 栈应用：十进制转换二进制
def divideBy2(decNumber):
    remstack = Stack()

    while decNumber > 0:
        rem = decNumber % 2
        remstack.push(rem)
        decNumber = decNumber // 2

    binString = ""
    while not remstack.isEmpty():
        binString = binString + str(remstack.pop())

    return binString

def baseConverter(decNumber, base):
    digits = "0123456789ABCDEF"

    remstack = Stack()

    while decNumber > 0:
        rem = decNumber % base
        remstack.push(rem)
        decNumber = decNumber // base

    newString = ""
    while not remstack.isEmpty():
        newString = newString + digits[remstack.pop()]
    
    return newString


# 栈应用：中序表达式变前序表达式（波兰表达式）
def infixToPrefix(infixexpr):
    prec = {}
    prec['*'] = 3
    prec['/'] = 3
    prec['+'] = 2
    prec['-'] = 2
    prec['('] = 1
    opStack = Stack()
    postfixList = []
    tokenList = infixexpr.split()

    for token in tokenList:
        if token in "ABCDEFGHIJKLMNOPQRSTUVWXYZ" or token in "0123456789":
            postfixList.append(token)
        elif token == '(':
            opStack.push(token)
        elif token == ')':
            topToken = opStack.pop()
            while topToken != '(':
                postfixList.append(topToken)
                topToken = opStack.pop()
        else:
            while (not opStack.isEmpty()) and \
                (prec[opStack.peek()] >= prec[token]):
                    postfixList.append(opStack.pop())
            opStack.push(token)
    
    while not opStack.isEmpty():
        postfixList.append(opStack.pop())
    
    return " ".join(postfixList)


# 栈应用：中序表达式变后序表达式（逆波兰表达式）
def infixToPostfix(infixexpr):
    prec = {}
    prec['*'] = 3
    prec['/'] = 3
    prec['+'] = 2
    prec['-'] = 2
    prec['('] = 1
    opStack = Stack()
    postfixList = []
    tokenList = infixexpr.split()

    for token in tokenList:
        if token in "ABCDEFGHIJKLMNOPQRSTUVWXYZ" or token in "0123456789":
            postfixList.append(token)
        elif token == '(':
            opStack.push(token)
        elif token == ')':
            topToken = opStack.pop()
            while topToken != '(':
                postfixList.append(topToken)
                topToken = opStack.pop()
        else:
            while (not opStack.isEmpty()) and \
                (prec[opStack.peek()] >= prec[token]):
                    postfixList.append(opStack.pop())
            opStack.push(token)
    
    while not opStack.isEmpty():
        postfixList.append(opStack.pop())
    
    return " ".join(postfixList)


def postfixEval(postfixExpr):
    operandStack = Stack()
    tokenList = postfixExpr.split()

    for token in tokenList:
        if token in "0123456789":
            operandStack.push(int(token))
        else:
            operand2 = operandStack.pop()
            operand1 = operandStack.pop()
            result = doMath(token, operand1, operand2)
            operandStack.push(result)

    return operandStack.pop()

def doMath(op, op1, op2):
    if op == "*":
        return op1 * op2
    elif op == "/":
        return op1 / op2
    elif op == "+":
        return op1 + op2
    else:
        return op1 - op2
        

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

    print(divideBy2(42))

    print(baseConverter(25, 2))
    print(baseConverter(25, 8))
    print(baseConverter(25, 10))
    print(baseConverter(25, 16))

    print(infixToPostfix("A * B + C * D"))
    print(infixToPostfix("( A + B ) * C - ( D - E ) * ( F + G )"))

    print(postfixEval('7 8 + 3 2 + /'))
    print(postfixEval(infixToPostfix("( 7 + 8 ) / ( 3 + 2 )")))

# Discussion Questions 1
    print(divideBy2(17))
    print(divideBy2(45))
    print(divideBy2(96))

# Discussion Questions 3
    print(infixToPrefix("( A + B ) * ( C + D ) * ( E + F )"))
    print(infixToPrefix("A + ( ( B + C ) * ( D + E ) )"))
    print(infixToPrefix("A * B * C * D + E + F"))

# Discussion Questions 3
    print(infixToPostfix("( A + B ) * ( C + D ) * ( E + F )"))
    print(infixToPostfix("A + ( ( B + C ) * ( D + E ) )"))
    print(infixToPostfix("A * B * C * D + E + F"))