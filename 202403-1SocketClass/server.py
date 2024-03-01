#!/usr/bin/env python3
# -*- coding: UTF-8 -*-


import socket
import sys


s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
host = socket.gethostname()
port = 12345
s.bind((host, port))

s.listen(5)
while True:
    c, addr = s.accept()
    print('IP Addr:', addr)
    c.send("Welcome to here!\r\n".encode('utf-8'))
    c.close()