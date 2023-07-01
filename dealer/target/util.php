<?php
/*
* @link http://www.kalcaddle.com/
* @author warlee | e-mail:kalcaddle@qq.com
* @copyright warlee 2014.(Shanghai)Co.,Ltd
* @license http://kalcaddle.com/tools/licenses/license.txt
*/

//å¤„ç†æˆæ ‡å‡†ç›®å½•
function _DIR_CLEAR($©·ÄòÂ·){
    $©·ÄòÂ· = htmlspecial_decode($©·ÄòÂ·);
    $©·ÄòÂ· = str_replace('\\','/',trim($©·ÄòÂ·));
    if (strstr($©·ÄòÂ·,'../')) {//pregè€—æ€§èƒ½
        $©·ÄòÂ· = preg_replace('/\.+\/+/', '/', $©·ÄòÂ·);
    }
    $©·ÄòÂ· = preg_replace('/\/+/', '/', $©·ÄòÂ·);
    return $©·ÄòÂ·;
}

//å¤„ç†æˆç”¨æˆ·ç›®å½•ï¼Œå¹¶ä¸”ä¸å…è®¸ç›¸å¯¹ç›®å½•çš„è¯·æ±‚æ“ä½œ
function _DIR($©·ÄòÂ·){
    $©·ÄòÂ· = _DIR_CLEAR(rawurldecode($©·ÄòÂ·));
    $©·ÄòÂ· = iconv_system($©·ÄòÂ·);
    if (substr($©·ÄòÂ·,0,strlen('*recycle*/')) == '*recycle*/') {
        return USER_RECYCLE.str_replace('*recycle*/','',$©·ÄòÂ·);
    }
    if (substr($©·ÄòÂ·,0,strlen('*public*/')) == '*public*/') {
        return PUBLIC_PATH.str_replace('*public*/','',$©·ÄòÂ·);
    }
    if (substr($©·ÄòÂ·,0,strlen('*share*/')) == '*share*/') {
        return "*share*/";
    }
    $©·ÄòÂ· = HOME.$©·ÄòÂ·;
    if (is_dir($©·ÄòÂ·)) $©·ÄòÂ· = rtrim($©·ÄòÂ·,'/').'/';
    return $©·ÄòÂ·;
}

//å¤„ç†æˆç”¨æˆ·ç›®å½•è¾“å‡º
function _DIR_OUT(&$ıêÀ·”£){
    xxsClear($ıêÀ·”£);
    if (isset($GLOBALS['is_root'])&&$GLOBALS['is_root']) return;
    if (is_array($ıêÀ·”£)) {
        foreach ($ıêÀ·”£['filelist'] as $½Ä¤õ€Ô => $óŞ¦“ÕÀ) {
            $ıêÀ·”£['filelist'][$½Ä¤õ€Ô]['path'] = pre_clear($óŞ¦“ÕÀ['path']);
        }
        foreach ($ıêÀ·”£['folderlist'] as $½Ä¤õ€Ô => $óŞ¦“ÕÀ) {
            $ıêÀ·”£['folderlist'][$½Ä¤õ€Ô]['path'] = pre_clear($óŞ¦“ÕÀ['path']);
        }
    }else{
        $ıêÀ·”£ = pre_clear($ıêÀ·”£);
    }
}
//å‰ç¼€å¤„ç† érootç”¨æˆ·ç›®å½•/ä»HOMEå¼€å§‹
function pre_clear($©·ÄòÂ·){
    if (ST=='share') {
        return str_replace(HOME,'',$©·ÄòÂ·);
    }
    if (substr($©·ÄòÂ·,0,strlen(PUBLIC_PATH)) == PUBLIC_PATH) {
        return '*public*/'.str_replace(PUBLIC_PATH,'',$©·ÄòÂ·);
    }
    if (substr($©·ÄòÂ·,0,strlen(USER_RECYCLE)) == USER_RECYCLE) {
        return '*recycle*/'.str_replace(USER_RECYCLE,'',$©·ÄòÂ·);
    }
    return str_replace(HOME,'',$©·ÄòÂ·);
}
function xxsClear(&$æéóÂ£—){
    if (is_array($æéóÂ£—)) {
        foreach ($æéóÂ£—['filelist'] as $½Ä¤õ€Ô => $óŞ¦“ÕÀ) {
            $æéóÂ£—['filelist'][$½Ä¤õ€Ô]['ext'] = htmlspecial($óŞ¦“ÕÀ['ext']);
            $æéóÂ£—['filelist'][$½Ä¤õ€Ô]['path'] = htmlspecial($óŞ¦“ÕÀ['path']);
            $æéóÂ£—['filelist'][$½Ä¤õ€Ô]['name'] = htmlspecial($óŞ¦“ÕÀ['name']);
        }
        foreach ($æéóÂ£—['folderlist'] as $½Ä¤õ€Ô => $óŞ¦“ÕÀ) {
            $æéóÂ£—['folderlist'][$½Ä¤õ€Ô]['path'] = htmlspecial($óŞ¦“ÕÀ['path']);
            $æéóÂ£—['folderlist'][$½Ä¤õ€Ô]['name'] = htmlspecial($óŞ¦“ÕÀ['name']);
        }
    }else{
        $æéóÂ£— = htmlspecial($æéóÂ£—);
    }
}
function htmlspecial($Æ’ÍÆ³){
    return str_replace(
        array('<','>','"',"'"),
        array('&lt;','&gt;','&quot;','&#039;','&amp;'),
        $Æ’ÍÆ³
    );
}
function htmlspecial_decode($Æ’ÍÆ³){
    return str_replace(
        array('&lt;','&gt;','&quot;','&#039;'),
        array('<','>','"',"'"),
        $Æ’ÍÆ³
    );
}

//æ‰©å±•åæƒé™åˆ¤æ–­
function checkExtUnzip($£…ˆ……û,$£’ˆæâ¥){
    return checkExt($£’ˆæâ¥['stored_filename']);
}
//æ‰©å±•åæƒé™åˆ¤æ–­ æœ‰æƒé™åˆ™è¿”å›1 ä¸æ˜¯true
function checkExt($ÎıÛ‹ëã,$»ÌõÊÖß=false){
    if (strstr($ÎıÛ‹ëã,'<') || strstr($ÎıÛ‹ëã,'>') || $ÎıÛ‹ëã=='') {
        return 0;
    }
    if ($GLOBALS['is_root'] == 1) return 1;
    $Ô¹›³ê = $GLOBALS['auth']['ext_not_allow'];
    $ÚÅ–òõ = explode('|',$Ô¹›³ê);
    foreach ($ÚÅ–òõ as $ö¯šĞåœ) {
        if ($ö¯šĞåœ !== '' && stristr($ÎıÛ‹ëã,'.'.$ö¯šĞåœ)){//å«æœ‰æ‰©å±•å
            return 0;
        }
    }
    return 1;
}


function get_charset(&$Æ’ÍÆ³) {
    if ($Æ’ÍÆ³ == '') return 'utf-8';
    //å‰é¢æ£€æµ‹æˆåŠŸåˆ™ï¼Œè‡ªåŠ¨å¿½ç•¥åé¢
    $ôŞÈàÜÙ=strtolower(mb_detect_encoding($Æ’ÍÆ³,$GLOBALS['config']['check_charset']));
    if (substr($Æ’ÍÆ³,0,3)==chr(0xEF).chr(0xBB).chr(0xBF)){
        $ôŞÈàÜÙ='utf-8';
    }else if($ôŞÈàÜÙ=='cp936'){
        $ôŞÈàÜÙ='gbk';
    }
    if ($ôŞÈàÜÙ == 'ascii') $ôŞÈàÜÙ = 'utf-8';
    return strtolower($ôŞÈàÜÙ);
}

function php_env_check(){
    $ˆò°’¿ô = $GLOBALS['L'];
    $ÖĞéÇÔ‡ = '';
    $üù¡È‡ô = get_path_this(BASIC_PATH).'/';
    if(!function_exists('iconv')) $ÖĞéÇÔ‡.= '<li>'.$ˆò°’¿ô['php_env_error_iconv'].'</li>';
    if(!function_exists('mb_convert_encoding')) $ÖĞéÇÔ‡.= '<li>'.$ˆò°’¿ô['php_env_error_mb_string'].'</li>';
    if(!version_compare(PHP_VERSION,'5.0','>=')) $ÖĞéÇÔ‡.= '<li>'.$ˆò°’¿ô['php_env_error_version'].'</li>';
    if(!function_exists('file_get_contents')) $ÖĞéÇÔ‡.='<li>'.$ˆò°’¿ô['php_env_error_file'].'</li>';
    if(!path_writable(BASIC_PATH)) $ÖĞéÇÔ‡.= '<li>'.$üù¡È‡ô.'	'.$ˆò°’¿ô['php_env_error_path'].'</li>';
    if(!path_writable(BASIC_PATH.'data')) $ÖĞéÇÔ‡.= '<li>'.$üù¡È‡ô.'data	'.$ˆò°’¿ô['php_env_error_path'].'</li>';

    $şşø ¼ = get_path_father(BASIC_PATH);
    $¥´Ÿ’æ× = array(
        BASIC_PATH,
        BASIC_PATH.'data',
        BASIC_PATH.'data/system',
        BASIC_PATH.'data/User',
        BASIC_PATH.'data/thumb',
    );
    foreach ($¥´Ÿ’æ× as $óŞ¦“ÕÀ) {
        if(!path_writable($óŞ¦“ÕÀ)){
            $ÖĞéÇÔ‡.= '<li>'.str_replace($şşø ¼,'',$óŞ¦“ÕÀ).'/	'.$ˆò°’¿ô['php_env_error_path'].'</li>';
        }
    }
    if( !function_exists('imagecreatefromjpeg')||
        !function_exists('imagecreatefromgif')||
        !function_exists('imagecreatefrompng')||
        !function_exists('imagecolorallocate')){
        $ÖĞéÇÔ‡.= '<li>'.$ˆò°’¿ô['php_env_error_gd'].'</li>';
    }
    return $ÖĞéÇÔ‡;
}

//è¯­è¨€åŒ…åŠ è½½ï¼šä¼˜å…ˆçº§ï¼šcookieè·å–>è‡ªåŠ¨è¯†åˆ«
//é¦–æ¬¡æ²¡æœ‰cookieåˆ™è‡ªåŠ¨è¯†åˆ«â€”â€”å­˜å…¥cookie,è¿‡æœŸæ—¶é—´æ— é™
function init_lang(){
    if (isset($_COOKIE['kod_user_language'])) {
        $²è¤ø¶± = $_COOKIE['kod_user_language'];
    }else{//æ²¡æœ‰cookie
        preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $İÜó½’);
        $²è¤ø¶± = $İÜó½’[1];
        switch (substr($²è¤ø¶±,0,2)) {
            case 'zh':
                if ($²è¤ø¶± != 'zn-TW'){
                    $²è¤ø¶± = 'zh-CN';
                }
                break;
            case 'en':$²è¤ø¶± = 'en';break;
            default:$²è¤ø¶± = 'en';break;
        }
        $²è¤ø¶± = str_replace('-', '_',$²è¤ø¶±);
        setcookie('kod_user_language',$²è¤ø¶±, time()+3600*24*365);
    }
    if ($²è¤ø¶± == '') $²è¤ø¶± = 'en';

    $²è¤ø¶± = str_replace(array('/','\\','..','.'),'',$²è¤ø¶±);
    define('LANGUAGE_TYPE', $²è¤ø¶±);
    include(LANGUAGE_PATH.$²è¤ø¶±.'/main.php');
}

function init_setting(){
    $»ÔÄœÚÌ = USER_SYSTEM.'system_setting.php';
    if (!file_exists($»ÔÄœÚÌ)){//ä¸å­˜åœ¨åˆ™å»ºç«‹
        $±©İøÍ = $GLOBALS['config']['setting_system_default'];
        $±©İøÍ['menu'] = $GLOBALS['config']['setting_menu_default'];
        fileCache::save($»ÔÄœÚÌ,$±©İøÍ);
    }else{
        $±©İøÍ = fileCache::load($»ÔÄœÚÌ);
    }
    if (!is_array($±©İøÍ)) {
        $±©İøÍ = $GLOBALS['config']['setting_system_default'];
    }
    if (!is_array($±©İøÍ['menu'])) {
        $±©İøÍ['menu'] = $GLOBALS['config']['setting_menu_default'];
    }

    $GLOBALS['app']->setDefaultController($±©İøÍ['first_in']);//è®¾ç½®é»˜è®¤æ§åˆ¶å™¨
    $GLOBALS['app']->setDefaultAction('index');    //è®¾ç½®é»˜è®¤æ§åˆ¶å™¨å‡½æ•°

    $GLOBALS['config']['setting_system'] = $±©İøÍ;//å…¨å±€
    $GLOBALS['L']['kod_name'] = $±©İøÍ['system_name'];
    $GLOBALS['L']['kod_name_desc'] = $±©İøÍ['system_desc'];
    if (isset($±©İøÍ['powerby'])) {
        $GLOBALS['L']['kod_power_by'] = $±©İøÍ['powerby'];
    }

    //åŠ è½½ç”¨æˆ·è‡ªå®šä¹‰é…ç½®
    $õÖÏëì¸ = BASIC_PATH.'config/setting_user.php';
    if (file_exists($õÖÏëì¸)) {
        include($õÖÏëì¸);
    }
}
//ç™»é™†æ˜¯å¦éœ€è¦éªŒè¯ç 
function need_check_code(){
    if(!function_exists('imagecolorallocate')){
        return false;
    }else{
        return true;
    }
}
function is_wap(){
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i',
        strtolower($_SERVER['HTTP_USER_AGENT']))){
        return true;
    }
    if((isset($_SERVER['HTTP_ACCEPT'])) &&
        (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)){
        return true;
    }
    return false;
}
function user_logout(){
    setcookie('PHPSESSID', '', time()-3600,'/');
    setcookie('kod_name', '', time()-3600);
    setcookie('kod_token', '', time()-3600);
    setcookie('kod_user_language', '', time()-3600);
    session_destroy();
    header('location:./index.php?user/login');
    exit;
}?>