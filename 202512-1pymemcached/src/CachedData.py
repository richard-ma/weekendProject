class CachedData:
    def __init__(self):
        self.store = dict()

    def set(self, key, value):
        self.store[key] = value

    def get(self, key):
        return self.store.get(key, None)