#!/usr/bin/env python3

class Actor(object):
    def __init__(self):
        self.isBusy = False # 初始化忙碌状态为False

    def occupied(self): # 分配任务，设置为忙碌状态
        self.isBusy = True
        print(type(self). __name__ , "is occupied with current movie")

    def available(self): # 设置为空闲状态
        self.isBusy = False
        print(type(self). __name__ , "is free for the movie")

    def getStatus(self): # 获取忙碌状态数据
        return self.isBusy


class Agent(object):
    def __init__(self):
        self.principal = None

    def work(self):
        self.actor = Actor()
        if self.actor.getStatus(): # 当演员忙碌时为其分配工作
            self.actor.occupied()
        else:
            self.actor.available() # 当演员空闲时设置状态为可用


if __name__ == '__main__':
    r = Agent()
    r.work()
