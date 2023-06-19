import unittest
from stone.token import *


class TestToken(unittest.TestCase):
    def setUp(self):
        self.token = Token(1)
    
    def test_getLineNumber(self):
        assert self.token.getLineNumber() == 1

    def test_isIdentifier(self):
        assert self.token.isIdentifier() is False

    def test_isNumber(self):
        assert self.token.isNumber() is False

    def test_isString(self):
        assert self.token.isString() is False

    def test_getNumber(self):
        self.assertRaises(Exception, self.token.getNumber)

    def test_getText(self):
        assert self.token.getText() == ""


class TestNumToken(unittest.TestCase):
    def setUp(self):
        self.token = NumToken(1, 5)

    def test_isNumber(self):
        assert self.token.isNumber() is True

    def test_getText(self):
        assert self.token.getText() == "5"

    def test_getNumber(self):
        assert self.token.getNumber() == 5


class TestIdToken(unittest.TestCase):
    def setUp(self):
        self.token = IdToken(1, "else")

    def test_isIdentifier(self):
        assert self.token.isIdentifier() is True

    def test_getText(self):
        assert self.token.getText() == "else"