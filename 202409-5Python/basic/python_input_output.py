#!/usr/bin/env python3

#s = input("input number:")
#num = int(s)

print('hello', 'world', sep='**', end='end\n')

sqlist = [x * x for x in range(1, 11) if x % 2 != 0]
print(sqlist)

print([ch.upper() for ch in 'comprehension' if not ch in 'aeiou'])