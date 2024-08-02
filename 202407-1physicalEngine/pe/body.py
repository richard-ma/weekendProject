import numpy as np


class Body:
    def __init__(self, m: float):
        self._m = m 
        self._v = np.zeros(3)
        self._a = np.zeros(3)
        self._p = np.zeros(3)

    def force(self, F):
        # F = ma -> a = F / m
        self._a = F / self._m
        
    def get_position(self):
        return self._p