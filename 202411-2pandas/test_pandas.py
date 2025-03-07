#!/usr/bin/env python3

import numpy as np
import pandas as pd

data_a = {
    'A': [2.2, 1.3, 8.5],
    'B': [2.3, 2.3, 5.5],
    'C': [2.2, 3.3, 5.4],
    'D': [2.7, 4.3, 5.5],
    'E': [5.2, 5.3, 9.5],
}

df = pd.DataFrame(data_a)
print(df.head())

print(df.describe())

data = {'1': ['2024-09-30', '2_4', '包', 'a', 'b', 'c'], '2': ['2024-10-08', '2_4', '张', 'a', 'b', 'c'], '3': ['2024-10-09', '2_4', '马', 'a', 'b', 'c'], '4': ['2024-10-10', '2_4', '张', 'a', 'b', 'c'], '5': ['2024-10-11', '2_4', '马', 'a', 'b', 'c'], '6': ['2024-10-12', '2_4', '马', 'a', 'b', 'c'], '7': ['2024-10-14', '2_4', '包', 'a', 'b', 'c'], '8': ['2024-10-15', '2_4', '张', 'a', 'b', 'c'], '9': ['2024-10-16', '2_4', '马', 'a', 'b', 'c'], '10': ['2024-10-17', '2_4', '张', 'a', 'b', 'c'], '11': ['2024-10-18', '2_4', '马', 'a', 'b', 'c'], '12': ['2024-10-21', '2_4', '包', 'a', 'b', 'c'], '13': ['2024-10-22', '2_4', '张', 'a', 'b', 'c'], '14': ['2024-10-23', '2_4', '马', 'a', 'b', 'c'], '15': ['2024-10-24', '2_4', '张', 'a', 'b', 'c'], '16': ['2024-10-25', '2_4', '马', 'a', 'b', 'c'], '17': ['2024-10-28', '2_4', '包', 'a', 'b', 'c'], '18': ['2024-10-29', '2_4', '张', 'a', 'b', 'c'], '19': ['2024-10-30', '2_4', '马', 'a', 'b', 'c'], '20': ['2024-10-31', '2_4', '张', 'a', 'b', 'c']}
data = {'1': ['hello', 'world', 'add'], '2': ['time', 'to', 'add']}
df = pd.DataFrame.from_dict(data, orient='index')
print(df.head())