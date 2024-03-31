#!/usr/bin/env python
# APP Framework 1.0

import csv
import os
import sys
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


class MyApp(App):
    def process(self):
        import re

        keyword_filename = self.input(
            "请将find.txt拖动到此窗口，然后按回车键。",
            default="./test/find.txt")
        keyword_list = self.readTxtToList(keyword_filename)
        keyword_expression_list = [re.compile(re.escape(keyword), re.IGNORECASE) for keyword in keyword_list]  # all keyword to re expression
        # pprint(keyword_list)

        input_filename = self.input(
            "请将csv文件拖动到此窗口，然后按回车键。",
            default="./test/product_data_cscart.csv")
        self.setWorkingDirFromFilename(input_filename)
        # pprint(self.workingDir)
        output_filename = self.addSuffixToFilename(input_filename, '_new')

        data = self.readCsvToDict(input_filename)
        # pprint(data)

        for line in data:
            for i in range(len(keyword_list)):
                if keyword_expression_list[i].search(line['title']):
                    line['new_title'] = keyword_list[i]
                    break
            else:
                line['new_title'] = line['title']

            self.printCounter("%s" % (line['title']))

        fieldnames = list(data[0].keys())
        # pprint(fieldnames)
        self.writeCsvFromDict(output_filename, data, fieldnames=fieldnames)


if __name__ == "__main__":
    app = MyApp()
    app.run()