<?php

function getOptions($options=array()) {
    $defaultOptions = array(
        'remove_whitespace' => TRUE,
        'remove_comment' => TRUE,
        'ob_int' => TRUE,
        'ob_str' => TRUE,
        'ob_str_method' => -1, // -1 for random choice, 0 for base64, 1 for gzencode, 2 for rot13, 3 for geinflate
        'ob_var' => TRUE,
    );
    
    return array_merge($defaultOptions, $options);
}

function bugsNotice($code) {
    if (strpos($code, '${') != FALSE) {
        echo "[NOTICE] '\${ }' in code, maybe cause some bugs in obfuscated code. See [BUGS 1001]" . PHP_EOL;
    }
}

function obfuscatePHP($code, $options=array()) {
    bugsNotice($code); // 检测代码中的特殊结构，可能造成转换后的代码出现BUG

    $options = getOptions($options); // 获得默认配置
    $origin_options = $options; // 备份设置，用于部分修改后恢复原设置
    $var_name_dict = array(); // 存储变量名的替换关系
    $tokens = token_get_all($code); // 将文件内容解析为token对象
    for ($key=0; $key < count($tokens); $key++) {
        /*
        token: array(3)
            token index
            token string
            line number
        */
        $token = &$tokens[$key];
        if (is_array($token)) {
            $token_index = $token[0]; // token索引号，用于查找token name
            $token_name = is_numeric($token_index) ? token_name($token_index) : ''; // 使用token 索引号查找token name
            $token_str = &$token[1]; // token中所包含的字符

            switch($token_index) {
                case T_WHITESPACE: // 空格字符
                    if ($options['remove_whitespace']) {
                        $token_str = ''; // 去掉空格
                    }
                    break;
                case T_COMMENT: // 注释部分
                case T_DOC_COMMENT:
                    if ($options['remove_comment']) {
                        $token_str = ''; // 去掉注释
                    }
                    break;
                case T_LNUMBER: // 整数
                    if ($options['ob_int']) {
                        $token_str = ob_int($token_str); // 混淆整数
                    }
                    break;
                case T_CONSTANT_ENCAPSED_STRING: // "foo"引号引起来的字符串
                    if ($options['ob_str']) {
                        $token_str = ob_str($token_str, $options['ob_str_method']); // 混淆字符串
                    }
                    break;
                case T_VARIABLE: // 变量
                    if (!$options['ob_var']) {
                        break; // 关闭了混淆变量名的开关，不进行混淆
                    }

                    $skip_array = array( // 不可修改的变量名，需要跳过处理
                        '$_SERVER', '$_GET', '$_POST',
                        '$_COOKIE', '$_REQUEST', '$this',
                        '$GLOBALS', '$_SESSION', '$_FILES',
                        '$_ENV',
                    );
                    if (!in_array($token_str, $skip_array) && $token_str[0] === '$') { // 没有在跳过范围内，则进行处理
                        $token_str = ob_var($token_str, $var_name_dict);
                    }
                    break;
                case T_CONST:
                    $options['ob_str'] = FALSE; // const 常量不可以混淆字符串，否则影响结果
                    break;
                case T_PROTECTED:
                    $options['ob_str'] = FALSE; // protected 保护类属性不可以混淆字符串，否则影响结果
                    break;
            }
        } else { // 括号分号等符号
            switch($token) {
                case ';':
                    $options = $origin_options; // 复位所有设置
                    // echo ";" . $options['ob_str'] . PHP_EOL;
                    break;
                case '(':
                    $options['ob_str'] = FALSE; // 函数参数不可以混淆，否则影响结果
                    // echo "(" . $options['ob_str'] . PHP_EOL;
                    break;
                case ')':
                    $options = $origin_options; // 复位所有设置
                    // echo ")" . $options['ob_str'] . PHP_EOL;
                    break;
            }
        }
    }

    $obfuscateCode = '';
    foreach ($tokens as $key => $val) {
        if (is_array($val)) {
            $obfuscateCode .= $val[1];
        } else {
            $obfuscateCode .= $val;
        }
    }
    return $obfuscateCode;
}

function obfuscatePHPFile($sourceFile, $targetFile='', $options=array()) {
    $code = file_get_contents($sourceFile);
    $code = checkBom($code);
    $obfuscateCode = obfuscatePHP($code, $options);
    if ($targetFile === '') {
        return $obfuscateCode;
    } else {
        file_put_contents($targetFile, $obfuscateCode);
    }
}

function ob_int($num) {
    if (strpos($num, '0x') === 0) {
        $num = base_convert($num, 16, 10);
    }
    $repeat = ($num % 5) + 1;
    $str    = '0x' . str_repeat('0', $repeat) . base_convert($num, 10, 16);
    return $str;
}

function ob_str($str, $choice_method=-1) {
    $methods = array(
        'ob_str_base64',
        'ob_str_gzencode1',
        'ob_str_rot13',
        'ob_str_gzencode2',
    );

    $choice_method = $choice_method === -1 ? rand(0, 3) : $choice_method;
    $func = $methods[$choice_method];
    $str = $func($str);
    return $str;
}

function ob_str_base64($str) {
    $str = base64_encode($str);
    return 'trim(base64_decode(\'' . $str . '\'), \'\\\'"\')';
}

function ob_str_gzencode1($str) {
    $str = base64_encode(gzencode($str));
    return 'trim(gzdecode(base64_decode(\''. $str .'\')), \'\\\'"\')';
}

function ob_str_gzencode2($str) {
    $str = base64_encode(gzencode($str));
    $str = strtr($str, array('=' => ''));
    return 'trim(gzinflate(substr(base64_decode(\''. $str .'\'), 10, -8)), \'\\\'"\')';
}

function ob_str_rot13($str) {
    $str = base64_encode(str_rot13($str));
    return 'trim(str_rot13(base64_decode(\''. $str .'\')), \'\\\'"\')';
}

function ob_var($str, &$var_name_dict, $add_dollar=TRUE, $nameLength=6, $prefix='') {
    $keys = array_keys($var_name_dict);

    if (in_array($str, $keys)) {
        $varName = $var_name_dict[$str];
    } else {
        $varName = generate_name($nameLength);
        while (in_array($varName, $var_name_dict)) {
            $varName = generate_name($nameLength);
        }
        $var_name_dict[$str] = $varName;
    }
    $varName = $prefix . $varName;

    if ($add_dollar) {
        $varName = '$' . $varName;
    }

    return $varName;
}

function generate_name($length=6) {
    $varName = '';
    foreach (range(1, $length) as $_) {
        $varName .= chr(rand(128, 254));
    }
    return $varName;
}

function checkBom($code) {
    $charset = array(
        1 => substr($code, 0, 1),
        2 => substr($code, 1, 1),
        3 => substr($code, 2, 1),
    );

    if (ord($charset[1]) === 239 && ord($charset[2]) === 187 && ord($charset[3]) === 191) {
        return substr($code, 3);
    } else {
        return $code;
    }
}