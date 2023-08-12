import unittest

from cscartapi import *


class TestCscartAPI(unittest.TestCase):
    def setUp(self):
        self.base_url = 'http://example.com'
        self.api = CscartAPI(self.base_url, None, None)

        self.test_entity = 'entity'
        self.test_id = 'id'
        self.test_1st_dict = {'key': 'value'}
        self.test_2nd_dict = {'key': 'value', 'another_key': 'another_value'}
        self.test_3rd_dict = {'third_key': 'third_value'}

    def tearDown(self):
        pass

    def test_set_entity(self):
        self.api.set_entity(self.test_entity)
        self.assertEqual(self.test_entity, self.api.entity)

    def test_set_id(self):
        self.api.set_id(self.test_id)
        self.assertEqual(self.test_id, self.api.id)

    def test_update_params(self):
        self.api.update_params(self.test_1st_dict)
        self.assertDictEqual(self.test_1st_dict, self.api.params)

        self.api.update_params(self.test_2nd_dict)
        self.assertDictEqual(self.test_2nd_dict, self.api.params)

        self.api.update_params(self.test_3rd_dict)
        self.test_2nd_dict.update(self.test_3rd_dict),
        self.assertDictEqual(
            self.test_2nd_dict,
            self.api.params
        )

    def test_update_data(self):
        self.api.update_data(self.test_1st_dict)
        self.assertDictEqual(self.test_1st_dict, self.api.data)

        self.api.update_data(self.test_2nd_dict)
        self.assertDictEqual(self.test_2nd_dict, self.api.data)

        self.api.update_data(self.test_3rd_dict)
        self.test_2nd_dict.update(self.test_3rd_dict),
        self.assertDictEqual(
            self.test_2nd_dict,
            self.api.data
        )

    def test_reset(self):
        self.api.reset()
        self.assertIsNone(self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)

    def test_get_id_is_None(self):
        obj = self.api.get(self.test_entity)
        self.assertEqual(self.test_entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_get_with_id(self):
        obj = self.api.get(self.test_entity, self.test_id)
        self.assertEqual(self.test_entity, self.api.entity)
        self.assertEqual(self.test_id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_create(self):
        obj = self.api.create(self.test_entity, self.test_1st_dict)
        self.assertEqual(self.test_entity, self.api.entity)
        self.assertIsNone(self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(self.test_1st_dict, self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_delete(self):
        obj = self.api.delete(self.test_entity, self.test_id)
        self.assertEqual(self.test_entity, self.api.entity)
        self.assertEqual(self.test_id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(dict(), self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_update(self):
        obj = self.api.update(self.test_entity, self.test_id, self.test_1st_dict)
        self.assertEqual(self.test_entity, self.api.entity)
        self.assertEqual(self.test_id, self.api.id)
        self.assertDictEqual(dict(), self.api.params)
        self.assertDictEqual(self.test_1st_dict, self.api.data)
        self.assertIsInstance(obj, CscartAPI)

    def test_get_id_is_None_url(self):
        self.api.get(self.test_entity)
        self.assertEqual(
            self.base_url + '/api/' + self.test_entity + '/',
            self.api.get_url()
        )

    def test_get_with_id_url(self):
        self.api.get(self.test_entity, self.test_id)
        self.assertEqual(
            self.base_url + '/api/' + self.test_entity + '/' + self.test_id,
            self.api.get_url()
        )

    def test_create_url(self):
        self.api.create(self.test_entity, self.test_1st_dict)
        self.assertEqual(
            self.base_url + '/api/' + self.test_entity + '/',
            self.api.get_url()
        )
        self.assertDictEqual(self.test_1st_dict, self.api.data)

    def test_delete_url(self):
        self.api.delete(self.test_entity, self.test_id)
        self.assertEqual(
            self.base_url + '/api/' + self.test_entity + '/' + self.test_id,
            self.api.get_url()
        )

    def test_update_url(self):
        self.api.update(self.test_entity, self.test_id, self.test_1st_dict)
        self.assertEqual(
            self.base_url + '/api/' + self.test_entity + '/' + self.test_id,
            self.api.get_url()
        )
        self.assertDictEqual(self.test_1st_dict, self.api.data)