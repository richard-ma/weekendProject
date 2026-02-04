# myadd的C语言版本实现

## 创建目录
    mkdir -p src/myapp

## 编写myapp.c代码文件

    #include <stdio.h>

    int main(int argc, char *argv[]) {
        printf("Hello, World! from myapp.c\n");
        return 0;
    }

## 编译代码
    gcc -o src/myapp/myapp src/myapp/myapp.c

编译成功后会在src/myapp目录中生成一个可执行文件myapp

## 运行可执行文件
    ./src/myapp/myapp

会看到输出

    Hello, World! from myapp.c

## 总结
1. 这里是最简化的一个c语言程序的开发过程，在开源项目中我们一般会得到myapp.c的源代码
1. 利用gcc编译器将源代码编译成可执行程序，开源项目中一般会使用make命令进行编译，由于过程复杂这里不赘述。make的本质还是用gcc来编译，只不过源代码文件多的情况下可以进行批处理，使用起来比逐条命令输入方便一些。
1. 编译后的可执行文件src/myapp/myapp就是我们要放入包中最主要的文件