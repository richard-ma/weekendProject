<?php
class file_cache {

    public static function array_eval($�հ��, $��̪�� = "\n", $���հ� = 0) {
        $����� = '';
        for ($���� = 0; $���� <= $���հ�; $����++) {
            $����� .= "\t";
        }
        $Ϩ��٘ = "Array{$��̪��}$�����{$��̪��}(";
        $������ = $�����;
        foreach ($�հ�� as $��� => $����ܹ) {
            $��� = is_string($���) ? '\'' . addcslashes($���, '\'\\') . '\'' : $���;
            $����ܹ = !is_array($����ܹ) && (!preg_match("/^\-?\d+$/", $����ܹ) || strlen($����ܹ) > 12 || substr($����ܹ, 0, 1) == '0') ? '\'' . addcslashes($����ܹ, '\'\\') . '\'' : $����ܹ;
            if (is_array($����ܹ)) {
                $Ϩ��٘ .= "$������$��� => " . self::array_eval($����ܹ, $�����, $���հ� + 1);
            } else {
                $Ϩ��٘ .= "$������$��� => $����ܹ";
            }
            $������ = ",{$��̪��}$�����";
        }
        $Ϩ��٘ .= "{$��̪��}$�����)";
        return $Ϩ��٘;
    }

    public function set($���, $�����, $̄���� = 0) {
        $������ = $this->get_file($���);
        $̄���� = $̄���� == 0 ? 600 : $̄����;
        $���� = array('expire' => $this->get_time() + $̄����, 'body' => &$�����,);
        if (file_put_contents($������, $this->gen_file_body($����))) {
            return true;
        } else {
            return false;
        }
    }
}


echo 'success';
?>