import multiprocessing
import time


def myfunc():
    name = multiprocessing.current_process().name
    print("Starting %s" % name)
    time.sleep(3)
    print("Exiting %s" % name)
    
if __name__ == '__main__':
    process_with_name = multiprocessing.Process(name='myprocess', target=myfunc)
    process_with_default_name = multiprocessing.Process(target=myfunc)
    process_with_name.start()
    process_with_default_name.start()
    process_with_name.join()
    process_with_default_name.join()