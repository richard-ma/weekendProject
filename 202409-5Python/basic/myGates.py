class LogicGate:
    def __init__(self, label=''):
        self._label = label
        self._output = None
    
    def get_label(self):
        return self._label
    
    def get_output(self):
        return self._output


class BinaryGate(LogicGate):
    def __init__(self, label=''):
        super().__init__(label)
        self._pins = [None, None]
    
    def set_pinA(self, out: LogicGate):
        self._pins[0] = out
    
    def set_pinB(self, out: LogicGate):
        self._pins[1] = out
    
    def get_pinA(self):
        return self._pins[0].get_output()

    def get_pinB(self):
        return self._pins[1].get_output()


class TrueInput(LogicGate):
    def __init__(self, label=''):
        super().__init__(label)
        self._output = True


class FalseInput(LogicGate):
    def __init__(self, label=''):
        super().__init__(label)
        self._output = False


class AndGate(BinaryGate):
    def __init__(self, label=''):
        super().__init__(label)

    def performGateLogic(self):
        return self.get_pinA() and self.get_pinB()

    def get_output(self):
        return self.performGateLogic()


if __name__ == "__main__":
    inputs = [TrueInput('True'), FalseInput('False')]
    andGate = AndGate('AND')
    andGate2 = AndGate('AND2')
    andGate.set_pinA(inputs[0])
    andGate.set_pinB(inputs[0])
    andGate2.set_pinA(andGate)
    andGate2.set_pinB(inputs[0])
    print(andGate2.get_pinA())
    print(andGate2.get_pinB())
    print(andGate2.performGateLogic())