from io import IOBase

class Reader:
    def __init__(self, f: IOBase):
        self._f = f


class LineNumberReader(Reader):
    def __init__(self, f: IOBase):
        super().__init__(f)
        self._lineNumber = 0

    def readline(self):
        self._lineNumber += 1
        return self._f.readline().strip('\n')

    def resetLineNumber(self, lineNumber=0) -> None:
        self._lineNumber = lineNumber

    def getLineNumber(self) -> int:
        return self._lineNumber


class Lexer:
    def __init__(self):
        self.regexPat = "\\s*((//.*)|([0-9]+)|(\"(\\\\\"|\\\\\\\\|\\\\n|[^\"])*\")" + \
                "|[A-Za-z][A-Za-z0-9]*|==|<=|>=|&&|\\|\\||[`~!@#\$%\^&\*\(\)-=_+\[\]\\\{\}\|;':\",./<>\?])?"
        self.pattern = re.compile(self.regexPat)