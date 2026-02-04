# 对于C代码的Deb打包步骤

## 创建打包目录

    myapp_1.0.0_amd64/  
    ├ DEBIAN/          # 固定大写，存放控制信息与脚本  
    │ ├── control      # 必选：包元数据核心文件  
    │ ├── preinst      # 可选：安装前执行脚本  
    │ ├── postinst     # 可选：安装后执行脚本  
    │ ├── prerm        # 可选：卸载前执行脚本  
    │ └── postrm       # 可选：卸载后执行脚本  
    ├ usr/             # 模拟系统根目录，存放实际程序文件  
    │ ├── bin/         # 可执行文件路径  

创建DEBIAN目录及文件，这里只需要编辑control文件，其他文件为空

    mkdir -p package/myapp_1.0.0_amd64/DEBIAN
    touch package/myapp_1.0.0_amd64/DEBIAN/control
    touch package/myapp_1.0.0_amd64/DEBIAN/preinst
    touch package/myapp_1.0.0_amd64/DEBIAN/postinst
    touch package/myapp_1.0.0_amd64/DEBIAN/prerm
    touch package/myapp_1.0.0_amd64/DEBIAN/postrm

创建可执行文件目录
    
    mkdir -p package/myapp_1.0.0_amd64/usr/bin

## 编写control文件

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

使用文本编辑器将以上代码粘贴到control文件中

## 复制编译好的可执行文件

使用下面的命令将myapp复制到打包目录中的/usr/bin中，这也意味这myapp这个命令安装后会在系统的/usr/bin目录中，可以直接在命令行中调用。

    cp src/myapp/myapp package/myapp_1.0.0_amd64/usr/bin

## 设置权限

虽然安装和卸载脚本在这个例子中不使用，但是必须要对其进行权限设置，一般设置为755可执行权限。

    chmod 755 package/myapp_1.0.0_amd64/DEBIAN/preinst
    chmod 755 package/myapp_1.0.0_amd64/DEBIAN/postinst
    chmod 755 package/myapp_1.0.0_amd64/DEBIAN/prerm
    chmod 755 package/myapp_1.0.0_amd64/DEBIAN/postrm

C语言编译后的可执行文件也需要755可执行权限

    chmod 755 package/myapp_1.0.0_amd64/usr/bin/myapp

## 生成Deb包

当所有的文件和目录都准备好后，我们可以使用打包工具进行Deb包的生成。

    cd package
    dpkg-deb --build myapp_1.0.0_amd64 myapp_1.0.0_amd64.deb

成功打包后，在package目录下就会生成一个名为myapp_1.0.0_amd64.deb的文件。

## 查看Deb包信息

    # 查看control信息
    dpkg -I myapp_1.0.0_amd64.deb
    # 查看包内所有文件列表
    dpkg -c myapp_1.0.0_amd64.deb
    # 解压deb包查看原始结构（目录中就是打包目录的结构和内容）
    dpkg-deb -R myapp_1.0.0_amd64.deb extract_dir/

## 测试安装和卸载

    # 安装（-i=install，-f修复依赖）
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