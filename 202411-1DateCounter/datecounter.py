#!/usr/bin/env python3

import json, sys
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


if __name__ == "__main__":
    if len(sys.argv) < 1:
        print("清输入日期配置文件名称")
        exit()
    date_filename = sys.argv[1]
    print(date_filename)
    schedule_filename = './schedule.ini'
    date_fmt = '%Y-%m-%d'

    date_config = ConfigParser()
    date_config.read(date_filename)

    schedule = ConfigParser()
    schedule.read(schedule_filename)

    ans = dict()
    schedule_table = dict()

    for section in schedule.sections():
        section_name = schedule.get(section, "name")
        section_schedule = get_list(schedule.get(section, "schedule"))
        schedule_table[section] = section_schedule

    for d in date_range(date_config.get('DEFAULT', 'start'), date_config.get('DEFAULT', 'end')):
        key = d.weekday()

        for k, v in schedule_table.items():
            if key > 4:
                continue # Sat Sun

            if v[key] in ans.keys():
                ans[v[key]].append((k, d.strftime(date_fmt)))
            else:
                ans[v[key]] = [(k, d.strftime(date_fmt))]

    print(schedule_table)
    print(ans)