#!/usr/bin/env python3


def list_add(list1, list2):
    for i in range(len(list1)):
        list1[i] += list2[i]
    return list1

def check_state(state):
    if state[0] <= state[1] and state[2] <= state[3]:
        return True
    else:
        return False


if __name__ == "__main__":
    start, end = [3, 3, 0, 0], [0, 0, 3, 3]

    q = list()
    q.append(start)


    while q:
        state = q.pop(0)
            if flg is True:
                if state[0] >= 2:
                    tmp = list_add(state, [-2, 0, 2, 0])
                    q.append(tmp)
                if state[1] >= 2:
                    tmp = list_add(state, [0, -2, 0, 2])
                    q.append(tmp)
                if state[0] >=1 and state[1] >= 1:
                    tmp = list_add(state, [-1, -1, 1, 1])
                    q.append(tmp)
