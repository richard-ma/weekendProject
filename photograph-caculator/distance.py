#!/usr/bin/env python
# encoding: utf-8

import math


class DistanceCaculator(object):
    """
    计算被摄物体和相机的距离
    需要参数：焦距 单位mm
    """

    def __init__(self):
        """初始化函数

        :returns: None

        """
        # 焦距和视角的对应关系，视角是弧度值
        self._table = {
            14: math.radians(114),
            17: math.radians(104),
            20: math.radians(94),
            24: math.radians(84),
            28: math.radians(75),
            35: math.radians(63),
            50: math.radians(46),
            70: math.radians(34),
            80: math.radians(30),
            85: math.radians(28.5),
            100: math.radians(24),
            135: math.radians(18),
            200: math.radians(12),
            300: math.radians(8.25),
            400: math.radians(6.17),
            500: math.radians(5),
            600: math.radians(4.17),
            800: math.radians(3.10),
        }

    @staticmethod
    def liner_inter(x0, y0, x1, y1, x):
        """用分段线性插值计算中间值

        :x0: 起点x坐标
        :y0: 起点y坐标
        :x1: 终点x坐标
        :y1: 终点y坐标
        :x: 插值x坐标
        :returns: 插值y坐标

        """
        return y0 + (y1 - y0) / (x1 - x0) * (x - x0)

    @staticmethod
    def caculateDisatance(object_legnth, radians):
        """计算物距公式

        :focal_length: 焦距（单位mm）
        :returns: 物距

        """

        return (object_legnth / 2) / math.tan(radians / 2)

    @staticmethod
    def approximateNumber(data, num):
        """查找list中的n对应位置

        :data: list数据
        :num: 待查找的数值
        :returns: 在list中的key值(lower_bound, upper_bound)

        """

        upper_bound = max(data)
        lower_bound = min(data)

        for value in data:
            if value < num and lower_bound < value:
                lower_bound = value

            if value > num and upper_bound > value:
                upper_bound = value

            if num == value:
                lower_bound = upper_bound = value
                break

        return lower_bound, upper_bound

    def exec(self, focal_length, object_legnth):
        """执行计算

        :focal_length: 焦距（单位mm）
        :object_length: 物体长度（单位m）
        :returns: 物距（单位m）

        """
        lower_bound, upper_bound = DistanceCaculator.approximateNumber(
            list(self._table.keys()), focal_length)
        radians = DistanceCaculator.liner_inter(
            lower_bound, self._table[lower_bound], upper_bound,
            self._table[upper_bound], focal_length)
        distance = DistanceCaculator.caculateDisatance(object_legnth, radians)

        return distance


if __name__ == "__main__":
    print('{}m'.format(DistanceCaculator().exec(75, 1)))
