#!/usr/bin/env python
# APP Framework 1.0

import csv
import os
import sys
import datetime
from pprint import pprint


class App:
    def __init__(self):
        self.title_line = sys.argv[0]
        self.counter = 1
        self.workingDir = None

    def printCounter(self, data=None):
        print("[%04d] Porcessing: %s" % (self.counter, str(data)))
        self.counter += 1

    def initCounter(self, value=1):
        self.counter = value

    def run(self):
        self.usage()
        self.process()

    def usage(self):
        print("*" * 80)
        print("*", " " * 76, "*")
        print(" " * ((80 - 12 - len(self.title_line)) // 2),
              self.title_line,
              " " * ((80 - 12 - len(self.title_line)) // 2))
        print("*", " " * 76, "*")
        print("*" * 80)

    def input(self, notification, default=None):
        var = input(notification)

        if len(var) == 0:
            return default
        else:
            return var

    def readTxtToList(self, filename, encoding="GBK"):
        data = list()
        with open(filename, 'r+', encoding=encoding) as f:
            for row in f.readlines():
                # remove \n and \r
                data.append(row.replace('\n', '').replace('\r', ''))
        return data

    def readCsvToDict(self, filename, encoding="GBK"):
        data = list()
        with open(filename, 'r+', encoding=encoding) as f:
            reader = csv.DictReader(f)
            for row in reader:
                data.append(row)
        return data

    def writeCsvFromDict(self, filename, data, fieldnames=None, encoding="GBK", newline=''):
        if fieldnames is None:
            fieldnames = data[0].keys()

        with open(filename, 'w+', encoding=encoding, newline=newline) as f:
            writer = csv.DictWriter(f,
                                    fieldnames=fieldnames)
            writer.writeheader()
            writer.writerows(data)

    def addSuffixToFilename(self, filename, suffix):
        filename, ext = os.path.splitext(filename)
        return filename + suffix + ext

    def getWorkingDir(self):
        return self.workingDir

    def setWorkingDir(self, wd):
        self.workingDir = wd
        return self.workingDir

    def setWorkingDirFromFilename(self, filename):
        return self.setWorkingDir(os.path.dirname(filename))

    def process(self):
        pass

class LeaveDetail:
    TYPE = {
        'SICK': 1, # 病假
        'PERSONAL': 2 # 事假
    }
    TYPE_REVERSE = {v:k for k, v in TYPE.items()}

    def __init__(self, name, start_date, end_date, leave_type, hours=24):
        if hours > 24:
            raise ValueError('hours must <= 24')

        if leave_type not in LeaveDetail.TYPE.values():
            raise ValueError('%s not in LeaveDetail.TYPE' % (leave_type))

        self._name = name
        self._start_date = start_date
        self._end_date = end_date
        self._hours = hours
        self._type = leave_type

        self._leave_days = -1

    def __str__(self):
        return ' '.join([str(i) for i in [self._name, self.leave_days, self.leave_type, self._hours]])

    def __gt__(self, other):
        return self._hours > other

    def __lt__(self, other):
        return self._hours < other

    def __eq__(self, other):
        return abs(self._hours - other) < 1e-5

    @property
    def leave_days(self):
        if self._leave_days < 0:
            self._leave_days = self.gen_leave_days()
        return self._leave_days

    def gen_leave_days(self):
        # 根据起始日期和结束日期计算请假天数
        days = -1 
        start_date = datetime.datetime.strptime(self._start_date, '%Y/%m/%d')
        end_date = datetime.datetime.strptime(self._end_date, '%Y/%m/%d')
        days = (end_date - start_date).days + 1
        return days

    @property
    def leave_type(self):
        return LeaveDetail.TYPE_REVERSE[self._type]


class MyApp(App):
    def process(self):
        def make_leavedetail(record):
            leave_type, hours = record['缺勤原因'][:2], record['缺勤原因'][2:-2]

            if leave_type == '病假':
                leave_type = LeaveDetail.TYPE['SICK']
            elif leave_type == '事假':
                leave_type = LeaveDetail.TYPE['PERSONAL']
            else:
                raise ValueError('leave_type of record: %s' % (leave_type))

            if len(hours) == 0:
                hours = 24.0
            else:
                hours = float(hours)

            ld = LeaveDetail(record['学生姓名'], record['开始时间'], record['结束时间'], leave_type, hours)

            return ld


        leavedetail_filename = self.input(
            "请将请假记录拖动到此窗口，然后按回车键。",
            default="data/test.csv")
        leavedetail_dict = self.readCsvToDict(leavedetail_filename, encoding='UTF-8')
        data = list()
        for record in leavedetail_dict:
            ld = make_leavedetail(record)
            data.append(ld)

        output = {}
        for d in data:
            if d > 4:
                if d._name in output.keys():
                    output[d._name] += d.leave_days
                else:
                    output[d._name] = d.leave_days

        output_filename = 'data/output.csv'
        fieldnames = ['学生姓名', '请假天数']
        output = [{fieldnames[0]: k, fieldnames[1]: v} for k, v in output.items()]
        self.writeCsvFromDict(output_filename, output, fieldnames=fieldnames, encoding='UTF-8')


if __name__ == "__main__":
    app = MyApp()
    app.run()