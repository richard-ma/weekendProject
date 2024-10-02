#!/usr/bin/env python3

class Node:
    def __init__(self, initdata):
        self.data = initdata
        self.next = None

    def getData(self):
        return self.data

    def getNext(self):
        return self.next

    def setData(self, newdata):
        self.data = newdata

    def setNext(self, newnext):
        self.next = newnext


class UnorderedList:
    def __init__(self):
        self.head = None

    def isEmpty(self):
        return self.head == None

    # 头插法添加数据
    def add(self, newdata):
        node = Node(newdata)
        node.setNext(self.head)
        self.head = node

    def size(self):
        current = self.head
        count = 0
        while current != None:
            count += 1
            current = current.getNext()

        return count

    def search(self, val):
        current = self.head
        found = False
        while current != None and not found:
            if current.getData() == val:
                found = True
            else:
                current = current.getNext()
        return found

    def remove(self, val):
        current = self.head
        previous = None
        found = False

        while not found:
            if current.getData() == val:
                found = True
            else:
                previous = current
                current = current.getNext()
        
        if previous == None: # 删除第一个node
            self.head = current.getNext()
        else: # 删除后续node
            previous.setNext(current.getNext())


class OrderedList:
    def __init__(self):
        self.head = None

    def search(self, val):
        current = self.head
        found = False
        stop = False

        while current != None and not found and not stop:
            if current.getData() == val:
                found = True
            else:
                if current.getData() > val:
                    stop = True
                else:
                    current = current.getNext()
        return found

    def add(self, val):
        current = self.head
        previous = None
        stop = False

        while current != None and not stop:
            if current.getData() > val:
                stop = True
            else:
                previous = current
                current = current.getNext()

        node = Node(val)
        if previous == None:
            node.setNext(self.head)
            self.head = node
        else:
            node.setNext(current)
            previous.setNext(node)


if __name__ == "__main__":
    temp = Node(93)
    print(temp.getData())

    mylist = UnorderedList()
    mylist.add(31)
    mylist.add(77)
    mylist.add(17)
    mylist.add(93)
    mylist.add(26)
    mylist.add(54)

    print(mylist.size())
    print(mylist.search(17))
    print(mylist.search(18))