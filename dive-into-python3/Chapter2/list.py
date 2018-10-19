#!/usr/bin/env python
# encoding: utf-8

if __name__ == '__main__':
    # slice
    a_list = ['a', 'b', 'mpilgrim', 'z', 'example']
    print(a_list[1:3])
    print(a_list[1:-1])
    print(a_list[0:3])
    print(a_list[:3])
    print(a_list[3:])
    print(a_list[:])

    # add
    a_list = ['a']
    print(a_list + [2.0, 3])
    a_list.append(True)
    print(a_list)
    a_list.extend(['four', 'omega'])
    print(a_list)
    a_list.insert(0, 'ooo')
    print(a_list)

    # search
    a_list = ['a', 'b', 'new', 'mpilgrim', 'new']
    print(a_list.count('new'))
    print(a_list.index('mpilgrim'))

    # remove
    a_list = ['a', 'b', 'new', 'mpilgrim', 'new']
    del a_list[1]
    print(a_list)

    a_list.remove('new')
    print(a_list)

    print(a_list)
    print(a_list.pop())
    print(a_list)
    print(a_list.pop(0))
    print(a_list)

    # list as stack
    a_list = ['a', 'b', 'new', 'mpilgrim', 'new']
    a_list.append('newnode')
    print(a_list)
    print(a_list.pop())
    print(a_list)

    # list as queue
    a_list = ['a', 'b', 'new', 'mpilgrim', 'new']
    a_list.append('newnode')
    print(a_list)
    print(a_list.pop(0))
    print(a_list)
