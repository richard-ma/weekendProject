# A obfuscator for PHP - 一个PHP语言的代码混淆器

## BUGS
* 1001: 当ob_var为TRUE时，`$array = array(); $sta = 'array'; $b = ${$sta};` 这里的array变量被替换，则sta变量无法引用，造成程序错误
* 1002: 当remove_whitespace为TRUE时，字符串中的空格有可能被删除，造成字符串变化
* 1003: 当用于某些系统插件编码时，由于系统插件可能读取文件的注释，当remove_commet为TRUE时，需要手动保留一些注释项不被删除，否则可能造成插件安装失败。