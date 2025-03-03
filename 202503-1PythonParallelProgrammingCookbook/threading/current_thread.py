# Thread.current_thread()
# currentThread() -> current_thread()
# getName() -> name

import threading
import time


def function_A():
    print(threading.current_thread().name + str('--> Starting'))
    time.sleep(2)
    print(threading.current_thread().name + str('--> Exiting'))

def function_B():
    print(threading.current_thread().name + str('--> Starting'))
    time.sleep(2)
    print(threading.current_thread().name + str('--> Exiting'))

def function_C():
    print(threading.current_thread().name + str('--> Starting'))
    time.sleep(2)
    print(threading.current_thread().name + str('--> Exiting'))


if __name__ == "__main__":
    t1 = threading.Thread(name="function_A", target=function_A)
    t2 = threading.Thread(name="function_B", target=function_B)
    t3 = threading.Thread(name="function_C", target=function_C)

    t1.start()
    t2.start()
    t3.start()
    
    t1.join()
    t2.join()
    t3.join()