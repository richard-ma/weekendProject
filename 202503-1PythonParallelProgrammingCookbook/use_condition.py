import logging
import threading
import time

LOG_FORMAT = "%(asctime)s %(threadName) - 17s %(levelname) - 8s %(message)s"
logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)

items = []
condition = threading.Condition()

class Consumer(threading.Thread):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

    def consume(self):
        logging.info("Consumer waiting for lock")
        with condition:
            if len(items) == 0:
                logging.info("Consumer is waiting")
                condition.wait()
            items.pop(0)
            logging.info("Consumer consumed 1 item")

            condition.notify()
            
    def run(self):
        for _ in range(20):
            time.sleep(2)
            self.consume()

class Producer(threading.Thread):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        
    def produce(self):
        logging.info("Producer waiting for lock")
        with condition:
            if len(items) == 10:
                logging.info("Producer is waiting")
                condition.wait()
                
            items.append(1)
            logging.info("Producer added 1 item")
            condition.notify()

    def run(self):
        for _ in range(20):
            time.sleep(0.5)
            self.produce()

if __name__ == "__main__":
    producer = Producer()
    consumer = Consumer()
    
    producer.start()
    consumer.start()
    
    producer.join() 
    consumer.join()