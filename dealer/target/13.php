<?php

class  d
{
    static $���މ� = array();

    static function test() {
        echo "test", PHP_EOL;
        global $����;
        $�Ǿ͎�           = '/a/b/c/d/';
        $᠈��            = 'array';
        self::${$᠈��}[] = 1;
        if (1 == 2) {
        } else {
            //
            //$var = [];
            preg_replace_callback('/(\w+)\/([^\/]+)/', function ($��ݐ��) use (&$����) {
                $����[$��ݐ��[1]] = strip_tags($��ݐ��[2]);
            }, $�Ǿ͎�);
            assert($����['a'] === 'b');
            assert($����['c'] === 'd');
            assert(count($����) == 2);
        }
        $���� = array();
    }
}

function a() {
    $���♤ = '123123';
    return $���♤;
}

d::test();
print_r($����);
echo a();

?>