# 模板方法 Template Method

## 概要
1. 在子类方法中实现算法，有助于减少重复代码

## 优点
1. 没有重复代码
1. 使用继承，需要重写方法不多
1. 灵活决定如何实现算法步骤

## 缺点
1. 由于方法可能在任意一层实现，所以维护起来比较痛苦
1. 文档和严格的错误处理必须有程序员完成

## 依赖
1. 底层组件通过继承调用高层组件方法
1. 不要出现底层和高层循环依赖

## 和策略模式比较
1. 模板使用继承封装算法
1. 策略使用组合封装算法