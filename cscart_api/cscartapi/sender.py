from enum import Enum


class Method(Enum):
    GET = 1
    POST = 2
    PUT = 3
    DELETE = 4


class Sender:
    def __init__(self, username, api_key):
        self.username = username
        self.api_key = api_key

    def get(self, url):
        return self.send(Method.GET, url)

    def post(self, url, data):
        return self.send(Method.POST, url, data)

    def delete(self, url):
        return self.send(Method.DELETE, url)

    def put(self, url, data):
        return self.send(Method.PUT, url, data)

    def send(self, method, url, data=None):
        raise NotImplementedError()