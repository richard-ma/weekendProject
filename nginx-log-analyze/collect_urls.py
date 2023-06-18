from app import App
from collections import Counter
import sys


class UrlCollector(App):
    def __init__(self):
        super().__init__()

    def process(self):
        filename = sys.argv[1]

        data = self.readCsvToDict(filename)
        urls = Counter([item['url'] for item in data])
        urls = [
            {'url':k, 'times':v} for k, v in urls.items()
        ]
        self.writeCsvFromDict('urls.csv', urls, fieldnames=list(urls[0].keys()))


if __name__ == "__main__":
    collector = UrlCollector()
    collector.run()