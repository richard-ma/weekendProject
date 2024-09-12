#!/usr/bin/env python3

class Borg:
    _shared_state = {}

    def __new__(cls, *args, **kwargs):
        obj = super(Borg, cls).__new__(cls, *args, **kwargs)
        obj.__dict__ = cls._shared_state
        return obj

if __name__ == "__main__":
    a = Borg()
    a.test = True
    b = Borg()
    # a和b为不同的对象，但共享属性值
    
    print(a, a.test)
    print(b, b.test)

    b.test = False
    # 修改b的属性值，a的对应属性也随之变动

    print(a, a.test)
    print(b, b.test)
