#!/usr/bin/env python3

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

        return Fraction(new_num, new_den)

if __name__ == "__main__":
    f = Fraction(3, 5)
    f.show()
    print(f)

    other_f = Fraction(1, 5)
    print(f + other_f)