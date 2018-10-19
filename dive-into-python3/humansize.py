#!/usr/bin/env python
# encoding: utf-8

SUFFIXES = {1000: ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            1024: ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']}

def approximate_size(size, a_kilobyte_is_1024_byte=True):
    if size < 0:
        raise ValueError('number must be non-negative')

    multiple = 1024 if a_kilobyte_is_1024_byte else 1000

    for suffix in SUFFIXES[multiple]:
        size /= multiple
        if size < multiple:
            return '{0:.1f} {1}'.format(size, suffix)

    raise ValueError('number too large')

import unittest

class TestApproximateSize(unittest.TestCase):

    def setUp(self):
        self.value = 1000000000000

    def tearDown(self):
        pass

    def test_approximate_size(self):
        self.assertEqual('1.0 TB', approximate_size(self.value, False))
        self.assertEqual('931.0 GiB', approximate_size(self.value))

if __name__ == '__main__':
    unittest.main()
