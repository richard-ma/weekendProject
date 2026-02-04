# myadd的Python语言版本实现

## 创建目录
    mkdir -p src/myapp


## 编写myapp.py代码文件

    #!/usr/bin/env python3

    if __name__ == "__main__":
        print("Hello, World! from myapp.py")

## 运行可执行文件

    python3 src/myapp/myapp.py

会看到输出

    Hello, World! from myapp.py

## 总结
1. 这里是最简化的一个Python语言程序的开发过程，在开源项目中我们一般会得到myapp.py的源代码
1. 由于Python语言不需要编译，可以直接调用Python解释器来运行代码。代码运行只需要有解释器就可以，所以不受计算机系统和硬件架构影响。
1. 可执行脚本文件src/myapp/myapp.py就是我们要放入包中最主要的文件