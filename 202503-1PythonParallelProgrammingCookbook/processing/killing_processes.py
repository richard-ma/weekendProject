import multiprocessing
import time

def foo():
    print("Starting %s" % multiprocessing.current_process().name)
    for i in range(10):
        print("-> %d" % i)
        time.sleep(1)
    print("Exiting %s" % multiprocessing.current_process().name)


if __name__ == "__main__":
    p = multiprocessing.Process(target=foo)
    print("Process before execution:", p, p.is_alive())
    p.start()
    print("Process running:", p, p.is_alive())
    p.terminate()
    print("Process terminated:", p, p.is_alive())
    p.join()
    print("Process joined:", p, p.is_alive())
    print("Done!")