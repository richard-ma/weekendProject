#!/usr/bin/env python3

def gcd(m:int, n:int) -> int:
    while m % n != 0:
        oldm = m
        oldn = n

        m = oldn
        n = oldm % oldn
    return n

class Fraction:
    def __init__(self, top: int, bottom: int) -> None:
        self.num = top
        self.den = bottom

    def show(self):
        print(self.num, '/', self.den)

    def __str__(self) -> str:
        return str(self.num) + '/' + str(self.den)

    def __add__(self, otherfraction):
        new_num = self.num * otherfraction.den + \
                self.den * otherfraction.num
        new_den = self.den * otherfraction.den

        common = gcd(new_num, new_den)

        return Fraction(new_num // common, new_den // common)

    def __eq__(self, other):
        firstnum = self.num * other.den
        secondsum = self.den * other.num

        return firstnum == secondsum

if __name__ == "__main__":
    f = Fraction(3, 5)
    f.show()
    print(f)

    other_f = Fraction(1, 5)
    print(f + other_f)

    other = Fraction(3, 5)
    print(f == other) # True