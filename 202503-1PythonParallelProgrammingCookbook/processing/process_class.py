import multiprocessing

class MyProcess(multiprocessing.Process):
    def run(self):
        print("Child Process PID: {}".format(multiprocessing.current_process().pid))


if __name__ == "__main__":
    for i in range(10):
        process = MyProcess()
        process.start()
        process.join()