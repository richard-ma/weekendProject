#!/usr/bin/env python3

class LogicGate:
    def __init__(self, n):
        self.label = n
        self.output = None

    def getLabel(self):
        return self.label

    def getOutput(self):
        self.output = self.performGateLogic()
        return self.output


class TrueInput(LogicGate):
    def __init__(self, n):
        super().__init__(n)
    
    def getOutput(self):
        self.output = 1
        return self.output


class FalseInput(LogicGate):
    def __init__(self, n):
        super().__init__(n)
    
    def getOutput(self):
        self.output = 0
        return self.output


class BinaryGate(LogicGate):
    def __init__(self, n):
        super().__init__(n)
        self.pinA = None
        self.pinB = None

    def getPinA(self):
        if self.pinA == None:
            return int(input("Enter Pin A input for gete " + \
                self.getLabel() + "-->"))
        else:
            return self.pinA.getFrom().getOutput()

    def getPinB(self):
        if self.pinB == None:
            return int(input("Enter Pin B input for gete " + \
                self.getLabel() + "-->"))
        else:
            return self.pinB.getFrom().getOutput()


class UnaryGate(LogicGate):
    def __init__(self, n):
        super().__init__(n)

        self.pin = None

    def getPin(self):
        if self.pin == None:
            return int(input("Enter Pin input for gete " + \
                self.getLabel() + "-->"))
        else:
            return self.pin.getFrom().getOutput()


class AndGate(BinaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        a = self.getPinA()
        b = self.getPinB()
        if a == 1 and b == 1:
            return 1
        else:
            return 0

    def setNextPin(self, source):
        if self.pinA == None:
            self.pinA = source
        else:
            if self.pinB == None:
                self.pinB = source
            else:
                raise RuntimeError("Error NO EMPTY PINS")


class OrGate(BinaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        a = self.getPinA()
        b = self.getPinB()
        if a == 1 or b == 1:
            return 1
        else:
            return 0

    def setNextPin(self, source):
        if self.pinA == None:
            self.pinA = source
        else:
            if self.pinB == None:
                self.pinB = source
            else:
                raise RuntimeError("Error NO EMPTY PINS")


class NotGate(UnaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        val = self.getPin()
        if val == 1:
            return 0
        else:
            return 1

    def setNextPin(self, source):
        if self.pin == None:
            self.pin = source
        else:
            raise RuntimeError("Error NO EMPTY PINS")


# 练习1.8 10题：NAND, NOR, XOR的实现
class NAndGate(BinaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        a = self.getPinA()
        b = self.getPinB()
        if a == 0 or b == 0:
            return 1
        else:
            return 0

    def setNextPin(self, source):
        if self.pinA == None:
            self.pinA = source
        else:
            if self.pinB == None:
                self.pinB = source
            else:
                raise RuntimeError("Error NO EMPTY PINS")


class NOrGate(BinaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        a = self.getPinA()
        b = self.getPinB()
        if a == 0 and b == 0:
            return 1
        else:
            return 0

    def setNextPin(self, source):
        if self.pinA == None:
            self.pinA = source
        else:
            if self.pinB == None:
                self.pinB = source
            else:
                raise RuntimeError("Error NO EMPTY PINS")


class XOrGate(BinaryGate):
    def __init__(self, n):
        super().__init__(n)

    def performGateLogic(self):
        a = self.getPinA()
        b = self.getPinB()
        if a ==  b:
            return 0
        else:
            return 1

    def setNextPin(self, source):
        if self.pinA == None:
            self.pinA = source
        else:
            if self.pinB == None:
                self.pinB = source
            else:
                raise RuntimeError("Error NO EMPTY PINS")


class Connector:
    def __init__(self, fgate, tgate):
        self.fromgate = fgate
        self.togate = tgate

        tgate.setNextPin(self)

    def getFrom(self):
        return self.fromgate

    def getTo(self):
        return self.togate


if __name__ == "__main__":
    # g1 = AndGate("G1")
    # print(g1.getOutput())

    # g2 = OrGate("G2")
    # print(g2.getOutput())

    # g3 = NotGate("G3")
    # print(g3.getOutput())

    # g1 = AndGate("G1")
    # g2 = AndGate("G2")
    # g3 = OrGate("G3")
    # g4 = NotGate("G4")
    # c1 = Connector(g1, g3)
    # c2 = Connector(g2, g3)
    # c3 = Connector(g3, g4)
    # print(g4.getOutput())

    # g1 = NAndGate("NAnd")
    # print(g1.getOutput())
    # g2 = NOrGate("NOr")
    # print(g2.getOutput())
    # g3 = XOrGate("XOr")
    # print(g3.getOutput())

# 练习1.8 11题：HALF-ADDER的实现
    g1 = XOrGate("XOR")
    g2 = AndGate("AND")
    in1 = TrueInput("TrueInput")
    in2 = FalseInput("FalseInput")
    c1 = Connector(in1, g1)
    c2 = Connector(in2, g1)
    c3 = Connector(in1, g2)
    c4 = Connector(in2, g2)
    print(g1.getOutput())
    print(g2.getOutput())
