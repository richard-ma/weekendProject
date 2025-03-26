import multiprocessing
import random
import time

class Producer(multiprocessing.Process):
    def __init__(self, queue):
        multiprocessing.Process.__init__(self)
        self.queue = queue
    
    def run(self):
        for i in range(10):
            item = random.randint(0, 256)
            self.queue.put(item)
            print(f'Process Producer: item {item} appended to queue')
            time.sleep(1)
            print(f'The size of queue is {self.queue.qsize()}')

class Consumer(multiprocessing.Process):
    def __init__(self, queue):
        multiprocessing.Process.__init__(self)
        self.queue = queue
        
    def run(self):
        while True:
            if self.queue.empty():
                print('The queue is empty')
                break
            time.sleep(2)
            item = self.queue.get()
            print(f'Process Consumer: item {item} popped from by queue')
            time.sleep(1)
            
if __name__ == '__main__':
    queue = multiprocessing.Queue()
    process_producer = Producer(queue)
    process_consumer = Consumer(queue)
    process_producer.start()
    process_consumer.start()
    process_producer.join()
    process_consumer.join()