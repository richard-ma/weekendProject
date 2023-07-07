# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://docs.scrapy.org/en/latest/topics/item-pipeline.html


# useful for handling different item types with a single interface
from itemadapter import ItemAdapter

import openpyxl


class MoviePipeline:
    def __init__(self):
        self.wb = openpyxl.Workbook() # 创建工作簿
        self.ws = self.wb.active # 创建工作表
        self.ws.title = "Douban Movie Top250"
        self.ws.append(('标题', '评分', '主题'))

    def close_spider(self, spider):
        self.wb.save('电影数据.xlsx')

    def process_item(self, item, spider):
        title = item.get('title', '')
        rank = item.get('rank', '')
        subject = item.get('subject', '')
        self.ws.append((title, rank, subject))
        return item
