import requests

from requests.auth import HTTPBasicAuth

from cscartapi.sender import Sender, Method


class SenderRequests(Sender):
    def __init__(self, username, api_key):
        super().__init__(username, api_key)
        self.basic_auth = HTTPBasicAuth(self.username, self.api_key)

    def send(self, method, url, data=None):
        if method is Method.GET:
            response = requests.get(url, auth=self.basic_auth)
        elif method is Method.POST:
            response = requests.post(url, json=data)
        elif method is Method.PUT:
            response = requests.put(url, json=data)
        elif method is Method.DELETE:
            response = requests.delete(url)
        else:
            raise TypeError(method + " not in sender.Method list.") # 访问方法超出API允许范围
        
        return response.json()