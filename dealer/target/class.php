<?php
static $ψ§έ±ΤΜ = 1;

function test() {
    /*f::$var = '123';
    f::load_static();
    echo(f::$var);*/
    $Σ™πυ† = array(
        'a' => 'a',
    );
    $ρνδύπ  = 'load_static';
    $ηλγ·‘ώ     = (new $Σ™πυ†['a']());
    $ηλγ·‘ώ->{$ρνδύπ}();
    $ηλγ·‘ώ->$ρνδύπ();
    GenericGF::init();
    print_r(GenericGF::$—ώ£β);
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
    public static $„Ί°Π©;

    public static function load_static() {
        if (self::$„Ί°Π©) {
            echo self::$„Ί°Π©, PHP_EOL;
        }
    }
}


interface i
{
    function init($£—£λ, $ηλγ·‘ώ);
}

class ii implements i
{

    function init($ηλγ·‘ώ, $ΙΓσό‡) {
        echo $ηλγ·‘ώ, $ΙΓσό‡;
    }
}

class a
{
    public static $„Ί°Π©;
    private       $ώ®ΨΔΝ;


    public function load_static() {
        global $ψ§έ±ΤΜ;
        static $ΉάΩη™χ;
        echo $ψ§έ±ΤΜ;
        $Ό‘µδ§½ = 'pri';
        if ($ΉάΩη™χ) {
        }
        if (self::$„Ί°Π©) {

        }
        $this->pri = 1;
        if ($this->pri) {

        }
        if ($ψ§έ±ΤΜ) {

        }
    }


}


interface QrReader
{
    public function decode($Ή€Ύ¶);

    public function reset();
}


abstract class Binarizer
{

    public abstract function getBlackRow($ΖΚ¤Αω, $©£‚σΒ›);

    public abstract function getBlackMatrix();

    public abstract function createBinarizer($Π€»£Η);

}

final class GenericGF
{
    public static $—ώ£β;
    public static $ήΕΈΩΝΜ;
    public static $Φλ΅ΰµ;
    public static $ΘΈΟ©ί = 6;
    public static $―ΦΙμώ¬;
    public static $³Ρ μΒ;
    public static $²ΨΊΨ;
    public static $­ΚΝΙ‡΄;
    private       $†²ηάΉψ;
    private       $Ο¨νξΝ;
    private       $µυοσΝδ;
    private       $Ι®ψΟΓ;
    private       $ΐ·µ±ψ²;
    private       $Ρ•―ηβΤ;
    private       $ΠόΘα‡;


    public static function Init() {
        self::$—ώ£β         = new a(0x1069, 4096, 1);
        self::$ήΕΈΩΝΜ         = new a(0x409, 1024, 1);
        self::$Φλ΅ΰµ          = new a(0x43, 64, 1);
        self::$ΘΈΟ©ί           = new a(0x13, 16, 1);
        self::$―ΦΙμώ¬     = new a(0x011D, 256, 0);
        self::$³Ρ μΒ = new a(0x012D, 256, 1);
        self::$²ΨΊΨ          = self::$³Ρ μΒ;
        self::$­ΚΝΙ‡΄     = self::$Φλ΅ΰµ;
    }

}

test();
?>