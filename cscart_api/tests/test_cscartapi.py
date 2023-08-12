import unittest

from cscartapi import *


class TestCscartAPI(unittest.TestCase):
    def setUp(self):
        self.base_url = 'http://example.com'
        self.api = CscartAPI(self.base_url, None, None)

        self.entity = 'entity'
        self.id = 'id'

        self.dict_1st = {'key': 'value'}
        self.dict_2nd = {'key': 'value', 'another_key': 'another_value'}
        self.dict_3rd = {'third_key': 'third_value'}

        self.sort_key = 'sort_key'
        self.sort_order_asc = 'ASC'
        self.sort_order_desc = 'DESC'

        self.page = 1
        self.items_per_page = 10

    def tearDown(self):
        pass

    def test_set_entity(self):
        self.api.set_entity(self.entity)
        self.assertEqual(self.entity, self.api.entity)

    def test_set_id(self):
        self.api.set_id(self.id)
        self.assertEqual(self.id, self.api.id)

    def test_update_params(self):
        self.api.update_params(self.dict_1st)
        self.assertDictEqual(self.dict_1st, self.api.params)

        self.api.update_params(self.dict_2nd)
        self.assertDictEqual(self.dict_2nd, self.api.params)

        self.api.update_params(self.dict_3rd)
        self.dict_2nd.update(self.dict_3rd),
        self.assertDictEqual(
            self.dict_2nd,
            self.api.params
        )

    def test_update_data(self):
        self.api.update_data(self.dict_1st)
        self.assertDictEqual(self.dict_1st, self.api.data)

        self.api.update_data(self.dict_2nd)
        self.assertDictEqual(self.dict_2nd, self.api.data)

        self.api.update_data(self.dict_3rd)
        self.dict_2nd.update(self.dict_3rd),
        self.assertDictEqual(
            self.dict_2nd,
            self.api.data
        )

    def test_reset(self):
        self.api.reset()
        self.assertIsNone(self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)

    def test_get_id_is_None(self):
        obj = self.api.get(self.entity)
        self.assertEqual(self.entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_get_with_id(self):
        obj = self.api.get(self.entity, self.id)
        self.assertEqual(self.entity, self.api.entity)
        self.assertEqual(self.id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_create(self):
        obj = self.api.create(self.entity, self.dict_1st)
        self.assertEqual(self.entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(self.dict_1st, self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_delete(self):
        obj = self.api.delete(self.entity, self.id)
        self.assertEqual(self.entity, self.api.entity)
        self.assertEqual(self.id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_update(self):
        obj = self.api.update(self.entity, self.id, self.dict_1st)
        self.assertEqual(self.entity, self.api.entity)
        self.assertEqual(self.id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(self.dict_1st, self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_get_id_is_None_url(self):
        self.api.get(self.entity)
        self.assertEqual(
            self.base_url + '/api/' + self.entity + '/',
            self.api.get_url()
        )

    def test_get_with_id_url(self):
        self.api.get(self.entity, self.id)
        self.assertEqual(
            self.base_url + '/api/' + self.entity + '/' + self.id,
            self.api.get_url()
        )

    def test_create_url(self):
        self.api.create(self.entity, self.dict_1st)
        self.assertEqual(
            self.base_url + '/api/' + self.entity + '/',
            self.api.get_url()
        )
        self.assertDictEqual(self.dict_1st, self.api.data)

    def test_delete_url(self):
        self.api.delete(self.entity, self.id)
        self.assertEqual(
            self.base_url + '/api/' + self.entity + '/' + self.id,
            self.api.get_url()
        )

    def test_update_url(self):
        self.api.update(self.entity, self.id, self.dict_1st)
        self.assertEqual(
            self.base_url + '/api/' + self.entity + '/' + self.id,
            self.api.get_url()
        )
        self.assertDictEqual(self.dict_1st, self.api.data)