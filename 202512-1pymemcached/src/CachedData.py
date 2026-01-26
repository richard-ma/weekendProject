class CachedData:
    def __init__(self):
        self.store = dict()

    def set(self, key, value):
        self.store[key] = value

    def get(self, key):
        return self.store.get(key, None)

    def delete(self, key):
        if key in self.store:
            del self.store[key]
    
    def exists(self, key):
        return key in self.store
    
    def keys(self):
        return list(self.store.keys())