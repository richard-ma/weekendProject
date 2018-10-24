#!/usr/bin/env python
# encoding: utf-8

import re

def plural(noun):
    if re.search('[sxz]$', noun):
        return re.sub('$', 'es', noun)
    elif re.search('[^aeioudgkprt]h$', noun):
        return re.sub('$', 'es', noun)
    elif re.search('[^aeiou]y$', noun):
        return re.sub('y$', 'ies', noun)
    else:
        return noun + 's'

import unittest

class TestPlural(unittest.TestCase):

    def setUp(self):
        pass

    def tearDown(self):
        pass

    def test_plural(self):
        self.assertEqual('ses', plural('s'))
        self.assertEqual('xes', plural('x'))
        self.assertEqual('zes', plural('z'))

        self.assertEqual('ahs', plural('ah'))
        self.assertEqual('ehs', plural('eh'))
        self.assertEqual('ihs', plural('ih'))
        self.assertEqual('ohs', plural('oh'))
        self.assertEqual('uhs', plural('uh'))
        self.assertEqual('dhs', plural('dh'))
        self.assertEqual('ghs', plural('gh'))
        self.assertEqual('khs', plural('kh'))
        self.assertEqual('phs', plural('ph'))
        self.assertEqual('rhs', plural('rh'))
        self.assertEqual('ths', plural('th'))

        self.assertEqual('ches', plural('ch'))
        self.assertEqual('shes', plural('sh'))

        self.assertEqual('ays', plural('ay'))
        self.assertEqual('eys', plural('ey'))
        self.assertEqual('iys', plural('iy'))
        self.assertEqual('oys', plural('oy'))
        self.assertEqual('uys', plural('uy'))

        self.assertEqual('cies', plural('cy'))

        self.assertEqual('tests', plural('test'))
        self.assertEqual('apples', plural('apple'))


if __name__ == '__main__':
    unittest.main()
