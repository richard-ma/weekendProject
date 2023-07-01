<?php

class  d
{
    static $›Љ„Ю‰ѕ = array();

    static function test() {
        echo "test", PHP_EOL;
        global $і®©В€Ѕ;
        $ ЗѕНЋ±           = '/a/b/c/d/';
        $б €св€            = 'array';
        self::${$б €св€}[] = 1;
        if (1 == 2) {
        } else {
            //
            //$var = [];
            preg_replace_callback('/(\w+)\/([^\/]+)/', function ($№ќЭђ‹С) use (&$і®©В€Ѕ) {
                $і®©В€Ѕ[$№ќЭђ‹С[1]] = strip_tags($№ќЭђ‹С[2]);
            }, $ ЗѕНЋ±);
            assert($і®©В€Ѕ['a'] === 'b');
            assert($і®©В€Ѕ['c'] === 'd');
            assert(count($і®©В€Ѕ) == 2);
        }
        $і®©В€Ѕ = array();
    }
}

function a() {
    $ЂЉъв™¤ = '123123';
    return $ЂЉъв™¤;
}

d::test();
print_r($і®©В€Ѕ);
echo a();

?>