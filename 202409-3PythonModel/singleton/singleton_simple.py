#!/usr/bin/env python3

class Singleton:
    def __new__(cls):
        if not hasattr(cls, 'instance'):
            cls.instance = super(Singleton, cls).__new__(cls)
        return cls.instance

if __name__ == "__main__":
    s = Singleton()
    print("s Object created: ", s)
    s1 = Singleton()
    print("s1 Object created: ", s1)
