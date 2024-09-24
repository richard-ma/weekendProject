#!/usr/bin/env python3

def gcd(m:int, n:int) -> int:
    while m % n != 0:
        oldm = m
        oldn = n

        m = oldn
        n = oldm % oldn
    return n

class Fraction:
    # 练习1.8 5题：确保分子分母均为整数
    def __init__(self, top: int, bottom: int) -> None:
    # 练习1.8 2题：在创建分数时约分
        common = gcd(top, bottom)
        self.num = top // common
        self.den = bottom // common

    def show(self):
        print(self.num, '/', self.den)

    def __str__(self) -> str:
        return str(self.num) + '/' + str(self.den)

    def __add__(self, otherfraction):
        new_num = self.num * otherfraction.den + \
                self.den * otherfraction.num
        new_den = self.den * otherfraction.den

        return Fraction(new_num, new_den)

    # 练习1.8 3题：添加方法
    def __sub__(self, otherfraction):
        new_num = self.num * otherfraction.den - \
                self.den * otherfraction.num
        new_den = self.den * otherfraction.den

        return Fraction(new_num, new_den)

    def __mul__(self, otherfraction):
        new_num = self.num * otherfraction.num
        new_den = self.den * otherfraction.den

        return Fraction(new_num, new_den)

    def resiprocal(self): # 将本分数变为倒数
        return Fraction(self.den, self.num)

    def __truediv__(self, otherfraction):
        return self.__mul__(otherfraction.resiprocal())

    def getFristSecond(self, other):
        firstnum = self.num * other.den
        secondsum = self.den * other.num

        return (firstnum, secondsum)

    def __eq__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum == secondsum

    # 练习1.8 4题：添加__ne__, __gt__, __lt__, __ge__, __le__方法

    def __ne__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum != secondsum

    def __gt__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum > secondsum

    def __ge__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum >= secondsum

    def __lt__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum < secondsum

    def __le__(self, other):
        firstnum, secondsum = self.getFristSecond(other)
        return firstnum <= secondsum

    # 练习1.8 1题：添加getNum和getDen方法
    def getNum(self):
        return self.num

    def getDen(self):
        return self.den

if __name__ == "__main__":
    f = Fraction(3, 5)
    f.show()
    print(f)

    other_f = Fraction(1, 5)
    print(f + other_f)

    print(f - other_f)
    print(f * other_f)
    print(f / other_f)

    other = Fraction(3, 5)
    print(f == other) # True
    print(other_f != other) # True
    print(f != other) # False
    print(f > other_f) # True
    print(other_f < f) # True
    print(f >= f) # True
    print(f <= f) # True
