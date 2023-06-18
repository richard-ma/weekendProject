#!/usr/bin/sh

sudo docker pull dperson/samba
sudo docker run -it --name samba -p 139:139 -p 445:445 -v /home/share:/home/share -d dperson/samba -n -p -w "WORKGROUP" -s "HomeShare;/home/share;yes;no;yes"
sudo docker container ls -a | grep samba

