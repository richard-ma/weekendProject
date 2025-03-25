import logging
import threading
import time
import random

LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"
logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)

items = []
event = threading.Event()

class Consumer(threading.Thread):
    def __init__(self, *args, **kwargs):
        super(Consumer, self).__init__(*args, **kwargs)
        
    def run(self):
        global items
        while True:
            event.wait()
            item = items.pop()
            logging.info("Consumer notify: {} popped from list by {}".format(item, self.name))
            event.clear()

class Producer(threading.Thread):
    def __init__(self, *args, **kwargs):
        super(Producer, self).__init__(*args, **kwargs)
        
    def run(self):
        global items
        for i in range(10):
            time.sleep(2)
            item = random.randint(0, 100)
            items.append(item)
            logging.info("Producer notify: item {} appended to list by {}".format(item, self.name))
            event.set()


if __name__ == '__main__':
    producer = Producer()
    consumer = Consumer()
    producer.start()
    consumer.start()
    producer.join()
    consumer.join()
    
    logging.info("Producer and Consumer has finished")