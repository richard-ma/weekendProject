# dealer: A obfuscator for PHP - 一个PHP语言的代码混淆器

## 使用方法
* 本程序需要在Linux系统下运行
* 确保目录中有dealer.php, dealer_runner.php
* 确认待处理文件的位置，比如/home/srouce.php
* 确认处理后文件希望保存的位置，比如/home/dest.php
* 运行命令`php dealer_runner.php /home/source.php /home/dest.php`
* 如果处理文件为wordpress/woocommerce插件，需要执行以下操作
    * 打开/home/source.php文件，将开头的注释部分（使用/* */符号包裹起来的几行）复制
    * 粘贴到/home/dest.php文件开头，然后再替换文件创建zip压缩包

## BUGS
* 1001: 当ob_var为TRUE时，`$array = array(); $sta = 'array'; $b = ${$sta};` 这里的array变量被替换，则sta变量无法引用，造成程序错误
* 1002: 当remove_whitespace为TRUE时，字符串中的空格有可能被删除，造成字符串变化
* 1003: 当用于某些系统插件编码时，由于系统插件可能读取文件的注释，当remove_commet为TRUE时，需要手动保留一些注释项不被删除，否则可能造成插件安装失败。