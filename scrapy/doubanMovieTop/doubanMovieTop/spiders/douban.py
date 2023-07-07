import scrapy
from scrapy import Selector, Request
from doubanMovieTop.items import MovieItem


class DoubanSpider(scrapy.Spider):
    name = "douban"
    allowed_domains = ["movie.douban.com"]
    # start_urls = ["https://movie.douban.com/top250"]

    def start_requests(self):
        for page in range(10):
            yield Request(f'https://movie.douban.com/top250?start={page * 25}&filter=')

    def parse(self, response):
        sel = Selector(response)
        list_items = sel.css('#content > div > div.article ol > li')
        for list_item in list_items:
            detail_url = list_item.css('div.info > div.hd > a::attr(href)').extract_first()
            movie_item = MovieItem()
            movie_item['title'] = list_item.css('span.title::text').extract_first()
            movie_item['rank'] = list_item.css('span.rating_num::text').extract_first()
            movie_item['subject'] = list_item.css('span.inq::text').extract_first()

            yield Request(
                url=detail_url, callback=self.parse_detail,
                cb_kwargs={'item': movie_item}
            )
    
    def parse_detail(self, response, **kwargs):
        movie_item = kwargs['item']
        sel = Selector(response)
        movie_item['duration'] = sel.css('span[property="v:runtime"]::attr(content)').extract()
        movie_item['intro'] = sel.css('span[property="v:summary"]::text').extract_first() or ''
        yield movie_item