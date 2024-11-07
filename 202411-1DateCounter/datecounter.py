#!/usr/bin/env python3

import json, sys
import numpy as np
import pandas as pd
from datetime import datetime, timedelta, date
from configparser import ConfigParser

def date_range(start, end, periods=1):
    start_time = date.fromisoformat(start)
    end_time = date.fromisoformat(end)

    interval = timedelta(days=1)
    current_time = start_time

    while current_time < end_time:
        yield current_time
        current_time += interval

# for d in date_range('2024-10-01', '2024-11-01'):
#     print(d, d.weekday(d))

def get_list(item_obj):
    return json.loads(item_obj)

def load_date_config(date_filename):
    date_config = ConfigParser()
    date_config.read(date_filename)
    start = date_config.get('GENERAL', 'start')
    end = date_config.get('GENERAL', 'end')

    special_days = dict()
    if date_config.has_section('SPEDAYS'):
        for special_date in date_config.options('SPEDAYS'):
            special_days[special_date] = date_config.getint('SPEDAYS', special_date)

    return [start, end, special_days]

def load_schedule_config(schedule_filename):
    schedule = ConfigParser()
    schedule.read(schedule_filename)

    schedule_table = dict()

    for section in schedule.sections():
        section_name = schedule.get(section, "name")
        section_schedule = get_list(schedule.get(section, "schedule"))
        schedule_table[section] = section_schedule

    return schedule_table

def weekday(date, special_days, date_str_fmt='%Y-%m-%d'):
    date_str = date.strftime(date_str_fmt)
    if date_str in special_days.keys():
        return special_days[date_str]
    else:
        return date.weekday()


if __name__ == "__main__":
    if len(sys.argv) < 1:
        print("清输入日期配置文件名称")
        exit()
    date_filename = sys.argv[1]
    print(date_filename)
    schedule_filename = './schedule.ini'
    date_fmt = '%Y-%m-%d'

    start, end, special_days = load_date_config(date_filename)
    print(start, end, special_days)
    schedule_table = load_schedule_config(schedule_filename)
    print(schedule_table)

    ans = dict()
    idx = 0
    for d in date_range(start, end):
        key = weekday(d, special_days)

        for k, v in schedule_table.items():
            if key > 4:
                continue # Sat Sun
            else:
                idx += 1
                ans[idx] = list()
                ans[idx].extend([d.strftime(date_fmt), k, v[key]])
            
    print(ans)
    df = pd.DataFrame.from_dict(ans, orient='index', columns=('date', 'class', 'name'))
    grouped = df.groupby(['class', 'name'])
    print(grouped.count())