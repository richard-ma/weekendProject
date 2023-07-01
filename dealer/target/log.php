<?php

class log {
    /**
     * @var int log file name
     */
    public static $ã·¨»„ß = 0;
    /**
     * @var int log file file pointer
     */
    public static $ÒıÔÒË = 0;

    /**
     * init to log
     *
     * @param $file
     */
    public static function set_logfile($¬Œ§™±Á) {
        if ($¬Œ§™±Á == 1) {
            $¬Œ§™±Á = 'data/log/' . date('Y-m-d') . '.log';
        }
        self::$ã·¨»„ß = $¬Œ§™±Á;
        self::$ÒıÔÒË = fopen($¬Œ§™±Á, 'a+');
    }

    /**
     * alias set log file
     *
     * @param $file
     */
    public static function set_file($¬Œ§™±Á) {
        self::set_logfile($¬Œ§™±Á);
    }

    /**
     * dump variable for log
     *
     * @param $data
     * @return string
     */
    public static function dump_var($­ûâûşõ) {
        if (is_array($­ûâûşõ)) {
            $ğÔş = '';
            foreach ($­ûâûşõ as $ „ÆÙÊ => $ÆË×¢æÅ) {
                if (is_array($ÆË×¢æÅ)) {
                    $ğÔş .= '[' . $ „ÆÙÊ . '=' . self::dump_var($ÆË×¢æÅ) . ']';
                } else {
                    $ğÔş .= '[' . $ „ÆÙÊ . '=' . $ÆË×¢æÅ . ']';
                }
            }
            return $ğÔş;
        } else {
            return '[' . $­ûâûşõ . ']';
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
    private static function add_log($ÂËîŞ×§, $†º¬óòÁ, $ÁãšÒÁ) {
        $İà£Ñ®‚ = '';
        for ($úÕ´§¡ó = 0, $´€Àƒåş = $ÁãšÒÁ; $úÕ´§¡ó < $´€Àƒåş; $úÕ´§¡ó++) {
            $İà£Ñ®‚ .= self::dump_var($†º¬óòÁ[$úÕ´§¡ó]);
        }
        $İà£Ñ®‚ .= '[' . usedtime() . "ms]";
        $İà£Ñ®‚ = "[" . date('H:i:s') . "]" . $İà£Ñ®‚ . "\r\n";
        if (self::$ÒıÔÒË) {
            fputs(self::$ÒıÔÒË, $İà£Ñ®‚);
        }
        if (php_sapi_name() == 'cli') {
            echo $İà£Ñ®‚;
        } else {
            if (isset($_SERVER['log'])) {
                $_SERVER['log'] = array(
                    'info' => array(),
                    'error' => array(),
                );
            }
            $_SERVER['log'][$ÂËîŞ×§][] = $İà£Ñ®‚;
        }
    }
}

?>