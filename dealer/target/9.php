<?php
/**
 * User: djunny
 * Date: 2016-01-26
 * Time: 20:56
 * Mail: 199962760@qq.com
 */
print_R('a');
function parse_name($ÏòÎÏ·, $Â£’Öà = 0) {
    if ($Â£’Öà) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($–†ŠÀ¬¼) {
            return strtoupper($–†ŠÀ¬¼[1]);
        }, $ÏòÎÏ·));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $ÏòÎÏ·), "_"));
    }
}
?>