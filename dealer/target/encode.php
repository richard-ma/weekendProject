<?php

/**
 * User: djunny
 * Date: 2016-11-22
 * Time: 16:32
 * Mail: 199962760@qq.com
 */
class core
{
    static $��ƪ� = 1;

    static function addslashes(&$�����) {
        if (is_array($�����)) {
            foreach ($����� as $�ݤ�� => &$���ﴂ) {
                core::addslashes($���ﴂ);
            }
        } else {
            $����� = addslashes($�����);
        }
        return $�����;
    }
}

$��˃�� = array('1');
core::addslashes($��˃��);
core::$��ƪ� = 2;
echo core::$��ƪ�;

$����� = /*<encode>*/
    'format_code'/*</encode>*/
;
$��ɟ�� = /*<encode>*/str_rot13('1')/*</encode>*/
;
$�ڜ�ڎ = /*<encode>*/
    1/*</encode>*/
;
$����� = /*<encode>*/
    3/*</encode>*/
;
assert($�ڜ�ڎ == 1);
assert($����� == 3);
assert($����� == 'format_code');
echo $�����;


?>