import unittest
from stone.ast import *
from stone.token import *


class TestASTLeaf(unittest.TestCase):
    def setUp(self):
        self._token = IdToken(33, "else")
        self._astleaf = ASTLeaf(self._token)

    def test_child(self):
        self.assertRaises(Exception, self._astleaf.child)

    def test_children(self):
        from collections.abc import Iterable
        assert isinstance(self._astleaf.children(), Iterable)

    def test_numChildren(self):
        assert self._astleaf.numChildren() == 0

    def test_location(self):
        assert self._astleaf.location() == "at line " + str(self._token.getLineNumber())

    def test_token(self):
        assert self._astleaf.token() is self._token

    def test_toString(self):
        assert str(self._astleaf) == self._token.getText()


class TestASTList(unittest.TestCase):
    def setUp(self):
        self._tokens = [
            IdToken(33, "else"),
            NumToken(66, 3),
            StrToken(99, "string_token")
        ]

        self._astleafs = [ASTLeaf(token) for token in self._tokens]
        self._astList = ASTList(self._astleafs)

    def test_child(self):
        assert self._astList.child(0) is self._astleafs[0]

    def test_children(self):
        for child in self._astList.children():
            assert child in self._astleafs

    def test_numChildren(self):
        assert self._astList.numChildren() == len(self._astleafs)

    def test_location(self):
        assert self._astList.location() == "at line " + str(self._astleafs[0].token().getLineNumber())
    
    def test_toString(self):
        assert str(self._astList) == "(else 3 string_token)"


class TestNumberLiteral(unittest.TestCase):
    def setUp(self):
        self._token = NumToken(66, 3)
        self._numberLiteral = NumberLiteral(self._token)    

    def test_value(self):
        assert self._numberLiteral.value() == self._token.getNumber()


class TestName(unittest.TestCase):
    def setUp(self):
        self._token = StrToken(99, "string_token")
        self._name = Name(self._token)    

    def test_name(self):
        assert self._name.name() == self._token.getText()


class TestBinaryExpr(unittest.TestCase):
    def setUp(self):
        self._tokens = [
            NumToken(66, 3),
            StrToken(66, "+"),
            NumToken(66, 4),
        ]

        self._astleafs = [ASTLeaf(token) for token in self._tokens]
        self._binaryExpr = BinaryExpr(self._astleafs)

    def test_left(self):
        assert self._binaryExpr.left() is self._astleafs[0]

    def test_right(self):
        assert self._binaryExpr.right() is self._astleafs[2]

    def test_operator(self):
        assert self._binaryExpr.operator() == self._tokens[1].getText()