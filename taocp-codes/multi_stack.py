class MultiStack:
    def __init__(self, num_of_stack:int, size_of_stack:int, available_size=0):
        self._size_of_stack = size_of_stack
        self._data = [-1] * (num_of_stack * (size_of_stack + 1) + available_size)
        self._base = list()
        self._top = list()

        for i in range(num_of_stack):
            self._base.append(i * (size_of_stack + 1))
            self._top.append(self._base[i])

    def push(self, stack_id:int, value:int) -> bool:
        if self._top[stack_id] + 1 <= self._base[stack_id] + self._size_of_stack:
            self._top[stack_id] += 1
            self._data[self._top[stack_id]] = value
        else:
            raise Exception("Stack %d is full." % (stack_id))


    def pop(self, stack_id:int) -> int:
        if self._top[stack_id] == self._base[stack_id]:
            raise Exception("Stack %d is empty." % (stack_id))
        else:
            ret = self._data[self._top[stack_id]]
            self._data[self._top[stack_id]] = -1
            self._top[stack_id] -= 1
            return ret

    def resize(self) -> bool:
        pass

    def print(self):
        print(self._data)
        print(self._base)
        print(self._top)

if __name__ == "__main__":
    num_of_stack = 3
    size_of_stack = 3

    ms = MultiStack(num_of_stack, size_of_stack)
    ms.print()

    for step in range(size_of_stack * num_of_stack):
        ms.push(step//size_of_stack, step)
        ms.print()

    for stack_id in range(num_of_stack):
        try:
            ms.push(stack_id, step+1)
        except Exception as e:
            print(e)

    for step in range(size_of_stack * num_of_stack-1, -1, -1):
        ms.pop(step//size_of_stack)
        ms.print()