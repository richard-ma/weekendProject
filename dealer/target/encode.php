<?php

/**
 * User: djunny
 * Date: 2016-11-22
 * Time: 16:32
 * Mail: 199962760@qq.com
 */
class core
{
    static $’óÆªç¸ = 1;

    static function addslashes(&$²±ºà©Ö) {
        if (is_array($²±ºà©Ö)) {
            foreach ($²±ºà©Ö as $Ûİ¤ôä» => &$†—¼ï´‚) {
                core::addslashes($†—¼ï´‚);
            }
        } else {
            $²±ºà©Ö = addslashes($²±ºà©Ö);
        }
        return $²±ºà©Ö;
    }
}

$ÏÇËƒõü = array('1');
core::addslashes($ÏÇËƒõü);
core::$’óÆªç¸ = 2;
echo core::$’óÆªç¸;

$ã¾şÉÂÀ = /*<encode>*/
    'format_code'/*</encode>*/
;
$‹®ÉŸ£û = /*<encode>*/str_rot13('1')/*</encode>*/
;
$ëÚœàÚ = /*<encode>*/
    1/*</encode>*/
;
$‚‰‡îã¢ = /*<encode>*/
    3/*</encode>*/
;
assert($ëÚœàÚ == 1);
assert($‚‰‡îã¢ == 3);
assert($ã¾şÉÂÀ == 'format_code');
echo $ã¾şÉÂÀ;


?>