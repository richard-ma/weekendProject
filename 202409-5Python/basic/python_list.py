#!/usr/bin/env python3

if __name__ == "__main__":
    list_a = ['a', 'b', 'c']
    list_b = [1, 2, 3]

    print(list_a + list_b) # [a, b, c, 1, 2, 3]
    print(list_a * 3) # [a, b, c, a, b, c, a, b, c]
    print('a' in list_a) # True
    print('z' in list_b) # False
    print(len(list_b)) # 3
    print(list_b[:1]) # [1]

    list_b.append(5)
    print(list_b) # [1, 2, 3, 5]
    list_b.insert(2, 4)
    print(list_b) # [1, 2, 3, 4, 5]
    print(list_b.pop()) # 5
    print(list_b.pop(0)) # 1
    list_b.sort()
    print(list_b) # [2, 3, 4]
    list_b.reverse()
    print(list_b) # [4, 3, 2]
    del list_b[2]
    print(list_b) # [4, 3]
    print(list_b.index(4)) # 0
    print(list_b.count(4)) # 1
    list_b.remove(4)
    print(list_b) # [3]