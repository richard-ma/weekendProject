#!/usr/bin/env python3

import time
import os
from configparser import ConfigParser
from collections import Counter
from watchdog.observers import Observer
from watchdog.events import DirModifiedEvent, FileModifiedEvent, FileSystemEventHandler


def load_name_config(names_filename='./names.ini'):
    config = ConfigParser()
    config.read(names_filename)

    names_dict = dict()
    if config.has_section('names'):
        for name in config.options('names'):
            names_dict[config.getint('names', name)] = name
    
    return names_dict

def load_tickets(tickets_filename='./tickets.ini'):
    f = open(tickets_filename, 'r')
    data = f.read()
    ret = data.replace('\n', ' ').split(' ')
    f.close()

    return ret

def combine_result(names, tickets):
    d = dict()
    for idx, name in names.items():
        d[name] = tickets[str(idx)]
    return d


class MyHandler(FileSystemEventHandler):
    def on_modified(self, event: DirModifiedEvent | FileModifiedEvent) -> None:
        names = load_name_config()

        tickets = load_tickets()
        tickets = Counter(tickets)

        ans = combine_result(names, tickets)
        os.system('clear')
        for k, v in ans.items():
            print(k, v)

        return super().on_modified(event)

if __name__ == '__main__':
    event_handler = MyHandler()
    observer = Observer()
    observer.schedule(event_handler, path='.', recursive=False)
    observer.start()

    try:
        while True:
            time.sleep(0.1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()