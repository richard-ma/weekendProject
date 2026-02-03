# Deb包

Deb包是Linux发行版Debian采用的包管理系统默认的包格式，许多基于Debian的Linux发行版也默认使用这种二进制安装包格式。

## Deb包结构
1. debian-binary：是一个文本文件，包含了 deb 文件的格式版本号，通常内容为 “2.0\n”。
2. control.tar.gz：这包含了软件包的元数据和控制脚本。文件主要对包的信息进行记录，在安装或卸载包时，根据这些信息进行相应的操作。
3. data.tar.gz：该文件包含了实际安装的程序数据，是安装后真正使用的软件部分，如软件的二进制执行文件、库文件、配置文件、文档等。

## Deb打包步骤

手动打包就是按固定规范创建目录并加入需要的文件，再用dpkg-deb命令将其压缩封装为deb包的过程。

### 创建目录
1. 打包使用的根目录，目录名格式为`包名_版本_架构`，例如程序名为myapp，版本为1.0.0，在64为PC上运行，目录名称就是`myapp_1.0.0_amd64`。
    1. 包名通常和软件名称相同。
    1. 版本号可以根据软件的版本号填写
    1. 架构通常有以下几种：
        * `amd64`为64为x86架构计算机
        * `arm64`为arm架构计算机
        * `all`为跨架构（所有架构都可以使用，通常为纯数据包或者不受架构影响的脚本程序包）
1. 根目录中的目录文件结构图

    myapp_1.0.0_amd64/  
    ├ DEBIAN/          # 固定大写，存放控制信息与脚本  
    │ ├── control      # 必选：包元数据核心文件  
    │ ├── preinst      # 可选：安装前执行脚本  
    │ ├── postinst     # 可选：安装后执行脚本  
    │ ├── prerm        # 可选：卸载前执行脚本  
    │ └── postrm       # 可选：卸载后执行脚本  
    ├ usr/             # 模拟系统根目录，存放实际程序文件  
    │ ├── bin/         # 可执行文件路径  
    │ ├── lib/         # 库文件路径  
    │ └── share/       # 图标、文档、desktop文件  
    └ etc/             # 配置文件路径  

    1. DEBIAN目录中的所有文件都是存放包信息的文件
    1. 其他目录中（/usr /etc等）的文件是软件要使用的文件，会被复制到系统对应的目录中

### 编写包元数据核心文件DEBIAN/control

    Package: myapp  
    Version: 1.0.0  
    Section: utils  
    Priority: optional  
    Architecture: amd64  
    Depends: libc6 (>= 2.17), curl  
    Recommends: git  
    Suggests: vim  
    Maintainer: Your Name \<your@email.com\>  
    Description: A custom demo application  
      Longer description of myapp.  
      This is the second line of detailed description.  
      It can explain functions, usage, features.  
    Homepage: https://your-domain.com  

* Package：包名，小写字母 + 数字 + 短横线，无空格
* Version：版本号，格式主版.次版.修订版
* Architecture：架构，常见amd64(x86_64)、arm64、all(跨架构脚本 / 纯数据)
* Depends：强制依赖，系统安装此包前必须已装的软件
* Maintainer：维护者信息，格式名称 <邮箱>
* Description：首行短描述，换行后空两格写长描述  

### 可选维护脚本

1. 所有维护脚本都是shell脚本
1. 脚本功能
    * preinst：安装前执行（如创建用户、停止旧进程）
    * postinst：安装后执行（如设置权限、更新系统缓存、注册服务）
    * prerm：卸载前执行（如停止服务、清理进程）
    * postrm：卸载后执行（如删除配置、清理残留文件）
1. 脚本需要可执行权限，通常用755

### 放入软件程序和资源文件
1. 将软件从源代码进行编译，找到编译好的二进制文件
1. 将二进制可执行文件放到对应目录下
1. 将配置文件、数据文件等资源文件放到对应目录下
1. 可执行文件权限为755，其他文件为644

### 执行打包命令
    # 语法：dpkg-deb --build [打包根目录] [输出文件名]
    dpkg-deb --build myapp_1.0.0_amd64 myapp_1.0.0_amd64.deb

## Deb包信息校验

### 查看包内部信息
    # 查看control信息
    dpkg -I myapp_1.0.0_amd64.deb
    # 查看包内所有文件列表
    dpkg -c myapp_1.0.0_amd64.deb
    # 解压deb包查看原始结构（验证三部分）
    dpkg-deb -R myapp_1.0.0_amd64.deb extract_dir/

## 安装测试

    # 安装（-i=install，-f修复依赖）
    sudo dpkg -i myapp_1.0.0_amd64.deb
    sudo apt -f install  # 补全缺失依赖

    # 验证安装
    which myapp
    dpkg -l | grep myapp

    # 卸载（保留配置）
    sudo dpkg -r myapp
    # 彻底卸载（删除配置）
    sudo dpkg -P myapp