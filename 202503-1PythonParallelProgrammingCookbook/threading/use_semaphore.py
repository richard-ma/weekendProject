import logging
import threading
import time
import random


LOG_FORMAT = "%(asctime)s %(threadName) - 17s %(levelname) - 8s %(message)s"
logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)

semaphore = threading.Semaphore(0)
item = 0

def consumer():
    logging.info("Consumer is waiting.")
    semaphore.acquire()
    logging.info("Consumer notify: consumed item number %s", item)
    
def producer():
    global item
    time.sleep(3)
    item = random.randint(0, 1000)
    logging.info("Producer notify: produced item number %s", item)
    semaphore.release()

def main():
    for _ in range(10):
        producer_thread = threading.Thread(target=producer)
        consumer_thread = threading.Thread(target=consumer)
        producer_thread.start()
        consumer_thread.start()
        producer_thread.join()
        consumer_thread.join()

    logging.info("Producer and Consumer have finished.")

if __name__ == "__main__":
    main()