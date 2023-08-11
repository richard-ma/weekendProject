import os
import unittest

from cscartapi.sender_requests import *


class TestSenderRequests(unittest.TestCase):
    def setUp(self):
        self.base_url = os.environ['CSCART_BASE_URL']
        self.username = os.environ['CSCART_USERNAME']
        self.api_key = os.environ['CSCART_API_KEY']

        self.sender = SenderRequests(self.username, self.api_key)

    def tearDown(self):
        pass

    def test_get(self):
        url = self.base_url + "users/"

        response = self.sender.get(url)
        self.assertTrue(type(response) is dict)
        self.assertIn('users', response.keys())