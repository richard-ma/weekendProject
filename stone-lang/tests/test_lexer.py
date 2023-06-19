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
        assert self.reader.readline() == "line one"
        assert self.reader.getLineNumber() == 1
        assert self.reader.readline() == "line two"
        assert self.reader.getLineNumber() == 2

    def test_resetLineNumber(self):
        self.reader.resetLineNumber()
        assert self.reader.getLineNumber() == 0

        self.reader.resetLineNumber(3)
        assert self.reader.getLineNumber() == 3