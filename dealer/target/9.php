<?php
/**
 * User: djunny
 * Date: 2016-01-26
 * Time: 20:56
 * Mail: 199962760@qq.com
 */
print_R('a');
function parse_name($���Ϸ�, $�£��� = 0) {
    if ($�£���) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($������) {
            return strtoupper($������[1]);
        }, $���Ϸ�));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $���Ϸ�), "_"));
    }
}
?>