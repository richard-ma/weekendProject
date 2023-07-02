# A obfuscator for PHP - 一个PHP语言的代码混淆器

## BUGS
* 1001: 当ob_var为TRUE时，`$array = array(); $sta = 'array'; $b = ${$sta};` 这里的array变量被替换，则sta变量无法引用，造成程序错误
* 1002: 当remove_whitespace为TRUE时，字符串中的空格有可能被删除，造成字符串变化