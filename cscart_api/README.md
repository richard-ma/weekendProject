# CSCART API Client

## 安装依赖

1. `$ pip install -r requirements.txt`

## 使用

### 网站设置
1. 记下网站域名，类似http://example.com，这就是后面客户端程序使用的domain
1. 使用准备开启API的管理员账户进入cscart管理后台
1. 记下该管理员的用户名，这就是后面客户端程序使用的admin_username
1. Customers -> Administrators
1. 切换到API access选项卡，选中Yes, allow this user to use the API
1. 记下这里生成的API Key，就是后面客户端程序使用的api_key
1. 点击save changes保存


### 测试API服务是否正常
1. 准备工具curl
1. `curl --user admin_username:api_key -X GET 'http://example.com/api/users'`
1. 如果看到JSON格式的返回users用户信息数据，则证明API服务正常


### 编写客户端程序
1. `from cscartapi import CscartAPI, CscartAPIException`
1. `api = CscartAPI(doamin, admin_username, api_key)`
1. `api.get('users')`
1. `response = api.commit()`
1. `print(response)` 这里的response保存的就是测试API服务是否正常获得的users用户信息数据
1. 更多的使用方法请参考examples.py文件中的代码和注释

## 测试CscartAPI

### 测试前的设置

1. 设置环境变量
    1. CSCART_BASE_URL: Cscart API的链接地址domain
    1. CSCART_USERNAME: Cscart已开启API功能的管理员用户名admin_username
    1. CSCART_API_KEY: Cscart管理员开启API功能时提供的api_key
1. 安装python依赖
    1. `$ pip install -r requirements.txt`

### 运行测试
1. 运行测试命令`$ pytest tests`

## TODO
1. 打包发布
1. 支持httpx作为底层数据发送（现在默认使用requests）
1. 完善requests发送机制
    1. 添加超时重发或者抛出异常
    1. 添加log