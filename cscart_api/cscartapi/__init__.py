from enum import Enum

from cscartapi.sender_requests import SenderRequests


class CscartAPIException(Exception):
    def __init__(self, status_code, message):
        super().__init__(self)
        self.status_code = status_code
        self.message = message

    def __str__(self):
        return "[%s] %s" % (str(self.status_code), str(self.message))


class CscartAPI:
    def __init__(self, base_url, username, api_key, api_version='1.0'):
        self.base_url = base_url
        if self.base_url.endswith('/') is False: # ensure self.base_url end with /
            self.base_url += '/'
        self.version = '' if api_version == '1.0' else api_version
        self.username = username
        self.api_key = api_key

        self.sender = SenderRequests(self.username, self.api_key)

        self.reset()

    def reset(self):
        self.method = None
        self.entity = None
        self.params = dict()
        self.data = dict()
        self.id = None

    def set_entity(self, entity: str):
        self.entity = entity

    def set_id(self, id: str):
        self.id = id

    def update_params(self, params: dict):
        # merge two dicts
        self.params.update(params)

    def update_data(self, data: dict):
        self.data.update(data)

    def get_url(self) -> str:
        # get base url
        url = self.base_url

        # get version of api
        url += 'api/' + self.version
        if not url.endswith('/'):
            url += '/'

        # get entity (entity must not None)
        if self.entity is None:
            raise ValueError('Entity must not be None!')
        url += self.entity + '/'

        # get id (if it has)
        if self.id:
            url += self.id

        # get params (if it has)
        if len(self.params) > 0:
            url += '?' + '&'.join(['='.join([k, v]) for k, v in self.params.items()])

        # return url
        return url

    def commit(self):
        # get url
        url = self.get_url()

        # get method
        method = self.method

        response = None
        # call sender to send request
        if method == 'GET':
            response = self.sender.get(url)
        elif method == 'POST':
            response = self.sender.post(url, self.data)
        elif method == 'PUT':
            response = self.sender.put(url, self.data)
        elif method == 'DELETE':
            response = self.sender.delete(url)
        else:
            raise TypeError(method + " not in sender.Method list.") # 访问方法超出API允许范围

        # get response (raise exception when error)
        if 'message' in response.keys(): # error accourd
            raise CscartAPIException(response['status'], response['message'])

        # TODO logging

        # reset api
        self.reset()

        # return response
        return response

    def get(self, entity: str, id: str | None = None):
        self.method = 'GET' 
        self.sets(entity=entity, id=id)
        return self

    def create(self, entity: str, data: dict):
        self.method = 'POST' 
        self.sets(entity=entity, data=data)
        return self

    def delete(self, entity: str, id: str):
        self.method = 'DELETE' 
        self.sets(entity=entity, id=id)
        return self

    def update(self, entity: str, id: str, data: dict):
        self.method = 'PUT' 
        self.sets(entity=entity, id=id, data=data)
        return self

    def sets(self, entity: str, id: str | None = None, data: dict | None = None):
        self.set_entity(entity)
        self.set_id(id)
        if data is not None:
            self.update_data(data)

    def order_by(self, key: str, sort_order: Enum('asc', 'desc') = 'desc'):
        self.update_params({
            'sort_by': key,
            'sort_order': str(sort_order),
        })
        return self

    def page(self, page: int, items_per_page: int | None = None):
        self.update_params({'page': str(page)})
        if items_per_page is not None:
            self.update_params({'items_per_page': str(items_per_page)})
        return self

    def filter(self, key: str, value: str):
        self.update_params({
            key: value,
        })
        return self
