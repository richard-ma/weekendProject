#!/usr/bin/env python3

if __name__ == "__main__":
    s = 'hello'
    # take 10 place
    print(s.center(10)) # center
    print(s.ljust(10)) # left
    print(s.rjust(10)) # right

    print(s.count('l')) # 2
    print(s.upper()) # HELLO
    print(s.lower()) # hello
    print(s.find('l')) # 2
    print(s.split('e')) # ['h', 'llo']
