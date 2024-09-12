# Factory 工厂模式

## 模式及变体
1. 简单工厂模式：允许接口创建对象，但不会暴露对象的逻辑
1. 工厂方法模式：允许接口创建对象，但使用哪个类来创建对象则交由子类决定
1. 抽象工厂模式：能够创建一系列相关对象而无需指定/公开其具体类接口；该模式能够提供其他工厂的对象，在其内部创建其他对象

## 抽象类实现
1. from abc import ABCMeta, abstractmethod
1. class Animal(metaclass=ABCMeta)
1. @abstractmethod def do_say(self)

## 优点
1. 不单纯实例化某个类，而是根据接口实现
1. 松耦合，创建对象和使用代码分开，添加新类更容易，维护成本低
