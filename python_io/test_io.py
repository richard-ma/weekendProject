import io
import unittest

'''
IOBase
    RawIOBase
        FileIO 
            open(filename, 'rb', buffering=0)
            open(filename, 'wb', buffering=0)
    BufferedIOBase
        BufferedWriter: open(filename, 'wb')
        BufferedReader: open(filename, 'rb')
        BufferedRWPair
        BufferedRandom
        BytesIO
    TextIOBase
        TextIOWrapper: open(filename, 'r') -> ACCESS BufferedIOBase
        StringIO
'''


class TestIO(unittest.TestCase):
    def setUp(self):
        self.FILENAME = "./demo.txt"

    def tearDown(self):
        if self.f:
            self.f.close()

    def test_open_r(self):
        self.f = open(self.FILENAME, 'r')
        self.assertIsInstance(self.f, io.TextIOWrapper)

    def test_open_w(self):
        self.f = open(self.FILENAME, 'w')
        self.assertIsInstance(self.f, io.TextIOWrapper)

    def test_open_rb(self):
        self.f = open(self.FILENAME, 'rb')
        self.assertIsInstance(self.f, io.BufferedReader)

    def test_open_wb(self):
        self.f = open(self.FILENAME, 'wb')
        self.assertIsInstance(self.f, io.BufferedWriter)

    def test_open_buffering_0(self):
        self.f = open(self.FILENAME, 'rb', buffering=0)
        self.assertIsInstance(self.f, io.FileIO)
        self.f = open(self.FILENAME, 'wb', buffering=0)
        self.assertIsInstance(self.f, io.FileIO)

    def test_open_rw_plus(self):
        self.f = open(self.FILENAME, 'r+')
        self.assertIsInstance(self.f, io.TextIOWrapper)
        self.f = open(self.FILENAME, 'w+')
        self.assertIsInstance(self.f, io.TextIOWrapper)

    def test_StringIO(self):
        self.f = io.StringIO()
        self.f.write("hello")
        self.f.write(" world!")
        self.assertIsInstance(self.f, io.TextIOBase) # TextIOBase is parent class of StringIO
        self.assertEqual(self.f.getvalue(), "hello world!")