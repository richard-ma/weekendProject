import unittest
import io
from stone.lexer import *


class TestLineNumberReader(unittest.TestCase):
    def setUp(self):
        s = "line one\nline two\nline three\n"
        f = io.StringIO(s)
        self.reader = LineNumberReader(f)

    def test_initial(self):
        assert self.reader.getLineNumber() == 0

    def test_readline(self):
        assert self.reader.readline() == "line one\n"
        assert self.reader.getLineNumber() == 1
        assert self.reader.readline() == "line two\n"
        assert self.reader.getLineNumber() == 2

    def test_resetLineNumber(self):
        self.reader.resetLineNumber()
        assert self.reader.getLineNumber() == 0

        self.reader.resetLineNumber(3)
        assert self.reader.getLineNumber() == 3


class TestLexer(unittest.TestCase):
    def setUp(self):
        f = io.StringIO("even = 0\nodd = 0\ni = 1\nwhile i < 10 {\nif i % 2 == 0 { // even number\neven = even + 1\n} else {\nodd = odd + 1\n}\ni = i + 1\n}\neven + odd\n")
        reader = LineNumberReader(f)
        self.lexer = Lexer(reader)

    def test_toStringLiteral(self):
        assert self.lexer.toStringLiteral("hello") == "hello"
        assert self.lexer.toStringLiteral("\n") == '\n'
        assert self.lexer.toStringLiteral("\"") == '"'
        assert self.lexer.toStringLiteral("\\") == '\\'