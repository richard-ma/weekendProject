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
 * ThinkPHP Modelæ¨¡å‹ç±»
 * å®ç°äº†ORMå’ŒActiveRecordsæ¨¡å¼
 */
class Model {
    // æ“ä½œçŠ¶æ€
    const MODEL_INSERT          =   1;      //  æ’å…¥æ¨¡å‹æ•°æ®
    const MODEL_UPDATE          =   2;      //  æ›´æ–°æ¨¡å‹æ•°æ®
    const MODEL_BOTH            =   3;      //  åŒ…å«ä¸Šé¢ä¸¤ç§æ–¹å¼
    const MUST_VALIDATE         =   1;      // å¿…é¡»éªŒè¯
    const EXISTS_VALIDATE       =   0;      // è¡¨å•å­˜åœ¨å­—æ®µåˆ™éªŒè¯
    const VALUE_VALIDATE        =   2;      // è¡¨å•å€¼ä¸ä¸ºç©ºåˆ™éªŒè¯

    // å½“å‰æ•°æ®åº“æ“ä½œå¯¹è±¡
    protected $“„ˆÓ’ã               =   null;
    // ä¸»é”®åç§°
    protected $Ò¿µãöæ               =   'id';
    // ä¸»é”®æ˜¯å¦è‡ªåŠ¨å¢é•¿
    protected $æçÔáŒâ          =   false;    
    // æ•°æ®è¡¨å‰ç¼€
    protected $÷ÈÜúş      =   null;
    // æ¨¡å‹åç§°
    protected $–Àêçœ­             =   '';
    // æ•°æ®åº“åç§°
    protected $ëÙôĞ‚ñ           =   '';
    //æ•°æ®åº“é…ç½®
    protected $Ó²¾ìµ¶       =   '';
    // æ•°æ®è¡¨åï¼ˆä¸åŒ…å«è¡¨å‰ç¼€ï¼‰
    protected $…ª‰Ö™Š        =   '';
    // å®é™…æ•°æ®è¡¨åï¼ˆåŒ…å«è¡¨å‰ç¼€ï¼‰
    protected $í´Ãª”ƒ    =   '';
    // æœ€è¿‘é”™è¯¯ä¿¡æ¯
    protected $Öú¢ùÅß            =   '';
    // å­—æ®µä¿¡æ¯
    protected $£¬ÂÂõê           =   array();
    // æ•°æ®ä¿¡æ¯
    protected $Íúşş‹×             =   array();
    // æŸ¥è¯¢è¡¨è¾¾å¼å‚æ•°
    protected $ëÉóèÉ³          =   array();
    protected $Óê½–á        =   array();  // è‡ªåŠ¨éªŒè¯å®šä¹‰
    protected $õ–½’§Ã            =   array();  // è‡ªåŠ¨å®Œæˆå®šä¹‰
    protected $¹Ì·¸À¶             =   array();  // å­—æ®µæ˜ å°„å®šä¹‰
    protected $ºÛ©ëğ‚           =   array();  // å‘½åèŒƒå›´å®šä¹‰
    // æ˜¯å¦è‡ªåŠ¨æ£€æµ‹æ•°æ®è¡¨å­—æ®µä¿¡æ¯
    protected $¥ÆúµÈ  =   true;
    // æ˜¯å¦æ‰¹å¤„ç†éªŒè¯
    protected $€ò­œ³Á    =   false;
    // é“¾æ“ä½œæ–¹æ³•åˆ—è¡¨
    protected $³¼§ş’          =   array('order','alias','having','group','lock','distinct','auto','filter','validate','result','token','index');

    /**
     * æ¶æ„å‡½æ•°
     * å–å¾—DBç±»çš„å®ä¾‹å¯¹è±¡ å­—æ®µæ£€æŸ¥
     * @access public
     * @param string $name æ¨¡å‹åç§°
     * @param string $tablePrefix è¡¨å‰ç¼€
     * @param mixed $connection æ•°æ®åº“è¿æ¥ä¿¡æ¯
     */
    public function __construct($–Àêçœ­='',$÷ÈÜúş='',$Ó²¾ìµ¶='') {
        // æ¨¡å‹åˆå§‹åŒ–
        $this->_initialize();
        // è·å–æ¨¡å‹åç§°
        if(!empty($–Àêçœ­)) {
            if(strpos($–Àêçœ­,'.')) { // æ”¯æŒ æ•°æ®åº“å.æ¨¡å‹åçš„ å®šä¹‰
                list($this->dbName,$this->name) = explode('.',$–Àêçœ­);
            }else{
                $this->name   =  $–Àêçœ­;
            }
        }elseif(empty($this->name)){
            $this->name =   $this->getModelName();
        }
        // è®¾ç½®è¡¨å‰ç¼€
        if(is_null($÷ÈÜúş)) {// å‰ç¼€ä¸ºNullè¡¨ç¤ºæ²¡æœ‰å‰ç¼€
            $this->tablePrefix = '';
        }elseif('' != $÷ÈÜúş) {
            $this->tablePrefix = $÷ÈÜúş;
        }elseif(!isset($this->tablePrefix)){
            $this->tablePrefix = C('DB_PREFIX');
        }

        // æ•°æ®åº“åˆå§‹åŒ–æ“ä½œ
        // è·å–æ•°æ®åº“æ“ä½œå¯¹è±¡
        // å½“å‰æ¨¡å‹æœ‰ç‹¬ç«‹çš„æ•°æ®åº“è¿æ¥ä¿¡æ¯
        $this->db(0,empty($this->connection)?$Ó²¾ìµ¶:$this->connection,true);
    }

    /**
     * è‡ªåŠ¨æ£€æµ‹æ•°æ®è¡¨ä¿¡æ¯
     * @access protected
     * @return void
     */
    protected function _checkTableInfo() {
        // å¦‚æœä¸æ˜¯Modelç±» è‡ªåŠ¨è®°å½•æ•°æ®è¡¨ä¿¡æ¯
        // åªåœ¨ç¬¬ä¸€æ¬¡æ‰§è¡Œè®°å½•
        if(empty($this->fields)) {
            // å¦‚æœæ•°æ®è¡¨å­—æ®µæ²¡æœ‰å®šä¹‰åˆ™è‡ªåŠ¨è·å–
            if(C('DB_FIELDS_CACHE')) {
                $“„ˆÓ’ã   =  $this->dbName?:C('DB_NAME');
                $£¬ÂÂõê = F('_fields/'.strtolower($“„ˆÓ’ã.'.'.$this->tablePrefix.$this->name));
                if($£¬ÂÂõê) {
                    $this->fields   =   $£¬ÂÂõê;
                    $this->pk       =   $£¬ÂÂõê['_pk'];
                    return ;
                }
            }
            // æ¯æ¬¡éƒ½ä¼šè¯»å–æ•°æ®è¡¨ä¿¡æ¯
            $this->flush();
        }
    }

    /**
     * è·å–å­—æ®µä¿¡æ¯å¹¶ç¼“å­˜
     * @access public
     * @return void
     */
    public function flush() {
        // ç¼“å­˜ä¸å­˜åœ¨åˆ™æŸ¥è¯¢æ•°æ®è¡¨ä¿¡æ¯
        $this->db->setModel($this->name);
        $£¬ÂÂõê =   $this->db->getFields($this->getTableName());
        if(!$£¬ÂÂõê) { // æ— æ³•è·å–å­—æ®µä¿¡æ¯
            return false;
        }
        $this->fields   =   array_keys($£¬ÂÂõê);
        foreach ($£¬ÂÂõê as $…êâò«=>$Ùãë²‡¿){
            // è®°å½•å­—æ®µç±»å‹
            $ÕÃ“ßÏ¦[$…êâò«]     =   $Ùãë²‡¿['type'];
            if($Ùãë²‡¿['primary']) {
                $this->pk   =   $…êâò«;
                $this->fields['_pk']   =   $…êâò«;
                if($Ùãë²‡¿['autoinc']) $this->autoinc   =   true;
            }
        }
        // è®°å½•å­—æ®µç±»å‹ä¿¡æ¯
        $this->fields['_type'] =  $ÕÃ“ßÏ¦;

        // 2008-3-7 å¢åŠ ç¼“å­˜å¼€å…³æ§åˆ¶
        if(C('DB_FIELDS_CACHE')){
            // æ°¸ä¹…ç¼“å­˜æ•°æ®è¡¨ä¿¡æ¯
            $“„ˆÓ’ã   =  $this->dbName?:C('DB_NAME');
            F('_fields/'.strtolower($“„ˆÓ’ã.'.'.$this->tablePrefix.$this->name),$this->fields);
        }
    }

    /**
     * è®¾ç½®æ•°æ®å¯¹è±¡çš„å€¼
     * @access public
     * @param string $name åç§°
     * @param mixed $value å€¼
     * @return void
     */
    public function __set($–Àêçœ­,$·Äõ) {
        // è®¾ç½®æ•°æ®å¯¹è±¡å±æ€§
        $this->data[$–Àêçœ­]  =   $·Äõ;
    }

    /**
     * è·å–æ•°æ®å¯¹è±¡çš„å€¼
     * @access public
     * @param string $name åç§°
     * @return mixed
     */
    public function __get($–Àêçœ­) {
        return isset($this->data[$–Àêçœ­])?$this->data[$–Àêçœ­]:null;
    }

    /**
     * æ£€æµ‹æ•°æ®å¯¹è±¡çš„å€¼
     * @access public
     * @param string $name åç§°
     * @return boolean
     */
    public function __isset($–Àêçœ­) {
        return isset($this->data[$–Àêçœ­]);
    }

    /**
     * é”€æ¯æ•°æ®å¯¹è±¡çš„å€¼
     * @access public
     * @param string $name åç§°
     * @return void
     */
    public function __unset($–Àêçœ­) {
        unset($this->data[$–Àêçœ­]);
    }

    /**
     * åˆ©ç”¨__callæ–¹æ³•å®ç°ä¸€äº›ç‰¹æ®Šçš„Modelæ–¹æ³•
     * @access public
     * @param string $method æ–¹æ³•åç§°
     * @param array $args è°ƒç”¨å‚æ•°
     * @return mixed
     */
    public function __call($·ÖĞ‘ÅÅ,$„€¼»ôğ) {
        if(in_array(strtolower($·ÖĞ‘ÅÅ),$this->methods,true)) {
            // è¿è´¯æ“ä½œçš„å®ç°
            $this->options[strtolower($·ÖĞ‘ÅÅ)] =   $„€¼»ôğ[0];
            return $this;
        }elseif(in_array(strtolower($·ÖĞ‘ÅÅ),array('count','sum','min','max','avg'),true)){
            // ç»Ÿè®¡æŸ¥è¯¢çš„å®ç°
            $š¿§Ğ°‡ =  isset($„€¼»ôğ[0])?$„€¼»ôğ[0]:'*';
            return $this->getField(strtoupper($·ÖĞ‘ÅÅ).'('.$š¿§Ğ°‡.') AS tp_'.$·ÖĞ‘ÅÅ);
        }elseif(strtolower(substr($·ÖĞ‘ÅÅ,0,5))=='getby') {
            // æ ¹æ®æŸä¸ªå­—æ®µè·å–è®°å½•
            $š¿§Ğ°‡   =   parse_name(substr($·ÖĞ‘ÅÅ,5));
            $ä°Ø¥¸[$š¿§Ğ°‡] =  $„€¼»ôğ[0];
            return $this->where($ä°Ø¥¸)->find();
        }elseif(strtolower(substr($·ÖĞ‘ÅÅ,0,10))=='getfieldby') {
            // æ ¹æ®æŸä¸ªå­—æ®µè·å–è®°å½•çš„æŸä¸ªå€¼
            $–Àêçœ­   =   parse_name(substr($·ÖĞ‘ÅÅ,10));
            $ä°Ø¥¸[$–Àêçœ­] =$„€¼»ôğ[0];
            return $this->where($ä°Ø¥¸)->getField($„€¼»ôğ[1]);
        }elseif(isset($this->_scope[$·ÖĞ‘ÅÅ])){// å‘½åèŒƒå›´çš„å•ç‹¬è°ƒç”¨æ”¯æŒ
            return $this->scope($·ÖĞ‘ÅÅ,$„€¼»ôğ[0]);
        }else{
            E(__CLASS__.':'.$·ÖĞ‘ÅÅ.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }
    // å›è°ƒæ–¹æ³• åˆå§‹åŒ–æ¨¡å‹
    protected function _initialize() {}

    /**
     * å¯¹ä¿å­˜åˆ°æ•°æ®åº“çš„æ•°æ®è¿›è¡Œå¤„ç†
     * @access protected
     * @param mixed $data è¦æ“ä½œçš„æ•°æ®
     * @return boolean
     */
     protected function _facade($Íúşş‹×) {

        // æ£€æŸ¥æ•°æ®å­—æ®µåˆæ³•æ€§
        if(!empty($this->fields)) {
            if(!empty($this->options['field'])) {
                $£¬ÂÂõê =   $this->options['field'];
                unset($this->options['field']);
                if(is_string($£¬ÂÂõê)) {
                    $£¬ÂÂõê =   explode(',',$£¬ÂÂõê);
                }    
            }else{
                $£¬ÂÂõê =   $this->fields;
            }        
            foreach ($Íúşş‹× as $…êâò«=>$Ùãë²‡¿){
                if(!in_array($…êâò«,$£¬ÂÂõê,true)){
                    if(APP_DEBUG){
                        E(L('_DATA_TYPE_INVALID_').':['.$…êâò«.'=>'.$Ùãë²‡¿.']');
                    }                    
                    unset($Íúşş‹×[$…êâò«]);
                }elseif(is_scalar($Ùãë²‡¿)) {
                    // å­—æ®µç±»å‹æ£€æŸ¥ å’Œ å¼ºåˆ¶è½¬æ¢
                    $this->_parseType($Íúşş‹×,$…êâò«);
                }
            }
        }
       
        // å®‰å…¨è¿‡æ»¤
        if(!empty($this->options['filter'])) {
            $Íúşş‹× = array_map($this->options['filter'],$Íúşş‹×);
            unset($this->options['filter']);
        }
        $this->_before_write($Íúşş‹×);
        return $Íúşş‹×;
     }

    // å†™å…¥æ•°æ®å‰çš„å›è°ƒæ–¹æ³• åŒ…æ‹¬æ–°å¢å’Œæ›´æ–°
    protected function _before_write(&$Íúşş‹×) {}

    /**
     * æ–°å¢æ•°æ®
     * @access public
     * @param mixed $data æ•°æ®
     * @param array $options è¡¨è¾¾å¼
     * @param boolean $replace æ˜¯å¦replace
     * @return mixed
     */
    public function add($Íúşş‹×='',$ëÉóèÉ³=array(),$ØÅôúå‡=false) {
        if(empty($Íúşş‹×)) {
            // æ²¡æœ‰ä¼ é€’æ•°æ®ï¼Œè·å–å½“å‰æ•°æ®å¯¹è±¡çš„å€¼
            if(!empty($this->data)) {
                $Íúşş‹×           =   $this->data;
                // é‡ç½®æ•°æ®
                $this->data     = array();
            }else{
                $this->error    = L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³    =   $this->_parseOptions($ëÉóèÉ³);
        // æ•°æ®å¤„ç†
        $Íúşş‹×       =   $this->_facade($Íúşş‹×);
        if(false === $this->_before_insert($Íúşş‹×,$ëÉóèÉ³)) {
            return false;
        }
        // å†™å…¥æ•°æ®åˆ°æ•°æ®åº“
        $èß÷—Æ = $this->db->insert($Íúşş‹×,$ëÉóèÉ³,$ØÅôúå‡);
        if(false !== $èß÷—Æ ) {
            $ùÊÓ×¥Î   =   $this->getLastInsID();
            if($ùÊÓ×¥Î) {
                // è‡ªå¢ä¸»é”®è¿”å›æ’å…¥ID
                $Íúşş‹×[$this->getPk()]  = $ùÊÓ×¥Î;
                if(false === $this->_after_insert($Íúşş‹×,$ëÉóèÉ³)) {
                    return false;
                }
                return $ùÊÓ×¥Î;
            }
            if(false === $this->_after_insert($Íúşş‹×,$ëÉóèÉ³)) {
                return false;
            }
        }
        return $èß÷—Æ;
    }
    // æ’å…¥æ•°æ®å‰çš„å›è°ƒæ–¹æ³•
    protected function _before_insert(&$Íúşş‹×,$ëÉóèÉ³) {}
    // æ’å…¥æˆåŠŸåçš„å›è°ƒæ–¹æ³•
    protected function _after_insert($Íúşş‹×,$ëÉóèÉ³) {}

    public function addAll($éÁ­ÉùÈ,$ëÉóèÉ³=array(),$ØÅôúå‡=false){
        if(empty($éÁ­ÉùÈ)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³ =  $this->_parseOptions($ëÉóèÉ³);
        // æ•°æ®å¤„ç†
        foreach ($éÁ­ÉùÈ as $…êâò«=>$Íúşş‹×){
            $éÁ­ÉùÈ[$…êâò«] = $this->_facade($Íúşş‹×);
        }
        // å†™å…¥æ•°æ®åˆ°æ•°æ®åº“
        $èß÷—Æ = $this->db->insertAll($éÁ­ÉùÈ,$ëÉóèÉ³,$ØÅôúå‡);
        if(false !== $èß÷—Æ ) {
            $ùÊÓ×¥Î   =   $this->getLastInsID();
            if($ùÊÓ×¥Î) {
                return $ùÊÓ×¥Î;
            }
        }
        return $èß÷—Æ;
    }

    /**
     * é€šè¿‡Selectæ–¹å¼æ·»åŠ è®°å½•
     * @access public
     * @param string $fields è¦æ’å…¥çš„æ•°æ®è¡¨å­—æ®µå
     * @param string $table è¦æ’å…¥çš„æ•°æ®è¡¨å
     * @param array $options è¡¨è¾¾å¼
     * @return boolean
     */
    public function selectAdd($£¬ÂÂõê='',$ØÓÌªó¦='',$ëÉóèÉ³=array()) {
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³ =  $this->_parseOptions($ëÉóèÉ³);
        // å†™å…¥æ•°æ®åˆ°æ•°æ®åº“
        if(false === $èß÷—Æ = $this->db->selectInsert($£¬ÂÂõê?:$ëÉóèÉ³['field'],$ØÓÌªó¦?:$this->getTableName(),$ëÉóèÉ³)){
            // æ•°æ®åº“æ’å…¥æ“ä½œå¤±è´¥
            $this->error = L('_OPERATION_WRONG_');
            return false;
        }else {
            // æ’å…¥æˆåŠŸ
            return $èß÷—Æ;
        }
    }

    /**
     * ä¿å­˜æ•°æ®
     * @access public
     * @param mixed $data æ•°æ®
     * @param array $options è¡¨è¾¾å¼
     * @return boolean
     */
    public function save($Íúşş‹×='',$ëÉóèÉ³=array()) {
        if(empty($Íúşş‹×)) {
            // æ²¡æœ‰ä¼ é€’æ•°æ®ï¼Œè·å–å½“å‰æ•°æ®å¯¹è±¡çš„å€¼
            if(!empty($this->data)) {
                $Íúşş‹×           =   $this->data;
                // é‡ç½®æ•°æ®
                $this->data     =   array();
            }else{
                $this->error    =   L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // æ•°æ®å¤„ç†
        $Íúşş‹×       =   $this->_facade($Íúşş‹×);
        if(empty($Íúşş‹×)){
            // æ²¡æœ‰æ•°æ®åˆ™ä¸æ‰§è¡Œ
            $this->error    =   L('_DATA_TYPE_INVALID_');
            return false;
        }
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³    =   $this->_parseOptions($ëÉóèÉ³);
        $Ò¿µãöæ         =   $this->getPk();
        if(!isset($ëÉóèÉ³['where']) ) {
            // å¦‚æœå­˜åœ¨ä¸»é”®æ•°æ® åˆ™è‡ªåŠ¨ä½œä¸ºæ›´æ–°æ¡ä»¶
            if(isset($Íúşş‹×[$Ò¿µãöæ])) {
                $ä°Ø¥¸[$Ò¿µãöæ]         =   $Íúşş‹×[$Ò¿µãöæ];
                $ëÉóèÉ³['where']   =   $ä°Ø¥¸;
                unset($Íúşş‹×[$Ò¿µãöæ]);
            }else{
                // å¦‚æœæ²¡æœ‰ä»»ä½•æ›´æ–°æ¡ä»¶åˆ™ä¸æ‰§è¡Œ
                $this->error        =   L('_OPERATION_WRONG_');
                return false;
            }
        }
        if(is_array($ëÉóèÉ³['where']) && isset($ëÉóèÉ³['where'][$Ò¿µãöæ])){
            $Ò‹ÀÚ°Ö    =   $ëÉóèÉ³['where'][$Ò¿µãöæ];
        }        
        if(false === $this->_before_update($Íúşş‹×,$ëÉóèÉ³)) {
            return false;
        }        
        $èß÷—Æ     =   $this->db->update($Íúşş‹×,$ëÉóèÉ³);
        if(false !== $èß÷—Æ) {
            if(isset($Ò‹ÀÚ°Ö)) $Íúşş‹×[$Ò¿µãöæ]   =  $Ò‹ÀÚ°Ö;
            $this->_after_update($Íúşş‹×,$ëÉóèÉ³);
        }
        return $èß÷—Æ;
    }
    // æ›´æ–°æ•°æ®å‰çš„å›è°ƒæ–¹æ³•
    protected function _before_update(&$Íúşş‹×,$ëÉóèÉ³) {}
    // æ›´æ–°æˆåŠŸåçš„å›è°ƒæ–¹æ³•
    protected function _after_update($Íúşş‹×,$ëÉóèÉ³) {}

    /**
     * åˆ é™¤æ•°æ®
     * @access public
     * @param mixed $options è¡¨è¾¾å¼
     * @return mixed
     */
    public function delete($ëÉóèÉ³=array()) {
        if(empty($ëÉóèÉ³) && empty($this->options['where'])) {
            // å¦‚æœåˆ é™¤æ¡ä»¶ä¸ºç©º åˆ™åˆ é™¤å½“å‰æ•°æ®å¯¹è±¡æ‰€å¯¹åº”çš„è®°å½•
            if(!empty($this->data) && isset($this->data[$this->getPk()]))
                return $this->delete($this->data[$this->getPk()]);
            else
                return false;
        }
        $Ò¿µãöæ   =  $this->getPk();
        if(is_numeric($ëÉóèÉ³)  || is_string($ëÉóèÉ³)) {
            // æ ¹æ®ä¸»é”®åˆ é™¤è®°å½•
            if(strpos($ëÉóèÉ³,',')) {
                $ä°Ø¥¸[$Ò¿µãöæ]     =  array('IN', $ëÉóèÉ³);
            }else{
                $ä°Ø¥¸[$Ò¿µãöæ]     =  $ëÉóèÉ³;
            }
            $ëÉóèÉ³            =  array();
            $ëÉóèÉ³['where']   =  $ä°Ø¥¸;
        }
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³ =  $this->_parseOptions($ëÉóèÉ³);
        if(empty($ëÉóèÉ³['where'])){
            // å¦‚æœæ¡ä»¶ä¸ºç©º ä¸è¿›è¡Œåˆ é™¤æ“ä½œ é™¤éè®¾ç½® 1=1
            return false;
        }        
        if(is_array($ëÉóèÉ³['where']) && isset($ëÉóèÉ³['where'][$Ò¿µãöæ])){
            $Ò‹ÀÚ°Ö            =  $ëÉóèÉ³['where'][$Ò¿µãöæ];
        }

        if(false === $this->_before_delete($ëÉóèÉ³)) {
            return false;
        }        
        $èß÷—Æ  =    $this->db->delete($ëÉóèÉ³);
        if(false !== $èß÷—Æ) {
            $Íúşş‹× = array();
            if(isset($Ò‹ÀÚ°Ö)) $Íúşş‹×[$Ò¿µãöæ]   =  $Ò‹ÀÚ°Ö;
            $this->_after_delete($Íúşş‹×,$ëÉóèÉ³);
        }
        // è¿”å›åˆ é™¤è®°å½•ä¸ªæ•°
        return $èß÷—Æ;
    }
    // åˆ é™¤æ•°æ®å‰çš„å›è°ƒæ–¹æ³•
    protected function _before_delete($ëÉóèÉ³) {}    
    // åˆ é™¤æˆåŠŸåçš„å›è°ƒæ–¹æ³•
    protected function _after_delete($Íúşş‹×,$ëÉóèÉ³) {}

    /**
     * æŸ¥è¯¢æ•°æ®é›†
     * @access public
     * @param array $options è¡¨è¾¾å¼å‚æ•°
     * @return mixed
     */
    public function select($ëÉóèÉ³=array()) {
        if(is_string($ëÉóèÉ³) || is_numeric($ëÉóèÉ³)) {
            // æ ¹æ®ä¸»é”®æŸ¥è¯¢
            $Ò¿µãöæ   =  $this->getPk();
            if(strpos($ëÉóèÉ³,',')) {
                $ä°Ø¥¸[$Ò¿µãöæ]     =  array('IN',$ëÉóèÉ³);
            }else{
                $ä°Ø¥¸[$Ò¿µãöæ]     =  $ëÉóèÉ³;
            }
            $ëÉóèÉ³            =  array();
            $ëÉóèÉ³['where']   =  $ä°Ø¥¸;
        }elseif(false === $ëÉóèÉ³){ // ç”¨äºå­æŸ¥è¯¢ ä¸æŸ¥è¯¢åªè¿”å›SQL
            $ëÉóèÉ³            =  array();
            // åˆ†æè¡¨è¾¾å¼
            $ëÉóèÉ³            =  $this->_parseOptions($ëÉóèÉ³);
            return  '( '.$this->db->buildSelectSql($ëÉóèÉ³).' )';
        }
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³    =  $this->_parseOptions($ëÉóèÉ³);
        // åˆ¤æ–­æŸ¥è¯¢ç¼“å­˜
        if(isset($ëÉóèÉ³['cache'])){
            $Ê¸ø¢¥õ  =   $ëÉóèÉ³['cache'];
            $…êâò«    =   is_string($Ê¸ø¢¥õ['key'])?$Ê¸ø¢¥õ['key']:md5(serialize($ëÉóèÉ³));
            $Íúşş‹×   =   S($…êâò«,'',$Ê¸ø¢¥õ);
            if(false !== $Íúşş‹×){
                return $Íúşş‹×;
            }
        }        
        $òô³èÓÉ  = $this->db->select($ëÉóèÉ³);
        if(false === $òô³èÓÉ) {
            return false;
        }
        if(empty($òô³èÓÉ)) { // æŸ¥è¯¢ç»“æœä¸ºç©º
            return null;
        }
        $òô³èÓÉ  =   array_map(array($this,'_read_data'),$òô³èÓÉ);
        $this->_after_select($òô³èÓÉ,$ëÉóèÉ³);
        if(isset($ëÉóèÉ³['index'])){ // å¯¹æ•°æ®é›†è¿›è¡Œç´¢å¼•
            $„ıˆÍ¹ˆ  =   explode(',',$ëÉóèÉ³['index']);
            foreach ($òô³èÓÉ as $èß÷—Æ){
                $´ „¼ Ÿ   =  $èß÷—Æ[$„ıˆÍ¹ˆ[0]];
                if(isset($„ıˆÍ¹ˆ[1]) && isset($èß÷—Æ[$„ıˆÍ¹ˆ[1]])){
                    $Ñ¢Î€Í[$´ „¼ Ÿ] =  $èß÷—Æ[$„ıˆÍ¹ˆ[1]];
                }else{
                    $Ñ¢Î€Í[$´ „¼ Ÿ] =  $èß÷—Æ;
                }
            }
            $òô³èÓÉ  =   $Ñ¢Î€Í;         
        }
        if(isset($Ê¸ø¢¥õ)){
            S($…êâò«,$òô³èÓÉ,$Ê¸ø¢¥õ);
        }           
        return $òô³èÓÉ;
    }
    // æŸ¥è¯¢æˆåŠŸåçš„å›è°ƒæ–¹æ³•
    protected function _after_select(&$òô³èÓÉ,$ëÉóèÉ³) {}

    /**
     * ç”ŸæˆæŸ¥è¯¢SQL å¯ç”¨äºå­æŸ¥è¯¢
     * @access public
     * @param array $options è¡¨è¾¾å¼å‚æ•°
     * @return string
     */
    public function buildSql($ëÉóèÉ³=array()) {
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³ =  $this->_parseOptions($ëÉóèÉ³);
        return  '( '.$this->db->buildSelectSql($ëÉóèÉ³).' )';
    }

    /**
     * åˆ†æè¡¨è¾¾å¼
     * @access protected
     * @param array $options è¡¨è¾¾å¼å‚æ•°
     * @return array
     */
    protected function _parseOptions($ëÉóèÉ³=array()) {
        if(is_array($ëÉóèÉ³))
            $ëÉóèÉ³ =  array_merge($this->options,$ëÉóèÉ³);

        if(!isset($ëÉóèÉ³['table'])){
            // è‡ªåŠ¨è·å–è¡¨å
            $ëÉóèÉ³['table']   =   $this->getTableName();
            $£¬ÂÂõê             =   $this->fields;
        }else{
            // æŒ‡å®šæ•°æ®è¡¨ åˆ™é‡æ–°è·å–å­—æ®µåˆ—è¡¨ ä½†ä¸æ”¯æŒç±»å‹æ£€æµ‹
            $£¬ÂÂõê             =   $this->getDbFields();
        }

        // æ•°æ®è¡¨åˆ«å
        if(!empty($ëÉóèÉ³['alias'])) {
            $ëÉóèÉ³['table']  .=   ' '.$ëÉóèÉ³['alias'];
        }
        // è®°å½•æ“ä½œçš„æ¨¡å‹åç§°
        $ëÉóèÉ³['model']       =   $this->name;

        // å­—æ®µç±»å‹éªŒè¯
        if(isset($ëÉóèÉ³['where']) && is_array($ëÉóèÉ³['where']) && !empty($£¬ÂÂõê) && !isset($ëÉóèÉ³['join'])) {
            // å¯¹æ•°ç»„æŸ¥è¯¢æ¡ä»¶è¿›è¡Œå­—æ®µç±»å‹æ£€æŸ¥
            foreach ($ëÉóèÉ³['where'] as $…êâò«=>$Ùãë²‡¿){
                $…êâò«            =   trim($…êâò«);
                if(in_array($…êâò«,$£¬ÂÂõê,true)){
                    if(is_scalar($Ùãë²‡¿)) {
                        $this->_parseType($ëÉóèÉ³['where'],$…êâò«);
                    }
                }elseif(!is_numeric($…êâò«) && '_' != substr($…êâò«,0,1) && false === strpos($…êâò«,'.') && false === strpos($…êâò«,'(') && false === strpos($…êâò«,'|') && false === strpos($…êâò«,'&')){
                    if(APP_DEBUG){
                        E(L('_ERROR_QUERY_EXPRESS_').':['.$…êâò«.'=>'.$Ùãë²‡¿.']');
                    } 
                    unset($ëÉóèÉ³['where'][$…êâò«]);
                }
            }
        }
        // æŸ¥è¯¢è¿‡åæ¸…ç©ºsqlè¡¨è¾¾å¼ç»„è£… é¿å…å½±å“ä¸‹æ¬¡æŸ¥è¯¢
        $this->options  =   array();
        // è¡¨è¾¾å¼è¿‡æ»¤
        $this->_options_filter($ëÉóèÉ³);
        return $ëÉóèÉ³;
    }
    // è¡¨è¾¾å¼è¿‡æ»¤å›è°ƒæ–¹æ³•
    protected function _options_filter(&$ëÉóèÉ³) {}

    /**
     * æ•°æ®ç±»å‹æ£€æµ‹
     * @access protected
     * @param mixed $data æ•°æ®
     * @param string $key å­—æ®µå
     * @return void
     */
    protected function _parseType(&$Íúşş‹×,$…êâò«) {
        if(!isset($this->options['bind'][':'.$…êâò«]) && isset($this->fields['_type'][$…êâò«])){
            $„‚Ñ¸‹Ñ = strtolower($this->fields['_type'][$…êâò«]);
            if(false !== strpos($„‚Ñ¸‹Ñ,'enum')){
                // æ”¯æŒENUMç±»å‹ä¼˜å…ˆæ£€æµ‹
            }elseif(false === strpos($„‚Ñ¸‹Ñ,'bigint') && false !== strpos($„‚Ñ¸‹Ñ,'int')) {
                $Íúşş‹×[$…êâò«]   =  intval($Íúşş‹×[$…êâò«]);
            }elseif(false !== strpos($„‚Ñ¸‹Ñ,'float') || false !== strpos($„‚Ñ¸‹Ñ,'double')){
                $Íúşş‹×[$…êâò«]   =  floatval($Íúşş‹×[$…êâò«]);
            }elseif(false !== strpos($„‚Ñ¸‹Ñ,'bool')){
                $Íúşş‹×[$…êâò«]   =  (bool)$Íúşş‹×[$…êâò«];
            }
        }
    }

    /**
     * æ•°æ®è¯»å–åçš„å¤„ç†
     * @access protected
     * @param array $data å½“å‰æ•°æ®
     * @return array
     */
    protected function _read_data($Íúşş‹×) {
        // æ£€æŸ¥å­—æ®µæ˜ å°„
        if(!empty($this->_map) && C('READ_DATA_MAP')) {
            foreach ($this->_map as $…êâò«=>$Ùãë²‡¿){
                if(isset($Íúşş‹×[$Ùãë²‡¿])) {
                    $Íúşş‹×[$…êâò«] =   $Íúşş‹×[$Ùãë²‡¿];
                    unset($Íúşş‹×[$Ùãë²‡¿]);
                }
            }
        }
        return $Íúşş‹×;
    }

    /**
     * æŸ¥è¯¢æ•°æ®
     * @access public
     * @param mixed $options è¡¨è¾¾å¼å‚æ•°
     * @return mixed
     */
    public function find($ëÉóèÉ³=array()) {
        if(is_numeric($ëÉóèÉ³) || is_string($ëÉóèÉ³)) {
            $ä°Ø¥¸[$this->getPk()]  =   $ëÉóèÉ³;
            $ëÉóèÉ³                =   array();
            $ëÉóèÉ³['where']       =   $ä°Ø¥¸;
        }
        // æ€»æ˜¯æŸ¥æ‰¾ä¸€æ¡è®°å½•
        $ëÉóèÉ³['limit']   =   1;
        // åˆ†æè¡¨è¾¾å¼
        $ëÉóèÉ³            =   $this->_parseOptions($ëÉóèÉ³);
        // åˆ¤æ–­æŸ¥è¯¢ç¼“å­˜
        if(isset($ëÉóèÉ³['cache'])){
            $Ê¸ø¢¥õ  =   $ëÉóèÉ³['cache'];
            $…êâò«    =   is_string($Ê¸ø¢¥õ['key'])?$Ê¸ø¢¥õ['key']:md5(serialize($ëÉóèÉ³));
            $Íúşş‹×   =   S($…êâò«,'',$Ê¸ø¢¥õ);
            if(false !== $Íúşş‹×){
                $this->data     =   $Íúşş‹×;
                return $Íúşş‹×;
            }
        }
        $òô³èÓÉ          =   $this->db->select($ëÉóèÉ³);
        if(false === $òô³èÓÉ) {
            return false;
        }
        if(empty($òô³èÓÉ)) {// æŸ¥è¯¢ç»“æœä¸ºç©º
            return null;
        }
        // è¯»å–æ•°æ®åçš„å¤„ç†
        $Íúşş‹×   =   $this->_read_data($òô³èÓÉ[0]);
        $this->_after_find($Íúşş‹×,$ëÉóèÉ³);
        if(!empty($this->options['result'])) {
            return $this->returnResult($Íúşş‹×,$this->options['result']);
        }
        $this->data     =   $Íúşş‹×;
        if(isset($Ê¸ø¢¥õ)){
            S($…êâò«,$Íúşş‹×,$Ê¸ø¢¥õ);
        }
        return $this->data;
    }
    // æŸ¥è¯¢æˆåŠŸçš„å›è°ƒæ–¹æ³•
    protected function _after_find(&$èß÷—Æ,$ëÉóèÉ³) {}

    protected function returnResult($Íúşş‹×,$ÕÃ“ßÏ¦=''){
        if ($ÕÃ“ßÏ¦){
            if(is_callable($ÕÃ“ßÏ¦)){
                return call_user_func($ÕÃ“ßÏ¦,$Íúşş‹×);
            }
            switch (strtolower($ÕÃ“ßÏ¦)){
                case 'json':
                    return json_encode($Íúşş‹×);
                case 'xml':
                    return xml_encode($Íúşş‹×);
            }
        }
        return $Íúşş‹×;
    }

    /**
     * å¤„ç†å­—æ®µæ˜ å°„
     * @access public
     * @param array $data å½“å‰æ•°æ®
     * @param integer $type ç±»å‹ 0 å†™å…¥ 1 è¯»å–
     * @return array
     */
    public function parseFieldsMap($Íúşş‹×,$ÕÃ“ßÏ¦=1) {
        // æ£€æŸ¥å­—æ®µæ˜ å°„
        if(!empty($this->_map)) {
            foreach ($this->_map as $…êâò«=>$Ùãë²‡¿){
                if($ÕÃ“ßÏ¦==1) { // è¯»å–
                    if(isset($Íúşş‹×[$Ùãë²‡¿])) {
                        $Íúşş‹×[$…êâò«] =   $Íúşş‹×[$Ùãë²‡¿];
                        unset($Íúşş‹×[$Ùãë²‡¿]);
                    }
                }else{
                    if(isset($Íúşş‹×[$…êâò«])) {
                        $Íúşş‹×[$Ùãë²‡¿] =   $Íúşş‹×[$…êâò«];
                        unset($Íúşş‹×[$…êâò«]);
                    }
                }
            }
        }
        return $Íúşş‹×;
    }

    /**
     * è®¾ç½®è®°å½•çš„æŸä¸ªå­—æ®µå€¼
     * æ”¯æŒä½¿ç”¨æ•°æ®åº“å­—æ®µå’Œæ–¹æ³•
     * @access public
     * @param string|array $field  å­—æ®µå
     * @param string $value  å­—æ®µå€¼
     * @return boolean
     */
    public function setField($š¿§Ğ°‡,$·Äõ='') {
        if(is_array($š¿§Ğ°‡)) {
            $Íúşş‹×           =   $š¿§Ğ°‡;
        }else{
            $Íúşş‹×[$š¿§Ğ°‡]   =   $·Äõ;
        }
        return $this->save($Íúşş‹×);
    }

    /**
     * å­—æ®µå€¼å¢é•¿
     * @access public
     * @param string $field  å­—æ®µå
     * @param integer $step  å¢é•¿å€¼
     * @return boolean
     */
    public function setInc($š¿§Ğ°‡,$„“°€‚Ø=1) {
        return $this->setField($š¿§Ğ°‡,array('exp',$š¿§Ğ°‡.'+'.$„“°€‚Ø));
    }

    /**
     * å­—æ®µå€¼å‡å°‘
     * @access public
     * @param string $field  å­—æ®µå
     * @param integer $step  å‡å°‘å€¼
     * @return boolean
     */
    public function setDec($š¿§Ğ°‡,$„“°€‚Ø=1) {
        return $this->setField($š¿§Ğ°‡,array('exp',$š¿§Ğ°‡.'-'.$„“°€‚Ø));
    }

    /**
     * è·å–ä¸€æ¡è®°å½•çš„æŸä¸ªå­—æ®µå€¼
     * @access public
     * @param string $field  å­—æ®µå
     * @param string $spea  å­—æ®µæ•°æ®é—´éš”ç¬¦å· NULLè¿”å›æ•°ç»„
     * @return mixed
     */
    public function getField($š¿§Ğ°‡,$¨ØšƒÖü=null) {
        $ëÉóèÉ³['field']       =   $š¿§Ğ°‡;
        $ëÉóèÉ³                =   $this->_parseOptions($ëÉóèÉ³);
        // åˆ¤æ–­æŸ¥è¯¢ç¼“å­˜
        if(isset($ëÉóèÉ³['cache'])){
            $Ê¸ø¢¥õ  =   $ëÉóèÉ³['cache'];
            $…êâò«    =   is_string($Ê¸ø¢¥õ['key'])?$Ê¸ø¢¥õ['key']:md5($¨ØšƒÖü.serialize($ëÉóèÉ³));
            $Íúşş‹×   =   S($…êâò«,'',$Ê¸ø¢¥õ);
            if(false !== $Íúşş‹×){
                return $Íúşş‹×;
            }
        }        
        $š¿§Ğ°‡                  =   trim($š¿§Ğ°‡);
        if(strpos($š¿§Ğ°‡,',') && false !== $¨ØšƒÖü) { // å¤šå­—æ®µ
            if(!isset($ëÉóèÉ³['limit'])){
                $ëÉóèÉ³['limit']   =   is_numeric($¨ØšƒÖü)?$¨ØšƒÖü:'';
            }
            $òô³èÓÉ          =   $this->db->select($ëÉóèÉ³);
            if(!empty($òô³èÓÉ)) {
                $Íá·¡ÔÆ         =   explode(',', $š¿§Ğ°‡);
                $š¿§Ğ°‡          =   array_keys($òô³èÓÉ[0]);
                $…êâò«            =   array_shift($š¿§Ğ°‡);
                $¶ææ¦õø           =   array_shift($š¿§Ğ°‡);
                $Ñ¢Î€Í           =   array();
                $‚Öµğ´°          =   count($Íá·¡ÔÆ);
                foreach ($òô³èÓÉ as $èß÷—Æ){
                    $–Àêçœ­   =  $èß÷—Æ[$…êâò«];
                    if(2==$‚Öµğ´°) {
                        $Ñ¢Î€Í[$–Àêçœ­]   =  $èß÷—Æ[$¶ææ¦õø];
                    }else{
                        $Ñ¢Î€Í[$–Àêçœ­]   =  is_string($¨ØšƒÖü)?implode($¨ØšƒÖü,array_slice($èß÷—Æ,1)):$èß÷—Æ;
                    }
                }
                if(isset($Ê¸ø¢¥õ)){
                    S($…êâò«,$Ñ¢Î€Í,$Ê¸ø¢¥õ);
                }
                return $Ñ¢Î€Í;
            }
        }else{   // æŸ¥æ‰¾ä¸€æ¡è®°å½•
            // è¿”å›æ•°æ®ä¸ªæ•°
            if(true !== $¨ØšƒÖü) {// å½“sepaæŒ‡å®šä¸ºtrueçš„æ—¶å€™ è¿”å›æ‰€æœ‰æ•°æ®
                $ëÉóèÉ³['limit']   =   is_numeric($¨ØšƒÖü)?$¨ØšƒÖü:1;
            }
            $èß÷—Æ = $this->db->select($ëÉóèÉ³);
            if(!empty($èß÷—Æ)) {
                if(true !== $¨ØšƒÖü && 1==$ëÉóèÉ³['limit']) {
                    $Íúşş‹×   =   reset($èß÷—Æ[0]);
                    if(isset($Ê¸ø¢¥õ)){
                        S($…êâò«,$Íúşş‹×,$Ê¸ø¢¥õ);
                    }            
                    return $Íúşş‹×;
                }
                foreach ($èß÷—Æ as $Ùãë²‡¿){
                    $ØÊ’ğ¬Å[]    =   $Ùãë²‡¿[$š¿§Ğ°‡];
                }
                if(isset($Ê¸ø¢¥õ)){
                    S($…êâò«,$ØÊ’ğ¬Å,$Ê¸ø¢¥õ);
                }                
                return $ØÊ’ğ¬Å;
            }
        }
        return null;
    }

    /**
     * åˆ›å»ºæ•°æ®å¯¹è±¡ ä½†ä¸ä¿å­˜åˆ°æ•°æ®åº“
     * @access public
     * @param mixed $data åˆ›å»ºæ•°æ®
     * @param string $type çŠ¶æ€
     * @return mixed
     */
     public function create($Íúşş‹×='',$ÕÃ“ßÏ¦='') {
        // å¦‚æœæ²¡æœ‰ä¼ å€¼é»˜è®¤å–POSTæ•°æ®
        if(empty($Íúşş‹×)) {
            $Íúşş‹×   =   I('post.');
        }elseif(is_object($Íúşş‹×)){
            $Íúşş‹×   =   get_object_vars($Íúşş‹×);
        }
        // éªŒè¯æ•°æ®
        if(empty($Íúşş‹×) || !is_array($Íúşş‹×)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }

        // çŠ¶æ€
        $ÕÃ“ßÏ¦ = $ÕÃ“ßÏ¦?:(!empty($Íúşş‹×[$this->getPk()])?self::MODEL_UPDATE:self::MODEL_INSERT);

        // æ£€æŸ¥å­—æ®µæ˜ å°„
        if(!empty($this->_map)) {
            foreach ($this->_map as $…êâò«=>$Ùãë²‡¿){
                if(isset($Íúşş‹×[$…êâò«])) {
                    $Íúşş‹×[$Ùãë²‡¿] =   $Íúşş‹×[$…êâò«];
                    unset($Íúşş‹×[$…êâò«]);
                }
            }
        }

        // æ£€æµ‹æäº¤å­—æ®µçš„åˆæ³•æ€§
        if(isset($this->options['field'])) { // $this->field('field1,field2...')->create()
            $£¬ÂÂõê =   $this->options['field'];
            unset($this->options['field']);
        }elseif($ÕÃ“ßÏ¦ == self::MODEL_INSERT && isset($this->insertFields)) {
            $£¬ÂÂõê =   $this->insertFields;
        }elseif($ÕÃ“ßÏ¦ == self::MODEL_UPDATE && isset($this->updateFields)) {
            $£¬ÂÂõê =   $this->updateFields;
        }
        if(isset($£¬ÂÂõê)) {
            if(is_string($£¬ÂÂõê)) {
                $£¬ÂÂõê =   explode(',',$£¬ÂÂõê);
            }
            // åˆ¤æ–­ä»¤ç‰ŒéªŒè¯å­—æ®µ
            if(C('TOKEN_ON'))   $£¬ÂÂõê[] = C('TOKEN_NAME');
            foreach ($Íúşş‹× as $…êâò«=>$Ùãë²‡¿){
                if(!in_array($…êâò«,$£¬ÂÂõê)) {
                    unset($Íúşş‹×[$…êâò«]);
                }
            }
        }

        // æ•°æ®è‡ªåŠ¨éªŒè¯
        if(!$this->autoValidation($Íúşş‹×,$ÕÃ“ßÏ¦)) return false;

        // è¡¨å•ä»¤ç‰ŒéªŒè¯
        if(!$this->autoCheckToken($Íúşş‹×)) {
            $this->error = L('_TOKEN_ERROR_');
            return false;
        }

        // éªŒè¯å®Œæˆç”Ÿæˆæ•°æ®å¯¹è±¡
        if($this->autoCheckFields) { // å¼€å¯å­—æ®µæ£€æµ‹ åˆ™è¿‡æ»¤éæ³•å­—æ®µæ•°æ®
            $£¬ÂÂõê =   $this->getDbFields();
            foreach ($Íúşş‹× as $…êâò«=>$Ùãë²‡¿){
                if(!in_array($…êâò«,$£¬ÂÂõê)) {
                    unset($Íúşş‹×[$…êâò«]);
                }elseif(MAGIC_QUOTES_GPC && is_string($Ùãë²‡¿)){
                    $Íúşş‹×[$…êâò«] =   stripslashes($Ùãë²‡¿);
                }
            }
        }

        // åˆ›å»ºå®Œæˆå¯¹æ•°æ®è¿›è¡Œè‡ªåŠ¨å¤„ç†
        $this->autoOperation($Íúşş‹×,$ÕÃ“ßÏ¦);
        // èµ‹å€¼å½“å‰æ•°æ®å¯¹è±¡
        $this->data =   $Íúşş‹×;
        // è¿”å›åˆ›å»ºçš„æ•°æ®ä»¥ä¾›å…¶ä»–è°ƒç”¨
        return $Íúşş‹×;
     }

    // è‡ªåŠ¨è¡¨å•ä»¤ç‰ŒéªŒè¯
    // TODO  ajaxæ— åˆ·æ–°å¤šæ¬¡æäº¤æš‚ä¸èƒ½æ»¡è¶³
    public function autoCheckToken($Íúşş‹×) {
        // æ”¯æŒä½¿ç”¨token(false) å…³é—­ä»¤ç‰ŒéªŒè¯
        if(isset($this->options['token']) && !$this->options['token']) return true;
        if(C('TOKEN_ON')){
            $–Àêçœ­   = C('TOKEN_NAME', null, '__hash__');
            if(!isset($Íúşş‹×[$–Àêçœ­]) || !isset($_SESSION[$–Àêçœ­])) { // ä»¤ç‰Œæ•°æ®æ— æ•ˆ
                return false;
            }

            // ä»¤ç‰ŒéªŒè¯
            list($…êâò«,$·Äõ)  =  explode('_',$Íúşş‹×[$–Àêçœ­]);
            if($·Äõ && $_SESSION[$–Àêçœ­][$…êâò«] === $·Äõ) { // é˜²æ­¢é‡å¤æäº¤
                unset($_SESSION[$–Àêçœ­][$…êâò«]); // éªŒè¯å®Œæˆé”€æ¯session
                return true;
            }
            // å¼€å¯TOKENé‡ç½®
            if(C('TOKEN_RESET')) unset($_SESSION[$–Àêçœ­][$…êâò«]);
            return false;
        }
        return true;
    }

    /**
     * ä½¿ç”¨æ­£åˆ™éªŒè¯æ•°æ®
     * @access public
     * @param string $value  è¦éªŒè¯çš„æ•°æ®
     * @param string $rule éªŒè¯è§„åˆ™
     * @return boolean
     */
    public function regex($·Äõ,$³Ş¢³ÙŞ) {
        $Ê²±à = array(
            'require'   =>  '/\S+/',
            'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency'  =>  '/^\d+(\.\d+)?$/',
            'number'    =>  '/^\d+$/',
            'zip'       =>  '/^\d{6}$/',
            'integer'   =>  '/^[-\+]?\d+$/',
            'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
            'english'   =>  '/^[A-Za-z]+$/',
        );
        // æ£€æŸ¥æ˜¯å¦æœ‰å†…ç½®çš„æ­£åˆ™è¡¨è¾¾å¼
        if(isset($Ê²±à[strtolower($³Ş¢³ÙŞ)]))
            $³Ş¢³ÙŞ       =   $Ê²±à[strtolower($³Ş¢³ÙŞ)];
        return preg_match($³Ş¢³ÙŞ,$·Äõ)===1;
    }

    /**
     * è‡ªåŠ¨è¡¨å•å¤„ç†
     * @access public
     * @param array $data åˆ›å»ºæ•°æ®
     * @param string $type åˆ›å»ºç±»å‹
     * @return mixed
     */
    private function autoOperation(&$Íúşş‹×,$ÕÃ“ßÏ¦) {
        if(!empty($this->options['auto'])) {
            $õ–½’§Ã   =   $this->options['auto'];
            unset($this->options['auto']);
        }elseif(!empty($this->_auto)){
            $õ–½’§Ã   =   $this->_auto;
        }
        // è‡ªåŠ¨å¡«å……
        if(isset($õ–½’§Ã)) {
            foreach ($õ–½’§Ã as $ç¡Í”Õˆ){
                // å¡«å……å› å­å®šä¹‰æ ¼å¼
                // array('field','å¡«å……å†…å®¹','å¡«å……æ¡ä»¶','é™„åŠ è§„åˆ™',[é¢å¤–å‚æ•°])
                if(empty($ç¡Í”Õˆ[2])) $ç¡Í”Õˆ[2] =  self::MODEL_INSERT; // é»˜è®¤ä¸ºæ–°å¢çš„æ—¶å€™è‡ªåŠ¨å¡«å……
                if( $ÕÃ“ßÏ¦ == $ç¡Í”Õˆ[2] || $ç¡Í”Õˆ[2] == self::MODEL_BOTH) {
                    if(empty($ç¡Í”Õˆ[3])) $ç¡Í”Õˆ[3] =  'string';
                    switch(trim($ç¡Í”Õˆ[3])) {
                        case 'function':    //  ä½¿ç”¨å‡½æ•°è¿›è¡Œå¡«å…… å­—æ®µçš„å€¼ä½œä¸ºå‚æ•°
                        case 'callback': // ä½¿ç”¨å›è°ƒæ–¹æ³•
                            $„€¼»ôğ = isset($ç¡Í”Õˆ[4])?(array)$ç¡Í”Õˆ[4]:array();
                            if(isset($Íúşş‹×[$ç¡Í”Õˆ[0]])) {
                                array_unshift($„€¼»ôğ,$Íúşş‹×[$ç¡Í”Õˆ[0]]);
                            }
                            if('function'==$ç¡Í”Õˆ[3]) {
                                $Íúşş‹×[$ç¡Í”Õˆ[0]]  = call_user_func_array($ç¡Í”Õˆ[1], $„€¼»ôğ);
                            }else{
                                $Íúşş‹×[$ç¡Í”Õˆ[0]]  =  call_user_func_array(array(&$this,$ç¡Í”Õˆ[1]), $„€¼»ôğ);
                            }
                            break;
                        case 'field':    // ç”¨å…¶å®ƒå­—æ®µçš„å€¼è¿›è¡Œå¡«å……
                            $Íúşş‹×[$ç¡Í”Õˆ[0]] = $Íúşş‹×[$ç¡Í”Õˆ[1]];
                            break;
                        case 'ignore': // ä¸ºç©ºå¿½ç•¥
                            if($ç¡Í”Õˆ[1]===$Íúşş‹×[$ç¡Í”Õˆ[0]])
                                unset($Íúşş‹×[$ç¡Í”Õˆ[0]]);
                            break;
                        case 'string':
                        default: // é»˜è®¤ä½œä¸ºå­—ç¬¦ä¸²å¡«å……
                            $Íúşş‹×[$ç¡Í”Õˆ[0]] = $ç¡Í”Õˆ[1];
                    }
                    if(isset($Íúşş‹×[$ç¡Í”Õˆ[0]]) && false === $Íúşş‹×[$ç¡Í”Õˆ[0]] )   unset($Íúşş‹×[$ç¡Í”Õˆ[0]]);
                }
            }
        }
        return $Íúşş‹×;
    }

    /**
     * è‡ªåŠ¨è¡¨å•éªŒè¯
     * @access protected
     * @param array $data åˆ›å»ºæ•°æ®
     * @param string $type åˆ›å»ºç±»å‹
     * @return boolean
     */
    protected function autoValidation($Íúşş‹×,$ÕÃ“ßÏ¦) {
        if(!empty($this->options['validate'])) {
            $Óê½–á   =   $this->options['validate'];
            unset($this->options['validate']);
        }elseif(!empty($this->_validate)){
            $Óê½–á   =   $this->_validate;
        }
        // å±æ€§éªŒè¯
        if(isset($Óê½–á)) { // å¦‚æœè®¾ç½®äº†æ•°æ®è‡ªåŠ¨éªŒè¯åˆ™è¿›è¡Œæ•°æ®éªŒè¯
            if($this->patchValidate) { // é‡ç½®éªŒè¯é”™è¯¯ä¿¡æ¯
                $this->error = array();
            }
            foreach($Óê½–á as $…êâò«=>$Ùãë²‡¿) {
                // éªŒè¯å› å­å®šä¹‰æ ¼å¼
                // array(field,rule,message,condition,type,when,params)
                // åˆ¤æ–­æ˜¯å¦éœ€è¦æ‰§è¡ŒéªŒè¯
                if(empty($Ùãë²‡¿[5]) || $Ùãë²‡¿[5]== self::MODEL_BOTH || $Ùãë²‡¿[5]== $ÕÃ“ßÏ¦ ) {
                    if(0==strpos($Ùãë²‡¿[2],'{%') && strpos($Ùãë²‡¿[2],'}'))
                        // æ”¯æŒæç¤ºä¿¡æ¯çš„å¤šè¯­è¨€ ä½¿ç”¨ {%è¯­è¨€å®šä¹‰} æ–¹å¼
                        $Ùãë²‡¿[2]  =  L(substr($Ùãë²‡¿[2],2,-1));
                    $Ùãë²‡¿[3]  =  isset($Ùãë²‡¿[3])?$Ùãë²‡¿[3]:self::EXISTS_VALIDATE;
                    $Ùãë²‡¿[4]  =  isset($Ùãë²‡¿[4])?$Ùãë²‡¿[4]:'regex';
                    // åˆ¤æ–­éªŒè¯æ¡ä»¶
                    switch($Ùãë²‡¿[3]) {
                        case self::MUST_VALIDATE:   // å¿…é¡»éªŒè¯ ä¸ç®¡è¡¨å•æ˜¯å¦æœ‰è®¾ç½®è¯¥å­—æ®µ
                            if(false === $this->_validationField($Íúşş‹×,$Ùãë²‡¿)) 
                                return false;
                            break;
                        case self::VALUE_VALIDATE:    // å€¼ä¸ä¸ºç©ºçš„æ—¶å€™æ‰éªŒè¯
                            if('' != trim($Íúşş‹×[$Ùãë²‡¿[0]]))
                                if(false === $this->_validationField($Íúşş‹×,$Ùãë²‡¿)) 
                                    return false;
                            break;
                        default:    // é»˜è®¤è¡¨å•å­˜åœ¨è¯¥å­—æ®µå°±éªŒè¯
                            if(isset($Íúşş‹×[$Ùãë²‡¿[0]]))
                                if(false === $this->_validationField($Íúşş‹×,$Ùãë²‡¿)) 
                                    return false;
                    }
                }
            }
            // æ‰¹é‡éªŒè¯çš„æ—¶å€™æœ€åè¿”å›é”™è¯¯
            if(!empty($this->error)) return false;
        }
        return true;
    }

    /**
     * éªŒè¯è¡¨å•å­—æ®µ æ”¯æŒæ‰¹é‡éªŒè¯
     * å¦‚æœæ‰¹é‡éªŒè¯è¿”å›é”™è¯¯çš„æ•°ç»„ä¿¡æ¯
     * @access protected
     * @param array $data åˆ›å»ºæ•°æ®
     * @param array $val éªŒè¯å› å­
     * @return boolean
     */
    protected function _validationField($Íúşş‹×,$Ùãë²‡¿) {
        if($this->patchValidate && isset($this->error[$Ùãë²‡¿[0]]))
            return ; //å½“å‰å­—æ®µå·²ç»æœ‰è§„åˆ™éªŒè¯æ²¡æœ‰é€šè¿‡
        if(false === $this->_validationFieldItem($Íúşş‹×,$Ùãë²‡¿)){
            if($this->patchValidate) {
                $this->error[$Ùãë²‡¿[0]]   =   $Ùãë²‡¿[2];
            }else{
                $this->error            =   $Ùãë²‡¿[2];
                return false;
            }
        }
        return ;
    }

    /**
     * æ ¹æ®éªŒè¯å› å­éªŒè¯å­—æ®µ
     * @access protected
     * @param array $data åˆ›å»ºæ•°æ®
     * @param array $val éªŒè¯å› å­
     * @return boolean
     */
    protected function _validationFieldItem($Íúşş‹×,$Ùãë²‡¿) {
        switch(strtolower(trim($Ùãë²‡¿[4]))) {
            case 'function':// ä½¿ç”¨å‡½æ•°è¿›è¡ŒéªŒè¯
            case 'callback':// è°ƒç”¨æ–¹æ³•è¿›è¡ŒéªŒè¯
                $„€¼»ôğ = isset($Ùãë²‡¿[6])?(array)$Ùãë²‡¿[6]:array();
                if(is_string($Ùãë²‡¿[0]) && strpos($Ùãë²‡¿[0], ','))
                    $Ùãë²‡¿[0] = explode(',', $Ùãë²‡¿[0]);
                if(is_array($Ùãë²‡¿[0])){
                    // æ”¯æŒå¤šä¸ªå­—æ®µéªŒè¯
                    foreach($Ùãë²‡¿[0] as $š¿§Ğ°‡)
                        $÷€¬Î—î[$š¿§Ğ°‡] = $Íúşş‹×[$š¿§Ğ°‡];
                    array_unshift($„€¼»ôğ, $÷€¬Î—î);
                }else{
                    array_unshift($„€¼»ôğ, $Íúşş‹×[$Ùãë²‡¿[0]]);
                }
                if('function'==$Ùãë²‡¿[4]) {
                    return call_user_func_array($Ùãë²‡¿[1], $„€¼»ôğ);
                }else{
                    return call_user_func_array(array(&$this, $Ùãë²‡¿[1]), $„€¼»ôğ);
                }
            case 'confirm': // éªŒè¯ä¸¤ä¸ªå­—æ®µæ˜¯å¦ç›¸åŒ
                return $Íúşş‹×[$Ùãë²‡¿[0]] == $Íúşş‹×[$Ùãë²‡¿[1]];
            case 'unique': // éªŒè¯æŸä¸ªå€¼æ˜¯å¦å”¯ä¸€
                if(is_string($Ùãë²‡¿[0]) && strpos($Ùãë²‡¿[0],','))
                    $Ùãë²‡¿[0]  =  explode(',',$Ùãë²‡¿[0]);
                $Éíüƒ§ = array();
                if(is_array($Ùãë²‡¿[0])) {
                    // æ”¯æŒå¤šä¸ªå­—æ®µéªŒè¯
                    foreach ($Ùãë²‡¿[0] as $š¿§Ğ°‡)
                        $Éíüƒ§[$š¿§Ğ°‡]   =  $Íúşş‹×[$š¿§Ğ°‡];
                }else{
                    $Éíüƒ§[$Ùãë²‡¿[0]] = $Íúşş‹×[$Ùãë²‡¿[0]];
                }
                if(!empty($Íúşş‹×[$this->getPk()])) { // å®Œå–„ç¼–è¾‘çš„æ—¶å€™éªŒè¯å”¯ä¸€
                    $Éíüƒ§[$this->getPk()] = array('neq',$Íúşş‹×[$this->getPk()]);
                }
                if($this->where($Éíüƒ§)->find())   return false;
                return true;
            default:  // æ£€æŸ¥é™„åŠ è§„åˆ™
                return $this->check($Íúşş‹×[$Ùãë²‡¿[0]],$Ùãë²‡¿[1],$Ùãë²‡¿[4]);
        }
    }

    /**
     * éªŒè¯æ•°æ® æ”¯æŒ in between equal length regex expire ip_allow ip_deny
     * @access public
     * @param string $value éªŒè¯æ•°æ®
     * @param mixed $rule éªŒè¯è¡¨è¾¾å¼
     * @param string $type éªŒè¯æ–¹å¼ é»˜è®¤ä¸ºæ­£åˆ™éªŒè¯
     * @return boolean
     */
    public function check($·Äõ,$³Ş¢³ÙŞ,$ÕÃ“ßÏ¦='regex'){
        $ÕÃ“ßÏ¦   =   strtolower(trim($ÕÃ“ßÏ¦));
        switch($ÕÃ“ßÏ¦) {
            case 'in': // éªŒè¯æ˜¯å¦åœ¨æŸä¸ªæŒ‡å®šèŒƒå›´ä¹‹å†… é€—å·åˆ†éš”å­—ç¬¦ä¸²æˆ–è€…æ•°ç»„
            case 'notin':
                $Òşéà»   = is_array($³Ş¢³ÙŞ)? $³Ş¢³ÙŞ : explode(',',$³Ş¢³ÙŞ);
                return $ÕÃ“ßÏ¦ == 'in' ? in_array($·Äõ ,$Òşéà») : !in_array($·Äõ ,$Òşéà»);
            case 'between': // éªŒè¯æ˜¯å¦åœ¨æŸä¸ªèŒƒå›´
            case 'notbetween': // éªŒè¯æ˜¯å¦ä¸åœ¨æŸä¸ªèŒƒå›´            
                if (is_array($³Ş¢³ÙŞ)){
                    $êæ¤Õ´‡    =    $³Ş¢³ÙŞ[0];
                    $Û•¾¥İ    =    $³Ş¢³ÙŞ[1];
                }else{
                    list($êæ¤Õ´‡,$Û•¾¥İ)   =  explode(',',$³Ş¢³ÙŞ);
                }
                return $ÕÃ“ßÏ¦ == 'between' ? $·Äõ>=$êæ¤Õ´‡ && $·Äõ<=$Û•¾¥İ : $·Äõ<$êæ¤Õ´‡ || $·Äõ>$Û•¾¥İ;
            case 'equal': // éªŒè¯æ˜¯å¦ç­‰äºæŸä¸ªå€¼
            case 'notequal': // éªŒè¯æ˜¯å¦ç­‰äºæŸä¸ªå€¼            
                return $ÕÃ“ßÏ¦ == 'equal' ? $·Äõ == $³Ş¢³ÙŞ : $·Äõ != $³Ş¢³ÙŞ;
            case 'length': // éªŒè¯é•¿åº¦
                $ÃêèüÌ¡  =  mb_strlen($·Äõ,'utf-8'); // å½“å‰æ•°æ®é•¿åº¦
                if(strpos($³Ş¢³ÙŞ,',')) { // é•¿åº¦åŒºé—´
                    list($êæ¤Õ´‡,$Û•¾¥İ)   =  explode(',',$³Ş¢³ÙŞ);
                    return $ÃêèüÌ¡ >= $êæ¤Õ´‡ && $ÃêèüÌ¡ <= $Û•¾¥İ;
                }else{// æŒ‡å®šé•¿åº¦
                    return $ÃêèüÌ¡ == $³Ş¢³ÙŞ;
                }
            case 'expire':
                list($»‹‡¤¬,$ô›Ãİ)   =  explode(',',$³Ş¢³ÙŞ);
                if(!is_numeric($»‹‡¤¬)) $»‹‡¤¬   =  strtotime($»‹‡¤¬);
                if(!is_numeric($ô›Ãİ)) $ô›Ãİ   =  strtotime($ô›Ãİ);
                return NOW_TIME >= $»‹‡¤¬ && NOW_TIME <= $ô›Ãİ;
            case 'ip_allow': // IP æ“ä½œè®¸å¯éªŒè¯
                return in_array(get_client_ip(),explode(',',$³Ş¢³ÙŞ));
            case 'ip_deny': // IP æ“ä½œç¦æ­¢éªŒè¯
                return !in_array(get_client_ip(),explode(',',$³Ş¢³ÙŞ));
            case 'regex':
            default:    // é»˜è®¤ä½¿ç”¨æ­£åˆ™éªŒè¯ å¯ä»¥ä½¿ç”¨éªŒè¯ç±»ä¸­å®šä¹‰çš„éªŒè¯åç§°
                // æ£€æŸ¥é™„åŠ è§„åˆ™
                return $this->regex($·Äõ,$³Ş¢³ÙŞ);
        }
    }

    /**
     * SQLæŸ¥è¯¢
     * @access public
     * @param string $sql  SQLæŒ‡ä»¤
     * @param mixed $parse  æ˜¯å¦éœ€è¦è§£æSQL
     * @return mixed
     */
    public function query($€™åËÈ,$˜Äíå›Î=false) {
        if(!is_bool($˜Äíå›Î) && !is_array($˜Äíå›Î)) {
            $˜Äíå›Î = func_get_args();
            array_shift($˜Äíå›Î);
        }
        $€™åËÈ  =   $this->parseSql($€™åËÈ,$˜Äíå›Î);
        return $this->db->query($€™åËÈ);
    }

    /**
     * æ‰§è¡ŒSQLè¯­å¥
     * @access public
     * @param string $sql  SQLæŒ‡ä»¤
     * @param mixed $parse  æ˜¯å¦éœ€è¦è§£æSQL
     * @return false | integer
     */
    public function execute($€™åËÈ,$˜Äíå›Î=false) {
        if(!is_bool($˜Äíå›Î) && !is_array($˜Äíå›Î)) {
            $˜Äíå›Î = func_get_args();
            array_shift($˜Äíå›Î);
        }
        $€™åËÈ  =   $this->parseSql($€™åËÈ,$˜Äíå›Î);
        return $this->db->execute($€™åËÈ);
    }

    /**
     * è§£æSQLè¯­å¥
     * @access public
     * @param string $sql  SQLæŒ‡ä»¤
     * @param boolean $parse  æ˜¯å¦éœ€è¦è§£æSQL
     * @return string
     */
    protected function parseSql($€™åËÈ,$˜Äíå›Î) {
        // åˆ†æè¡¨è¾¾å¼
        if(true === $˜Äíå›Î) {
            $ëÉóèÉ³ =  $this->_parseOptions();
            $€™åËÈ    =   $this->db->parseSql($€™åËÈ,$ëÉóèÉ³);
        }elseif(is_array($˜Äíå›Î)){ // SQLé¢„å¤„ç†
            $˜Äíå›Î  =   array_map(array($this->db,'escapeString'),$˜Äíå›Î);
            $€™åËÈ    =   vsprintf($€™åËÈ,$˜Äíå›Î);
        }else{
            $€™åËÈ    =   strtr($€™åËÈ,array('__TABLE__'=>$this->getTableName(),'__PREFIX__'=>$this->tablePrefix));
            $çì­Î«ú =   $this->tablePrefix;
            $€™åËÈ    =   preg_replace_callback("/__([A-Z_-]+)__/sU", function($’ã‘×¥ö) use($çì­Î«ú){ return $çì­Î«ú.strtolower($’ã‘×¥ö[1]);}, $€™åËÈ);
        }
        $this->db->setModel($this->name);
        return $€™åËÈ;
    }

    /**
     * åˆ‡æ¢å½“å‰çš„æ•°æ®åº“è¿æ¥
     * @access public
     * @param integer $linkNum  è¿æ¥åºå·
     * @param mixed $config  æ•°æ®åº“è¿æ¥ä¿¡æ¯
     * @param boolean $force å¼ºåˆ¶é‡æ–°è¿æ¥
     * @return Model
     */
    public function db($ú‡¢àÂ='',$®‘şİ·ù='',$¥óÙ€¶·=false) {
        if('' === $ú‡¢àÂ && $this->db) {
            return $this->db;
        }

        static $´¼®ªáº = array();
        if(!isset($´¼®ªáº[$ú‡¢àÂ]) || $¥óÙ€¶· ) {
            // åˆ›å»ºä¸€ä¸ªæ–°çš„å®ä¾‹
            if(!empty($®‘şİ·ù) && is_string($®‘şİ·ù) && false === strpos($®‘şİ·ù,'/')) { // æ”¯æŒè¯»å–é…ç½®å‚æ•°
                $®‘şİ·ù  =  C($®‘şİ·ù);
            }
            $´¼®ªáº[$ú‡¢àÂ]            =    Db::getInstance($®‘şİ·ù);
        }elseif(NULL === $®‘şİ·ù){
            $´¼®ªáº[$ú‡¢àÂ]->close(); // å…³é—­æ•°æ®åº“è¿æ¥
            unset($´¼®ªáº[$ú‡¢àÂ]);
            return ;
        }

        // åˆ‡æ¢æ•°æ®åº“è¿æ¥
        $this->db   =    $´¼®ªáº[$ú‡¢àÂ];
        $this->_after_db();
        // å­—æ®µæ£€æµ‹
        if(!empty($this->name) && $this->autoCheckFields)    $this->_checkTableInfo();
        return $this;
    }
    // æ•°æ®åº“åˆ‡æ¢åå›è°ƒæ–¹æ³•
    protected function _after_db() {}

    /**
     * å¾—åˆ°å½“å‰çš„æ•°æ®å¯¹è±¡åç§°
     * @access public
     * @return string
     */
    public function getModelName() {
        if(empty($this->name)){
            $–Àêçœ­ = substr(get_class($this),0,-strlen(C('DEFAULT_M_LAYER')));
            if ( $ÂÁß¬Ñ = strrpos($–Àêçœ­,'\\') ) {//æœ‰å‘½åç©ºé—´
                $this->name = substr($–Àêçœ­,$ÂÁß¬Ñ+1);
            }else{
                $this->name = $–Àêçœ­;
            }
        }
        return $this->name;
    }

    /**
     * å¾—åˆ°å®Œæ•´çš„æ•°æ®è¡¨å
     * @access public
     * @return string
     */
    public function getTableName() {
        if(empty($this->trueTableName)) {
            $…ª‰Ö™Š  = !empty($this->tablePrefix) ? $this->tablePrefix : '';
            if(!empty($this->tableName)) {
                $…ª‰Ö™Š .= $this->tableName;
            }else{
                $…ª‰Ö™Š .= parse_name($this->name);
            }
            $this->trueTableName    =   strtolower($…ª‰Ö™Š);
        }
        return (!empty($this->dbName)?$this->dbName.'.':'').$this->trueTableName;
    }

    /**
     * å¯åŠ¨äº‹åŠ¡
     * @access public
     * @return void
     */
    public function startTrans() {
        $this->commit();
        $this->db->startTrans();
        return ;
    }

    /**
     * æäº¤äº‹åŠ¡
     * @access public
     * @return boolean
     */
    public function commit() {
        return $this->db->commit();
    }

    /**
     * äº‹åŠ¡å›æ»š
     * @access public
     * @return boolean
     */
    public function rollback() {
        return $this->db->rollback();
    }

    /**
     * è¿”å›æ¨¡å‹çš„é”™è¯¯ä¿¡æ¯
     * @access public
     * @return string
     */
    public function getError(){
        return $this->error;
    }

    /**
     * è¿”å›æ•°æ®åº“çš„é”™è¯¯ä¿¡æ¯
     * @access public
     * @return string
     */
    public function getDbError() {
        return $this->db->getError();
    }

    /**
     * è¿”å›æœ€åæ’å…¥çš„ID
     * @access public
     * @return string
     */
    public function getLastInsID() {
        return $this->db->getLastInsID();
    }

    /**
     * è¿”å›æœ€åæ‰§è¡Œçš„sqlè¯­å¥
     * @access public
     * @return string
     */
    public function getLastSql() {
        return $this->db->getLastSql($this->name);
    }
    // é‰´äºgetLastSqlæ¯”è¾ƒå¸¸ç”¨ å¢åŠ _sql åˆ«å
    public function _sql(){
        return $this->getLastSql();
    }

    /**
     * è·å–ä¸»é”®åç§°
     * @access public
     * @return string
     */
    public function getPk() {
        return $this->pk;
    }

    /**
     * è·å–æ•°æ®è¡¨å­—æ®µä¿¡æ¯
     * @access public
     * @return array
     */
    public function getDbFields(){
        if(isset($this->options['table'])) {// åŠ¨æ€æŒ‡å®šè¡¨å
            $ØÊ’ğ¬Å      =   explode(' ',$this->options['table']);
            $£¬ÂÂõê     =   $this->db->getFields($ØÊ’ğ¬Å[0]);
            return  $£¬ÂÂõê?array_keys($£¬ÂÂõê):false;
        }
        if($this->fields) {
            $£¬ÂÂõê     =  $this->fields;
            unset($£¬ÂÂõê['_type'],$£¬ÂÂõê['_pk']);
            return $£¬ÂÂõê;
        }
        return false;
    }

    /**
     * è®¾ç½®æ•°æ®å¯¹è±¡å€¼
     * @access public
     * @param mixed $data æ•°æ®
     * @return Model
     */
    public function data($Íúşş‹×=''){
        if('' === $Íúşş‹× && !empty($this->data)) {
            return $this->data;
        }
        if(is_object($Íúşş‹×)){
            $Íúşş‹×   =   get_object_vars($Íúşş‹×);
        }elseif(is_string($Íúşş‹×)){
            parse_str($Íúşş‹×,$Íúşş‹×);
        }elseif(!is_array($Íúşş‹×)){
            E(L('_DATA_TYPE_INVALID_'));
        }
        $this->data = $Íúşş‹×;
        return $this;
    }

    /**
     * æŒ‡å®šå½“å‰çš„æ•°æ®è¡¨
     * @access public
     * @param mixed $table
     * @return Model
     */
    public function table($ØÓÌªó¦) {
        $çì­Î«ú =   $this->tablePrefix;
        if(is_array($ØÓÌªó¦)) {
            $this->options['table'] =   $ØÓÌªó¦;
        }elseif(!empty($ØÓÌªó¦)) {
            //å°†__TABLE_NAME__æ›¿æ¢æˆå¸¦å‰ç¼€çš„è¡¨å
            $ØÓÌªó¦  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($’ã‘×¥ö) use($çì­Î«ú){ return $çì­Î«ú.strtolower($’ã‘×¥ö[1]);}, $ØÓÌªó¦);
            $this->options['table'] =   $ØÓÌªó¦;
        }
        return $this;
    }

    /**
     * æŸ¥è¯¢SQLç»„è£… join
     * @access public
     * @param mixed $join
     * @param string $type JOINç±»å‹
     * @return Model
     */
    public function join($ƒÃŞşØÌ,$ÕÃ“ßÏ¦='INNER') {
        $çì­Î«ú =   $this->tablePrefix;
        if(is_array($ƒÃŞşØÌ)) {
            foreach ($ƒÃŞşØÌ as $…êâò«=>&$ùôó¥£){
                $ùôó¥£  =   preg_replace_callback("/__([A-Z_-]+)__/sU", function($’ã‘×¥ö) use($çì­Î«ú){ return $çì­Î«ú.strtolower($’ã‘×¥ö[1]);}, $ùôó¥£);
                $ùôó¥£  =   false !== stripos($ùôó¥£,'JOIN')? $ùôó¥£ : $ÕÃ“ßÏ¦.' JOIN ' .$ùôó¥£;
            }
            $this->options['join']      =   $ƒÃŞşØÌ;
        }elseif(!empty($ƒÃŞşØÌ)) {
            //å°†__TABLE_NAME__å­—ç¬¦ä¸²æ›¿æ¢æˆå¸¦å‰ç¼€çš„è¡¨å
            $ƒÃŞşØÌ  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($’ã‘×¥ö) use($çì­Î«ú){ return $çì­Î«ú.strtolower($’ã‘×¥ö[1]);}, $ƒÃŞşØÌ);
            $this->options['join'][]    =   false !== stripos($ƒÃŞşØÌ,'JOIN')? $ƒÃŞşØÌ : $ÕÃ“ßÏ¦.' JOIN '.$ƒÃŞşØÌ;
        }
        return $this;
    }

    /**
     * æŸ¥è¯¢SQLç»„è£… union
     * @access public
     * @param mixed $union
     * @param boolean $all
     * @return Model
     */
    public function union($ß¢á¨•,$î¾›Ñğî=false) {
        if(empty($ß¢á¨•)) return $this;
        if($î¾›Ñğî) {
            $this->options['union']['_all']  =   true;
        }
        if(is_object($ß¢á¨•)) {
            $ß¢á¨•   =  get_object_vars($ß¢á¨•);
        }
        // è½¬æ¢unionè¡¨è¾¾å¼
        if(is_string($ß¢á¨•) ) {
            $çì­Î«ú =   $this->tablePrefix;
            //å°†__TABLE_NAME__å­—ç¬¦ä¸²æ›¿æ¢æˆå¸¦å‰ç¼€çš„è¡¨å
            $ëÉóèÉ³  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($’ã‘×¥ö) use($çì­Î«ú){ return $çì­Î«ú.strtolower($’ã‘×¥ö[1]);}, $ß¢á¨•);
        }elseif(is_array($ß¢á¨•)){
            if(isset($ß¢á¨•[0])) {
                $this->options['union']  =  array_merge($this->options['union'],$ß¢á¨•);
                return $this;
            }else{
                $ëÉóèÉ³ =  $ß¢á¨•;
            }
        }else{
            E(L('_DATA_TYPE_INVALID_'));
        }
        $this->options['union'][]  =   $ëÉóèÉ³;
        return $this;
    }

    /**
     * æŸ¥è¯¢ç¼“å­˜
     * @access public
     * @param mixed $key
     * @param integer $expire
     * @param string $type
     * @return Model
     */
    public function cache($…êâò«=true,$½ã“»š=null,$ÕÃ“ßÏ¦=''){
        if(false !== $…êâò«)
            $this->options['cache']  =  array('key'=>$…êâò«,'expire'=>$½ã“»š,'type'=>$ÕÃ“ßÏ¦);
        return $this;
    }

    /**
     * æŒ‡å®šæŸ¥è¯¢å­—æ®µ æ”¯æŒå­—æ®µæ’é™¤
     * @access public
     * @param mixed $field
     * @param boolean $except æ˜¯å¦æ’é™¤
     * @return Model
     */
    public function field($š¿§Ğ°‡,$…üÕƒÙÎ=false){
        if(true === $š¿§Ğ°‡) {// è·å–å…¨éƒ¨å­—æ®µ
            $£¬ÂÂõê     =  $this->getDbFields();
            $š¿§Ğ°‡      =  $£¬ÂÂõê?:'*';
        }elseif($…üÕƒÙÎ) {// å­—æ®µæ’é™¤
            if(is_string($š¿§Ğ°‡)) {
                $š¿§Ğ°‡  =  explode(',',$š¿§Ğ°‡);
            }
            $£¬ÂÂõê     =  $this->getDbFields();
            $š¿§Ğ°‡      =  $£¬ÂÂõê?array_diff($£¬ÂÂõê,$š¿§Ğ°‡):$š¿§Ğ°‡;
        }
        $this->options['field']   =   $š¿§Ğ°‡;
        return $this;
    }

    /**
     * è°ƒç”¨å‘½åèŒƒå›´
     * @access public
     * @param mixed $scope å‘½åèŒƒå›´åç§° æ”¯æŒå¤šä¸ª å’Œç›´æ¥å®šä¹‰
     * @param array $args å‚æ•°
     * @return Model
     */
    public function scope($¼Œù¤Ç×='',$„€¼»ôğ=NULL){
        if('' === $¼Œù¤Ç×) {
            if(isset($this->_scope['default'])) {
                // é»˜è®¤çš„å‘½åèŒƒå›´
                $ëÉóèÉ³    =   $this->_scope['default'];
            }else{
                return $this;
            }
        }elseif(is_string($¼Œù¤Ç×)){ // æ”¯æŒå¤šä¸ªå‘½åèŒƒå›´è°ƒç”¨ ç”¨é€—å·åˆ†å‰²
            $›Ìãâ‘Ã         =   explode(',',$¼Œù¤Ç×);
            $ëÉóèÉ³        =   array();
            foreach ($›Ìãâ‘Ã as $–Àêçœ­){
                if(!isset($this->_scope[$–Àêçœ­])) continue;
                $ëÉóèÉ³    =   array_merge($ëÉóèÉ³,$this->_scope[$–Àêçœ­]);
            }
            if(!empty($„€¼»ôğ) && is_array($„€¼»ôğ)) {
                $ëÉóèÉ³    =   array_merge($ëÉóèÉ³,$„€¼»ôğ);
            }
        }elseif(is_array($¼Œù¤Ç×)){ // ç›´æ¥ä¼ å…¥å‘½åèŒƒå›´å®šä¹‰
            $ëÉóèÉ³        =   $¼Œù¤Ç×;
        }
        
        if(is_array($ëÉóèÉ³) && !empty($ëÉóèÉ³)){
            $this->options  =   array_merge($this->options,array_change_key_case($ëÉóèÉ³));
        }
        return $this;
    }

    /**
     * æŒ‡å®šæŸ¥è¯¢æ¡ä»¶ æ”¯æŒå®‰å…¨è¿‡æ»¤
     * @access public
     * @param mixed $where æ¡ä»¶è¡¨è¾¾å¼
     * @param mixed $parse é¢„å¤„ç†å‚æ•°
     * @return Model
     */
    public function where($ä°Ø¥¸,$˜Äíå›Î=null){
        if(!is_null($˜Äíå›Î) && is_string($ä°Ø¥¸)) {
            if(!is_array($˜Äíå›Î)) {
                $˜Äíå›Î = func_get_args();
                array_shift($˜Äíå›Î);
            }
            $˜Äíå›Î = array_map(array($this->db,'escapeString'),$˜Äíå›Î);
            $ä°Ø¥¸ =   vsprintf($ä°Ø¥¸,$˜Äíå›Î);
        }elseif(is_object($ä°Ø¥¸)){
            $ä°Ø¥¸  =   get_object_vars($ä°Ø¥¸);
        }
        if(is_string($ä°Ø¥¸) && '' != $ä°Ø¥¸){
            $Éíüƒ§    =   array();
            $Éíüƒ§['_string']   =   $ä°Ø¥¸;
            $ä°Ø¥¸  =   $Éíüƒ§;
        }        
        if(isset($this->options['where'])){
            $this->options['where'] =   array_merge($this->options['where'],$ä°Ø¥¸);
        }else{
            $this->options['where'] =   $ä°Ø¥¸;
        }
        
        return $this;
    }

    /**
     * æŒ‡å®šæŸ¥è¯¢æ•°é‡
     * @access public
     * @param mixed $offset èµ·å§‹ä½ç½®
     * @param mixed $length æŸ¥è¯¢æ•°é‡
     * @return Model
     */
    public function limit($ê££¥‚ü,$ÃêèüÌ¡=null){
        if(is_null($ÃêèüÌ¡) && strpos($ê££¥‚ü,',')){
            list($ê££¥‚ü,$ÃêèüÌ¡)   =   explode(',',$ê££¥‚ü);
        }
        $this->options['limit']     =   intval($ê££¥‚ü).( $ÃêèüÌ¡? ','.intval($ÃêèüÌ¡) : '' );
        return $this;
    }

    /**
     * æŒ‡å®šåˆ†é¡µ
     * @access public
     * @param mixed $page é¡µæ•°
     * @param mixed $listRows æ¯é¡µæ•°é‡
     * @return Model
     */
    public function page($Áİå ë‰,$é’´šÉ¬=null){
        if(is_null($é’´šÉ¬) && strpos($Áİå ë‰,',')){
            list($Áİå ë‰,$é’´šÉ¬)   =   explode(',',$Áİå ë‰);
        }
        $this->options['page']      =   array(intval($Áİå ë‰),intval($é’´šÉ¬));
        return $this;
    }

    /**
     * æŸ¥è¯¢æ³¨é‡Š
     * @access public
     * @param string $comment æ³¨é‡Š
     * @return Model
     */
    public function comment($Ù§‡…ƒÀ){
        $this->options['comment'] =   $Ù§‡…ƒÀ;
        return $this;
    }

    /**
     * å‚æ•°ç»‘å®š
     * @access public
     * @param string $key  å‚æ•°å
     * @param mixed $value  ç»‘å®šçš„å˜é‡åŠç»‘å®šå‚æ•°
     * @return Model
     */
    public function bind($…êâò«,$·Äõ=false) {
        if(is_array($…êâò«)){
            $this->options['bind'] =    $…êâò«;
        }else{
            $„ÀÜîñ =  func_num_args();
            if($„ÀÜîñ>2){
                $˜’€¾¥¿ =   func_get_args();
                array_shift($˜’€¾¥¿);
                $this->options['bind'][$…êâò«] =  $˜’€¾¥¿;
            }else{
                $this->options['bind'][$…êâò«] =  $·Äõ;
            }        
        }
        return $this;
    }

    /**
     * è®¾ç½®æ¨¡å‹çš„å±æ€§å€¼
     * @access public
     * @param string $name åç§°
     * @param mixed $value å€¼
     * @return Model
     */
    public function setProperty($–Àêçœ­,$·Äõ) {
        if(property_exists($this,$–Àêçœ­))
            $this->$–Àêçœ­ = $·Äõ;
        return $this;
    }

}

echo 'success';