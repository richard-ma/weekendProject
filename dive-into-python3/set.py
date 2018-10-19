#!/usr/bin/env python
# encoding: utf-8

if __name__ == '__main__':
    # add
    a_set = {'a', 'b', 'mpilgrim', 'z', 'example'}

    a_set.add('c')
    print(a_set)
    # add a exsist item
    a_set.add('a')
    print(a_set)

    a_set.update({'1', '2'})
    print(a_set)
    a_set.update(['3', '4'])
    print(a_set)
    a_set.update(['2', '8'])
    print(a_set)

    # remove
    a_set.discard('2')
    print(a_set)
    a_set.discard('2') # discard twice: Nothing
    print(a_set)


    a_set.remove('8')
    print(a_set)
    #a_set.remove('8') # KeyError
    #print(a_set)

    print(a_set.pop())
    print(a_set)
    a_set.clear()
    print(a_set)

    # operations
    a_set = {1, 2, 3}
    b_set = {2, 4, 6}
    print(a_set.union(b_set))
    print(a_set.intersection(b_set))
    print(a_set.difference(b_set))
    print(b_set.difference(a_set))
    print(a_set.symmetric_difference(b_set))
    print(b_set.symmetric_difference(a_set))

    # subset & superset
    a_set = {1, 2}
    b_set = {1, 2, 3}
    print(a_set.issubset(b_set))
    print(b_set.issubset(a_set))
    print(a_set.issuperset(b_set))
    print(b_set.issuperset(a_set))
