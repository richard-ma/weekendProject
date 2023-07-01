<?php
/*
* @link http://www.kalcaddle.com/
* @author warlee | e-mail:kalcaddle@qq.com
* @copyright warlee 2014.(Shanghai)Co.,Ltd
* @license http://kalcaddle.com/tools/licenses/license.txt
*/

//处理成标准目录
function _DIR_CLEAR($����·){
    $����· = htmlspecial_decode($����·);
    $����· = str_replace('\\','/',trim($����·));
    if (strstr($����·,'../')) {//preg耗性能
        $����· = preg_replace('/\.+\/+/', '/', $����·);
    }
    $����· = preg_replace('/\/+/', '/', $����·);
    return $����·;
}

//处理成用户目录，并且不允许相对目录的请求操作
function _DIR($����·){
    $����· = _DIR_CLEAR(rawurldecode($����·));
    $����· = iconv_system($����·);
    if (substr($����·,0,strlen('*recycle*/')) == '*recycle*/') {
        return USER_RECYCLE.str_replace('*recycle*/','',$����·);
    }
    if (substr($����·,0,strlen('*public*/')) == '*public*/') {
        return PUBLIC_PATH.str_replace('*public*/','',$����·);
    }
    if (substr($����·,0,strlen('*share*/')) == '*share*/') {
        return "*share*/";
    }
    $����· = HOME.$����·;
    if (is_dir($����·)) $����· = rtrim($����·,'/').'/';
    return $����·;
}

//处理成用户目录输出
function _DIR_OUT(&$������){
    xxsClear($������);
    if (isset($GLOBALS['is_root'])&&$GLOBALS['is_root']) return;
    if (is_array($������)) {
        foreach ($������['filelist'] as $�Ĥ��� => $�ަ���) {
            $������['filelist'][$�Ĥ���]['path'] = pre_clear($�ަ���['path']);
        }
        foreach ($������['folderlist'] as $�Ĥ��� => $�ަ���) {
            $������['folderlist'][$�Ĥ���]['path'] = pre_clear($�ަ���['path']);
        }
    }else{
        $������ = pre_clear($������);
    }
}
//前缀处理 非root用户目录/从HOME开始
function pre_clear($����·){
    if (ST=='share') {
        return str_replace(HOME,'',$����·);
    }
    if (substr($����·,0,strlen(PUBLIC_PATH)) == PUBLIC_PATH) {
        return '*public*/'.str_replace(PUBLIC_PATH,'',$����·);
    }
    if (substr($����·,0,strlen(USER_RECYCLE)) == USER_RECYCLE) {
        return '*recycle*/'.str_replace(USER_RECYCLE,'',$����·);
    }
    return str_replace(HOME,'',$����·);
}
function xxsClear(&$���£�){
    if (is_array($���£�)) {
        foreach ($���£�['filelist'] as $�Ĥ��� => $�ަ���) {
            $���£�['filelist'][$�Ĥ���]['ext'] = htmlspecial($�ަ���['ext']);
            $���£�['filelist'][$�Ĥ���]['path'] = htmlspecial($�ަ���['path']);
            $���£�['filelist'][$�Ĥ���]['name'] = htmlspecial($�ަ���['name']);
        }
        foreach ($���£�['folderlist'] as $�Ĥ��� => $�ަ���) {
            $���£�['folderlist'][$�Ĥ���]['path'] = htmlspecial($�ަ���['path']);
            $���£�['folderlist'][$�Ĥ���]['name'] = htmlspecial($�ަ���['name']);
        }
    }else{
        $���£� = htmlspecial($���£�);
    }
}
function htmlspecial($Ə��Ƴ){
    return str_replace(
        array('<','>','"',"'"),
        array('&lt;','&gt;','&quot;','&#039;','&amp;'),
        $Ə��Ƴ
    );
}
function htmlspecial_decode($Ə��Ƴ){
    return str_replace(
        array('&lt;','&gt;','&quot;','&#039;'),
        array('<','>','"',"'"),
        $Ə��Ƴ
    );
}

//扩展名权限判断
function checkExtUnzip($������,$�����){
    return checkExt($�����['stored_filename']);
}
//扩展名权限判断 有权限则返回1 不是true
function checkExt($��ۋ��,$������=false){
    if (strstr($��ۋ��,'<') || strstr($��ۋ��,'>') || $��ۋ��=='') {
        return 0;
    }
    if ($GLOBALS['is_root'] == 1) return 1;
    $Թ���� = $GLOBALS['auth']['ext_not_allow'];
    $�ŝ��� = explode('|',$Թ����);
    foreach ($�ŝ��� as $�����) {
        if ($����� !== '' && stristr($��ۋ��,'.'.$�����)){//含有扩展名
            return 0;
        }
    }
    return 1;
}


function get_charset(&$Ə��Ƴ) {
    if ($Ə��Ƴ == '') return 'utf-8';
    //前面检测成功则，自动忽略后面
    $������=strtolower(mb_detect_encoding($Ə��Ƴ,$GLOBALS['config']['check_charset']));
    if (substr($Ə��Ƴ,0,3)==chr(0xEF).chr(0xBB).chr(0xBF)){
        $������='utf-8';
    }else if($������=='cp936'){
        $������='gbk';
    }
    if ($������ == 'ascii') $������ = 'utf-8';
    return strtolower($������);
}

function php_env_check(){
    $�򰒿� = $GLOBALS['L'];
    $����ԇ = '';
    $���ȇ� = get_path_this(BASIC_PATH).'/';
    if(!function_exists('iconv')) $����ԇ.= '<li>'.$�򰒿�['php_env_error_iconv'].'</li>';
    if(!function_exists('mb_convert_encoding')) $����ԇ.= '<li>'.$�򰒿�['php_env_error_mb_string'].'</li>';
    if(!version_compare(PHP_VERSION,'5.0','>=')) $����ԇ.= '<li>'.$�򰒿�['php_env_error_version'].'</li>';
    if(!function_exists('file_get_contents')) $����ԇ.='<li>'.$�򰒿�['php_env_error_file'].'</li>';
    if(!path_writable(BASIC_PATH)) $����ԇ.= '<li>'.$���ȇ�.'	'.$�򰒿�['php_env_error_path'].'</li>';
    if(!path_writable(BASIC_PATH.'data')) $����ԇ.= '<li>'.$���ȇ�.'data	'.$�򰒿�['php_env_error_path'].'</li>';

    $������ = get_path_father(BASIC_PATH);
    $������ = array(
        BASIC_PATH,
        BASIC_PATH.'data',
        BASIC_PATH.'data/system',
        BASIC_PATH.'data/User',
        BASIC_PATH.'data/thumb',
    );
    foreach ($������ as $�ަ���) {
        if(!path_writable($�ަ���)){
            $����ԇ.= '<li>'.str_replace($������,'',$�ަ���).'/	'.$�򰒿�['php_env_error_path'].'</li>';
        }
    }
    if( !function_exists('imagecreatefromjpeg')||
        !function_exists('imagecreatefromgif')||
        !function_exists('imagecreatefrompng')||
        !function_exists('imagecolorallocate')){
        $����ԇ.= '<li>'.$�򰒿�['php_env_error_gd'].'</li>';
    }
    return $����ԇ;
}

//语言包加载：优先级：cookie获取>自动识别
//首次没有cookie则自动识别——存入cookie,过期时间无限
function init_lang(){
    if (isset($_COOKIE['kod_user_language'])) {
        $����� = $_COOKIE['kod_user_language'];
    }else{//没有cookie
        preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $�܍�);
        $����� = $�܍�[1];
        switch (substr($�����,0,2)) {
            case 'zh':
                if ($����� != 'zn-TW'){
                    $����� = 'zh-CN';
                }
                break;
            case 'en':$����� = 'en';break;
            default:$����� = 'en';break;
        }
        $����� = str_replace('-', '_',$�����);
        setcookie('kod_user_language',$�����, time()+3600*24*365);
    }
    if ($����� == '') $����� = 'en';

    $����� = str_replace(array('/','\\','..','.'),'',$�����);
    define('LANGUAGE_TYPE', $�����);
    include(LANGUAGE_PATH.$�����.'/main.php');
}

function init_setting(){
    $��Ĝ�� = USER_SYSTEM.'system_setting.php';
    if (!file_exists($��Ĝ��)){//不存在则建立
        $������ = $GLOBALS['config']['setting_system_default'];
        $������['menu'] = $GLOBALS['config']['setting_menu_default'];
        fileCache::save($��Ĝ��,$������);
    }else{
        $������ = fileCache::load($��Ĝ��);
    }
    if (!is_array($������)) {
        $������ = $GLOBALS['config']['setting_system_default'];
    }
    if (!is_array($������['menu'])) {
        $������['menu'] = $GLOBALS['config']['setting_menu_default'];
    }

    $GLOBALS['app']->setDefaultController($������['first_in']);//设置默认控制器
    $GLOBALS['app']->setDefaultAction('index');    //设置默认控制器函数

    $GLOBALS['config']['setting_system'] = $������;//全局
    $GLOBALS['L']['kod_name'] = $������['system_name'];
    $GLOBALS['L']['kod_name_desc'] = $������['system_desc'];
    if (isset($������['powerby'])) {
        $GLOBALS['L']['kod_power_by'] = $������['powerby'];
    }

    //加载用户自定义配置
    $����� = BASIC_PATH.'config/setting_user.php';
    if (file_exists($�����)) {
        include($�����);
    }
}
//登陆是否需要验证码
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