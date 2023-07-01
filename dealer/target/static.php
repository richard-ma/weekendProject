<?php
namespace {
    static $×ó«€‰ý = 1;

    interface i
    {
        function init($ÂÅÊúÃ€, $Ø»ËìÅŸ);
    }

    class ii implements i
    {

        function init($Ø»ËìÅŸ, $Á˜·œ¢Œ) {
            echo $Ø»ËìÅŸ, $Á˜·œ¢Œ;
        }
    }

    class a
    {
        public static $â·² ;
        private       $¤ÀÒÇÉü;


        public function load_static() {
            global $×ó«€‰ý;
            static $¹´˜ßÕ¦;
            echo $×ó«€‰ý;
            $ñõÑÈÎß = 'pri';
            if ($¹´˜ßÕ¦) {
            }
            if (self::$â·² ) {

            }
            $this->pri = 1;
            if ($this->pri) {

            }
            if ($×ó«€‰ý) {

            }
        }


    }


    interface QrReader
    {
        public function decode($Ï­œâË¨);

        public function reset();
    }


    abstract class Binarizer
    {

        public abstract function getBlackRow($Ç³ö‚˜Ç, $Ÿ¿È—µó);

        public abstract function getBlackMatrix();

        public abstract function createBinarizer($½Õ£Ô¨Æ);

    }

    final class GenericGF
    {
        public static $¡‹œù¨;
        public static $¿¥öÏô°;
        public static $×¯ž¶¼í;
        public static $‘¤†éµö=6;
        public static $ø¢Œ›Ðž;
        public static $â™„ÅÝ©;
        public static $²è½ÆÞÞ;
        public static $ÉèÌîØÆ;
        private       $®¿êØ‚Ó;
        private       $—ñ± ÊÝ;
        private       $Ž«‰¶‡¥;
        private       $ðŽÝÙ§£;
        private       $É¦Ÿšý;
        private       $åŽàà;
        private       $ß˜ßß©ä;


        public static function Init() {
            self::$¡‹œù¨         = new a(0x1069, 4096, 1);
            self::$¿¥öÏô°         = new a(0x409, 1024, 1);
            self::$×¯ž¶¼í          = new a(0x43, 64, 1);
            self::$‘¤†éµö           = new a(0x13, 16, 1);
            self::$ø¢Œ›Ðž     = new a(0x011D, 256, 0);
            self::$â™„ÅÝ© = new a(0x012D, 256, 1);
            self::$²è½ÆÞÞ          = self::$â™„ÅÝ©;
            self::$ÉèÌîØÆ     = self::$×¯ž¶¼í;
        }

    }

    if (PHP_EOL) {
    }
    $„žÜîáª = array(
        'a' => $Ø»ËìÅŸ,
    );
    $úÇõ£ÏÛ = 'load_static';
    $Ø»ËìÅŸ     = (new a());
    $Ø»ËìÅŸ->{$úÇõ£ÏÛ}();
    $ËÓˆéÖ• = new ii();
    $ËÓˆéÖ•->init('a', 'b');
    $_SERVER['s'][0] = 'AZTEC_PARAM';
    echo GenericGF::${$_SERVER['s'][0]};
}

?>