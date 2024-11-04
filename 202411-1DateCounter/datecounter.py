#!/usr/bin/env python3

import json
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
    schedule_filename = './schedule.ini'
    schedule = ConfigParser()

    schedule.read(schedule_filename)

    ans = dict()
    schedule_table = dict()

    for section in schedule.sections():
        section_name = schedule.get(section, "name")
        section_schedule = get_list(schedule.get(section, "schedule"))
        schedule_table[section] = section_schedule

    for d in date_range('2024-10-01', '2024-11-01'):
        key = d.weekday()

        for k, v in schedule_table.items():
            if key > 4:
                continue # Sat Sun

            if v[key] in ans.keys():
                ans[v[key]].append(k)
            else:
                ans[v[key]] = [k]

    print(schedule_table)
    print(ans)