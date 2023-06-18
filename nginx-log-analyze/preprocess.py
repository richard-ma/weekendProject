from app import App
from collections import Counter


# 保留status 2xx或3xx
def status_filter(data):
    first = data['status'][0]
    if first == '2' or first == '3':
        return True
    else:
        return False

# 删除ip 为127.0.0.1
def ip_filter(data):
    if data['ip'] == '127.0.0.1':
        return False
    else:
        return True


class PreProcessor(App):
    def __init__(self):
        super().__init__()

    def process(self):
        filename = self.input("输入数据csv文件")
        data = self.readCsvToDict(filename)

        filter_stack = [status_filter, ip_filter]
        for item in filter_stack:
            data = filter(item, data)

        data = map(split_url_param, data)

        self.writeCsvFromDict('filterd_data.csv', list(data))


if __name__ == "__main__":
    processor = PreProcessor()
    processor.run()