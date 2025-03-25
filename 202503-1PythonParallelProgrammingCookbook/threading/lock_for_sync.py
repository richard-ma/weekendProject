# lock to sync
# Lock是最基本的同步原语，它提供了两个方法：acquire和release。
# 一个线程调用acquire方法获得锁，另一个线程调用acquire方法会被阻塞，直到第一个线程调用release方法释放锁。
# Lock可以被多次acquire，但是必须成对出现，也就是说，acquire和release的次数必须相等。
# Lock是一个非递归锁，也就是说，一个线程不能acquire一个已经acquire的Lock，否则会造成死锁, 不允许同一个线程多次获取。
# Lock是一个上下文管理器，可以使用with语句来简化acquire和release的操作。
# Lock是一个低级同步原语，一般不直接使用，而是使用更高级的同步原语，比如RLock、Condition、Semaphore等。
# Lock是线程安全的，可以被多个线程安全地访问。
# Lock是一个全局锁，它会阻塞所有线程，直到获取到锁。
# Lock是一个互斥锁，它只允许一个线程访问共享资源。
# Lock是一个非公平锁，它不保证获取锁的顺序。
# Lock是一个阻塞锁，它会阻塞线程，直到获取到锁。
# Lock是一个可重入锁，它允许同一个线程多次获取锁。
# Lock是一个独占锁，它只允许一个线程获取锁。

import threading
import time
import os
from threading import Thread
from random import randint

# Lock
threadLock = threading.Lock()

class MyThreadClass(Thread):
    def __init__(self, name, duration):
        super().__init__(name=name)
        self.name = name
        self.duration = duration
        
    def run(self):
        threadLock.acquire()
        print("-->" + self.name + " running, belonging to process ID " + str(os.getpid()))
        time.sleep(self.duration)
        print("-->" + self.name + " over.")
        threadLock.release()

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