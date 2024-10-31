#!/usr/bin/env python3

from datetime import datetime, timedelta, date

def date_range(start, end, periods=1):
    start_time = date.fromisoformat(start)
    end_time = date.fromisoformat(end)

    interval = timedelta(days=1)
    current_time = start_time

    while current_time < end_time:
        yield current_time
        current_time += interval

for d in date_range('2024-10-01', '2024-11-01'):
    print(d, d.weekday())