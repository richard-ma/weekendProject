import unittest
from pythonds.fraction import Fraction


class TestFraction(unittest.TestCase):
    def setUp(self):
        self.f = Fraction(3, 5)
        self.other = Fraction(1, 5)
        return super().setUp()
    
    def tearDown(self):
        return super().tearDown()
    
    def test_convert_to_string(self):
        self.assertEqual(str(self.f), '3/5')

    def test_compare(self):
        self.assertEqual(Fraction(1, 5), Fraction(1, 5))
        self.assertGreater(Fraction(3, 5), Fraction(1, 5))
        self.assertLess(Fraction(1, 5), Fraction(3, 5))
        self.assertNotEqual(Fraction(1, 5), Fraction(3, 5))
        self.assertGreaterEqual(Fraction(3, 5), Fraction(1, 5))
        self.assertGreaterEqual(Fraction(3, 5), Fraction(3, 5))
        self.assertLessEqual(Fraction(1, 5), Fraction(3, 5))
        self.assertLessEqual(Fraction(3, 5), Fraction(3, 5))

    def test_basic_calculate(self):
        self.assertEqual(Fraction(1, 5) + Fraction(2, 5), Fraction(3, 5)) 
        self.assertEqual(Fraction(3, 5) - Fraction(1, 5), Fraction(2, 5)) 
        self.assertEqual(Fraction(1, 5) * Fraction(1, 2), Fraction(1, 10))
        self.assertEqual(Fraction(1, 3) / Fraction(1, 4), Fraction(4, 3)) 

    def test_negative_den(self):
        self.assertEqual(Fraction(2, -5), Fraction(-2, 5))

    def test_getter(self):
        f = Fraction(1, 5)
        self.assertEqual(1, f.getNum())
        self.assertEqual(5, f.getDen())