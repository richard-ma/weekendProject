<?php
namespace {
    static $�󫀉� = 1;

    interface i
    {
        function init($����À, $ػ��ş);
    }

    class ii implements i
    {

        function init($ػ��ş, $������) {
            echo $ػ��ş, $������;
        }
    }

    class a
    {
        public static $⷏���;
        private       $������;


        public function load_static() {
            global $�󫀉�;
            static $����զ;
            echo $�󫀉�;
            $������ = 'pri';
            if ($����զ) {
            }
            if (self::$⷏���) {

            }
            $this->pri = 1;
            if ($this->pri) {

            }
            if ($�󫀉�) {

            }
        }


    }


    interface QrReader
    {
        public function decode($ϭ��˨);

        public function reset();
    }


    abstract class Binarizer
    {

        public abstract function getBlackRow($ǳ����, $��ȗ��);

        public abstract function getBlackMatrix();

        public abstract function createBinarizer($�գԨ�);

    }

    final class GenericGF
    {
        public static $������;
        public static $������;
        public static $ׯ����;
        public static $�����=6;
        public static $����О;
        public static $♄�ݩ;
        public static $�����;
        public static $������;
        private       $���؂�;
        private       $����;
        private       $������;
        private       $���٧�;
        private       $ɦ����;
        private       $厁���;
        private       $ߘ�ߩ�;


        public static function Init() {
            self::$������         = new a(0x1069, 4096, 1);
            self::$������         = new a(0x409, 1024, 1);
            self::$ׯ����          = new a(0x43, 64, 1);
            self::$�����           = new a(0x13, 16, 1);
            self::$����О     = new a(0x011D, 256, 0);
            self::$♄�ݩ = new a(0x012D, 256, 1);
            self::$�����          = self::$♄�ݩ;
            self::$������     = self::$ׯ����;
        }

    }

    if (PHP_EOL) {
    }
    $����� = array(
        'a' => $ػ��ş,
    );
    $������ = 'load_static';
    $ػ��ş     = (new a());
    $ػ��ş->{$������}();
    $�ӈ�֕ = new ii();
    $�ӈ�֕->init('a', 'b');
    $_SERVER['s'][0] = 'AZTEC_PARAM';
    echo GenericGF::${$_SERVER['s'][0]};
}

?>