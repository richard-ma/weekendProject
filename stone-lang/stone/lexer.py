import re
from io import IOBase
from collections import deque
from token import *


class Reader:
    def __init__(self, f: IOBase):
        self._f = f


class LineNumberReader(Reader):
    def __init__(self, f: IOBase):
        super().__init__(f)
        self._lineNumber = 0

    def readline(self):
        self._lineNumber += 1
        line = self._f.readline()
        return line if line != "" else None

    def resetLineNumber(self, lineNumber=0) -> None:
        self._lineNumber = lineNumber

    def getLineNumber(self) -> int:
        return self._lineNumber


class Lexer:
    def __init__(self, reader: Reader):
        self.regexPat = "\\s*((//.*)|([0-9]+)|(\"(\\\\\"|\\\\\\\\|\\\\n|[^\"])*\")" + \
                "|[A-Za-z][A-Za-z0-9]*|==|<=|>=|&&|\\|\\||[`~!@#\$%\^&\*\(\)-=_+\[\]\\\{\}\|;':\",./<>\?])?"
        self.pattern = re.compile(self.regexPat)
        self.hasMore = True
        self.reader = reader
        self.queue = deque()

    def read(self):
        if self.fillQueue(0):
            return self.queue.popleft()
        else:
            return Token.EOF

    def peek(self, i: int):
        if self.fillQueue(i):
            return self.queue[i]
        else:
            return Token.EOF

    def fillQueue(self, i: int) -> bool:
        while i >= len(self.queue):
            if self.hasMore:
                self.readline()
            else:
                return False
        return True

    def readline(self):
        try:
            line = self.reader.readline()
        except Exception as e:
            raise Exception(e)
        
        if line is None:
            self.hasMore = False
            return
        
        lineNo = self.reader.getLineNumber()
        pos = 0
        endPos = len(line)
        while pos < endPos:
            matcher = self.pattern.match(line, pos, endPos)
            if matcher:
                self.addToken(lineNo, matcher)
                pos = matcher.end()
            else:
                raise Exception("Bad token at line " + lineNo)
        self.queue.append(IdToken(lineNo, Token.EOL))

    def addToken(self, lineNo, matcher):
        m = matcher.group(1)
        if m is not None:
            if matcher.group(2) is None:
                if matcher.group(3) is not None:
                    token = NumToken(lineNo, int(m))
                elif matcher.group(4) is not None:
                    token = StrToken(lineNo, self.toStringLiteral(str(m)))
                else:
                    token = IdToken(lineNo, m)
                self.queue.append(token)

    def toStringLiteral(self, s):
        sb = list()
        l = len(s)
        i = 0
        while i < l:
            c = s[i]
            if c == '\\' and i + 1 < l:
                c2 = s[i+1]
                if c2 == '"' or c2 == '\\':
                    c = s[i+1]
                    i += 1
                elif c2 == 'n':
                    c = '\n'
                    i += 1
            sb.append(c)
            i += 1
        return ''.join(sb)


if __name__ == "__main__":
    with open("samples/first.stone", 'r') as f:
        reader = LineNumberReader(f)
        lexer = Lexer(reader)
        token = lexer.read()
        while token != None: 
            print(token.getLineNumber(), token.getText())
            token = lexer.read()