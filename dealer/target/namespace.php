<?php
namespace z {

    class aa {
        static $�ǘ��� = 1;

        function getBlackMatrix() {
            $��͸ڠ  = 1;
            $����� = 2;
            if ($��͸ڠ >= self::$�ǘ��� && $����� >= self::$�ǘ���) {

            }
        }
    }

    class a {
        public $����;

        function b($����) {
            $this->$���� = $����;
        }
    }

    class_alias('\z\a', '\z\b');

    //echo "{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}";
    //echo $_SERVER['REQUEST_TIME'];
    $����ƛ = new \z\a();
    $����ƛ->b('g');
    echo $����ƛ->g;
}


?>