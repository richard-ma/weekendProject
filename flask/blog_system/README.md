# Flask Login Basic

## Deployment
1. Get a domain
1. 申请一个主机
1. 登录主机Linux系统
1. 更新系统
1. 安装sshd，导入自己的公钥
1. 防火墙允许ssh和5000端口访问
1. 重启防火墙让规则生效
1. 从git仓库clone出项目
1. 安装pip
1. 安装virtualenv
1. 创建venv环境并进入虚拟环境
1. 利用requirements.txt恢复虚拟环境
1. 恢复环境变量或编辑配置文件
1. flask运行测试基本功能是否完好
1. 配置gunicorn，用于生产环境
1. `pip install gunicorn`
1. 删除nginx的网站默认配置
1. 创建新的nginx用于flask配置文件，让nginx处理所有静态文件(/deployment/nginx_flaskblog)
1. 防火墙关闭对5000端口的访问允许
1. 重启防火墙让规则生效
1. 重启nginx让配置生效
1. 测试1.2.3.4/static目录下的文件是否可以访问，确认nginx配置生效
1. 启动gunicorn `gunicorn -w 3 run:app`
1. worker数量可以更改，一般设置为CPU内核数*2+1
1. 查看内核数量nproc --all
1. 网站已经可以由80端口访问，测试博客所有功能可以正常使用
1. 让gunicorn后台运行
1. `sudo apt-get install supervisor`
1. 修改supervisor配置 `sudo vim /etc/supervisor/conf.d/flaskblog.conf` (/deployment/supervisor_flaskblog)
1. 创建日志文件
1. `sudo mkdir -p /var/log/flaskblog`
1. `sudo touch /var/log/flaskblog/flaskblog.err.log`
1. `sudo touch /var/log/flaskblog/flaskblog.out.log`
1. `sudo supervisorctl reload` 让supervisor设置生效，启动gunicorn
1. nginx不接受过大的HTTP request，修改nginx配置/etc/nginx/nginx.conf
1. 添加`client_max_body_size 5M;` 加大nginx接收请求的大小
1. sudo systemctl restart nginx 上传头像正常
1. 退出服务器，服务上线