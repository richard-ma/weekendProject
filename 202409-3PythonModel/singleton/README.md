# Singleton 单例模式

## 应用举例

1. 数据库连接
	1. 集群中的数据库连接使用单例模式会产生很多对象，这时候使用数据库连接池复用连接对象效率较高
1. 日志记录的logger对象
1. 全局配置
1. 管理服务器监控的列表，管理列表的对象

## 缺点
1. 全局变量的修改可能影响其他模块
1. 对同一个对象创建多个引用
1. 增加耦合，类互相影响