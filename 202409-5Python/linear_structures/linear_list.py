#!/usr/bin/env python3

class List:
    def __init__(self):
        self._data = list()

    def add(self, val):
        if val not in self._data:
            self._data.append(val)

    def remove(self, val):
        self._data.remove(val)

    def search(self, val):
        return val in self._data

    def isEmpyt(self):
        return len(self._data) == 0

    def append(self, val):
        if val not in self._data:
            self._data.append(val)

    def index(self, val):
        return self._data.index(val)

    def insert(self, pos, val):
        self._data.insert(pos, val)

    def pop(self, pos=None):
        return self._data.pop(pos)
    