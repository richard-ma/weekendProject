from concurrent.futures import ThreadPoolExecutor
from random import randint
from time import sleep

def hello(message):
    sleep(randint(1, 3))
    return message

pool = ThreadPoolExecutor(3)

t = pool.submit(hello, ("world"))
print(t.done())
sleep(4)
print(t.done())
print("Result: " + t.result())
