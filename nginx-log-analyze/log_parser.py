import re
from collections import Counter
from app import App


class NginxLogAnalyzer(App):
    def __init__(self):
        self._data = list()
        self._keys = ['ip', 'time', 'method', 'url', 'protocol', 'status', 'bytes', 'referer', 'ua']

    def load_log(self, path):
        with open(path, mode="r", encoding="utf-8") as f:
            for line in f:
                line = line.strip()
                result = self.parse(line)
                l = dict()
                for key in self._keys:
                    l[key] = result.group(key)
                self._data.append(l)

    # @return result.group('ip')
    def parse(self, line):
        # 解析单行nginx日志
        regular_expression = '(?P<{}>.*?) - - \[(?P<{}>.*?)\] "(?P<{}>.*?) (?P<{}>.*?) (?P<{}>.*?)" (?P<{}>.*?) (?P<{}>.*?) "(?P<{}>.*?)" "(?P<{}>.*?)"'.format(*self._keys)
        obj = re.compile(regular_expression)
        try:
            result = obj.match(line)
            return result
        except:
            pass


if __name__ == '__main__':
    nginx_log_analyzer = NginxLogAnalyzer()
    nginx_log_analyzer.load_log("./wwwlogs/happyingift.com.log")
    nginx_log_analyzer.writeCsvFromDict("nginx_log.csv", nginx_log_analyzer._data, encoding="utf-8", fieldnames=nginx_log_analyzer._keys)
