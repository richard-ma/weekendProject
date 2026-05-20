# Mazes For Programmers

* 迷宫程序设计
* ISBN: 9787577206516

## 基本迷宫规则
* 迷宫起点为左下角，终点为右上角

# 简单迷宫算法

## binary tree
* 对所有单元格随机打通上方或右方的墙
* 只有上方或右方的墙直接打通
* 优点：算法简单
* 缺点：第一行和最后一列总是通路，迷宫复杂度低
* src/binary_tree.py
* draw_maze_to_png.py
* on_binary_tree.png

## sidewinder
* 对每行的单元格随机分组，组内打通右侧的墙，组间用墙分隔
* 每组打通一个向上的墙
* 优点：算法简单
* 缺点：第一行总是通路，复杂度比binary tree稍强
* src/sidewinder.py
* draw_maze_to_png.py
* on_sidewinder.png

## aldous_broder
* 随机选择一个起始单元格作为当前单元格
* 选择一个当前单元格没有任何连接的邻居，将当前单元格和邻居中间的墙擦除
* 当前单元格更新为刚建立连接的邻居
* 以此方法遍历所有单元格
* 优点：第一行和最后一列不会出现整体的联通
* 缺点：整个迷宫没有岔路，整体难度还是不高
* src/aldous_broder.py
* aldous_broder_demo.py
* aldous_broder.png