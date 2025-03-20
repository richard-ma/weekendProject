class Cell:
    def __init__(self, value: int = None):
        if value is None:
            self._value = 0
        else:
            self._value = value 

    def value(self, value: int = None):
        if value is not None:
            self._value = value

        return self._value
    
    def __eq__(self, value):
        return self._value == value

    def __ne__(self, value):
        return self._value != value

    def __gt__(self, value):
        return self._value > value

    def __ge__(self, value):
        return self._value >= value
    
    def __lt__(self, value):
        return self._value < value
    
    def __le__(self, value):
        return self._value <= value
