import multiprocessing

def myFunc(i):
    print("Called function in process: %s" % i)
    for j in range(i):
        print("Called function in process: %s, sub function: %s" % (i, j))

    
if __name__ == '__main__':
    for i in range(6):
        p = multiprocessing.Process(target=myFunc, args=(i,))
        p.start()
        p.join()