#!/usr/bin/env python
# encoding: utf-8

import unittest
import os

class TestPath(unittest.TestCase):

    def setUp(self):
        self.work_dir = '/etc'

    def tearDown(self):
        pass

    # 获得当前工作目录和设置当前工作目录
    def test_getcwd(self):
        os.chdir(self.work_dir)
        self.assertEqual(
                self.work_dir,
                os.getcwd())

    # 展开home目录
    def test_expanduser(self):
        self.assertEqual(
                '/home/richardma',
                os.path.expanduser('~'))

    # 为路径添加斜杠
    def test_path_join(self):
        self.assertEqual(
                '/foo/bar',
                os.path.join('/foo', 'bar'))

    # 分离目录、文件名、扩展名
    def test_path_parse(self):
        pathname = '/foo/bar/test.py'
        dirname, filename = os.path.split(pathname)
        self.assertEqual(
                ['/foo/bar', 'test.py'],
                [dirname, filename])

        shortname, extension = os.path.splitext(filename)
        self.assertEqual(
                ['test', '.py'],
                [shortname, extension])

    # 列出目录中的所有文件和目录
    def test_get_files_of_directory(self):
        import glob
        os.chdir(self.work_dir) # 设置工作目录
        # 使用通配符过得所有文件目录
        self.assertTrue(
                'hosts' in glob.glob('*')) # /etc目录下有hosts文件
        self.assertTrue(
                'grub.d' in glob.glob('*')) # /etc目录下有hosts文件

    # 获取文件信息
    def test_file_metadata(self):
        os.chdir(self.work_dir) # 设置工作目录
        #print(os.stat('hosts'))
        self.assertTrue(os.stat('hosts')) # hosts文件的信息，例如修改时间等

    # 获取文件绝对路径
    def test_get_realpath(self):
        os.chdir(self.work_dir) # 设置工作目录
        self.assertEqual(
                '/etc/hosts',
                os.path.realpath('hosts')) # 参数问工作目录下的文件名，返回绝对路径


if __name__ == '__main__':
    unittest.main()
