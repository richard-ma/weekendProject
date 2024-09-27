#!/usr/bin/env python3

class Queue:
    def __init__(self) -> None:
        self._data = list()

    def isEmpty(self):
        return len(self._data) == 0
    
    def enqueue(self, val):
        self._data.append(val)
        # self._data.insert(0, val)

    def dequeue(self):
        return self._data.pop(0)
        # return self._data.pop()

    def size(self):
        return len(self._data)


# 队列应用：击鼓传花
def hotPotato(namelist, num):
    simqueue = Queue()
    for name in namelist:
        simqueue.enqueue(name)

    while simqueue.size() > 1:
        for i in range(num):
            simqueue.enqueue(simqueue.dequeue())
        
        simqueue.dequeue()
    
    return simqueue.dequeue()


class Printer:
    def __init__(self) -> None:
        self.pagerate = ppm
        self.currentTask = None
        self.timeRemaining = 0

    def tick(self):
        if self.currentTask != None:
            self.timeRemaining = self.timeRemaining - 1
            if self.timeRemaining <= 0:
                self.currentTask = None
    
    def busy(self):
        if self.currentTask != None:
            return True
        else:
            return False
        
    def startNext(self, newtask):
        self.currentTask = newtask
        self.timeRemaining = newtask.getPages() * 60 / self.pagerate

import random

class Task:
    def __init__(self, time) -> None:
        self.timestamp = time
        self.pages = random.randrange(1, 21)

    def getStamp(self):
        return self.timestamp
    
    def getPages(self):
        return self.pages
    
    def waitTime(self, currentTime):
        return currentTime - self.timestamp

def simulation(numSeconds, pagesPerMinute):
    labprinter = Printer(pagesPerMinute)
    printQueue = Queue()
    waitingtimes = []

    for currentSecond in range(numSeconds):
        if newPrintTask():
            task = Task(currentSecond)
            printQueue.enqueue(task)
        if (not labprinter.busy()) and (not printQueue.isEmpty()):
            nexttask = printQueue.dequeue()
            waitingtimes.append(nexttask.waitTime(currentSecond))
            labprinter.startNext(nexttask)
        labprinter.tick()

    averageWait = sum(waitingtimes) / len(waitingtimes)
    print("Average Wait %6.2f sec %3d tasks remaining." % (averageWait, printQueue.size()))

def newPrintTask():
    num = random.randrange(1, 181)
    if num == 180:
        return True
    else:
        return False


if __name__ == "__main__":
    q=Queue()

    q.enqueue(4)
    q.enqueue('dog')
    q.enqueue(True)
    print(q.size())

    print(hotPotato(["Bill","David","Susan","Jane","Kent","Brad"],7))

    for i in range(10):
        simulation(3600, 5)