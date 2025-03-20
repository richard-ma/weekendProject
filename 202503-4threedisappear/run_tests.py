import unittest


if __name__ == "__main__":
    suite = unittest.defaultTestLoader.discover("tests", "test_*.py")
    runner = unittest.TextTestRunner()
    runner.run(suite)