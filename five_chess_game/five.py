# Create  by Ricky
from tkinter import *

# 用数组定义一个棋盘，棋盘大小为 自定义
# 数组索引代表位置，
# 元素值代表该位置的状态：-1代表没有棋子，0代表有黑棋，1代表有白棋。

def callback(event):
    global tag, tagx, tagy, a
    color = ["black", "white"]
    # 将点击位置转换为棋盘坐标（第几格）
    x = round(event.x / mesh) - 1
    y = round(event.y / mesh) - 1
    # 计算点击位置与棋盘坐标的距离
    errorX = mesh * (x + 1) - event.x
    errorY = mesh * (y + 1) - event.y
    dis = (errorX ** 2 + errorY ** 2) ** 0.5 # sqrt(x^2 + y^2) 计算原点到这一点的距离
    # 如果棋盘这一点没有子，点击位置误差在允许范围内，也没有任何一方获胜，则执行
    if QP[x][y] == -1 and dis < K / 2 * mesh and stop == 0:
        a.config(text=key[(tag + 1) % 2], fg=color[(tag + 1) % 2]) # 切换为对方落子
        QP[x][y] = tag #在这个格子上落子
        canvas.create_oval(mesh * (x + 1) - Qr, mesh * (y + 1) - Qr, mesh * (x + 1) + Qr, mesh * (y + 1) + Qr,
                           fill=color[tag]) # 在界面上画出这个子
        # 方向向量
        # 0, 1 为纵向
        # 1, 0 为横向
        # 1, 1 为右上左下斜向
        # 1, -1为右下左上斜向
        v = [[0, 1], [1, 0], [1, 1], [1, -1]]
        # 开始检查是否有一方获胜
        for i in v:
            x1, y1 = x, y
            while x1 < num - 1 and x1 > 0 and y1 > 0 and y1 < num - 1:
                x1 += i[0]
                y1 += i[1]
            # 执行到这里，x1和y1已经放到了这个方向的最后一个点
            # 横向为例就是这一行最右侧的那个点

            count = 0 # 连续子计数器清零，准备开始计数
            while x1 <= num - 1 and x1 >= 0 and y1 >= 0 and y1 <= num - 1:
                # 处理当前扫描到的子
                if QP[x1][y1] == tag: # 遇到本方的子
                    count += 1 # 计数器加一
                    if count == count_num: # 计数器达到获胜需要的连续子个数，则本方获胜
                        win() # 本方获胜，停止游戏
                else:
                    count = 0 # 遇到对方的子计数器清零，连续的子被打断

                # 从最后一个格向回扫描棋盘上的棋子
                # 横向为例就是从右向左扫描棋盘这一行
                x1 -= i[0]
                y1 -= i[1]

        tag = (tag + 1) % 2 # 切换到另一方走
        tagx, tagy = x, y # 这句没什么用

def restart():
    global QP, tag, a, b, stop
    QP = []
    for i in range(num):
        QP.append([-1] * num)
    canvas.create_rectangle(mesh - 20, mesh - 20, mesh * num + 20, mesh * num + 20, fill="yellow")
    for i in range(num):
        canvas.create_line(mesh, mesh * (i + 1), mesh * num, mesh * (i + 1))
        canvas.create_line(mesh * (i + 1), mesh, mesh * (i + 1), mesh * num)
    tag = 0
    stop = 0
    a.config(text=key[tag], fg=color[tag])
    b.config(text="走棋", fg=color[tag])
#获胜的函数
def win():
    global stop
    a.config(text=key[tag], fg=color[tag])
    b.config(text="获胜", fg='red')
    stop = 1

if __name__ == '__main__':
    num = input('请输入自定义网格大小，建议在 5 - 25 之间\n')  # 棋盘网格数量   num - 3 就是每行可以存放棋子的数目
    count_num = int(input('请输入获胜的条件，例如五个棋子获胜或者三个棋子获胜\n'))
    tag = 0  # tag标记该轮哪家走，0代表黑方，1代表白方
    stop = 0

    #print(type(num))
    num = int(num) + 3
    K = 0.9  # 点击的灵敏度 0~1 之间
    px = 5
    py = 50
    wide = 60
    high = 30
    mesh = round(400 / num) # 每个格子的大小，总长度是400
    Qr = 0.45 * mesh  # 棋子的大小，前面的系数在0~0.5之间选取
    key = ["黑方", "白方"]#定义棋子
    color = ["black", "white"]

    # 初始化棋盘
    QP = []
    for i in range(num):
        QP.append([-1] * num) # 棋盘全部-1,是一个空棋盘
    tk = Tk()
    tk.geometry(str((num + 1) * mesh + 2 * px) + 'x' + str((num + 1) * mesh + py + px))
    tk.title('五子棋')

    # 构造棋盘界面  图形绘制
    suen = Canvas(tk, width=(num + 1) * mesh + 2 * px, height=(num + 1) * mesh + py + px)
    suen.place(x=0, y=0)
    suen.create_rectangle(0, 0, (num + 1) * mesh + 2 * px, (num + 1) * mesh + py + px, fill="green")#上部分背景颜色
    canvas = Canvas(tk, width=str((num + 1) * mesh), height=str((num + 1) * mesh))
    canvas.place(x=px, y=py)
    canvas.create_rectangle(mesh - 20, mesh - 20, mesh * num + 20, mesh * num + 20, fill="yellow")#背景颜色
    for i in range(num):
        canvas.create_line(mesh, mesh * (i + 1), mesh * num, mesh * (i + 1))
        canvas.create_line(mesh * (i + 1), mesh, mesh * (i + 1), mesh * num)
    canvas.bind("<Button-1>", callback)#定义事件绑定 鼠标左键，点击左键调用callback

    # 定义一个开始游戏的按钮
    # 使用restart函数初始化界面，制造新的一局
    Button(tk, text='开始', command=restart).place(x=2 * px, y=(py - high) / 2, width=wide, heigh=high)

    # 中间的文字
    a = Label(tk, text=key[tag], fg=color[tag], bg='green', font=("Times", "14", "bold"))
    b = Label(tk, text="  ", fg=color[tag], bg='green', font=("Times", "14", "bold"))
    a.place(x=2 * px + 60 + 10 + 90, y=(py - high) / 2 + 4)
    b.place(x=(num + 1) * mesh + px - wide - px - 10 - 42 - 90, y=(py - high) / 2 + 4)
    # 开始tk的主循环，游戏开始
    tk.mainloop()
