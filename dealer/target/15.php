<?php
error_reporting(E_ERROR);
class aa {
    static $���ܬ� = 1;

    function getBlackMatrix() {
        $������  = 1;
        $�ԥ�ú = 2;
        if ($������ >= self::$���ܬ� && $�ԥ�ú >= self::$���ܬ�) {

        }
    }
}

class a {
    public $�ƚ���;

    function b($�ƚ���) {
        $this->$�ƚ��� = $�ƚ���;
    }
}

final class MathUtils {
    private function __construct() {
    }

    public static function round($���ӫ�) {
        return (int)($���ӫ� + ($���ӫ� < 0.0 ? -0.5 : 0.5));
    }

    public static function distance($�����, $��ǹ��, $�ˠ���, $��ݥ��) {
        $��ⷹ� = $����� - $�ˠ���;
        $�㍒� = $��ǹ�� - $��ݥ��;
        return (float)sqrt($��ⷹ� * $��ⷹ� + $�㍒� * $�㍒�);
    }
}

class Reader {

}

interface sReader {
}

abstract class mzReader extends Reader {
}
$_SERVER['HTTP_HOST'] = 1;
$_SERVER['REQUEST_URI'] = 1;

function main() {
    //echo "{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}"; // 没有HTTP_HOST键值
    //echo $_SERVER['REQUEST_TIME'];
}
define('ROOT_PATH', __DIR__);
!defined('ROOT_PATH') && exit('123');

$���΃� = new a();
$���΃�->b('g');
echo $���΃�->g;
echo main();
?>