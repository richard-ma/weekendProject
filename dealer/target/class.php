<?php
static $��ݱ�� = 1;

function test() {
    /*f::$var = '123';
    f::load_static();
    echo(f::$var);*/
    $�ә��� = array(
        'a' => 'a',
    );
    $�����  = 'load_static';
    $��㷑�     = (new $�ә���['a']());
    $��㷑�->{$�����}();
    $��㷑�->$�����();
    GenericGF::init();
    print_r(GenericGF::$������);
    /*
    if (PHP_EOL) {
    }
    $aaa[0] = 'a';
    $aaa[2] = 'init';

    echo $func, PHP_EOL;
    $i = new ii();
    $i->init('a', 'b');
    $_SERVER['s'][0] = 'AZTEC_PARAM';
    echo GenericGF::${$_SERVER['s'][0]};*/
}

class f
{
    public static $����Щ;

    public static function load_static() {
        if (self::$����Щ) {
            echo self::$����Щ, PHP_EOL;
        }
    }
}


interface i
{
    function init($������, $��㷑�);
}

class ii implements i
{

    function init($��㷑�, $������) {
        echo $��㷑�, $������;
    }
}

class a
{
    public static $����Щ;
    private       $���Ğ�;


    public function load_static() {
        global $��ݱ��;
        static $�����;
        echo $��ݱ��;
        $���䧽 = 'pri';
        if ($�����) {
        }
        if (self::$����Щ) {

        }
        $this->pri = 1;
        if ($this->pri) {

        }
        if ($��ݱ��) {

        }
    }


}


interface QrReader
{
    public function decode($������);

    public function reset();
}


abstract class Binarizer
{

    public abstract function getBlackRow($�ʤ���, $����);

    public abstract function getBlackMatrix();

    public abstract function createBinarizer($Ѐ��ǘ);

}

final class GenericGF
{
    public static $������;
    public static $�Ÿ���;
    public static $�����;
    public static $�ȸϩ� = 6;
    public static $������;
    public static $�Ѡ���;
    public static $�غ���;
    public static $���ɇ�;
    private       $���ܹ�;
    private       $�Ϩ���;
    private       $������;
    private       $�ɮ���;
    private       $������;
    private       $ѕ����;
    private       $�����;


    public static function Init() {
        self::$������         = new a(0x1069, 4096, 1);
        self::$�Ÿ���         = new a(0x409, 1024, 1);
        self::$�����          = new a(0x43, 64, 1);
        self::$�ȸϩ�           = new a(0x13, 16, 1);
        self::$������     = new a(0x011D, 256, 0);
        self::$�Ѡ��� = new a(0x012D, 256, 1);
        self::$�غ���          = self::$�Ѡ���;
        self::$���ɇ�     = self::$�����;
    }

}

test();
?>