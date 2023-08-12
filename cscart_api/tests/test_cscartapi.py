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
        self.sort_order_asc = 'asc'
        self.sort_order_desc = 'desc'

        self.page = 1
        self.items_per_page = 10

        self.filter_key = 'filter_key'
        self.filter_value = 'filter_value'

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
        self.assertIsNone(self.api.method)
        self.assertIsNone(self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)

    def test_get_id_is_None(self):
        obj = self.api.get(self.entity)
        self.assertEqual(self.api.method, 'GET')
        self.assertEqual(self.entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_get_with_id(self):
        obj = self.api.get(self.entity, self.id)
        self.assertEqual(self.api.method, 'GET')
        self.assertEqual(self.entity, self.api.entity)
        self.assertEqual(self.id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_create(self):
        obj = self.api.create(self.entity, self.dict_1st)
        self.assertEqual(self.api.method, 'POST')
        self.assertEqual(self.entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(self.dict_1st, self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_delete(self):
        obj = self.api.delete(self.entity, self.id)
        self.assertEqual(self.api.method, 'DELETE')
        self.assertEqual(self.entity, self.api.entity)
        self.assertEqual(self.id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_update(self):
        obj = self.api.update(self.entity, self.id, self.dict_1st)
        self.assertEqual(self.api.method, 'PUT')
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

    def test_order_by_with_asc(self):
        self.api.get(self.entity).order_by(self.sort_key, self.sort_order_asc)
        self.assertDictEqual({
            'sort_by': self.sort_key,
            'sort_order': self.sort_order_asc,
        }, self.api.params)

    def test_order_by_with_desc(self):
        self.api.get(self.entity).order_by(self.sort_key, self.sort_order_desc)
        self.assertDictEqual({
            'sort_by': self.sort_key,
            'sort_order': self.sort_order_desc,
        }, self.api.params)

    def test_page_with_default_items_per_page(self):
        self.api.get(self.entity).page(self.page)
        self.assertDictEqual({
            'page': str(self.page),
        }, self.api.params)

    def test_page_with_items_per_page(self):
        self.api.get(self.entity).page(self.page, self.items_per_page)
        self.assertDictEqual({
            'page': str(self.page),
            'items_per_page': str(self.items_per_page),
        }, self.api.params)

    def test_filter(self):
        self.api.get(self.entity).filter(self.filter_key, self.filter_value)
        self.assertDictEqual({
            self.filter_key: self.filter_value,
        }, self.api.params)

    def test_order_by_with_asc_url(self):
        self.api.get(self.entity).order_by(self.sort_key, self.sort_order_asc)
        self.assertTrue(
            self.api.get_url().endswith('?sort_by=%s&sort_order=%s' % (
                self.sort_key, 
                self.sort_order_asc
                )))

    def test_order_by_with_desc_url(self):
        self.api.get(self.entity).order_by(self.sort_key, self.sort_order_desc)
        self.assertTrue(
            self.api.get_url().endswith('?sort_by=%s&sort_order=%s' % (
                self.sort_key, 
                self.sort_order_desc
                )))

    def test_page_with_default_items_per_page_url(self):
        self.api.get(self.entity).page(self.page)
        self.assertTrue(
            self.api.get_url().endswith('?page=%s' % (
                self.page
                )))

    def test_page_with_items_per_page_url(self):
        self.api.get(self.entity).page(self.page, self.items_per_page)
        self.assertTrue(
            self.api.get_url().endswith('?page=%s&items_per_page=%s' % (
                self.page,
                self.items_per_page
                )))

    def test_filter(self):
        self.api.get(self.entity).filter(self.filter_key, self.filter_value)
        self.assertTrue(
            self.api.get_url().endswith('?%s=%s' % (
                self.filter_key,
                self.filter_value
                )))