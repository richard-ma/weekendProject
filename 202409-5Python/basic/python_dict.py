#!/usr/bin/env python3

d = {
    'david': 1410,
    'brad': 1137,
}

print(d.keys()) # all keys
print(d.values()) # all values
print(d.items()) # all key-value pairs

print(d.get('david')) # 1410
print(d.get('bob')) # None
print(d.get('bob', 0)) # 0
# 可以用于数据清洗，设置默认值