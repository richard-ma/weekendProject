import multiprocessing
import time

def foo():
    name = multiprocessing.current_process().name
    print("Starting %s" % name)
    if name == 'background_process':
        for i in range(0, 5):
            print("--> %d" % i)
            time.sleep(1)
    else:
        for i in range(5, 10):
            print("-> %d" % i)
            time.sleep(1)
    print("Exiting %s" % name)


if __name__ == "__main__":
    background_process = multiprocessing.Process(name='background_process', target=foo)
    background_process.daemon = True
    background_process.start()
    background_process.join()
    foreground_process = multiprocessing.Process(name='foreground_process', target=foo)
    foreground_process.start()
    foreground_process.join()
    print("Done!")