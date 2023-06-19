#!/usr/bin/bash

sudo apt-get update
sudo apt-get install redis-server

# 启动redis server
sudo systemctl start redis

# 测试redis-server是否成功启动
sudo systemctl status redis
