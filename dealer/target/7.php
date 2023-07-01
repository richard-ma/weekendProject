<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * ThinkPHP æ•°æ®åº“ä¸­é—´å±‚å®ç°ç±»
 */
class Db {
    // æ•°æ®åº“ç±»å‹
    protected $Ë¸ãˆ©Á     = null;
    // æ˜¯å¦è‡ªåŠ¨é‡Šæ”¾æŸ¥è¯¢ç»“æœ
    protected $©™ıßœÏ   = false;
    // å½“å‰æ“ä½œæ‰€å±çš„æ¨¡å‹å
    protected $Øı¬õ¦«      = '_think_';
    // æ˜¯å¦ä½¿ç”¨æ°¸ä¹…è¿æ¥
    protected $”ø½Éè‚   = false;
    // å½“å‰SQLæŒ‡ä»¤
    protected $úó½Ùê‘   = '';
    protected $¾ïèÅ¯›   = array();
    // æœ€åæ’å…¥ID
    protected $äÕ„§ì²  = null;
    // è¿”å›æˆ–è€…å½±å“è®°å½•æ•°
    protected $Ê¿±¶Ğ    = 0;
    // è¿”å›å­—æ®µæ•°
    protected $Üæİ–áÃ    = 0;
    // äº‹åŠ¡æŒ‡ä»¤æ•°
    protected $ç·¥àÉø = 0;
    // é”™è¯¯ä¿¡æ¯
    protected $µ çˆª´      = '';
    // æ•°æ®åº“è¿æ¥ID æ”¯æŒå¤šä¸ªè¿æ¥
    protected $€§È†     = array();
    // å½“å‰è¿æ¥ID
    protected $õõğ¾ÕÍ    = null;
    // å½“å‰æŸ¥è¯¢ID
    protected $‘ÉÒƒ…û    = null;
    // æ•°æ®åº“è¿æ¥å‚æ•°é…ç½®
    protected $¬ïÛçˆ¼     = '';
    // æ•°æ®åº“è¡¨è¾¾å¼
    protected $‰¤‘åŸ = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN','not in'=>'NOT IN','between'=>'BETWEEN','notbetween'=>'NOT BETWEEN','not between'=>'NOT BETWEEN');
    // æŸ¥è¯¢è¡¨è¾¾å¼
    protected $û€ä³ÜÕ  = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';
    // å‚æ•°ç»‘å®š
    protected $£æŠ¦¤Ô       = array();

    /**
     * å–å¾—æ•°æ®åº“ç±»å®ä¾‹
     * @static
     * @access public
     * @return mixed è¿”å›æ•°æ®åº“é©±åŠ¨ç±»
     */
    public static function getInstance($İÖ÷Ÿ¡Ï='') {
		static $ÓÈçÃ„õ	=	array();
		$¥×“·¦ö	=	to_guid_string($İÖ÷Ÿ¡Ï);
		if(!isset($ÓÈçÃ„õ[$¥×“·¦ö])){
			$€ÏóúÚŸ	=	new Db();
			$ÓÈçÃ„õ[$¥×“·¦ö]	=	$€ÏóúÚŸ->factory($İÖ÷Ÿ¡Ï);
		}
		return $ÓÈçÃ„õ[$¥×“·¦ö];
    }

    /**
     * åŠ è½½æ•°æ®åº“ æ”¯æŒé…ç½®æ–‡ä»¶æˆ–è€… DSN
     * @access public
     * @param mixed $db_config æ•°æ®åº“é…ç½®ä¿¡æ¯
     * @return string
     */
    public function factory($İÖ÷Ÿ¡Ï='') {
        // è¯»å–æ•°æ®åº“é…ç½®
        $İÖ÷Ÿ¡Ï = $this->parseConfig($İÖ÷Ÿ¡Ï);
        if(empty($İÖ÷Ÿ¡Ï['dbms']))
            E(L('_NO_DB_CONFIG_'));
        // æ•°æ®åº“ç±»å‹
        if(strpos($İÖ÷Ÿ¡Ï['dbms'],'\\')){
            $«÷—‡€¹  =   $İÖ÷Ÿ¡Ï['dbms'];
        }else{
            $Ë¸ãˆ©Á =   ucwords(strtolower($İÖ÷Ÿ¡Ï['dbms']));
            $«÷—‡€¹  =   'Think\\Db\\Driver\\'. $Ë¸ãˆ©Á;            
        }
        // æ£€æŸ¥é©±åŠ¨ç±»
        if(class_exists($«÷—‡€¹)) {
            $Ù¯’Ü = new $«÷—‡€¹($İÖ÷Ÿ¡Ï);
        }else {
            // ç±»æ²¡æœ‰å®šä¹‰
            E(L('_NO_DB_DRIVER_').': ' . $«÷—‡€¹);
        }
        return $Ù¯’Ü;
    }

    /**
     * æ ¹æ®DSNè·å–æ•°æ®åº“ç±»å‹ è¿”å›å¤§å†™
     * @access protected
     * @param string $dsn  dsnå­—ç¬¦ä¸²
     * @return string
     */
    protected function _getDsnType($ç‡Üî¦ ) {
        $Ğ±Ë÷è  =  explode(':',$ç‡Üî¦ );
        $Ë¸ãˆ©Á = strtoupper(trim($Ğ±Ë÷è[0]));
        return $Ë¸ãˆ©Á;
    }

    /**
     * åˆ†ææ•°æ®åº“é…ç½®ä¿¡æ¯ï¼Œæ”¯æŒæ•°ç»„å’ŒDSN
     * @access private
     * @param mixed $db_config æ•°æ®åº“é…ç½®ä¿¡æ¯
     * @return string
     */
    private function parseConfig($İÖ÷Ÿ¡Ï='') {
        if ( !empty($İÖ÷Ÿ¡Ï) && is_string($İÖ÷Ÿ¡Ï)) {
            // å¦‚æœDSNå­—ç¬¦ä¸²åˆ™è¿›è¡Œè§£æ
            $İÖ÷Ÿ¡Ï = $this->parseDSN($İÖ÷Ÿ¡Ï);
        }elseif(is_array($İÖ÷Ÿ¡Ï)) { // æ•°ç»„é…ç½®
             $İÖ÷Ÿ¡Ï =   array_change_key_case($İÖ÷Ÿ¡Ï);
             $İÖ÷Ÿ¡Ï = array(
                  'dbms'      =>  $İÖ÷Ÿ¡Ï['db_type'],
                  'username'  =>  $İÖ÷Ÿ¡Ï['db_user'],
                  'password'  =>  $İÖ÷Ÿ¡Ï['db_pwd'],
                  'hostname'  =>  $İÖ÷Ÿ¡Ï['db_host'],
                  'hostport'  =>  $İÖ÷Ÿ¡Ï['db_port'],
                  'database'  =>  $İÖ÷Ÿ¡Ï['db_name'],
                  'dsn'       =>  isset($İÖ÷Ÿ¡Ï['db_dsn'])?$İÖ÷Ÿ¡Ï['db_dsn']:'',
                  'params'    =>  isset($İÖ÷Ÿ¡Ï['db_params'])?$İÖ÷Ÿ¡Ï['db_params']:array(),
                  'charset'   =>  isset($İÖ÷Ÿ¡Ï['db_charset'])?$İÖ÷Ÿ¡Ï['db_charset']:'utf8',
             );
        }elseif(empty($İÖ÷Ÿ¡Ï)) {
            // å¦‚æœé…ç½®ä¸ºç©ºï¼Œè¯»å–é…ç½®æ–‡ä»¶è®¾ç½®
            if( C('DB_DSN') && 'pdo' != strtolower(C('DB_TYPE')) ) { // å¦‚æœè®¾ç½®äº†DB_DSN åˆ™ä¼˜å…ˆ
                $İÖ÷Ÿ¡Ï =  $this->parseDSN(C('DB_DSN'));
            }else{
                $İÖ÷Ÿ¡Ï = array (
                    'dbms'      =>  C('DB_TYPE'),
                    'username'  =>  C('DB_USER'),
                    'password'  =>  C('DB_PWD'),
                    'hostname'  =>  C('DB_HOST'),
                    'hostport'  =>  C('DB_PORT'),
                    'database'  =>  C('DB_NAME'),
                    'dsn'       =>  C('DB_DSN'),
                    'params'    =>  C('DB_PARAMS'),
                    'charset'   =>  C('DB_CHARSET'),
                );
            }
        }
        return $İÖ÷Ÿ¡Ï;
    }

    /**
     * åˆå§‹åŒ–æ•°æ®åº“è¿æ¥
     * @access protected
     * @param boolean $master ä¸»æœåŠ¡å™¨
     * @return void
     */
    protected function initConnect($ÖçÏ¬¯÷=true) {
        if(1 == C('DB_DEPLOY_TYPE'))
            // é‡‡ç”¨åˆ†å¸ƒå¼æ•°æ®åº“
            $this->_linkID = $this->multiConnect($ÖçÏ¬¯÷);
        else
            // é»˜è®¤å•æ•°æ®åº“
            if ( !$this->_linkID ) $this->_linkID = $this->connect();
    }

    /**
     * è¿æ¥åˆ†å¸ƒå¼æœåŠ¡å™¨
     * @access protected
     * @param boolean $master ä¸»æœåŠ¡å™¨
     * @return void
     */
    protected function multiConnect($ÖçÏ¬¯÷=false) {
        foreach ($this->config as $ñß¤–Ìª=>$ö·ŸÜæİ){
            $ººÏÊÁù[$ñß¤–Ìª]      =   explode(',',$ö·ŸÜæİ);
        }        
        // æ•°æ®åº“è¯»å†™æ˜¯å¦åˆ†ç¦»
        if(C('DB_RW_SEPARATE')){
            // ä¸»ä»å¼é‡‡ç”¨è¯»å†™åˆ†ç¦»
            if($ÖçÏ¬¯÷)
                // ä¸»æœåŠ¡å™¨å†™å…¥
                $Ü…ş¨™ë  =   floor(mt_rand(0,C('DB_MASTER_NUM')-1));
            else{
                if(is_numeric(C('DB_SLAVE_NO'))) {// æŒ‡å®šæœåŠ¡å™¨è¯»
                    $Ü…ş¨™ë = C('DB_SLAVE_NO');
                }else{
                    // è¯»æ“ä½œè¿æ¥ä»æœåŠ¡å™¨
                    $Ü…ş¨™ë = floor(mt_rand(C('DB_MASTER_NUM'),count($ººÏÊÁù['hostname'])-1));   // æ¯æ¬¡éšæœºè¿æ¥çš„æ•°æ®åº“
                }
            }
        }else{
            // è¯»å†™æ“ä½œä¸åŒºåˆ†æœåŠ¡å™¨
            $Ü…ş¨™ë = floor(mt_rand(0,count($ººÏÊÁù['hostname'])-1));   // æ¯æ¬¡éšæœºè¿æ¥çš„æ•°æ®åº“
        }
        $İÖ÷Ÿ¡Ï = array(
            'username'  =>  isset($ººÏÊÁù['username'][$Ü…ş¨™ë])?$ººÏÊÁù['username'][$Ü…ş¨™ë]:$ººÏÊÁù['username'][0],
            'password'  =>  isset($ººÏÊÁù['password'][$Ü…ş¨™ë])?$ººÏÊÁù['password'][$Ü…ş¨™ë]:$ººÏÊÁù['password'][0],
            'hostname'  =>  isset($ººÏÊÁù['hostname'][$Ü…ş¨™ë])?$ººÏÊÁù['hostname'][$Ü…ş¨™ë]:$ººÏÊÁù['hostname'][0],
            'hostport'  =>  isset($ººÏÊÁù['hostport'][$Ü…ş¨™ë])?$ººÏÊÁù['hostport'][$Ü…ş¨™ë]:$ººÏÊÁù['hostport'][0],
            'database'  =>  isset($ººÏÊÁù['database'][$Ü…ş¨™ë])?$ººÏÊÁù['database'][$Ü…ş¨™ë]:$ººÏÊÁù['database'][0],
            'dsn'       =>  isset($ººÏÊÁù['dsn'][$Ü…ş¨™ë])?$ººÏÊÁù['dsn'][$Ü…ş¨™ë]:$ººÏÊÁù['dsn'][0],
            'params'    =>  isset($ººÏÊÁù['params'][$Ü…ş¨™ë])?$ººÏÊÁù['params'][$Ü…ş¨™ë]:$ººÏÊÁù['params'][0],
            'charset'   =>  isset($ººÏÊÁù['charset'][$Ü…ş¨™ë])?$ººÏÊÁù['charset'][$Ü…ş¨™ë]:$ººÏÊÁù['charset'][0],            
        );
        return $this->connect($İÖ÷Ÿ¡Ï,$Ü…ş¨™ë);
    }

    /**
     * DSNè§£æ
     * æ ¼å¼ï¼š mysql://username:passwd@localhost:3306/DbName#charset
     * @static
     * @access public
     * @param string $dsnStr
     * @return array
     */
    public function parseDSN($³„í¸®«) {
        if( empty($³„í¸®«) ){return false;}
        $÷ªêğô‰ = parse_url($³„í¸®«);
        if($÷ªêğô‰['scheme']){
            $ç‡Üî¦  = array(
            'dbms'      =>  $÷ªêğô‰['scheme'],
            'username'  =>  isset($÷ªêğô‰['user']) ? $÷ªêğô‰['user'] : '',
            'password'  =>  isset($÷ªêğô‰['pass']) ? $÷ªêğô‰['pass'] : '',
            'hostname'  =>  isset($÷ªêğô‰['host']) ? $÷ªêğô‰['host'] : '',
            'hostport'  =>  isset($÷ªêğô‰['port']) ? $÷ªêğô‰['port'] : '',
            'database'  =>  isset($÷ªêğô‰['path']) ? substr($÷ªêğô‰['path'],1) : '',
            'charset'   =>  isset($÷ªêğô‰['fragment'])?$÷ªêğô‰['fragment']:'utf8',
            );
        }else {
            preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1, 6})\/(.*?)$/',trim($³„í¸®«),$Í´Ÿ³¥);
            $ç‡Üî¦  = array (
            'dbms'      =>  $Í´Ÿ³¥[1],
            'username'  =>  $Í´Ÿ³¥[2],
            'password'  =>  $Í´Ÿ³¥[3],
            'hostname'  =>  $Í´Ÿ³¥[4],
            'hostport'  =>  $Í´Ÿ³¥[5],
            'database'  =>  $Í´Ÿ³¥[6]
            );
        }
        $ç‡Üî¦ ['dsn'] =  ''; // å…¼å®¹é…ç½®ä¿¡æ¯æ•°ç»„
        return $ç‡Üî¦ ;
     }

    /**
     * æ•°æ®åº“è°ƒè¯• è®°å½•å½“å‰SQL
     * @access protected
     */
    protected function debug() {
        $this->modelSql[$this->model]   =  $this->queryStr;
        $this->model  =   '_think_';
        // è®°å½•æ“ä½œç»“æŸæ—¶é—´
        if (C('DB_SQL_LOG')) {
            G('queryEndTime');
            trace($this->queryStr.' [ RunTime:'.G('queryStartTime','queryEndTime',6).'s ]','','SQL');
        }
    }

    /**
     * è®¾ç½®é”æœºåˆ¶
     * @access protected
     * @return string
     */
    protected function parseLock($ş‚­”Î®=false) {
        if(!$ş‚­”Î®) return '';
        if('ORACLE' == $this->dbType) {
            return ' FOR UPDATE NOWAIT ';
        }
        return ' FOR UPDATE ';
    }

    /**
     * setåˆ†æ
     * @access protected
     * @param array $data
     * @return string
     */
    protected function parseSet($ıÄ…˜¯Œ) {
        foreach ($ıÄ…˜¯Œ as $ñß¤–Ìª=>$ö·ŸÜæİ){
            if(is_array($ö·ŸÜæİ) && 'exp' == $ö·ŸÜæİ[0]){
                $ÅüŒÈÈÚ[]  =   $this->parseKey($ñß¤–Ìª).'='.$ö·ŸÜæİ[1];
            }elseif(is_scalar($ö·ŸÜæİ) || is_null($ö·ŸÜæİ)) { // è¿‡æ»¤éæ ‡é‡æ•°æ®
              if(C('DB_BIND_PARAM') && 0 !== strpos($ö·ŸÜæİ,':')){
                $Å³º××Æ   =   md5($ñß¤–Ìª);
                $ÅüŒÈÈÚ[]  =   $this->parseKey($ñß¤–Ìª).'=:'.$Å³º××Æ;
                $this->bindParam($Å³º××Æ,$ö·ŸÜæİ);
              }else{
                $ÅüŒÈÈÚ[]  =   $this->parseKey($ñß¤–Ìª).'='.$this->parseValue($ö·ŸÜæİ);
              }
            }
        }
        return ' SET '.implode(',',$ÅüŒÈÈÚ);
    }

     /**
     * å‚æ•°ç»‘å®š
     * @access protected
     * @param string $name ç»‘å®šå‚æ•°å
     * @param mixed $value ç»‘å®šå€¼
     * @return void
     */
    protected function bindParam($Å³º××Æ,$½Äóİ„Ê){
        $this->bind[':'.$Å³º××Æ]  =   $½Äóİ„Ê;
    }

     /**
     * å‚æ•°ç»‘å®šåˆ†æ
     * @access protected
     * @param array $bind
     * @return array
     */
    protected function parseBind($£æŠ¦¤Ô){
        $£æŠ¦¤Ô           =   array_merge($this->bind,$£æŠ¦¤Ô);
        $this->bind     =   array();
        return $£æŠ¦¤Ô;
    }

    /**
     * å­—æ®µååˆ†æ
     * @access protected
     * @param string $key
     * @return string
     */
    protected function parseKey(&$ñß¤–Ìª) {
        return $ñß¤–Ìª;
    }
    
    /**
     * valueåˆ†æ
     * @access protected
     * @param mixed $value
     * @return string
     */
    protected function parseValue($½Äóİ„Ê) {
        if(is_string($½Äóİ„Ê)) {
            $½Äóİ„Ê =  '\''.$this->escapeString($½Äóİ„Ê).'\'';
        }elseif(isset($½Äóİ„Ê[0]) && is_string($½Äóİ„Ê[0]) && strtolower($½Äóİ„Ê[0]) == 'exp'){
            $½Äóİ„Ê =  $this->escapeString($½Äóİ„Ê[1]);
        }elseif(is_array($½Äóİ„Ê)) {
            $½Äóİ„Ê =  array_map(array($this, 'parseValue'),$½Äóİ„Ê);
        }elseif(is_bool($½Äóİ„Ê)){
            $½Äóİ„Ê =  $½Äóİ„Ê ? '1' : '0';
        }elseif(is_null($½Äóİ„Ê)){
            $½Äóİ„Ê =  'null';
        }
        return $½Äóİ„Ê;
    }

    /**
     * fieldåˆ†æ
     * @access protected
     * @param mixed $fields
     * @return string
     */
    protected function parseField($†³õ›Ë) {
        if(is_string($†³õ›Ë) && strpos($†³õ›Ë,',')) {
            $†³õ›Ë    = explode(',',$†³õ›Ë);
        }
        if(is_array($†³õ›Ë)) {
            // å®Œå–„æ•°ç»„æ–¹å¼ä¼ å­—æ®µåçš„æ”¯æŒ
            // æ”¯æŒ 'field1'=>'field2' è¿™æ ·çš„å­—æ®µåˆ«åå®šä¹‰
            $€¹óçàë   =  array();
            foreach ($†³õ›Ë as $ñß¤–Ìª=>$¹¸‹”–){
                if(!is_numeric($ñß¤–Ìª))
                    $€¹óçàë[] =  $this->parseKey($ñß¤–Ìª).' AS '.$this->parseKey($¹¸‹”–);
                else
                    $€¹óçàë[] =  $this->parseKey($¹¸‹”–);
            }
            $÷±íúÌƒ = implode(',', $€¹óçàë);
        }elseif(is_string($†³õ›Ë) && !empty($†³õ›Ë)) {
            $÷±íúÌƒ = $this->parseKey($†³õ›Ë);
        }else{
            $÷±íúÌƒ = '*';
        }
        //TODO å¦‚æœæ˜¯æŸ¥è¯¢å…¨éƒ¨å­—æ®µï¼Œå¹¶ä¸”æ˜¯joinçš„æ–¹å¼ï¼Œé‚£ä¹ˆå°±æŠŠè¦æŸ¥çš„è¡¨åŠ ä¸ªåˆ«åï¼Œä»¥å…å­—æ®µè¢«è¦†ç›–
        return $÷±íúÌƒ;
    }

    /**
     * tableåˆ†æ
     * @access protected
     * @param mixed $table
     * @return string
     */
    protected function parseTable($ú…ãä—ê) {
        if(is_array($ú…ãä—ê)) {// æ”¯æŒåˆ«åå®šä¹‰
            $€¹óçàë   =  array();
            foreach ($ú…ãä—ê as $–˜æã¨ù=>$Ö‚‘šÇÑ){
                if(!is_numeric($–˜æã¨ù))
                    $€¹óçàë[] =  $this->parseKey($–˜æã¨ù).' '.$this->parseKey($Ö‚‘šÇÑ);
                else
                    $€¹óçàë[] =  $this->parseKey($–˜æã¨ù);
            }
            $ú…ãä—ê  =  $€¹óçàë;
        }elseif(is_string($ú…ãä—ê)){
            $ú…ãä—ê  =  explode(',',$ú…ãä—ê);
            array_walk($ú…ãä—ê, array(&$this, 'parseKey'));
        }
        $ú…ãä—ê = implode(',',$ú…ãä—ê);
        return $ú…ãä—ê;
    }

    /**
     * whereåˆ†æ
     * @access protected
     * @param mixed $where
     * @return string
     */
    protected function parseWhere($™¶ÜÃî©) {
        $¥Û ûÊœ = '';
        if(is_string($™¶ÜÃî©)) {
            // ç›´æ¥ä½¿ç”¨å­—ç¬¦ä¸²æ¡ä»¶
            $¥Û ûÊœ = $™¶ÜÃî©;
        }else{ // ä½¿ç”¨æ•°ç»„è¡¨è¾¾å¼
            $ñÄÄë”Ú  = isset($™¶ÜÃî©['_logic'])?strtoupper($™¶ÜÃî©['_logic']):'';
            if(in_array($ñÄÄë”Ú,array('AND','OR','XOR'))){
                // å®šä¹‰é€»è¾‘è¿ç®—è§„åˆ™ ä¾‹å¦‚ OR XOR AND NOT
                $ñÄÄë”Ú    =   ' '.$ñÄÄë”Ú.' ';
                unset($™¶ÜÃî©['_logic']);
            }else{
                // é»˜è®¤è¿›è¡Œ AND è¿ç®—
                $ñÄÄë”Ú    =   ' AND ';
            }
            foreach ($™¶ÜÃî© as $ñß¤–Ìª=>$ö·ŸÜæİ){
                if(is_numeric($ñß¤–Ìª)){
                    $ñß¤–Ìª  = '_complex';
                }                    
                if(0===strpos($ñß¤–Ìª,'_')) {
                    // è§£æç‰¹æ®Šæ¡ä»¶è¡¨è¾¾å¼
                    $¥Û ûÊœ   .= $this->parseThinkWhere($ñß¤–Ìª,$ö·ŸÜæİ);
                }else{
                    // æŸ¥è¯¢å­—æ®µçš„å®‰å…¨è¿‡æ»¤
                    if(!preg_match('/^[A-Z_\|\&\-.a-z0-9\(\)\,]+$/',trim($ñß¤–Ìª))){
                        E(L('_EXPRESS_ERROR_').':'.$ñß¤–Ìª);
                    }
                    // å¤šæ¡ä»¶æ”¯æŒ
                    $úË¤˜·•  = is_array($ö·ŸÜæİ) &&  isset($ö·ŸÜæİ['_multi']);
                    $ñß¤–Ìª    = trim($ñß¤–Ìª);
                    if(strpos($ñß¤–Ìª,'|')) { // æ”¯æŒ name|title|nickname æ–¹å¼å®šä¹‰æŸ¥è¯¢å­—æ®µ
                        $€¹óçàë =  explode('|',$ñß¤–Ìª);
                        $Ğ¼õ£Å    =  array();
                        foreach ($€¹óçàë as $Ù°¬û=>$©ò†â÷){
                            $šŸ³ÓØ¿ =  $úË¤˜·•?$ö·ŸÜæİ[$Ù°¬û]:$ö·ŸÜæİ;
                            $Ğ¼õ£Å []   = $this->parseWhereItem($this->parseKey($©ò†â÷),$šŸ³ÓØ¿);
                        }
                        $¥Û ûÊœ .= '( '.implode(' OR ',$Ğ¼õ£Å ).' )';
                    }elseif(strpos($ñß¤–Ìª,'&')){
                        $€¹óçàë =  explode('&',$ñß¤–Ìª);
                        $Ğ¼õ£Å    =  array();
                        foreach ($€¹óçàë as $Ù°¬û=>$©ò†â÷){
                            $šŸ³ÓØ¿ =  $úË¤˜·•?$ö·ŸÜæİ[$Ù°¬û]:$ö·ŸÜæİ;
                            $Ğ¼õ£Å []   = '('.$this->parseWhereItem($this->parseKey($©ò†â÷),$šŸ³ÓØ¿).')';
                        }
                        $¥Û ûÊœ .= '( '.implode(' AND ',$Ğ¼õ£Å ).' )';
                    }else{
                        $¥Û ûÊœ .= $this->parseWhereItem($this->parseKey($ñß¤–Ìª),$ö·ŸÜæİ);
                    }
                }
                $¥Û ûÊœ .= $ñÄÄë”Ú;
            }
            $¥Û ûÊœ = substr($¥Û ûÊœ,0,-strlen($ñÄÄë”Ú));
        }
        return empty($¥Û ûÊœ)?'':' WHERE '.$¥Û ûÊœ;
    }

    // whereå­å•å…ƒåˆ†æ
    protected function parseWhereItem($ñß¤–Ìª,$ö·ŸÜæİ) {
        $¥Û ûÊœ = '';
        if(is_array($ö·ŸÜæİ)) {
            if(is_string($ö·ŸÜæİ[0])) {
				$‰¤‘åŸ	=	strtolower($ö·ŸÜæİ[0]);
                if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT)$/i',$ö·ŸÜæİ[0])) { // æ¯”è¾ƒè¿ç®—
                    $¥Û ûÊœ .= $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' '.$this->parseValue($ö·ŸÜæİ[1]);
                }elseif(preg_match('/^(NOTLIKE|LIKE)$/i',$ö·ŸÜæİ[0])){// æ¨¡ç³ŠæŸ¥æ‰¾
                    if(is_array($ö·ŸÜæİ[1])) {
                        $ËÜ™ºú²  =   isset($ö·ŸÜæİ[2])?strtoupper($ö·ŸÜæİ[2]):'OR';
                        if(in_array($ËÜ™ºú²,array('AND','OR','XOR'))){
                            $µËÊ…ô       =   array();
                            foreach ($ö·ŸÜæİ[1] as $ĞÙ¹îá¼){
                                $µËÊ…ô[] = $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' '.$this->parseValue($ĞÙ¹îá¼);
                            }
                            $¥Û ûÊœ .= '('.implode(' '.$ËÜ™ºú².' ',$µËÊ…ô).')';                          
                        }
                    }else{
                        $¥Û ûÊœ .= $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' '.$this->parseValue($ö·ŸÜæİ[1]);
                    }
                }elseif('exp'==$‰¤‘åŸ){ // ä½¿ç”¨è¡¨è¾¾å¼
                    $¥Û ûÊœ .= $ñß¤–Ìª.' '.$ö·ŸÜæİ[1];
                }elseif(preg_match('/^(NOTIN|NOT IN|IN)$/i',$ö·ŸÜæİ[0])){ // IN è¿ç®—
                    if(isset($ö·ŸÜæİ[2]) && 'exp'==$ö·ŸÜæİ[2]) {
                        $¥Û ûÊœ .= $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' '.$ö·ŸÜæİ[1];
                    }else{
                        if(is_string($ö·ŸÜæİ[1])) {
                             $ö·ŸÜæİ[1] =  explode(',',$ö·ŸÜæİ[1]);
                        }
                        $ÑŞí™–¢      =   implode(',',$this->parseValue($ö·ŸÜæİ[1]));
                        $¥Û ûÊœ .= $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' ('.$ÑŞí™–¢.')';
                    }
                }elseif(preg_match('/^(NOTBETWEEN|NOT BETWEEN|BETWEEN)$/i',$ö·ŸÜæİ[0])){ // BETWEENè¿ç®—
                    $ıÄ…˜¯Œ = is_string($ö·ŸÜæİ[1])? explode(',',$ö·ŸÜæİ[1]):$ö·ŸÜæİ[1];
                    $¥Û ûÊœ .=  $ñß¤–Ìª.' '.$this->exp[$‰¤‘åŸ].' '.$this->parseValue($ıÄ…˜¯Œ[0]).' AND '.$this->parseValue($ıÄ…˜¯Œ[1]);
                }else{
                    E(L('_EXPRESS_ERROR_').':'.$ö·ŸÜæİ[0]);
                }
            }else {
                $¹Æ–¥õ­ = count($ö·ŸÜæİ);
                $®éÇÂØû  = isset($ö·ŸÜæİ[$¹Æ–¥õ­-1]) ? (is_array($ö·ŸÜæİ[$¹Æ–¥õ­-1]) ? strtoupper($ö·ŸÜæİ[$¹Æ–¥õ­-1][0]) : strtoupper($ö·ŸÜæİ[$¹Æ–¥õ­-1]) ) : '' ; 
                if(in_array($®éÇÂØû,array('AND','OR','XOR'))) {
                    $¹Æ–¥õ­  = $¹Æ–¥õ­ -1;
                }else{
                    $®éÇÂØû   = 'AND';
                }
                for($ˆÍ­ı¥÷=0;$ˆÍ­ı¥÷<$¹Æ–¥õ­;$ˆÍ­ı¥÷++) {
                    $ıÄ…˜¯Œ = is_array($ö·ŸÜæİ[$ˆÍ­ı¥÷])?$ö·ŸÜæİ[$ˆÍ­ı¥÷][1]:$ö·ŸÜæİ[$ˆÍ­ı¥÷];
                    if('exp'==strtolower($ö·ŸÜæİ[$ˆÍ­ı¥÷][0])) {
                        $¥Û ûÊœ .= $ñß¤–Ìª.' '.$ıÄ…˜¯Œ.' '.$®éÇÂØû.' ';
                    }else{
                        $¥Û ûÊœ .= $this->parseWhereItem($ñß¤–Ìª,$ö·ŸÜæİ[$ˆÍ­ı¥÷]).' '.$®éÇÂØû.' ';
                    }
                }
                $¥Û ûÊœ = '( '.substr($¥Û ûÊœ,0,-4).' )';
            }
        }else {
            //å¯¹å­—ç¬¦ä¸²ç±»å‹å­—æ®µé‡‡ç”¨æ¨¡ç³ŠåŒ¹é…
            if(C('DB_LIKE_FIELDS') && preg_match('/^('.C('DB_LIKE_FIELDS').')$/i',$ñß¤–Ìª)) {
                $ö·ŸÜæİ  =  '%'.$ö·ŸÜæİ.'%';
                $¥Û ûÊœ .= $ñß¤–Ìª.' LIKE '.$this->parseValue($ö·ŸÜæİ);
            }else {
                $¥Û ûÊœ .= $ñß¤–Ìª.' = '.$this->parseValue($ö·ŸÜæİ);
            }
        }
        return $¥Û ûÊœ;
    }

    /**
     * ç‰¹æ®Šæ¡ä»¶åˆ†æ
     * @access protected
     * @param string $key
     * @param mixed $val
     * @return string
     */
    protected function parseThinkWhere($ñß¤–Ìª,$ö·ŸÜæİ) {
        $¥Û ûÊœ   = '';
        switch($ñß¤–Ìª) {
            case '_string':
                // å­—ç¬¦ä¸²æ¨¡å¼æŸ¥è¯¢æ¡ä»¶
                $¥Û ûÊœ = $ö·ŸÜæİ;
                break;
            case '_complex':
                // å¤åˆæŸ¥è¯¢æ¡ä»¶
                $¥Û ûÊœ   =   is_string($ö·ŸÜæİ)? $ö·ŸÜæİ : substr($this->parseWhere($ö·ŸÜæİ),6);
                break;
            case '_query':
                // å­—ç¬¦ä¸²æ¨¡å¼æŸ¥è¯¢æ¡ä»¶
                parse_str($ö·ŸÜæİ,$™¶ÜÃî©);
                if(isset($™¶ÜÃî©['_logic'])) {
                    $Ê˜éãŞ   =  ' '.strtoupper($™¶ÜÃî©['_logic']).' ';
                    unset($™¶ÜÃî©['_logic']);
                }else{
                    $Ê˜éãŞ   =  ' AND ';
                }
                $€¹óçàë   =  array();
                foreach ($™¶ÜÃî© as $¹¸‹”–=>$ıÄ…˜¯Œ)
                    $€¹óçàë[] = $this->parseKey($¹¸‹”–).' = '.$this->parseValue($ıÄ…˜¯Œ);
                $¥Û ûÊœ   = implode($Ê˜éãŞ,$€¹óçàë);
                break;
        }
        return '( '.$¥Û ûÊœ.' )';
    }

    /**
     * limitåˆ†æ
     * @access protected
     * @param mixed $lmit
     * @return string
     */
    protected function parseLimit($ Ãœò‰›) {
        return !empty($ Ãœò‰›)?   ' LIMIT '.$ Ãœò‰›.' ':'';
    }

    /**
     * joinåˆ†æ
     * @access protected
     * @param array $join
     * @return string
     */
    protected function parseJoin($„÷œ”ƒŒ) {
        $ò›ä®á = '';
        if(!empty($„÷œ”ƒŒ)) {
            $ò›ä®á    =   ' '.implode(' ',$„÷œ”ƒŒ).' ';
        }
        return $ò›ä®á;
    }

    /**
     * orderåˆ†æ
     * @access protected
     * @param mixed $order
     * @return string
     */
    protected function parseOrder($›“İˆ±§) {
        if(is_array($›“İˆ±§)) {
            $€¹óçàë   =  array();
            foreach ($›“İˆ±§ as $ñß¤–Ìª=>$ö·ŸÜæİ){
                if(is_numeric($ñß¤–Ìª)) {
                    $€¹óçàë[] =  $this->parseKey($ö·ŸÜæİ);
                }else{
                    $€¹óçàë[] =  $this->parseKey($ñß¤–Ìª).' '.$ö·ŸÜæİ;
                }
            }
            $›“İˆ±§   =  implode(',',$€¹óçàë);
        }
        return !empty($›“İˆ±§)?  ' ORDER BY '.$›“İˆ±§:'';
    }

    /**
     * groupåˆ†æ
     * @access protected
     * @param mixed $group
     * @return string
     */
    protected function parseGroup($–şôû­ò) {
        return !empty($–şôû­ò)? ' GROUP BY '.$–şôû­ò:'';
    }

    /**
     * havingåˆ†æ
     * @access protected
     * @param string $having
     * @return string
     */
    protected function parseHaving($¥óò·ï¤) {
        return  !empty($¥óò·ï¤)?   ' HAVING '.$¥óò·ï¤:'';
    }

    /**
     * commentåˆ†æ
     * @access protected
     * @param string $comment
     * @return string
     */
    protected function parseComment($°–ö’á½) {
        return  !empty($°–ö’á½)?   ' /* '.$°–ö’á½.' */':'';
    }

    /**
     * distinctåˆ†æ
     * @access protected
     * @param mixed $distinct
     * @return string
     */
    protected function parseDistinct($“«±‡„) {
        return !empty($“«±‡„)?   ' DISTINCT ' :'';
    }

    /**
     * unionåˆ†æ
     * @access protected
     * @param mixed $union
     * @return string
     */
    protected function parseUnion($ÛÎèÀ½ª) {
        if(empty($ÛÎèÀ½ª)) return '';
        if(isset($ÛÎèÀ½ª['_all'])) {
            $Ğ¼õ£Å   =   'UNION ALL ';
            unset($ÛÎèÀ½ª['_all']);
        }else{
            $Ğ¼õ£Å   =   'UNION ';
        }
        foreach ($ÛÎèÀ½ª as $Äë‡î){
            $··ı™ [] = $Ğ¼õ£Å .(is_array($Äë‡î)?$this->buildSelectSql($Äë‡î):$Äë‡î);
        }
        return implode(' ',$··ı™ );
    }

    /**
     * æ’å…¥è®°å½•
     * @access public
     * @param mixed $data æ•°æ®
     * @param array $options å‚æ•°è¡¨è¾¾å¼
     * @param boolean $replace æ˜¯å¦replace
     * @return false | integer
     */
    public function insert($ıÄ…˜¯Œ,$Øå£Ç˜«=array(),$Ñï‘Ø£=false) {
        $Ş½áª’÷  =  $†³õ›Ë    = array();
        $this->model  =   $Øå£Ç˜«['model'];
        foreach ($ıÄ…˜¯Œ as $ñß¤–Ìª=>$ö·ŸÜæİ){
            if(is_array($ö·ŸÜæİ) && 'exp' == $ö·ŸÜæİ[0]){
                $†³õ›Ë[]   =  $this->parseKey($ñß¤–Ìª);
                $Ş½áª’÷[]   =  $ö·ŸÜæİ[1];
            }elseif(is_scalar($ö·ŸÜæİ) || is_null($ö·ŸÜæİ)) { // è¿‡æ»¤éæ ‡é‡æ•°æ®
              $†³õ›Ë[]   =  $this->parseKey($ñß¤–Ìª);
              if(C('DB_BIND_PARAM') && 0 !== strpos($ö·ŸÜæİ,':')){
                $Å³º××Æ       =   md5($ñß¤–Ìª);
                $Ş½áª’÷[]   =   ':'.$Å³º××Æ;
                $this->bindParam($Å³º××Æ,$ö·ŸÜæİ);
              }else{
                $Ş½áª’÷[]   =  $this->parseValue($ö·ŸÜæİ);
              }                
            }
        }
        $··ı™    =  ($Ñï‘Ø£?'REPLACE':'INSERT').' INTO '.$this->parseTable($Øå£Ç˜«['table']).' ('.implode(',', $†³õ›Ë).') VALUES ('.implode(',', $Ş½áª’÷).')';
        $··ı™    .= $this->parseLock(isset($Øå£Ç˜«['lock'])?$Øå£Ç˜«['lock']:false);
        $··ı™    .= $this->parseComment(!empty($Øå£Ç˜«['comment'])?$Øå£Ç˜«['comment']:'');
        return $this->execute($··ı™ ,$this->parseBind(!empty($Øå£Ç˜«['bind'])?$Øå£Ç˜«['bind']:array()));
    }

    /**
     * é€šè¿‡Selectæ–¹å¼æ’å…¥è®°å½•
     * @access public
     * @param string $fields è¦æ’å…¥çš„æ•°æ®è¡¨å­—æ®µå
     * @param string $table è¦æ’å…¥çš„æ•°æ®è¡¨å
     * @param array $option  æŸ¥è¯¢æ•°æ®å‚æ•°
     * @return false | integer
     */
    public function selectInsert($†³õ›Ë,$–˜æã¨ù,$Øå£Ç˜«=array()) {
        $this->model  =   $Øå£Ç˜«['model'];
        if(is_string($†³õ›Ë))   $†³õ›Ë    = explode(',',$†³õ›Ë);
        array_walk($†³õ›Ë, array($this, 'parseKey'));
        $··ı™    =    'INSERT INTO '.$this->parseTable($–˜æã¨ù).' ('.implode(',', $†³õ›Ë).') ';
        $··ı™    .= $this->buildSelectSql($Øå£Ç˜«);
        return $this->execute($··ı™ ,$this->parseBind(!empty($Øå£Ç˜«['bind'])?$Øå£Ç˜«['bind']:array()));
    }

    /**
     * æ›´æ–°è®°å½•
     * @access public
     * @param mixed $data æ•°æ®
     * @param array $options è¡¨è¾¾å¼
     * @return false | integer
     */
    public function update($ıÄ…˜¯Œ,$Øå£Ç˜«) {
        $this->model  =   $Øå£Ç˜«['model'];
        $··ı™    = 'UPDATE '
            .$this->parseTable($Øå£Ç˜«['table'])
            .$this->parseSet($ıÄ…˜¯Œ)
            .$this->parseWhere(!empty($Øå£Ç˜«['where'])?$Øå£Ç˜«['where']:'')
            .$this->parseOrder(!empty($Øå£Ç˜«['order'])?$Øå£Ç˜«['order']:'')
            .$this->parseLimit(!empty($Øå£Ç˜«['limit'])?$Øå£Ç˜«['limit']:'')
            .$this->parseLock(isset($Øå£Ç˜«['lock'])?$Øå£Ç˜«['lock']:false)
            .$this->parseComment(!empty($Øå£Ç˜«['comment'])?$Øå£Ç˜«['comment']:'');
        return $this->execute($··ı™ ,$this->parseBind(!empty($Øå£Ç˜«['bind'])?$Øå£Ç˜«['bind']:array()));
    }

    /**
     * åˆ é™¤è®°å½•
     * @access public
     * @param array $options è¡¨è¾¾å¼
     * @return false | integer
     */
    public function delete($Øå£Ç˜«=array()) {
        $this->model  =   $Øå£Ç˜«['model'];
        $··ı™    = 'DELETE FROM '
            .$this->parseTable($Øå£Ç˜«['table'])
            .$this->parseWhere(!empty($Øå£Ç˜«['where'])?$Øå£Ç˜«['where']:'')
            .$this->parseOrder(!empty($Øå£Ç˜«['order'])?$Øå£Ç˜«['order']:'')
            .$this->parseLimit(!empty($Øå£Ç˜«['limit'])?$Øå£Ç˜«['limit']:'')
            .$this->parseLock(isset($Øå£Ç˜«['lock'])?$Øå£Ç˜«['lock']:false)
            .$this->parseComment(!empty($Øå£Ç˜«['comment'])?$Øå£Ç˜«['comment']:'');
        return $this->execute($··ı™ ,$this->parseBind(!empty($Øå£Ç˜«['bind'])?$Øå£Ç˜«['bind']:array()));
    }

    /**
     * æŸ¥æ‰¾è®°å½•
     * @access public
     * @param array $options è¡¨è¾¾å¼
     * @return mixed
     */
    public function select($Øå£Ç˜«=array()) {
        $this->model  =   $Øå£Ç˜«['model'];
        $··ı™         =   $this->buildSelectSql($Øå£Ç˜«);
        $©òäû¹     =   $this->query($··ı™ ,$this->parseBind(!empty($Øå£Ç˜«['bind'])?$Øå£Ç˜«['bind']:array()));
        return $©òäû¹;
    }

    /**
     * ç”ŸæˆæŸ¥è¯¢SQL
     * @access public
     * @param array $options è¡¨è¾¾å¼
     * @return string
     */
    public function buildSelectSql($Øå£Ç˜«=array()) {
        if(isset($Øå£Ç˜«['page'])) {
            // æ ¹æ®é¡µæ•°è®¡ç®—limit
            list($Ÿíë¶Òó,$„— ê¯—)   =   $Øå£Ç˜«['page'];
            $Ÿíë¶Òó    =  $Ÿíë¶Òó>0 ? $Ÿíë¶Òó : 1;
            $„— ê¯—=  $„— ê¯—>0 ? $„— ê¯— : (is_numeric($Øå£Ç˜«['limit'])?$Øå£Ç˜«['limit']:20);
            $¾È™˜ºş  =  $„— ê¯—*($Ÿíë¶Òó-1);
            $Øå£Ç˜«['limit'] =  $¾È™˜ºş.','.$„— ê¯—;
        }
        if(C('DB_SQL_BUILD_CACHE')) { // SQLåˆ›å»ºç¼“å­˜
            $ñß¤–Ìª    =  md5(serialize($Øå£Ç˜«));
            $½Äóİ„Ê  =  S($ñß¤–Ìª);
            if(false !== $½Äóİ„Ê) {
                return $½Äóİ„Ê;
            }
        }
        $··ı™   =     $this->parseSql($this->selectSql,$Øå£Ç˜«);
        $··ı™  .=     $this->parseLock(isset($Øå£Ç˜«['lock'])?$Øå£Ç˜«['lock']:false);
        if(isset($ñß¤–Ìª)) { // å†™å…¥SQLåˆ›å»ºç¼“å­˜
            S($ñß¤–Ìª,$··ı™ ,array('expire'=>0,'length'=>C('DB_SQL_BUILD_LENGTH'),'queue'=>C('DB_SQL_BUILD_QUEUE')));
        }
        return $··ı™ ;
    }

    /**
     * æ›¿æ¢SQLè¯­å¥ä¸­è¡¨è¾¾å¼
     * @access public
     * @param array $options è¡¨è¾¾å¼
     * @return string
     */
    public function parseSql($··ı™ ,$Øå£Ç˜«=array()){
        $··ı™    = str_replace(
            array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),
            array(
                $this->parseTable($Øå£Ç˜«['table']),
                $this->parseDistinct(isset($Øå£Ç˜«['distinct'])?$Øå£Ç˜«['distinct']:false),
                $this->parseField(!empty($Øå£Ç˜«['field'])?$Øå£Ç˜«['field']:'*'),
                $this->parseJoin(!empty($Øå£Ç˜«['join'])?$Øå£Ç˜«['join']:''),
                $this->parseWhere(!empty($Øå£Ç˜«['where'])?$Øå£Ç˜«['where']:''),
                $this->parseGroup(!empty($Øå£Ç˜«['group'])?$Øå£Ç˜«['group']:''),
                $this->parseHaving(!empty($Øå£Ç˜«['having'])?$Øå£Ç˜«['having']:''),
                $this->parseOrder(!empty($Øå£Ç˜«['order'])?$Øå£Ç˜«['order']:''),
                $this->parseLimit(!empty($Øå£Ç˜«['limit'])?$Øå£Ç˜«['limit']:''),
                $this->parseUnion(!empty($Øå£Ç˜«['union'])?$Øå£Ç˜«['union']:''),
                $this->parseComment(!empty($Øå£Ç˜«['comment'])?$Øå£Ç˜«['comment']:'')
            ),$··ı™ );
        return $··ı™ ;
    }

    /**
     * è·å–æœ€è¿‘ä¸€æ¬¡æŸ¥è¯¢çš„sqlè¯­å¥ 
     * @param string $model  æ¨¡å‹å
     * @access public
     * @return string
     */
    public function getLastSql($Øı¬õ¦«='') {
        return $Øı¬õ¦«?$this->modelSql[$Øı¬õ¦«]:$this->queryStr;
    }

    /**
     * è·å–æœ€è¿‘æ’å…¥çš„ID
     * @access public
     * @return string
     */
    public function getLastInsID() {
        return $this->lastInsID;
    }

    /**
     * è·å–æœ€è¿‘çš„é”™è¯¯ä¿¡æ¯
     * @access public
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * SQLæŒ‡ä»¤å®‰å…¨è¿‡æ»¤
     * @access public
     * @param string $str  SQLå­—ç¬¦ä¸²
     * @return string
     */
    public function escapeString($Ğ¼õ£Å ) {
        return addslashes($Ğ¼õ£Å );
    }

    /**
     * è®¾ç½®å½“å‰æ“ä½œæ¨¡å‹
     * @access public
     * @param string $model  æ¨¡å‹å
     * @return void
     */
    public function setModel($Øı¬õ¦«){
        $this->model =  $Øı¬õ¦«;
    }

   /**
     * ææ„æ–¹æ³•
     * @access public
     */
    public function __destruct() {
        // é‡Šæ”¾æŸ¥è¯¢
        if ($this->queryID){
            $this->free();
        }
        // å…³é—­è¿æ¥
        $this->close();
    }

    // å…³é—­æ•°æ®åº“ ç”±é©±åŠ¨ç±»å®šä¹‰
    public function close(){}

    public function test(){
        print_r('success');
    }
}