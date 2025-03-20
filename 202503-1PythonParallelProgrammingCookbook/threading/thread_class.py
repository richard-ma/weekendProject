# thread class

import time
from threading import Thread
import os
from random import randint


class MyThreadClass(Thread):
    def __init__(self, name, duration):
        super().__init__(name=name)
        self.name = name
        self.duration = duration
        
    def run(self):
        print("-->" + self.name + "running, belonging to process ID " + str(os.getpid()))
        time.sleep(self.duration)
        print("-->" + self.name + " over.")

def main():
    start_time = time.time()
    threads = list()

    for i in range(10):
        name = "Thread#" + str(i)
        t = MyThreadClass(name, randint(1, 10))
        threads.append(t)

    for t in threads:
        t.start()

    for t in threads:
        t.join()
        
    print("End")

    print("-- %s seconds --" % (time.time() - start_time))
        
        
if __name__ == "__main__":
    main()