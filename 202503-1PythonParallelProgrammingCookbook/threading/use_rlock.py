# RLock
# Lock 与 RLock的区别在于RLock可以被同一个线程多次acquire，而Lock不行。
# 但是，RLock被acquire多少次，就需要release多少次，才能被其他线程获取。
# 也就是说，RLock的acquire和release必须成对出现，否则其他线程无法获取RLock。

import threading
import time
import random


class Box:
    def __init__(self):
        self.lock = threading.RLock()
        self.total_items = 0
        
    def execute(self, value):
        with self.lock:
            self.total_items += value

    def add(self):
        with self.lock:
            self.execute(1)

    def remove(self):
        with self.lock:
            self.execute(-1)

def adder(box, items):
    print("N# {} items to ADD".format(items))
    while items:
        box.add()
        time.sleep(1)
        items -= 1
        print("ADDED one item --> {} item to ADD".format(items))

def remover(box, items):
    print("N# {} items to REMOVE".format(items))
    while items:
        box.remove()
        time.sleep(1)
        items -= 1
        print("REMOVED one item --> {} item to REMOVED".format(items))

def main():
    items = 10
    box = Box()
    
    t1 = threading.Thread(target=adder, args=(box, random.randint(10, 20)))
    t2 = threading.Thread(target=remover, args=(box, random.randint(1, 10)))
    
    t1.start()
    t2.start()
    
    t1.join()
    t2.join()
    
    
if __name__ == "__main__":
    main()