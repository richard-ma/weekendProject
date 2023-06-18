#!/usr/bin/sh

# 下载：https://github.com/google/cadvisor/releases/latest
sudo ./cadvisor  -port=8080 &>>/var/log/cadvisor.log
