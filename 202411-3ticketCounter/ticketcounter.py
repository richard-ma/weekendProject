#!/usr/bin/env python3

from configparser import ConfigParser
from collections import Counter


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

def combine_result(names, ticktes):
    d = dict()
    for idx, name in names.items():
        d[name] = tickets[str(idx)]
    return d


if __name__ == '__main__':
    names = load_name_config()

    tickets = load_tickets()
    tickets = Counter(tickets)

    ans = combine_result(names, tickets)
    for k, v in ans.items():
        print(k, v)