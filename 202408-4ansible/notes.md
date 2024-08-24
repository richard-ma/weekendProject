# Ansible

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

## ad hoc commands

1. ansible [pattern 主机或主机组] -m [module 使用的模块名称] -a "[module options 该模块对应的参数]"
1. 默认模块为command，但这个模块无法执行重定向等操作，如需复杂shell命令可以使用shell模块
1. 应了解最基本的一些常用模块及其参数

## Playbook

1. 使用YAML语法编写
1. 每个play包括两部分
    1. 目标主机组pattern
    1. 一个或多个任务task
    