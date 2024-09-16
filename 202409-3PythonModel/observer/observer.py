#!/usr/bin/env python3

class Subject:
    def __init__(self):
        self. __observers = [] # 观察者列表

    def register(self, observer):
        self. __observers.append(observer) # 将观察者添加到列表

    def notifyAll(self, *args, **kwargs):
        for observer in self. __observers:
            observer.notify(self, *args, **kwargs) # 调用所有观察者的notify方法


class Observer1:
    def __init__(self, subject):
        subject.register(self) # 将自己注册到主体中

    def notify(self, subject, *args): # 主体通知改变，观察者作出反应
        print(type(self). __name__, ':: Got', args, 'From', subject)


class Observer2:
    def __init__(self, subject):
        subject.register(self)

    def notify(self, subject, *args):
        print(type(self). __name__, ':: Got', args, 'From', subject)


if __name__ == "__main__":
    subject = Subject()
    observer1 = Observer1(subject)
    observer2 = Observer2(subject)
    subject.notifyAll('notification')
