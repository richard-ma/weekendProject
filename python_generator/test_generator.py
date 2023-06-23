import unittest


class TestGenerator(unittest.TestCase):
    def setUp(self):
        self.data = "Generator data"

    def generator(self):
        while True:
            yield self.data

    def test_generator(self):
        gen_obj = self.generator()
        for _ in range(10):
            self.assertEqual(self.data, next(gen_obj))