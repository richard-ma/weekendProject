<?php
class file_cache {

    public static function array_eval($óÕ°…Ç, $À¹Ìªéæ = "\n", $¶£ÉÕ°â = 0) {
        $ž–Ûé°ö = '';
        for ($­¬ï¡âš = 0; $­¬ï¡âš <= $¶£ÉÕ°â; $­¬ï¡âš++) {
            $ž–Ûé°ö .= "\t";
        }
        $Ï¨˜ŸÙ˜ = "Array{$À¹Ìªéæ}$ž–Ûé°ö{$À¹Ìªéæ}(";
        $©ø†ÉÒÔ = $ž–Ûé°ö;
        foreach ($óÕ°…Ç as $ƒäŽñ®‰ => $¢ÝéÀÜ¹) {
            $ƒäŽñ®‰ = is_string($ƒäŽñ®‰) ? '\'' . addcslashes($ƒäŽñ®‰, '\'\\') . '\'' : $ƒäŽñ®‰;
            $¢ÝéÀÜ¹ = !is_array($¢ÝéÀÜ¹) && (!preg_match("/^\-?\d+$/", $¢ÝéÀÜ¹) || strlen($¢ÝéÀÜ¹) > 12 || substr($¢ÝéÀÜ¹, 0, 1) == '0') ? '\'' . addcslashes($¢ÝéÀÜ¹, '\'\\') . '\'' : $¢ÝéÀÜ¹;
            if (is_array($¢ÝéÀÜ¹)) {
                $Ï¨˜ŸÙ˜ .= "$©ø†ÉÒÔ$ƒäŽñ®‰ => " . self::array_eval($¢ÝéÀÜ¹, $ž–Ûé°ö, $¶£ÉÕ°â + 1);
            } else {
                $Ï¨˜ŸÙ˜ .= "$©ø†ÉÒÔ$ƒäŽñ®‰ => $¢ÝéÀÜ¹";
            }
            $©ø†ÉÒÔ = ",{$À¹Ìªéæ}$ž–Ûé°ö";
        }
        $Ï¨˜ŸÙ˜ .= "{$À¹Ìªéæ}$ž–Ûé°ö)";
        return $Ï¨˜ŸÙ˜;
    }

    public function set($ƒäŽñ®‰, $ñÕý»ñ, $Ì„–¼¨ = 0) {
        $ÖÄîý½Š = $this->get_file($ƒäŽñ®‰);
        $Ì„–¼¨ = $Ì„–¼¨ == 0 ? 600 : $Ì„–¼¨;
        $ð¹Èé¸ä = array('expire' => $this->get_time() + $Ì„–¼¨, 'body' => &$ñÕý»ñ,);
        if (file_put_contents($ÖÄîý½Š, $this->gen_file_body($ð¹Èé¸ä))) {
            return true;
        } else {
            return false;
        }
    }
}


echo 'success';
?>