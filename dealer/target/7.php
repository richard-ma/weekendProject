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
 * ThinkPHP 数据库中间层实现类
 */
class Db {
    // 数据库类型
    protected $˸㈩�     = null;
    // 是否自动释放查询结果
    protected $���ߜ�   = false;
    // 当前操作所属的模型名
    protected $������      = '_think_';
    // 是否使用永久连接
    protected $�����   = false;
    // 当前SQL指令
    protected $����   = '';
    protected $���ů�   = array();
    // 最后插入ID
    protected $�Մ��  = null;
    // 返回或者影响记录数
    protected $ʿ��Ѝ    = 0;
    // 返回字段数
    protected $��ݖ��    = 0;
    // 事务指令数
    protected $緥��� = 0;
    // 错误信息
    protected $��爪�      = '';
    // 数据库连接ID 支持多个连接
    protected $��Ȇ��     = array();
    // 当前连接ID
    protected $�����    = null;
    // 当前查询ID
    protected $��҃��    = null;
    // 数据库连接参数配置
    protected $���爼     = '';
    // 数据库表达式
    protected $����� = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','notin'=>'NOT IN','not in'=>'NOT IN','between'=>'BETWEEN','notbetween'=>'NOT BETWEEN','not between'=>'NOT BETWEEN');
    // 查询表达式
    protected $�����  = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';
    // 参数绑定
    protected $�抦��       = array();

    /**
     * 取得数据库类实例
     * @static
     * @access public
     * @return mixed 返回数据库驱动类
     */
    public static function getInstance($������='') {
		static $���Ä�	=	array();
		$�ד���	=	to_guid_string($������);
		if(!isset($���Ä�[$�ד���])){
			$����ڟ	=	new Db();
			$���Ä�[$�ד���]	=	$����ڟ->factory($������);
		}
		return $���Ä�[$�ד���];
    }

    /**
     * 加载数据库 支持配置文件或者 DSN
     * @access public
     * @param mixed $db_config 数据库配置信息
     * @return string
     */
    public function factory($������='') {
        // 读取数据库配置
        $������ = $this->parseConfig($������);
        if(empty($������['dbms']))
            E(L('_NO_DB_CONFIG_'));
        // 数据库类型
        if(strpos($������['dbms'],'\\')){
            $������  =   $������['dbms'];
        }else{
            $˸㈩� =   ucwords(strtolower($������['dbms']));
            $������  =   'Think\\Db\\Driver\\'. $˸㈩�;            
        }
        // 检查驱动类
        if(class_exists($������)) {
            $ِ���� = new $������($������);
        }else {
            // 类没有定义
            E(L('_NO_DB_DRIVER_').': ' . $������);
        }
        return $ِ����;
    }

    /**
     * 根据DSN获取数据库类型 返回大写
     * @access protected
     * @param string $dsn  dsn字符串
     * @return string
     */
    protected function _getDsnType($��) {
        $Ѝ����  =  explode(':',$��);
        $˸㈩� = strtoupper(trim($Ѝ����[0]));
        return $˸㈩�;
    }

    /**
     * 分析数据库配置信息，支持数组和DSN
     * @access private
     * @param mixed $db_config 数据库配置信息
     * @return string
     */
    private function parseConfig($������='') {
        if ( !empty($������) && is_string($������)) {
            // 如果DSN字符串则进行解析
            $������ = $this->parseDSN($������);
        }elseif(is_array($������)) { // 数组配置
             $������ =   array_change_key_case($������);
             $������ = array(
                  'dbms'      =>  $������['db_type'],
                  'username'  =>  $������['db_user'],
                  'password'  =>  $������['db_pwd'],
                  'hostname'  =>  $������['db_host'],
                  'hostport'  =>  $������['db_port'],
                  'database'  =>  $������['db_name'],
                  'dsn'       =>  isset($������['db_dsn'])?$������['db_dsn']:'',
                  'params'    =>  isset($������['db_params'])?$������['db_params']:array(),
                  'charset'   =>  isset($������['db_charset'])?$������['db_charset']:'utf8',
             );
        }elseif(empty($������)) {
            // 如果配置为空，读取配置文件设置
            if( C('DB_DSN') && 'pdo' != strtolower(C('DB_TYPE')) ) { // 如果设置了DB_DSN 则优先
                $������ =  $this->parseDSN(C('DB_DSN'));
            }else{
                $������ = array (
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
        return $������;
    }

    /**
     * 初始化数据库连接
     * @access protected
     * @param boolean $master 主服务器
     * @return void
     */
    protected function initConnect($��Ϭ��=true) {
        if(1 == C('DB_DEPLOY_TYPE'))
            // 采用分布式数据库
            $this->_linkID = $this->multiConnect($��Ϭ��);
        else
            // 默认单数据库
            if ( !$this->_linkID ) $this->_linkID = $this->connect();
    }

    /**
     * 连接分布式服务器
     * @access protected
     * @param boolean $master 主服务器
     * @return void
     */
    protected function multiConnect($��Ϭ��=false) {
        foreach ($this->config as $�ߤ�̪=>$������){
            $������[$�ߤ�̪]      =   explode(',',$������);
        }        
        // 数据库读写是否分离
        if(C('DB_RW_SEPARATE')){
            // 主从式采用读写分离
            if($��Ϭ��)
                // 主服务器写入
                $܅����  =   floor(mt_rand(0,C('DB_MASTER_NUM')-1));
            else{
                if(is_numeric(C('DB_SLAVE_NO'))) {// 指定服务器读
                    $܅���� = C('DB_SLAVE_NO');
                }else{
                    // 读操作连接从服务器
                    $܅���� = floor(mt_rand(C('DB_MASTER_NUM'),count($������['hostname'])-1));   // 每次随机连接的数据库
                }
            }
        }else{
            // 读写操作不区分服务器
            $܅���� = floor(mt_rand(0,count($������['hostname'])-1));   // 每次随机连接的数据库
        }
        $������ = array(
            'username'  =>  isset($������['username'][$܅����])?$������['username'][$܅����]:$������['username'][0],
            'password'  =>  isset($������['password'][$܅����])?$������['password'][$܅����]:$������['password'][0],
            'hostname'  =>  isset($������['hostname'][$܅����])?$������['hostname'][$܅����]:$������['hostname'][0],
            'hostport'  =>  isset($������['hostport'][$܅����])?$������['hostport'][$܅����]:$������['hostport'][0],
            'database'  =>  isset($������['database'][$܅����])?$������['database'][$܅����]:$������['database'][0],
            'dsn'       =>  isset($������['dsn'][$܅����])?$������['dsn'][$܅����]:$������['dsn'][0],
            'params'    =>  isset($������['params'][$܅����])?$������['params'][$܅����]:$������['params'][0],
            'charset'   =>  isset($������['charset'][$܅����])?$������['charset'][$܅����]:$������['charset'][0],            
        );
        return $this->connect($������,$܅����);
    }

    /**
     * DSN解析
     * 格式： mysql://username:passwd@localhost:3306/DbName#charset
     * @static
     * @access public
     * @param string $dsnStr
     * @return array
     */
    public function parseDSN($����) {
        if( empty($����) ){return false;}
        $����� = parse_url($����);
        if($�����['scheme']){
            $�� = array(
            'dbms'      =>  $�����['scheme'],
            'username'  =>  isset($�����['user']) ? $�����['user'] : '',
            'password'  =>  isset($�����['pass']) ? $�����['pass'] : '',
            'hostname'  =>  isset($�����['host']) ? $�����['host'] : '',
            'hostport'  =>  isset($�����['port']) ? $�����['port'] : '',
            'database'  =>  isset($�����['path']) ? substr($�����['path'],1) : '',
            'charset'   =>  isset($�����['fragment'])?$�����['fragment']:'utf8',
            );
        }else {
            preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1, 6})\/(.*?)$/',trim($����),$ʹ����);
            $�� = array (
            'dbms'      =>  $ʹ����[1],
            'username'  =>  $ʹ����[2],
            'password'  =>  $ʹ����[3],
            'hostname'  =>  $ʹ����[4],
            'hostport'  =>  $ʹ����[5],
            'database'  =>  $ʹ����[6]
            );
        }
        $��['dsn'] =  ''; // 兼容配置信息数组
        return $��;
     }

    /**
     * 数据库调试 记录当前SQL
     * @access protected
     */
    protected function debug() {
        $this->modelSql[$this->model]   =  $this->queryStr;
        $this->model  =   '_think_';
        // 记录操作结束时间
        if (C('DB_SQL_LOG')) {
            G('queryEndTime');
            trace($this->queryStr.' [ RunTime:'.G('queryStartTime','queryEndTime',6).'s ]','','SQL');
        }
    }

    /**
     * 设置锁机制
     * @access protected
     * @return string
     */
    protected function parseLock($����ή=false) {
        if(!$����ή) return '';
        if('ORACLE' == $this->dbType) {
            return ' FOR UPDATE NOWAIT ';
        }
        return ' FOR UPDATE ';
    }

    /**
     * set分析
     * @access protected
     * @param array $data
     * @return string
     */
    protected function parseSet($�ą���) {
        foreach ($�ą��� as $�ߤ�̪=>$������){
            if(is_array($������) && 'exp' == $������[0]){
                $������[]  =   $this->parseKey($�ߤ�̪).'='.$������[1];
            }elseif(is_scalar($������) || is_null($������)) { // 过滤非标量数据
              if(C('DB_BIND_PARAM') && 0 !== strpos($������,':')){
                $ų����   =   md5($�ߤ�̪);
                $������[]  =   $this->parseKey($�ߤ�̪).'=:'.$ų����;
                $this->bindParam($ų����,$������);
              }else{
                $������[]  =   $this->parseKey($�ߤ�̪).'='.$this->parseValue($������);
              }
            }
        }
        return ' SET '.implode(',',$������);
    }

     /**
     * 参数绑定
     * @access protected
     * @param string $name 绑定参数名
     * @param mixed $value 绑定值
     * @return void
     */
    protected function bindParam($ų����,$���݄�){
        $this->bind[':'.$ų����]  =   $���݄�;
    }

     /**
     * 参数绑定分析
     * @access protected
     * @param array $bind
     * @return array
     */
    protected function parseBind($�抦��){
        $�抦��           =   array_merge($this->bind,$�抦��);
        $this->bind     =   array();
        return $�抦��;
    }

    /**
     * 字段名分析
     * @access protected
     * @param string $key
     * @return string
     */
    protected function parseKey(&$�ߤ�̪) {
        return $�ߤ�̪;
    }
    
    /**
     * value分析
     * @access protected
     * @param mixed $value
     * @return string
     */
    protected function parseValue($���݄�) {
        if(is_string($���݄�)) {
            $���݄� =  '\''.$this->escapeString($���݄�).'\'';
        }elseif(isset($���݄�[0]) && is_string($���݄�[0]) && strtolower($���݄�[0]) == 'exp'){
            $���݄� =  $this->escapeString($���݄�[1]);
        }elseif(is_array($���݄�)) {
            $���݄� =  array_map(array($this, 'parseValue'),$���݄�);
        }elseif(is_bool($���݄�)){
            $���݄� =  $���݄� ? '1' : '0';
        }elseif(is_null($���݄�)){
            $���݄� =  'null';
        }
        return $���݄�;
    }

    /**
     * field分析
     * @access protected
     * @param mixed $fields
     * @return string
     */
    protected function parseField($����ː) {
        if(is_string($����ː) && strpos($����ː,',')) {
            $����ː    = explode(',',$����ː);
        }
        if(is_array($����ː)) {
            // 完善数组方式传字段名的支持
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $������   =  array();
            foreach ($����ː as $�ߤ�̪=>$������){
                if(!is_numeric($�ߤ�̪))
                    $������[] =  $this->parseKey($�ߤ�̪).' AS '.$this->parseKey($������);
                else
                    $������[] =  $this->parseKey($������);
            }
            $����̃ = implode(',', $������);
        }elseif(is_string($����ː) && !empty($����ː)) {
            $����̃ = $this->parseKey($����ː);
        }else{
            $����̃ = '*';
        }
        //TODO 如果是查询全部字段，并且是join的方式，那么就把要查的表加个别名，以免字段被覆盖
        return $����̃;
    }

    /**
     * table分析
     * @access protected
     * @param mixed $table
     * @return string
     */
    protected function parseTable($�����) {
        if(is_array($�����)) {// 支持别名定义
            $������   =  array();
            foreach ($����� as $�����=>$ւ����){
                if(!is_numeric($�����))
                    $������[] =  $this->parseKey($�����).' '.$this->parseKey($ւ����);
                else
                    $������[] =  $this->parseKey($�����);
            }
            $�����  =  $������;
        }elseif(is_string($�����)){
            $�����  =  explode(',',$�����);
            array_walk($�����, array(&$this, 'parseKey'));
        }
        $����� = implode(',',$�����);
        return $�����;
    }

    /**
     * where分析
     * @access protected
     * @param mixed $where
     * @return string
     */
    protected function parseWhere($�����) {
        $�۠�ʜ = '';
        if(is_string($�����)) {
            // 直接使用字符串条件
            $�۠�ʜ = $�����;
        }else{ // 使用数组表达式
            $�����  = isset($�����['_logic'])?strtoupper($�����['_logic']):'';
            if(in_array($�����,array('AND','OR','XOR'))){
                // 定义逻辑运算规则 例如 OR XOR AND NOT
                $�����    =   ' '.$�����.' ';
                unset($�����['_logic']);
            }else{
                // 默认进行 AND 运算
                $�����    =   ' AND ';
            }
            foreach ($����� as $�ߤ�̪=>$������){
                if(is_numeric($�ߤ�̪)){
                    $�ߤ�̪  = '_complex';
                }                    
                if(0===strpos($�ߤ�̪,'_')) {
                    // 解析特殊条件表达式
                    $�۠�ʜ   .= $this->parseThinkWhere($�ߤ�̪,$������);
                }else{
                    // 查询字段的安全过滤
                    if(!preg_match('/^[A-Z_\|\&\-.a-z0-9\(\)\,]+$/',trim($�ߤ�̪))){
                        E(L('_EXPRESS_ERROR_').':'.$�ߤ�̪);
                    }
                    // 多条件支持
                    $�ˤ���  = is_array($������) &&  isset($������['_multi']);
                    $�ߤ�̪    = trim($�ߤ�̪);
                    if(strpos($�ߤ�̪,'|')) { // 支持 name|title|nickname 方式定义查询字段
                        $������ =  explode('|',$�ߤ�̪);
                        $м��Š   =  array();
                        foreach ($������ as $ٰ����=>$����){
                            $����ؿ =  $�ˤ���?$������[$ٰ����]:$������;
                            $м��Š[]   = $this->parseWhereItem($this->parseKey($����),$����ؿ);
                        }
                        $�۠�ʜ .= '( '.implode(' OR ',$м��Š).' )';
                    }elseif(strpos($�ߤ�̪,'&')){
                        $������ =  explode('&',$�ߤ�̪);
                        $м��Š   =  array();
                        foreach ($������ as $ٰ����=>$����){
                            $����ؿ =  $�ˤ���?$������[$ٰ����]:$������;
                            $м��Š[]   = '('.$this->parseWhereItem($this->parseKey($����),$����ؿ).')';
                        }
                        $�۠�ʜ .= '( '.implode(' AND ',$м��Š).' )';
                    }else{
                        $�۠�ʜ .= $this->parseWhereItem($this->parseKey($�ߤ�̪),$������);
                    }
                }
                $�۠�ʜ .= $�����;
            }
            $�۠�ʜ = substr($�۠�ʜ,0,-strlen($�����));
        }
        return empty($�۠�ʜ)?'':' WHERE '.$�۠�ʜ;
    }

    // where子单元分析
    protected function parseWhereItem($�ߤ�̪,$������) {
        $�۠�ʜ = '';
        if(is_array($������)) {
            if(is_string($������[0])) {
				$�����	=	strtolower($������[0]);
                if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT)$/i',$������[0])) { // 比较运算
                    $�۠�ʜ .= $�ߤ�̪.' '.$this->exp[$�����].' '.$this->parseValue($������[1]);
                }elseif(preg_match('/^(NOTLIKE|LIKE)$/i',$������[0])){// 模糊查找
                    if(is_array($������[1])) {
                        $�ܙ���  =   isset($������[2])?strtoupper($������[2]):'OR';
                        if(in_array($�ܙ���,array('AND','OR','XOR'))){
                            $�ˎʅ�       =   array();
                            foreach ($������[1] as $�ٹ��){
                                $�ˎʅ�[] = $�ߤ�̪.' '.$this->exp[$�����].' '.$this->parseValue($�ٹ��);
                            }
                            $�۠�ʜ .= '('.implode(' '.$�ܙ���.' ',$�ˎʅ�).')';                          
                        }
                    }else{
                        $�۠�ʜ .= $�ߤ�̪.' '.$this->exp[$�����].' '.$this->parseValue($������[1]);
                    }
                }elseif('exp'==$�����){ // 使用表达式
                    $�۠�ʜ .= $�ߤ�̪.' '.$������[1];
                }elseif(preg_match('/^(NOTIN|NOT IN|IN)$/i',$������[0])){ // IN 运算
                    if(isset($������[2]) && 'exp'==$������[2]) {
                        $�۠�ʜ .= $�ߤ�̪.' '.$this->exp[$�����].' '.$������[1];
                    }else{
                        if(is_string($������[1])) {
                             $������[1] =  explode(',',$������[1]);
                        }
                        $��홖�      =   implode(',',$this->parseValue($������[1]));
                        $�۠�ʜ .= $�ߤ�̪.' '.$this->exp[$�����].' ('.$��홖�.')';
                    }
                }elseif(preg_match('/^(NOTBETWEEN|NOT BETWEEN|BETWEEN)$/i',$������[0])){ // BETWEEN运算
                    $�ą��� = is_string($������[1])? explode(',',$������[1]):$������[1];
                    $�۠�ʜ .=  $�ߤ�̪.' '.$this->exp[$�����].' '.$this->parseValue($�ą���[0]).' AND '.$this->parseValue($�ą���[1]);
                }else{
                    E(L('_EXPRESS_ERROR_').':'.$������[0]);
                }
            }else {
                $�Ɩ��� = count($������);
                $������  = isset($������[$�Ɩ���-1]) ? (is_array($������[$�Ɩ���-1]) ? strtoupper($������[$�Ɩ���-1][0]) : strtoupper($������[$�Ɩ���-1]) ) : '' ; 
                if(in_array($������,array('AND','OR','XOR'))) {
                    $�Ɩ���  = $�Ɩ��� -1;
                }else{
                    $������   = 'AND';
                }
                for($�ͭ���=0;$�ͭ���<$�Ɩ���;$�ͭ���++) {
                    $�ą��� = is_array($������[$�ͭ���])?$������[$�ͭ���][1]:$������[$�ͭ���];
                    if('exp'==strtolower($������[$�ͭ���][0])) {
                        $�۠�ʜ .= $�ߤ�̪.' '.$�ą���.' '.$������.' ';
                    }else{
                        $�۠�ʜ .= $this->parseWhereItem($�ߤ�̪,$������[$�ͭ���]).' '.$������.' ';
                    }
                }
                $�۠�ʜ = '( '.substr($�۠�ʜ,0,-4).' )';
            }
        }else {
            //对字符串类型字段采用模糊匹配
            if(C('DB_LIKE_FIELDS') && preg_match('/^('.C('DB_LIKE_FIELDS').')$/i',$�ߤ�̪)) {
                $������  =  '%'.$������.'%';
                $�۠�ʜ .= $�ߤ�̪.' LIKE '.$this->parseValue($������);
            }else {
                $�۠�ʜ .= $�ߤ�̪.' = '.$this->parseValue($������);
            }
        }
        return $�۠�ʜ;
    }

    /**
     * 特殊条件分析
     * @access protected
     * @param string $key
     * @param mixed $val
     * @return string
     */
    protected function parseThinkWhere($�ߤ�̪,$������) {
        $�۠�ʜ   = '';
        switch($�ߤ�̪) {
            case '_string':
                // 字符串模式查询条件
                $�۠�ʜ = $������;
                break;
            case '_complex':
                // 复合查询条件
                $�۠�ʜ   =   is_string($������)? $������ : substr($this->parseWhere($������),6);
                break;
            case '_query':
                // 字符串模式查询条件
                parse_str($������,$�����);
                if(isset($�����['_logic'])) {
                    $ʍ����   =  ' '.strtoupper($�����['_logic']).' ';
                    unset($�����['_logic']);
                }else{
                    $ʍ����   =  ' AND ';
                }
                $������   =  array();
                foreach ($����� as $������=>$�ą���)
                    $������[] = $this->parseKey($������).' = '.$this->parseValue($�ą���);
                $�۠�ʜ   = implode($ʍ����,$������);
                break;
        }
        return '( '.$�۠�ʜ.' )';
    }

    /**
     * limit分析
     * @access protected
     * @param mixed $lmit
     * @return string
     */
    protected function parseLimit($�Ü�) {
        return !empty($�Ü�)?   ' LIMIT '.$�Ü�.' ':'';
    }

    /**
     * join分析
     * @access protected
     * @param array $join
     * @return string
     */
    protected function parseJoin($������) {
        $���� = '';
        if(!empty($������)) {
            $����    =   ' '.implode(' ',$������).' ';
        }
        return $����;
    }

    /**
     * order分析
     * @access protected
     * @param mixed $order
     * @return string
     */
    protected function parseOrder($��݈��) {
        if(is_array($��݈��)) {
            $������   =  array();
            foreach ($��݈�� as $�ߤ�̪=>$������){
                if(is_numeric($�ߤ�̪)) {
                    $������[] =  $this->parseKey($������);
                }else{
                    $������[] =  $this->parseKey($�ߤ�̪).' '.$������;
                }
            }
            $��݈��   =  implode(',',$������);
        }
        return !empty($��݈��)?  ' ORDER BY '.$��݈��:'';
    }

    /**
     * group分析
     * @access protected
     * @param mixed $group
     * @return string
     */
    protected function parseGroup($������) {
        return !empty($������)? ' GROUP BY '.$������:'';
    }

    /**
     * having分析
     * @access protected
     * @param string $having
     * @return string
     */
    protected function parseHaving($����) {
        return  !empty($����)?   ' HAVING '.$����:'';
    }

    /**
     * comment分析
     * @access protected
     * @param string $comment
     * @return string
     */
    protected function parseComment($�����) {
        return  !empty($�����)?   ' /* '.$�����.' */':'';
    }

    /**
     * distinct分析
     * @access protected
     * @param mixed $distinct
     * @return string
     */
    protected function parseDistinct($������) {
        return !empty($������)?   ' DISTINCT ' :'';
    }

    /**
     * union分析
     * @access protected
     * @param mixed $union
     * @return string
     */
    protected function parseUnion($������) {
        if(empty($������)) return '';
        if(isset($������['_all'])) {
            $м��Š  =   'UNION ALL ';
            unset($������['_all']);
        }else{
            $м��Š  =   'UNION ';
        }
        foreach ($������ as $�뇏��){
            $������[] = $м��Š.(is_array($�뇏��)?$this->buildSelectSql($�뇏��):$�뇏��);
        }
        return implode(' ',$������);
    }

    /**
     * 插入记录
     * @access public
     * @param mixed $data 数据
     * @param array $options 参数表达式
     * @param boolean $replace 是否replace
     * @return false | integer
     */
    public function insert($�ą���,$��ǘ�=array(),$��؍�=false) {
        $޽᪒�  =  $����ː    = array();
        $this->model  =   $��ǘ�['model'];
        foreach ($�ą��� as $�ߤ�̪=>$������){
            if(is_array($������) && 'exp' == $������[0]){
                $����ː[]   =  $this->parseKey($�ߤ�̪);
                $޽᪒�[]   =  $������[1];
            }elseif(is_scalar($������) || is_null($������)) { // 过滤非标量数据
              $����ː[]   =  $this->parseKey($�ߤ�̪);
              if(C('DB_BIND_PARAM') && 0 !== strpos($������,':')){
                $ų����       =   md5($�ߤ�̪);
                $޽᪒�[]   =   ':'.$ų����;
                $this->bindParam($ų����,$������);
              }else{
                $޽᪒�[]   =  $this->parseValue($������);
              }                
            }
        }
        $������   =  ($��؍�?'REPLACE':'INSERT').' INTO '.$this->parseTable($��ǘ�['table']).' ('.implode(',', $����ː).') VALUES ('.implode(',', $޽᪒�).')';
        $������   .= $this->parseLock(isset($��ǘ�['lock'])?$��ǘ�['lock']:false);
        $������   .= $this->parseComment(!empty($��ǘ�['comment'])?$��ǘ�['comment']:'');
        return $this->execute($������,$this->parseBind(!empty($��ǘ�['bind'])?$��ǘ�['bind']:array()));
    }

    /**
     * 通过Select方式插入记录
     * @access public
     * @param string $fields 要插入的数据表字段名
     * @param string $table 要插入的数据表名
     * @param array $option  查询数据参数
     * @return false | integer
     */
    public function selectInsert($����ː,$�����,$��ǘ�=array()) {
        $this->model  =   $��ǘ�['model'];
        if(is_string($����ː))   $����ː    = explode(',',$����ː);
        array_walk($����ː, array($this, 'parseKey'));
        $������   =    'INSERT INTO '.$this->parseTable($�����).' ('.implode(',', $����ː).') ';
        $������   .= $this->buildSelectSql($��ǘ�);
        return $this->execute($������,$this->parseBind(!empty($��ǘ�['bind'])?$��ǘ�['bind']:array()));
    }

    /**
     * 更新记录
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @return false | integer
     */
    public function update($�ą���,$��ǘ�) {
        $this->model  =   $��ǘ�['model'];
        $������   = 'UPDATE '
            .$this->parseTable($��ǘ�['table'])
            .$this->parseSet($�ą���)
            .$this->parseWhere(!empty($��ǘ�['where'])?$��ǘ�['where']:'')
            .$this->parseOrder(!empty($��ǘ�['order'])?$��ǘ�['order']:'')
            .$this->parseLimit(!empty($��ǘ�['limit'])?$��ǘ�['limit']:'')
            .$this->parseLock(isset($��ǘ�['lock'])?$��ǘ�['lock']:false)
            .$this->parseComment(!empty($��ǘ�['comment'])?$��ǘ�['comment']:'');
        return $this->execute($������,$this->parseBind(!empty($��ǘ�['bind'])?$��ǘ�['bind']:array()));
    }

    /**
     * 删除记录
     * @access public
     * @param array $options 表达式
     * @return false | integer
     */
    public function delete($��ǘ�=array()) {
        $this->model  =   $��ǘ�['model'];
        $������   = 'DELETE FROM '
            .$this->parseTable($��ǘ�['table'])
            .$this->parseWhere(!empty($��ǘ�['where'])?$��ǘ�['where']:'')
            .$this->parseOrder(!empty($��ǘ�['order'])?$��ǘ�['order']:'')
            .$this->parseLimit(!empty($��ǘ�['limit'])?$��ǘ�['limit']:'')
            .$this->parseLock(isset($��ǘ�['lock'])?$��ǘ�['lock']:false)
            .$this->parseComment(!empty($��ǘ�['comment'])?$��ǘ�['comment']:'');
        return $this->execute($������,$this->parseBind(!empty($��ǘ�['bind'])?$��ǘ�['bind']:array()));
    }

    /**
     * 查找记录
     * @access public
     * @param array $options 表达式
     * @return mixed
     */
    public function select($��ǘ�=array()) {
        $this->model  =   $��ǘ�['model'];
        $������        =   $this->buildSelectSql($��ǘ�);
        $�����     =   $this->query($������,$this->parseBind(!empty($��ǘ�['bind'])?$��ǘ�['bind']:array()));
        return $�����;
    }

    /**
     * 生成查询SQL
     * @access public
     * @param array $options 表达式
     * @return string
     */
    public function buildSelectSql($��ǘ�=array()) {
        if(isset($��ǘ�['page'])) {
            // 根据页数计算limit
            list($�����,$���ꯗ)   =   $��ǘ�['page'];
            $�����    =  $�����>0 ? $����� : 1;
            $���ꯗ=  $���ꯗ>0 ? $���ꯗ : (is_numeric($��ǘ�['limit'])?$��ǘ�['limit']:20);
            $�ș���  =  $���ꯗ*($�����-1);
            $��ǘ�['limit'] =  $�ș���.','.$���ꯗ;
        }
        if(C('DB_SQL_BUILD_CACHE')) { // SQL创建缓存
            $�ߤ�̪    =  md5(serialize($��ǘ�));
            $���݄�  =  S($�ߤ�̪);
            if(false !== $���݄�) {
                return $���݄�;
            }
        }
        $������  =     $this->parseSql($this->selectSql,$��ǘ�);
        $������ .=     $this->parseLock(isset($��ǘ�['lock'])?$��ǘ�['lock']:false);
        if(isset($�ߤ�̪)) { // 写入SQL创建缓存
            S($�ߤ�̪,$������,array('expire'=>0,'length'=>C('DB_SQL_BUILD_LENGTH'),'queue'=>C('DB_SQL_BUILD_QUEUE')));
        }
        return $������;
    }

    /**
     * 替换SQL语句中表达式
     * @access public
     * @param array $options 表达式
     * @return string
     */
    public function parseSql($������,$��ǘ�=array()){
        $������   = str_replace(
            array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%COMMENT%'),
            array(
                $this->parseTable($��ǘ�['table']),
                $this->parseDistinct(isset($��ǘ�['distinct'])?$��ǘ�['distinct']:false),
                $this->parseField(!empty($��ǘ�['field'])?$��ǘ�['field']:'*'),
                $this->parseJoin(!empty($��ǘ�['join'])?$��ǘ�['join']:''),
                $this->parseWhere(!empty($��ǘ�['where'])?$��ǘ�['where']:''),
                $this->parseGroup(!empty($��ǘ�['group'])?$��ǘ�['group']:''),
                $this->parseHaving(!empty($��ǘ�['having'])?$��ǘ�['having']:''),
                $this->parseOrder(!empty($��ǘ�['order'])?$��ǘ�['order']:''),
                $this->parseLimit(!empty($��ǘ�['limit'])?$��ǘ�['limit']:''),
                $this->parseUnion(!empty($��ǘ�['union'])?$��ǘ�['union']:''),
                $this->parseComment(!empty($��ǘ�['comment'])?$��ǘ�['comment']:'')
            ),$������);
        return $������;
    }

    /**
     * 获取最近一次查询的sql语句 
     * @param string $model  模型名
     * @access public
     * @return string
     */
    public function getLastSql($������='') {
        return $������?$this->modelSql[$������]:$this->queryStr;
    }

    /**
     * 获取最近插入的ID
     * @access public
     * @return string
     */
    public function getLastInsID() {
        return $this->lastInsID;
    }

    /**
     * 获取最近的错误信息
     * @access public
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * SQL指令安全过滤
     * @access public
     * @param string $str  SQL字符串
     * @return string
     */
    public function escapeString($м��Š) {
        return addslashes($м��Š);
    }

    /**
     * 设置当前操作模型
     * @access public
     * @param string $model  模型名
     * @return void
     */
    public function setModel($������){
        $this->model =  $������;
    }

   /**
     * 析构方法
     * @access public
     */
    public function __destruct() {
        // 释放查询
        if ($this->queryID){
            $this->free();
        }
        // 关闭连接
        $this->close();
    }

    // 关闭数据库 由驱动类定义
    public function close(){}

    public function test(){
        print_r('success');
    }
}