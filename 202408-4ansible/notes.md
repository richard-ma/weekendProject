# Ansible

## 基本元素与概念

1. Inventory
    1. 用于管理的node节点主机信息，这里可以对主机节点进行分组管理
    1. 可以同时管理多个系统
    1. 基于文件配置
    1. 可以分组
    1. 可以对分组或者特定主机指定变量，达到不同的配置粒度
1. Playbooks
    1. 用于完成某些任务的程序
1. Plugins
    1. 辅助功能，比如become用于以root用户执行，cache用于缓存等
1. Modules
    1. 执行playbook中任务的模块，比如shell用于执行命令，apt用来安装程序等
1. Roles
    1. 可复用的playbook代码
1. Collections
    1. 以上所有这些打包为Collection

## Installing

1. 主控机一定需要是Linux系统，并且安装有Python解释器
1. 受控端需要有Python和SSH支持

### pip

1. 安装包有以下两种
    1. ansible-core 只包含Builtin的基本Module
    1. ansible 大的多的安装包，有更多的Module
1. 安装ansible
    1. python3 -m pip install --user ansible
1. add .bashrc or .zshrc
    1. PATH=$PATH:$HOME/.local/bin

### 确认Ansible是否安装

1. ansible --version
1. ansible-community --version

### Upgrade Ansible

1. python3 -m pip install --upgrade --user ansible

### Config Ansible

1. 如果使用apt yum等包管理器安装，配置文件在/etc/ansible/ansible.cfg
1. 使用pip安装，需要自己生成配置文件
    1. ansible-config init --disabled > ansible.cfg
    1. ansible-config init --disabled -t all > ansible.cfg
1. 生成的配置文件作为ansible默认的配置文件，可使用ansible-config view查看

## Inventory 主机群组管理

1. 群组文件: /etc/ansible/hosts
1. 使用INI格式编写
1. 主机可以使用IP或者域名的形式，只要能ssh能连接即可
1. [web:vars]为对web分组配置的变量值 [all:vars]为对所有主机配置的变量值
1. 可以使用分组组合成更大的分组

## ad hoc commands

1. ansible [pattern 主机或主机组] -m [module 使用的模块名称] -a "[module options 该模块对应的参数]"
1. 默认模块为command，但这个模块无法执行重定向等操作，如需复杂shell命令可以使用shell模块
1. 应了解最基本的一些常用模块及其参数
1. 使用become将普通用户变为root执行一些操作

## Playbook

### Playbook语法

1. 使用YAML语法编写，扩展名为yml
1. 每个play包括两部分
    1. 目标主机组pattern
    1. 一个或多个任务task

### Playbook执行

1. playbook的task顺序执行
1. 使用ansible-playbook执行playbook
    1. 使用--check参数检查playbook，并不直接在主机上运行任务，用于检查playbook，确认后再到生产环境运行
    1. 检查playbook常用参数--check, --diff, --list-hosts, --list-tasks, --syntax-check
    1. ansible-lint是检查规范playbook的工具
1. 执行结果颜色
    1. 绿色：成功，目标系统没有任何改变
    1. 黄色：成功，目标系统有改变
    1. 红色：失败

### Variables

1. 用于debug
1. 使用vars在playbook开头定义
1. 语法使用jinja2模板语法

### ansible facts

1. ansible all -m setup

### 程序结构：分支和循环

1. when: my_mood == "happy"
1. register: 将结果作为变量，方便后续task调用判断
1. notify：调用handler
1. loop：循环列表，使用{{item}}调用每次循环的值

## Templating

1. 使用jinja2模板
1. 用于对不同主机服务的配置文件书写，可以在传递配置文件时进行模板替换，达到对不同主机的适配
1. 模板可以使用默认值，如果playbook没有指定变量则使用默认值{{ variable-name | default(default-value)}}

## Roles

1. 目录结构
    1. defaults 默认值
    1. handlers
    1. meta
    1. README.md
    1. tasks
    1. templates
    1. tests
    1. vars

## Collections

1. Modules
1. Playbooks
1. Roles
1. Plugins
1. Docs
1. Tests
