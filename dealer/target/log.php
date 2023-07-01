<?php

class log {
    /**
     * @var int log file name
     */
    public static $㷨��� = 0;
    /**
     * @var int log file file pointer
     */
    public static $������ = 0;

    /**
     * init to log
     *
     * @param $file
     */
    public static function set_logfile($������) {
        if ($������ == 1) {
            $������ = 'data/log/' . date('Y-m-d') . '.log';
        }
        self::$㷨��� = $������;
        self::$������ = fopen($������, 'a+');
    }

    /**
     * alias set log file
     *
     * @param $file
     */
    public static function set_file($������) {
        self::set_logfile($������);
    }

    /**
     * dump variable for log
     *
     * @param $data
     * @return string
     */
    public static function dump_var($������) {
        if (is_array($������)) {
            $���� = '';
            foreach ($������ as $������ => $��ע��) {
                if (is_array($��ע��)) {
                    $���� .= '[' . $������ . '=' . self::dump_var($��ע��) . ']';
                } else {
                    $���� .= '[' . $������ . '=' . $��ע�� . ']';
                }
            }
            return $����;
        } else {
            return '[' . $������ . ']';
        }
    }

    /**
     * log::info($arg1,$arg2....$argn);
     *
     * @param mixed
     */
    public static function info() {
        self::add_log('info', func_get_args(), func_num_args());
    }

    /**
     * log::error($arg1,$arg2....$argn);
     *
     * @param mixed
     */
    public static function error() {
        self::add_log('error', func_get_args(), func_num_args());
        throw new Exception('error');
    }

    /**
     * add log
     *
     * @param $type
     * @param $arg_list
     * @param $arg_count
     */
    private static function add_log($����ק, $������, $�����) {
        $��Ѯ� = '';
        for ($�մ��� = 0, $������ = $�����; $�մ��� < $������; $�մ���++) {
            $��Ѯ� .= self::dump_var($������[$�մ���]);
        }
        $��Ѯ� .= '[' . usedtime() . "ms]";
        $��Ѯ� = "[" . date('H:i:s') . "]" . $��Ѯ� . "\r\n";
        if (self::$������) {
            fputs(self::$������, $��Ѯ�);
        }
        if (php_sapi_name() == 'cli') {
            echo $��Ѯ�;
        } else {
            if (isset($_SERVER['log'])) {
                $_SERVER['log'] = array(
                    'info' => array(),
                    'error' => array(),
                );
            }
            $_SERVER['log'][$����ק][] = $��Ѯ�;
        }
    }
}

?>