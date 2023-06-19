class Token:
    EOF = None #TODO end of file
    EOL = "\\n" # end of line

    def __init__(self, line: int):
        self.lineNumber = line

    def getLineNumber(self) -> int:
        return self.lineNumber

    def isIdentifier(self) -> bool:
        return False

    def isNumber(self) -> bool:
        return False

    def isString(self) -> bool:
        return False

    def getNumber(self) -> int:
        raise Exception("not number token")

    def getText(self) -> str:
        return ""

class NumToken(Token):
    def __init__(self, line: int, v: int):
        super().__init__(line)
        self.value = v

    def isNumber(self) -> bool:
        return True

    def getText(self) -> str:
        return str(self.value)

    def getNumber(self) -> int:
        return self.value

class IdToken(Token):
    def __init__(self, line: int, id: str):
        super().__init__(line)
        self.text = id
    
    def isIdentifier(self) -> bool:
        return True

    def getText(self) -> str:
        return self.text

class StrToken(Token):
    def __init__(self, line: int, string: str):
        super().__init__(line)
        self.literal = string

    def isStrng(self) -> bool:
        return True

    def getText(self) -> str:
        return self.literal