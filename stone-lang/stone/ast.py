from stone.token import *


class ASTree:
    def __init__(self):
        pass

    def child(self, i: int):
        pass

    def children(self):
        pass

    def numChildren(self):
        pass

    def location(self):
        pass


class ASTLeaf(ASTree):
    def __init__(self, token: Token):
        self._empty = list()
        self._token = token

    def child(self, i: int):
        raise Exception("Index Out Of Bounds")

    def children(self):
        return iter(self._empty)

    def numChildren(self):
        return 0

    def location(self):
        return "at line " + str(self._token.getLineNumber())

    def token(self):
        return self._token

    def __str__(self):
        return self._token.getText()


class ASTList(ASTree):
    def __init__(self, l: list):
        self._children = l

    def child(self, i: int):
        return self._children[i]

    def children(self):
        return iter(self._children)

    def numChildren(self):
        return len(self._children)

    def location(self):
        for c in self._children:
            s = c.location()
            if s is not None:
                return s

        return None

    def __str__(self):
        sb = list()
        sb.append('(')
        sep = ""
        for c in self._children:
            sb.append(sep)
            sep = " "
            sb.append(str(c))
        sb.append(')')
        return "".join(sb)


class NumberLiteral(ASTLeaf):
    def __init__(self, token: Token):
        super().__init__(token)

    def value(self):
        return self.token().getNumber()


class Name(ASTLeaf):
    def __init__(self, token: Token):
        super().__init__(token)

    def name(self):
        return self.token().getText()


class BinaryExpr(ASTList):
    def __init__(self, l: list):
        super().__init__(l)

    def left(self):
        return self.child(0)

    def right(self):
        return self.child(2)

    def operator(self):
        return self.child(1).token().getText()


class PrimaryExpr(ASTList):
    def __init__(self, l: list):
        super().__init__(l)

    @staticmethod
    def create(c: list):
        return c[0] if len(c) == 1 else PrimaryExpr(c)


class NegativeExpr(ASTList):
    def __init__(self, l: list):
        super().__init__(l)

    def operand(self):
        return self.child(0)
    
    def __str__(self):
        return "-" + str(self.operand())