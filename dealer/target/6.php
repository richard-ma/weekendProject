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
 * ThinkPHP Model模型类
 * 实现了ORM和ActiveRecords模式
 */
class Model {
    // 操作状态
    const MODEL_INSERT          =   1;      //  插入模型数据
    const MODEL_UPDATE          =   2;      //  更新模型数据
    const MODEL_BOTH            =   3;      //  包含上面两种方式
    const MUST_VALIDATE         =   1;      // 必须验证
    const EXISTS_VALIDATE       =   0;      // 表单存在字段则验证
    const VALUE_VALIDATE        =   2;      // 表单值不为空则验证

    // 当前数据库操作对象
    protected $���Ӓ�               =   null;
    // 主键名称
    protected $ҿ����               =   'id';
    // 主键是否自动增长
    protected $�����          =   false;    
    // 数据表前缀
    protected $��܏��      =   null;
    // 模型名称
    protected $���眭             =   '';
    // 数据库名称
    protected $���Ђ�           =   '';
    //数据库配置
    protected $Ӳ�쵶       =   '';
    // 数据表名（不包含表前缀）
    protected $���֙�        =   '';
    // 实际数据表名（包含表前缀）
    protected $�ê��    =   '';
    // 最近错误信息
    protected $������            =   '';
    // 字段信息
    protected $������           =   array();
    // 数据信息
    protected $������             =   array();
    // 查询表达式参数
    protected $����ɳ          =   array();
    protected $�꽁��        =   array();  // 自动验证定义
    protected $������            =   array();  // 自动完成定义
    protected $�̷���             =   array();  // 字段映射定义
    protected $�۩���           =   array();  // 命名范围定义
    // 是否自动检测数据表字段信息
    protected $�Ɓ���  =   true;
    // 是否批处理验证
    protected $�򭜳�    =   false;
    // 链操作方法列表
    protected $������          =   array('order','alias','having','group','lock','distinct','auto','filter','validate','result','token','index');

    /**
     * 架构函数
     * 取得DB类的实例对象 字段检查
     * @access public
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($���眭='',$��܏��='',$Ӳ�쵶='') {
        // 模型初始化
        $this->_initialize();
        // 获取模型名称
        if(!empty($���眭)) {
            if(strpos($���眭,'.')) { // 支持 数据库名.模型名的 定义
                list($this->dbName,$this->name) = explode('.',$���眭);
            }else{
                $this->name   =  $���眭;
            }
        }elseif(empty($this->name)){
            $this->name =   $this->getModelName();
        }
        // 设置表前缀
        if(is_null($��܏��)) {// 前缀为Null表示没有前缀
            $this->tablePrefix = '';
        }elseif('' != $��܏��) {
            $this->tablePrefix = $��܏��;
        }elseif(!isset($this->tablePrefix)){
            $this->tablePrefix = C('DB_PREFIX');
        }

        // 数据库初始化操作
        // 获取数据库操作对象
        // 当前模型有独立的数据库连接信息
        $this->db(0,empty($this->connection)?$Ӳ�쵶:$this->connection,true);
    }

    /**
     * 自动检测数据表信息
     * @access protected
     * @return void
     */
    protected function _checkTableInfo() {
        // 如果不是Model类 自动记录数据表信息
        // 只在第一次执行记录
        if(empty($this->fields)) {
            // 如果数据表字段没有定义则自动获取
            if(C('DB_FIELDS_CACHE')) {
                $���Ӓ�   =  $this->dbName?:C('DB_NAME');
                $������ = F('_fields/'.strtolower($���Ӓ�.'.'.$this->tablePrefix.$this->name));
                if($������) {
                    $this->fields   =   $������;
                    $this->pk       =   $������['_pk'];
                    return ;
                }
            }
            // 每次都会读取数据表信息
            $this->flush();
        }
    }

    /**
     * 获取字段信息并缓存
     * @access public
     * @return void
     */
    public function flush() {
        // 缓存不存在则查询数据表信息
        $this->db->setModel($this->name);
        $������ =   $this->db->getFields($this->getTableName());
        if(!$������) { // 无法获取字段信息
            return false;
        }
        $this->fields   =   array_keys($������);
        foreach ($������ as $����=>$��벇�){
            // 记录字段类型
            $�Ó�Ϧ[$����]     =   $��벇�['type'];
            if($��벇�['primary']) {
                $this->pk   =   $����;
                $this->fields['_pk']   =   $����;
                if($��벇�['autoinc']) $this->autoinc   =   true;
            }
        }
        // 记录字段类型信息
        $this->fields['_type'] =  $�Ó�Ϧ;

        // 2008-3-7 增加缓存开关控制
        if(C('DB_FIELDS_CACHE')){
            // 永久缓存数据表信息
            $���Ӓ�   =  $this->dbName?:C('DB_NAME');
            F('_fields/'.strtolower($���Ӓ�.'.'.$this->tablePrefix.$this->name),$this->fields);
        }
    }

    /**
     * 设置数据对象的值
     * @access public
     * @param string $name 名称
     * @param mixed $value 值
     * @return void
     */
    public function __set($���眭,$������) {
        // 设置数据对象属性
        $this->data[$���眭]  =   $������;
    }

    /**
     * 获取数据对象的值
     * @access public
     * @param string $name 名称
     * @return mixed
     */
    public function __get($���眭) {
        return isset($this->data[$���眭])?$this->data[$���眭]:null;
    }

    /**
     * 检测数据对象的值
     * @access public
     * @param string $name 名称
     * @return boolean
     */
    public function __isset($���眭) {
        return isset($this->data[$���眭]);
    }

    /**
     * 销毁数据对象的值
     * @access public
     * @param string $name 名称
     * @return void
     */
    public function __unset($���眭) {
        unset($this->data[$���眭]);
    }

    /**
     * 利用__call方法实现一些特殊的Model方法
     * @access public
     * @param string $method 方法名称
     * @param array $args 调用参数
     * @return mixed
     */
    public function __call($��Б��,$������) {
        if(in_array(strtolower($��Б��),$this->methods,true)) {
            // 连贯操作的实现
            $this->options[strtolower($��Б��)] =   $������[0];
            return $this;
        }elseif(in_array(strtolower($��Б��),array('count','sum','min','max','avg'),true)){
            // 统计查询的实现
            $���а� =  isset($������[0])?$������[0]:'*';
            return $this->getField(strtoupper($��Б��).'('.$���а�.') AS tp_'.$��Б��);
        }elseif(strtolower(substr($��Б��,0,5))=='getby') {
            // 根据某个字段获取记录
            $���а�   =   parse_name(substr($��Б��,5));
            $䰎إ�[$���а�] =  $������[0];
            return $this->where($䰎إ�)->find();
        }elseif(strtolower(substr($��Б��,0,10))=='getfieldby') {
            // 根据某个字段获取记录的某个值
            $���眭   =   parse_name(substr($��Б��,10));
            $䰎إ�[$���眭] =$������[0];
            return $this->where($䰎إ�)->getField($������[1]);
        }elseif(isset($this->_scope[$��Б��])){// 命名范围的单独调用支持
            return $this->scope($��Б��,$������[0]);
        }else{
            E(__CLASS__.':'.$��Б��.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }
    // 回调方法 初始化模型
    protected function _initialize() {}

    /**
     * 对保存到数据库的数据进行处理
     * @access protected
     * @param mixed $data 要操作的数据
     * @return boolean
     */
     protected function _facade($������) {

        // 检查数据字段合法性
        if(!empty($this->fields)) {
            if(!empty($this->options['field'])) {
                $������ =   $this->options['field'];
                unset($this->options['field']);
                if(is_string($������)) {
                    $������ =   explode(',',$������);
                }    
            }else{
                $������ =   $this->fields;
            }        
            foreach ($������ as $����=>$��벇�){
                if(!in_array($����,$������,true)){
                    if(APP_DEBUG){
                        E(L('_DATA_TYPE_INVALID_').':['.$����.'=>'.$��벇�.']');
                    }                    
                    unset($������[$����]);
                }elseif(is_scalar($��벇�)) {
                    // 字段类型检查 和 强制转换
                    $this->_parseType($������,$����);
                }
            }
        }
       
        // 安全过滤
        if(!empty($this->options['filter'])) {
            $������ = array_map($this->options['filter'],$������);
            unset($this->options['filter']);
        }
        $this->_before_write($������);
        return $������;
     }

    // 写入数据前的回调方法 包括新增和更新
    protected function _before_write(&$������) {}

    /**
     * 新增数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @param boolean $replace 是否replace
     * @return mixed
     */
    public function add($������='',$����ɳ=array(),$�����=false) {
        if(empty($������)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $������           =   $this->data;
                // 重置数据
                $this->data     = array();
            }else{
                $this->error    = L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // 分析表达式
        $����ɳ    =   $this->_parseOptions($����ɳ);
        // 数据处理
        $������       =   $this->_facade($������);
        if(false === $this->_before_insert($������,$����ɳ)) {
            return false;
        }
        // 写入数据到数据库
        $����Ə = $this->db->insert($������,$����ɳ,$�����);
        if(false !== $����Ə ) {
            $���ץ�   =   $this->getLastInsID();
            if($���ץ�) {
                // 自增主键返回插入ID
                $������[$this->getPk()]  = $���ץ�;
                if(false === $this->_after_insert($������,$����ɳ)) {
                    return false;
                }
                return $���ץ�;
            }
            if(false === $this->_after_insert($������,$����ɳ)) {
                return false;
            }
        }
        return $����Ə;
    }
    // 插入数据前的回调方法
    protected function _before_insert(&$������,$����ɳ) {}
    // 插入成功后的回调方法
    protected function _after_insert($������,$����ɳ) {}

    public function addAll($������,$����ɳ=array(),$�����=false){
        if(empty($������)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }
        // 分析表达式
        $����ɳ =  $this->_parseOptions($����ɳ);
        // 数据处理
        foreach ($������ as $����=>$������){
            $������[$����] = $this->_facade($������);
        }
        // 写入数据到数据库
        $����Ə = $this->db->insertAll($������,$����ɳ,$�����);
        if(false !== $����Ə ) {
            $���ץ�   =   $this->getLastInsID();
            if($���ץ�) {
                return $���ץ�;
            }
        }
        return $����Ə;
    }

    /**
     * 通过Select方式添加记录
     * @access public
     * @param string $fields 要插入的数据表字段名
     * @param string $table 要插入的数据表名
     * @param array $options 表达式
     * @return boolean
     */
    public function selectAdd($������='',$��̪�='',$����ɳ=array()) {
        // 分析表达式
        $����ɳ =  $this->_parseOptions($����ɳ);
        // 写入数据到数据库
        if(false === $����Ə = $this->db->selectInsert($������?:$����ɳ['field'],$��̪�?:$this->getTableName(),$����ɳ)){
            // 数据库插入操作失败
            $this->error = L('_OPERATION_WRONG_');
            return false;
        }else {
            // 插入成功
            return $����Ə;
        }
    }

    /**
     * 保存数据
     * @access public
     * @param mixed $data 数据
     * @param array $options 表达式
     * @return boolean
     */
    public function save($������='',$����ɳ=array()) {
        if(empty($������)) {
            // 没有传递数据，获取当前数据对象的值
            if(!empty($this->data)) {
                $������           =   $this->data;
                // 重置数据
                $this->data     =   array();
            }else{
                $this->error    =   L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // 数据处理
        $������       =   $this->_facade($������);
        if(empty($������)){
            // 没有数据则不执行
            $this->error    =   L('_DATA_TYPE_INVALID_');
            return false;
        }
        // 分析表达式
        $����ɳ    =   $this->_parseOptions($����ɳ);
        $ҿ����         =   $this->getPk();
        if(!isset($����ɳ['where']) ) {
            // 如果存在主键数据 则自动作为更新条件
            if(isset($������[$ҿ����])) {
                $䰎إ�[$ҿ����]         =   $������[$ҿ����];
                $����ɳ['where']   =   $䰎إ�;
                unset($������[$ҿ����]);
            }else{
                // 如果没有任何更新条件则不执行
                $this->error        =   L('_OPERATION_WRONG_');
                return false;
            }
        }
        if(is_array($����ɳ['where']) && isset($����ɳ['where'][$ҿ����])){
            $ҋ�ڰ�    =   $����ɳ['where'][$ҿ����];
        }        
        if(false === $this->_before_update($������,$����ɳ)) {
            return false;
        }        
        $����Ə     =   $this->db->update($������,$����ɳ);
        if(false !== $����Ə) {
            if(isset($ҋ�ڰ�)) $������[$ҿ����]   =  $ҋ�ڰ�;
            $this->_after_update($������,$����ɳ);
        }
        return $����Ə;
    }
    // 更新数据前的回调方法
    protected function _before_update(&$������,$����ɳ) {}
    // 更新成功后的回调方法
    protected function _after_update($������,$����ɳ) {}

    /**
     * 删除数据
     * @access public
     * @param mixed $options 表达式
     * @return mixed
     */
    public function delete($����ɳ=array()) {
        if(empty($����ɳ) && empty($this->options['where'])) {
            // 如果删除条件为空 则删除当前数据对象所对应的记录
            if(!empty($this->data) && isset($this->data[$this->getPk()]))
                return $this->delete($this->data[$this->getPk()]);
            else
                return false;
        }
        $ҿ����   =  $this->getPk();
        if(is_numeric($����ɳ)  || is_string($����ɳ)) {
            // 根据主键删除记录
            if(strpos($����ɳ,',')) {
                $䰎إ�[$ҿ����]     =  array('IN', $����ɳ);
            }else{
                $䰎إ�[$ҿ����]     =  $����ɳ;
            }
            $����ɳ            =  array();
            $����ɳ['where']   =  $䰎إ�;
        }
        // 分析表达式
        $����ɳ =  $this->_parseOptions($����ɳ);
        if(empty($����ɳ['where'])){
            // 如果条件为空 不进行删除操作 除非设置 1=1
            return false;
        }        
        if(is_array($����ɳ['where']) && isset($����ɳ['where'][$ҿ����])){
            $ҋ�ڰ�            =  $����ɳ['where'][$ҿ����];
        }

        if(false === $this->_before_delete($����ɳ)) {
            return false;
        }        
        $����Ə  =    $this->db->delete($����ɳ);
        if(false !== $����Ə) {
            $������ = array();
            if(isset($ҋ�ڰ�)) $������[$ҿ����]   =  $ҋ�ڰ�;
            $this->_after_delete($������,$����ɳ);
        }
        // 返回删除记录个数
        return $����Ə;
    }
    // 删除数据前的回调方法
    protected function _before_delete($����ɳ) {}    
    // 删除成功后的回调方法
    protected function _after_delete($������,$����ɳ) {}

    /**
     * 查询数据集
     * @access public
     * @param array $options 表达式参数
     * @return mixed
     */
    public function select($����ɳ=array()) {
        if(is_string($����ɳ) || is_numeric($����ɳ)) {
            // 根据主键查询
            $ҿ����   =  $this->getPk();
            if(strpos($����ɳ,',')) {
                $䰎إ�[$ҿ����]     =  array('IN',$����ɳ);
            }else{
                $䰎إ�[$ҿ����]     =  $����ɳ;
            }
            $����ɳ            =  array();
            $����ɳ['where']   =  $䰎إ�;
        }elseif(false === $����ɳ){ // 用于子查询 不查询只返回SQL
            $����ɳ            =  array();
            // 分析表达式
            $����ɳ            =  $this->_parseOptions($����ɳ);
            return  '( '.$this->db->buildSelectSql($����ɳ).' )';
        }
        // 分析表达式
        $����ɳ    =  $this->_parseOptions($����ɳ);
        // 判断查询缓存
        if(isset($����ɳ['cache'])){
            $ʸ����  =   $����ɳ['cache'];
            $����    =   is_string($ʸ����['key'])?$ʸ����['key']:md5(serialize($����ɳ));
            $������   =   S($����,'',$ʸ����);
            if(false !== $������){
                return $������;
            }
        }        
        $������  = $this->db->select($����ɳ);
        if(false === $������) {
            return false;
        }
        if(empty($������)) { // 查询结果为空
            return null;
        }
        $������  =   array_map(array($this,'_read_data'),$������);
        $this->_after_select($������,$����ɳ);
        if(isset($����ɳ['index'])){ // 对数据集进行索引
            $���͹�  =   explode(',',$����ɳ['index']);
            foreach ($������ as $����Ə){
                $������   =  $����Ə[$���͹�[0]];
                if(isset($���͹�[1]) && isset($����Ə[$���͹�[1]])){
                    $ѐ�΀�[$������] =  $����Ə[$���͹�[1]];
                }else{
                    $ѐ�΀�[$������] =  $����Ə;
                }
            }
            $������  =   $ѐ�΀�;         
        }
        if(isset($ʸ����)){
            S($����,$������,$ʸ����);
        }           
        return $������;
    }
    // 查询成功后的回调方法
    protected function _after_select(&$������,$����ɳ) {}

    /**
     * 生成查询SQL 可用于子查询
     * @access public
     * @param array $options 表达式参数
     * @return string
     */
    public function buildSql($����ɳ=array()) {
        // 分析表达式
        $����ɳ =  $this->_parseOptions($����ɳ);
        return  '( '.$this->db->buildSelectSql($����ɳ).' )';
    }

    /**
     * 分析表达式
     * @access protected
     * @param array $options 表达式参数
     * @return array
     */
    protected function _parseOptions($����ɳ=array()) {
        if(is_array($����ɳ))
            $����ɳ =  array_merge($this->options,$����ɳ);

        if(!isset($����ɳ['table'])){
            // 自动获取表名
            $����ɳ['table']   =   $this->getTableName();
            $������             =   $this->fields;
        }else{
            // 指定数据表 则重新获取字段列表 但不支持类型检测
            $������             =   $this->getDbFields();
        }

        // 数据表别名
        if(!empty($����ɳ['alias'])) {
            $����ɳ['table']  .=   ' '.$����ɳ['alias'];
        }
        // 记录操作的模型名称
        $����ɳ['model']       =   $this->name;

        // 字段类型验证
        if(isset($����ɳ['where']) && is_array($����ɳ['where']) && !empty($������) && !isset($����ɳ['join'])) {
            // 对数组查询条件进行字段类型检查
            foreach ($����ɳ['where'] as $����=>$��벇�){
                $����            =   trim($����);
                if(in_array($����,$������,true)){
                    if(is_scalar($��벇�)) {
                        $this->_parseType($����ɳ['where'],$����);
                    }
                }elseif(!is_numeric($����) && '_' != substr($����,0,1) && false === strpos($����,'.') && false === strpos($����,'(') && false === strpos($����,'|') && false === strpos($����,'&')){
                    if(APP_DEBUG){
                        E(L('_ERROR_QUERY_EXPRESS_').':['.$����.'=>'.$��벇�.']');
                    } 
                    unset($����ɳ['where'][$����]);
                }
            }
        }
        // 查询过后清空sql表达式组装 避免影响下次查询
        $this->options  =   array();
        // 表达式过滤
        $this->_options_filter($����ɳ);
        return $����ɳ;
    }
    // 表达式过滤回调方法
    protected function _options_filter(&$����ɳ) {}

    /**
     * 数据类型检测
     * @access protected
     * @param mixed $data 数据
     * @param string $key 字段名
     * @return void
     */
    protected function _parseType(&$������,$����) {
        if(!isset($this->options['bind'][':'.$����]) && isset($this->fields['_type'][$����])){
            $��Ѹ�� = strtolower($this->fields['_type'][$����]);
            if(false !== strpos($��Ѹ��,'enum')){
                // 支持ENUM类型优先检测
            }elseif(false === strpos($��Ѹ��,'bigint') && false !== strpos($��Ѹ��,'int')) {
                $������[$����]   =  intval($������[$����]);
            }elseif(false !== strpos($��Ѹ��,'float') || false !== strpos($��Ѹ��,'double')){
                $������[$����]   =  floatval($������[$����]);
            }elseif(false !== strpos($��Ѹ��,'bool')){
                $������[$����]   =  (bool)$������[$����];
            }
        }
    }

    /**
     * 数据读取后的处理
     * @access protected
     * @param array $data 当前数据
     * @return array
     */
    protected function _read_data($������) {
        // 检查字段映射
        if(!empty($this->_map) && C('READ_DATA_MAP')) {
            foreach ($this->_map as $����=>$��벇�){
                if(isset($������[$��벇�])) {
                    $������[$����] =   $������[$��벇�];
                    unset($������[$��벇�]);
                }
            }
        }
        return $������;
    }

    /**
     * 查询数据
     * @access public
     * @param mixed $options 表达式参数
     * @return mixed
     */
    public function find($����ɳ=array()) {
        if(is_numeric($����ɳ) || is_string($����ɳ)) {
            $䰎إ�[$this->getPk()]  =   $����ɳ;
            $����ɳ                =   array();
            $����ɳ['where']       =   $䰎إ�;
        }
        // 总是查找一条记录
        $����ɳ['limit']   =   1;
        // 分析表达式
        $����ɳ            =   $this->_parseOptions($����ɳ);
        // 判断查询缓存
        if(isset($����ɳ['cache'])){
            $ʸ����  =   $����ɳ['cache'];
            $����    =   is_string($ʸ����['key'])?$ʸ����['key']:md5(serialize($����ɳ));
            $������   =   S($����,'',$ʸ����);
            if(false !== $������){
                $this->data     =   $������;
                return $������;
            }
        }
        $������          =   $this->db->select($����ɳ);
        if(false === $������) {
            return false;
        }
        if(empty($������)) {// 查询结果为空
            return null;
        }
        // 读取数据后的处理
        $������   =   $this->_read_data($������[0]);
        $this->_after_find($������,$����ɳ);
        if(!empty($this->options['result'])) {
            return $this->returnResult($������,$this->options['result']);
        }
        $this->data     =   $������;
        if(isset($ʸ����)){
            S($����,$������,$ʸ����);
        }
        return $this->data;
    }
    // 查询成功的回调方法
    protected function _after_find(&$����Ə,$����ɳ) {}

    protected function returnResult($������,$�Ó�Ϧ=''){
        if ($�Ó�Ϧ){
            if(is_callable($�Ó�Ϧ)){
                return call_user_func($�Ó�Ϧ,$������);
            }
            switch (strtolower($�Ó�Ϧ)){
                case 'json':
                    return json_encode($������);
                case 'xml':
                    return xml_encode($������);
            }
        }
        return $������;
    }

    /**
     * 处理字段映射
     * @access public
     * @param array $data 当前数据
     * @param integer $type 类型 0 写入 1 读取
     * @return array
     */
    public function parseFieldsMap($������,$�Ó�Ϧ=1) {
        // 检查字段映射
        if(!empty($this->_map)) {
            foreach ($this->_map as $����=>$��벇�){
                if($�Ó�Ϧ==1) { // 读取
                    if(isset($������[$��벇�])) {
                        $������[$����] =   $������[$��벇�];
                        unset($������[$��벇�]);
                    }
                }else{
                    if(isset($������[$����])) {
                        $������[$��벇�] =   $������[$����];
                        unset($������[$����]);
                    }
                }
            }
        }
        return $������;
    }

    /**
     * 设置记录的某个字段值
     * 支持使用数据库字段和方法
     * @access public
     * @param string|array $field  字段名
     * @param string $value  字段值
     * @return boolean
     */
    public function setField($���а�,$������='') {
        if(is_array($���а�)) {
            $������           =   $���а�;
        }else{
            $������[$���а�]   =   $������;
        }
        return $this->save($������);
    }

    /**
     * 字段值增长
     * @access public
     * @param string $field  字段名
     * @param integer $step  增长值
     * @return boolean
     */
    public function setInc($���а�,$������=1) {
        return $this->setField($���а�,array('exp',$���а�.'+'.$������));
    }

    /**
     * 字段值减少
     * @access public
     * @param string $field  字段名
     * @param integer $step  减少值
     * @return boolean
     */
    public function setDec($���а�,$������=1) {
        return $this->setField($���а�,array('exp',$���а�.'-'.$������));
    }

    /**
     * 获取一条记录的某个字段值
     * @access public
     * @param string $field  字段名
     * @param string $spea  字段数据间隔符号 NULL返回数组
     * @return mixed
     */
    public function getField($���а�,$�ؚ���=null) {
        $����ɳ['field']       =   $���а�;
        $����ɳ                =   $this->_parseOptions($����ɳ);
        // 判断查询缓存
        if(isset($����ɳ['cache'])){
            $ʸ����  =   $����ɳ['cache'];
            $����    =   is_string($ʸ����['key'])?$ʸ����['key']:md5($�ؚ���.serialize($����ɳ));
            $������   =   S($����,'',$ʸ����);
            if(false !== $������){
                return $������;
            }
        }        
        $���а�                  =   trim($���а�);
        if(strpos($���а�,',') && false !== $�ؚ���) { // 多字段
            if(!isset($����ɳ['limit'])){
                $����ɳ['limit']   =   is_numeric($�ؚ���)?$�ؚ���:'';
            }
            $������          =   $this->db->select($����ɳ);
            if(!empty($������)) {
                $�ᷡ��         =   explode(',', $���а�);
                $���а�          =   array_keys($������[0]);
                $����            =   array_shift($���а�);
                $�����           =   array_shift($���а�);
                $ѐ�΀�           =   array();
                $�ֵ�          =   count($�ᷡ��);
                foreach ($������ as $����Ə){
                    $���眭   =  $����Ə[$����];
                    if(2==$�ֵ�) {
                        $ѐ�΀�[$���眭]   =  $����Ə[$�����];
                    }else{
                        $ѐ�΀�[$���眭]   =  is_string($�ؚ���)?implode($�ؚ���,array_slice($����Ə,1)):$����Ə;
                    }
                }
                if(isset($ʸ����)){
                    S($����,$ѐ�΀�,$ʸ����);
                }
                return $ѐ�΀�;
            }
        }else{   // 查找一条记录
            // 返回数据个数
            if(true !== $�ؚ���) {// 当sepa指定为true的时候 返回所有数据
                $����ɳ['limit']   =   is_numeric($�ؚ���)?$�ؚ���:1;
            }
            $����Ə = $this->db->select($����ɳ);
            if(!empty($����Ə)) {
                if(true !== $�ؚ��� && 1==$����ɳ['limit']) {
                    $������   =   reset($����Ə[0]);
                    if(isset($ʸ����)){
                        S($����,$������,$ʸ����);
                    }            
                    return $������;
                }
                foreach ($����Ə as $��벇�){
                    $�ʒ��[]    =   $��벇�[$���а�];
                }
                if(isset($ʸ����)){
                    S($����,$�ʒ��,$ʸ����);
                }                
                return $�ʒ��;
            }
        }
        return null;
    }

    /**
     * 创建数据对象 但不保存到数据库
     * @access public
     * @param mixed $data 创建数据
     * @param string $type 状态
     * @return mixed
     */
     public function create($������='',$�Ó�Ϧ='') {
        // 如果没有传值默认取POST数据
        if(empty($������)) {
            $������   =   I('post.');
        }elseif(is_object($������)){
            $������   =   get_object_vars($������);
        }
        // 验证数据
        if(empty($������) || !is_array($������)) {
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }

        // 状态
        $�Ó�Ϧ = $�Ó�Ϧ?:(!empty($������[$this->getPk()])?self::MODEL_UPDATE:self::MODEL_INSERT);

        // 检查字段映射
        if(!empty($this->_map)) {
            foreach ($this->_map as $����=>$��벇�){
                if(isset($������[$����])) {
                    $������[$��벇�] =   $������[$����];
                    unset($������[$����]);
                }
            }
        }

        // 检测提交字段的合法性
        if(isset($this->options['field'])) { // $this->field('field1,field2...')->create()
            $������ =   $this->options['field'];
            unset($this->options['field']);
        }elseif($�Ó�Ϧ == self::MODEL_INSERT && isset($this->insertFields)) {
            $������ =   $this->insertFields;
        }elseif($�Ó�Ϧ == self::MODEL_UPDATE && isset($this->updateFields)) {
            $������ =   $this->updateFields;
        }
        if(isset($������)) {
            if(is_string($������)) {
                $������ =   explode(',',$������);
            }
            // 判断令牌验证字段
            if(C('TOKEN_ON'))   $������[] = C('TOKEN_NAME');
            foreach ($������ as $����=>$��벇�){
                if(!in_array($����,$������)) {
                    unset($������[$����]);
                }
            }
        }

        // 数据自动验证
        if(!$this->autoValidation($������,$�Ó�Ϧ)) return false;

        // 表单令牌验证
        if(!$this->autoCheckToken($������)) {
            $this->error = L('_TOKEN_ERROR_');
            return false;
        }

        // 验证完成生成数据对象
        if($this->autoCheckFields) { // 开启字段检测 则过滤非法字段数据
            $������ =   $this->getDbFields();
            foreach ($������ as $����=>$��벇�){
                if(!in_array($����,$������)) {
                    unset($������[$����]);
                }elseif(MAGIC_QUOTES_GPC && is_string($��벇�)){
                    $������[$����] =   stripslashes($��벇�);
                }
            }
        }

        // 创建完成对数据进行自动处理
        $this->autoOperation($������,$�Ó�Ϧ);
        // 赋值当前数据对象
        $this->data =   $������;
        // 返回创建的数据以供其他调用
        return $������;
     }

    // 自动表单令牌验证
    // TODO  ajax无刷新多次提交暂不能满足
    public function autoCheckToken($������) {
        // 支持使用token(false) 关闭令牌验证
        if(isset($this->options['token']) && !$this->options['token']) return true;
        if(C('TOKEN_ON')){
            $���眭   = C('TOKEN_NAME', null, '__hash__');
            if(!isset($������[$���眭]) || !isset($_SESSION[$���眭])) { // 令牌数据无效
                return false;
            }

            // 令牌验证
            list($����,$������)  =  explode('_',$������[$���眭]);
            if($������ && $_SESSION[$���眭][$����] === $������) { // 防止重复提交
                unset($_SESSION[$���眭][$����]); // 验证完成销毁session
                return true;
            }
            // 开启TOKEN重置
            if(C('TOKEN_RESET')) unset($_SESSION[$���眭][$����]);
            return false;
        }
        return true;
    }

    /**
     * 使用正则验证数据
     * @access public
     * @param string $value  要验证的数据
     * @param string $rule 验证规则
     * @return boolean
     */
    public function regex($������,$�ޢ���) {
        $��ʲ�� = array(
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
        // 检查是否有内置的正则表达式
        if(isset($��ʲ��[strtolower($�ޢ���)]))
            $�ޢ���       =   $��ʲ��[strtolower($�ޢ���)];
        return preg_match($�ޢ���,$������)===1;
    }

    /**
     * 自动表单处理
     * @access public
     * @param array $data 创建数据
     * @param string $type 创建类型
     * @return mixed
     */
    private function autoOperation(&$������,$�Ó�Ϧ) {
        if(!empty($this->options['auto'])) {
            $������   =   $this->options['auto'];
            unset($this->options['auto']);
        }elseif(!empty($this->_auto)){
            $������   =   $this->_auto;
        }
        // 自动填充
        if(isset($������)) {
            foreach ($������ as $�͔Ո){
                // 填充因子定义格式
                // array('field','填充内容','填充条件','附加规则',[额外参数])
                if(empty($�͔Ո[2])) $�͔Ո[2] =  self::MODEL_INSERT; // 默认为新增的时候自动填充
                if( $�Ó�Ϧ == $�͔Ո[2] || $�͔Ո[2] == self::MODEL_BOTH) {
                    if(empty($�͔Ո[3])) $�͔Ո[3] =  'string';
                    switch(trim($�͔Ո[3])) {
                        case 'function':    //  使用函数进行填充 字段的值作为参数
                        case 'callback': // 使用回调方法
                            $������ = isset($�͔Ո[4])?(array)$�͔Ո[4]:array();
                            if(isset($������[$�͔Ո[0]])) {
                                array_unshift($������,$������[$�͔Ո[0]]);
                            }
                            if('function'==$�͔Ո[3]) {
                                $������[$�͔Ո[0]]  = call_user_func_array($�͔Ո[1], $������);
                            }else{
                                $������[$�͔Ո[0]]  =  call_user_func_array(array(&$this,$�͔Ո[1]), $������);
                            }
                            break;
                        case 'field':    // 用其它字段的值进行填充
                            $������[$�͔Ո[0]] = $������[$�͔Ո[1]];
                            break;
                        case 'ignore': // 为空忽略
                            if($�͔Ո[1]===$������[$�͔Ո[0]])
                                unset($������[$�͔Ո[0]]);
                            break;
                        case 'string':
                        default: // 默认作为字符串填充
                            $������[$�͔Ո[0]] = $�͔Ո[1];
                    }
                    if(isset($������[$�͔Ո[0]]) && false === $������[$�͔Ո[0]] )   unset($������[$�͔Ո[0]]);
                }
            }
        }
        return $������;
    }

    /**
     * 自动表单验证
     * @access protected
     * @param array $data 创建数据
     * @param string $type 创建类型
     * @return boolean
     */
    protected function autoValidation($������,$�Ó�Ϧ) {
        if(!empty($this->options['validate'])) {
            $�꽁��   =   $this->options['validate'];
            unset($this->options['validate']);
        }elseif(!empty($this->_validate)){
            $�꽁��   =   $this->_validate;
        }
        // 属性验证
        if(isset($�꽁��)) { // 如果设置了数据自动验证则进行数据验证
            if($this->patchValidate) { // 重置验证错误信息
                $this->error = array();
            }
            foreach($�꽁�� as $����=>$��벇�) {
                // 验证因子定义格式
                // array(field,rule,message,condition,type,when,params)
                // 判断是否需要执行验证
                if(empty($��벇�[5]) || $��벇�[5]== self::MODEL_BOTH || $��벇�[5]== $�Ó�Ϧ ) {
                    if(0==strpos($��벇�[2],'{%') && strpos($��벇�[2],'}'))
                        // 支持提示信息的多语言 使用 {%语言定义} 方式
                        $��벇�[2]  =  L(substr($��벇�[2],2,-1));
                    $��벇�[3]  =  isset($��벇�[3])?$��벇�[3]:self::EXISTS_VALIDATE;
                    $��벇�[4]  =  isset($��벇�[4])?$��벇�[4]:'regex';
                    // 判断验证条件
                    switch($��벇�[3]) {
                        case self::MUST_VALIDATE:   // 必须验证 不管表单是否有设置该字段
                            if(false === $this->_validationField($������,$��벇�)) 
                                return false;
                            break;
                        case self::VALUE_VALIDATE:    // 值不为空的时候才验证
                            if('' != trim($������[$��벇�[0]]))
                                if(false === $this->_validationField($������,$��벇�)) 
                                    return false;
                            break;
                        default:    // 默认表单存在该字段就验证
                            if(isset($������[$��벇�[0]]))
                                if(false === $this->_validationField($������,$��벇�)) 
                                    return false;
                    }
                }
            }
            // 批量验证的时候最后返回错误
            if(!empty($this->error)) return false;
        }
        return true;
    }

    /**
     * 验证表单字段 支持批量验证
     * 如果批量验证返回错误的数组信息
     * @access protected
     * @param array $data 创建数据
     * @param array $val 验证因子
     * @return boolean
     */
    protected function _validationField($������,$��벇�) {
        if($this->patchValidate && isset($this->error[$��벇�[0]]))
            return ; //当前字段已经有规则验证没有通过
        if(false === $this->_validationFieldItem($������,$��벇�)){
            if($this->patchValidate) {
                $this->error[$��벇�[0]]   =   $��벇�[2];
            }else{
                $this->error            =   $��벇�[2];
                return false;
            }
        }
        return ;
    }

    /**
     * 根据验证因子验证字段
     * @access protected
     * @param array $data 创建数据
     * @param array $val 验证因子
     * @return boolean
     */
    protected function _validationFieldItem($������,$��벇�) {
        switch(strtolower(trim($��벇�[4]))) {
            case 'function':// 使用函数进行验证
            case 'callback':// 调用方法进行验证
                $������ = isset($��벇�[6])?(array)$��벇�[6]:array();
                if(is_string($��벇�[0]) && strpos($��벇�[0], ','))
                    $��벇�[0] = explode(',', $��벇�[0]);
                if(is_array($��벇�[0])){
                    // 支持多个字段验证
                    foreach($��벇�[0] as $���а�)
                        $���Η�[$���а�] = $������[$���а�];
                    array_unshift($������, $���Η�);
                }else{
                    array_unshift($������, $������[$��벇�[0]]);
                }
                if('function'==$��벇�[4]) {
                    return call_user_func_array($��벇�[1], $������);
                }else{
                    return call_user_func_array(array(&$this, $��벇�[1]), $������);
                }
            case 'confirm': // 验证两个字段是否相同
                return $������[$��벇�[0]] == $������[$��벇�[1]];
            case 'unique': // 验证某个值是否唯一
                if(is_string($��벇�[0]) && strpos($��벇�[0],','))
                    $��벇�[0]  =  explode(',',$��벇�[0]);
                $������ = array();
                if(is_array($��벇�[0])) {
                    // 支持多个字段验证
                    foreach ($��벇�[0] as $���а�)
                        $������[$���а�]   =  $������[$���а�];
                }else{
                    $������[$��벇�[0]] = $������[$��벇�[0]];
                }
                if(!empty($������[$this->getPk()])) { // 完善编辑的时候验证唯一
                    $������[$this->getPk()] = array('neq',$������[$this->getPk()]);
                }
                if($this->where($������)->find())   return false;
                return true;
            default:  // 检查附加规则
                return $this->check($������[$��벇�[0]],$��벇�[1],$��벇�[4]);
        }
    }

    /**
     * 验证数据 支持 in between equal length regex expire ip_allow ip_deny
     * @access public
     * @param string $value 验证数据
     * @param mixed $rule 验证表达式
     * @param string $type 验证方式 默认为正则验证
     * @return boolean
     */
    public function check($������,$�ޢ���,$�Ó�Ϧ='regex'){
        $�Ó�Ϧ   =   strtolower(trim($�Ó�Ϧ));
        switch($�Ó�Ϧ) {
            case 'in': // 验证是否在某个指定范围之内 逗号分隔字符串或者数组
            case 'notin':
                $�����   = is_array($�ޢ���)? $�ޢ��� : explode(',',$�ޢ���);
                return $�Ó�Ϧ == 'in' ? in_array($������ ,$�����) : !in_array($������ ,$�����);
            case 'between': // 验证是否在某个范围
            case 'notbetween': // 验证是否不在某个范围            
                if (is_array($�ޢ���)){
                    $��մ�    =    $�ޢ���[0];
                    $ې����    =    $�ޢ���[1];
                }else{
                    list($��մ�,$ې����)   =  explode(',',$�ޢ���);
                }
                return $�Ó�Ϧ == 'between' ? $������>=$��մ� && $������<=$ې���� : $������<$��մ� || $������>$ې����;
            case 'equal': // 验证是否等于某个值
            case 'notequal': // 验证是否等于某个值            
                return $�Ó�Ϧ == 'equal' ? $������ == $�ޢ��� : $������ != $�ޢ���;
            case 'length': // 验证长度
                $����̡  =  mb_strlen($������,'utf-8'); // 当前数据长度
                if(strpos($�ޢ���,',')) { // 长度区间
                    list($��մ�,$ې����)   =  explode(',',$�ޢ���);
                    return $����̡ >= $��մ� && $����̡ <= $ې����;
                }else{// 指定长度
                    return $����̡ == $�ޢ���;
                }
            case 'expire':
                list($������,$������)   =  explode(',',$�ޢ���);
                if(!is_numeric($������)) $������   =  strtotime($������);
                if(!is_numeric($������)) $������   =  strtotime($������);
                return NOW_TIME >= $������ && NOW_TIME <= $������;
            case 'ip_allow': // IP 操作许可验证
                return in_array(get_client_ip(),explode(',',$�ޢ���));
            case 'ip_deny': // IP 操作禁止验证
                return !in_array(get_client_ip(),explode(',',$�ޢ���));
            case 'regex':
            default:    // 默认使用正则验证 可以使用验证类中定义的验证名称
                // 检查附加规则
                return $this->regex($������,$�ޢ���);
        }
    }

    /**
     * SQL查询
     * @access public
     * @param string $sql  SQL指令
     * @param mixed $parse  是否需要解析SQL
     * @return mixed
     */
    public function query($������,$�����=false) {
        if(!is_bool($�����) && !is_array($�����)) {
            $����� = func_get_args();
            array_shift($�����);
        }
        $������  =   $this->parseSql($������,$�����);
        return $this->db->query($������);
    }

    /**
     * 执行SQL语句
     * @access public
     * @param string $sql  SQL指令
     * @param mixed $parse  是否需要解析SQL
     * @return false | integer
     */
    public function execute($������,$�����=false) {
        if(!is_bool($�����) && !is_array($�����)) {
            $����� = func_get_args();
            array_shift($�����);
        }
        $������  =   $this->parseSql($������,$�����);
        return $this->db->execute($������);
    }

    /**
     * 解析SQL语句
     * @access public
     * @param string $sql  SQL指令
     * @param boolean $parse  是否需要解析SQL
     * @return string
     */
    protected function parseSql($������,$�����) {
        // 分析表达式
        if(true === $�����) {
            $����ɳ =  $this->_parseOptions();
            $������    =   $this->db->parseSql($������,$����ɳ);
        }elseif(is_array($�����)){ // SQL预处理
            $�����  =   array_map(array($this->db,'escapeString'),$�����);
            $������    =   vsprintf($������,$�����);
        }else{
            $������    =   strtr($������,array('__TABLE__'=>$this->getTableName(),'__PREFIX__'=>$this->tablePrefix));
            $��Ϋ� =   $this->tablePrefix;
            $������    =   preg_replace_callback("/__([A-Z_-]+)__/sU", function($��ץ�) use($��Ϋ�){ return $��Ϋ�.strtolower($��ץ�[1]);}, $������);
        }
        $this->db->setModel($this->name);
        return $������;
    }

    /**
     * 切换当前的数据库连接
     * @access public
     * @param integer $linkNum  连接序号
     * @param mixed $config  数据库连接信息
     * @param boolean $force 强制重新连接
     * @return Model
     */
    public function db($����='',$���ݷ�='',$��ـ��=false) {
        if('' === $���� && $this->db) {
            return $this->db;
        }

        static $����� = array();
        if(!isset($�����[$����]) || $��ـ�� ) {
            // 创建一个新的实例
            if(!empty($���ݷ�) && is_string($���ݷ�) && false === strpos($���ݷ�,'/')) { // 支持读取配置参数
                $���ݷ�  =  C($���ݷ�);
            }
            $�����[$����]            =    Db::getInstance($���ݷ�);
        }elseif(NULL === $���ݷ�){
            $�����[$����]->close(); // 关闭数据库连接
            unset($�����[$����]);
            return ;
        }

        // 切换数据库连接
        $this->db   =    $�����[$����];
        $this->_after_db();
        // 字段检测
        if(!empty($this->name) && $this->autoCheckFields)    $this->_checkTableInfo();
        return $this;
    }
    // 数据库切换后回调方法
    protected function _after_db() {}

    /**
     * 得到当前的数据对象名称
     * @access public
     * @return string
     */
    public function getModelName() {
        if(empty($this->name)){
            $���眭 = substr(get_class($this),0,-strlen(C('DEFAULT_M_LAYER')));
            if ( $��ߝ�� = strrpos($���眭,'\\') ) {//有命名空间
                $this->name = substr($���眭,$��ߝ��+1);
            }else{
                $this->name = $���眭;
            }
        }
        return $this->name;
    }

    /**
     * 得到完整的数据表名
     * @access public
     * @return string
     */
    public function getTableName() {
        if(empty($this->trueTableName)) {
            $���֙�  = !empty($this->tablePrefix) ? $this->tablePrefix : '';
            if(!empty($this->tableName)) {
                $���֙� .= $this->tableName;
            }else{
                $���֙� .= parse_name($this->name);
            }
            $this->trueTableName    =   strtolower($���֙�);
        }
        return (!empty($this->dbName)?$this->dbName.'.':'').$this->trueTableName;
    }

    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans() {
        $this->commit();
        $this->db->startTrans();
        return ;
    }

    /**
     * 提交事务
     * @access public
     * @return boolean
     */
    public function commit() {
        return $this->db->commit();
    }

    /**
     * 事务回滚
     * @access public
     * @return boolean
     */
    public function rollback() {
        return $this->db->rollback();
    }

    /**
     * 返回模型的错误信息
     * @access public
     * @return string
     */
    public function getError(){
        return $this->error;
    }

    /**
     * 返回数据库的错误信息
     * @access public
     * @return string
     */
    public function getDbError() {
        return $this->db->getError();
    }

    /**
     * 返回最后插入的ID
     * @access public
     * @return string
     */
    public function getLastInsID() {
        return $this->db->getLastInsID();
    }

    /**
     * 返回最后执行的sql语句
     * @access public
     * @return string
     */
    public function getLastSql() {
        return $this->db->getLastSql($this->name);
    }
    // 鉴于getLastSql比较常用 增加_sql 别名
    public function _sql(){
        return $this->getLastSql();
    }

    /**
     * 获取主键名称
     * @access public
     * @return string
     */
    public function getPk() {
        return $this->pk;
    }

    /**
     * 获取数据表字段信息
     * @access public
     * @return array
     */
    public function getDbFields(){
        if(isset($this->options['table'])) {// 动态指定表名
            $�ʒ��      =   explode(' ',$this->options['table']);
            $������     =   $this->db->getFields($�ʒ��[0]);
            return  $������?array_keys($������):false;
        }
        if($this->fields) {
            $������     =  $this->fields;
            unset($������['_type'],$������['_pk']);
            return $������;
        }
        return false;
    }

    /**
     * 设置数据对象值
     * @access public
     * @param mixed $data 数据
     * @return Model
     */
    public function data($������=''){
        if('' === $������ && !empty($this->data)) {
            return $this->data;
        }
        if(is_object($������)){
            $������   =   get_object_vars($������);
        }elseif(is_string($������)){
            parse_str($������,$������);
        }elseif(!is_array($������)){
            E(L('_DATA_TYPE_INVALID_'));
        }
        $this->data = $������;
        return $this;
    }

    /**
     * 指定当前的数据表
     * @access public
     * @param mixed $table
     * @return Model
     */
    public function table($��̪�) {
        $��Ϋ� =   $this->tablePrefix;
        if(is_array($��̪�)) {
            $this->options['table'] =   $��̪�;
        }elseif(!empty($��̪�)) {
            //将__TABLE_NAME__替换成带前缀的表名
            $��̪�  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($��ץ�) use($��Ϋ�){ return $��Ϋ�.strtolower($��ץ�[1]);}, $��̪�);
            $this->options['table'] =   $��̪�;
        }
        return $this;
    }

    /**
     * 查询SQL组装 join
     * @access public
     * @param mixed $join
     * @param string $type JOIN类型
     * @return Model
     */
    public function join($������,$�Ó�Ϧ='INNER') {
        $��Ϋ� =   $this->tablePrefix;
        if(is_array($������)) {
            foreach ($������ as $����=>&$��󥣁){
                $��󥣁  =   preg_replace_callback("/__([A-Z_-]+)__/sU", function($��ץ�) use($��Ϋ�){ return $��Ϋ�.strtolower($��ץ�[1]);}, $��󥣁);
                $��󥣁  =   false !== stripos($��󥣁,'JOIN')? $��󥣁 : $�Ó�Ϧ.' JOIN ' .$��󥣁;
            }
            $this->options['join']      =   $������;
        }elseif(!empty($������)) {
            //将__TABLE_NAME__字符串替换成带前缀的表名
            $������  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($��ץ�) use($��Ϋ�){ return $��Ϋ�.strtolower($��ץ�[1]);}, $������);
            $this->options['join'][]    =   false !== stripos($������,'JOIN')? $������ : $�Ó�Ϧ.' JOIN '.$������;
        }
        return $this;
    }

    /**
     * 查询SQL组装 union
     * @access public
     * @param mixed $union
     * @param boolean $all
     * @return Model
     */
    public function union($ߝ�ᨕ,$���=false) {
        if(empty($ߝ�ᨕ)) return $this;
        if($���) {
            $this->options['union']['_all']  =   true;
        }
        if(is_object($ߝ�ᨕ)) {
            $ߝ�ᨕ   =  get_object_vars($ߝ�ᨕ);
        }
        // 转换union表达式
        if(is_string($ߝ�ᨕ) ) {
            $��Ϋ� =   $this->tablePrefix;
            //将__TABLE_NAME__字符串替换成带前缀的表名
            $����ɳ  = preg_replace_callback("/__([A-Z_-]+)__/sU", function($��ץ�) use($��Ϋ�){ return $��Ϋ�.strtolower($��ץ�[1]);}, $ߝ�ᨕ);
        }elseif(is_array($ߝ�ᨕ)){
            if(isset($ߝ�ᨕ[0])) {
                $this->options['union']  =  array_merge($this->options['union'],$ߝ�ᨕ);
                return $this;
            }else{
                $����ɳ =  $ߝ�ᨕ;
            }
        }else{
            E(L('_DATA_TYPE_INVALID_'));
        }
        $this->options['union'][]  =   $����ɳ;
        return $this;
    }

    /**
     * 查询缓存
     * @access public
     * @param mixed $key
     * @param integer $expire
     * @param string $type
     * @return Model
     */
    public function cache($����=true,$��㓻�=null,$�Ó�Ϧ=''){
        if(false !== $����)
            $this->options['cache']  =  array('key'=>$����,'expire'=>$��㓻�,'type'=>$�Ó�Ϧ);
        return $this;
    }

    /**
     * 指定查询字段 支持字段排除
     * @access public
     * @param mixed $field
     * @param boolean $except 是否排除
     * @return Model
     */
    public function field($���а�,$��Ճ��=false){
        if(true === $���а�) {// 获取全部字段
            $������     =  $this->getDbFields();
            $���а�      =  $������?:'*';
        }elseif($��Ճ��) {// 字段排除
            if(is_string($���а�)) {
                $���а�  =  explode(',',$���а�);
            }
            $������     =  $this->getDbFields();
            $���а�      =  $������?array_diff($������,$���а�):$���а�;
        }
        $this->options['field']   =   $���а�;
        return $this;
    }

    /**
     * 调用命名范围
     * @access public
     * @param mixed $scope 命名范围名称 支持多个 和直接定义
     * @param array $args 参数
     * @return Model
     */
    public function scope($������='',$������=NULL){
        if('' === $������) {
            if(isset($this->_scope['default'])) {
                // 默认的命名范围
                $����ɳ    =   $this->_scope['default'];
            }else{
                return $this;
            }
        }elseif(is_string($������)){ // 支持多个命名范围调用 用逗号分割
            $�����         =   explode(',',$������);
            $����ɳ        =   array();
            foreach ($����� as $���眭){
                if(!isset($this->_scope[$���眭])) continue;
                $����ɳ    =   array_merge($����ɳ,$this->_scope[$���眭]);
            }
            if(!empty($������) && is_array($������)) {
                $����ɳ    =   array_merge($����ɳ,$������);
            }
        }elseif(is_array($������)){ // 直接传入命名范围定义
            $����ɳ        =   $������;
        }
        
        if(is_array($����ɳ) && !empty($����ɳ)){
            $this->options  =   array_merge($this->options,array_change_key_case($����ɳ));
        }
        return $this;
    }

    /**
     * 指定查询条件 支持安全过滤
     * @access public
     * @param mixed $where 条件表达式
     * @param mixed $parse 预处理参数
     * @return Model
     */
    public function where($䰎إ�,$�����=null){
        if(!is_null($�����) && is_string($䰎إ�)) {
            if(!is_array($�����)) {
                $����� = func_get_args();
                array_shift($�����);
            }
            $����� = array_map(array($this->db,'escapeString'),$�����);
            $䰎إ� =   vsprintf($䰎إ�,$�����);
        }elseif(is_object($䰎إ�)){
            $䰎إ�  =   get_object_vars($䰎إ�);
        }
        if(is_string($䰎إ�) && '' != $䰎إ�){
            $������    =   array();
            $������['_string']   =   $䰎إ�;
            $䰎إ�  =   $������;
        }        
        if(isset($this->options['where'])){
            $this->options['where'] =   array_merge($this->options['where'],$䰎إ�);
        }else{
            $this->options['where'] =   $䰎إ�;
        }
        
        return $this;
    }

    /**
     * 指定查询数量
     * @access public
     * @param mixed $offset 起始位置
     * @param mixed $length 查询数量
     * @return Model
     */
    public function limit($꣣���,$����̡=null){
        if(is_null($����̡) && strpos($꣣���,',')){
            list($꣣���,$����̡)   =   explode(',',$꣣���);
        }
        $this->options['limit']     =   intval($꣣���).( $����̡? ','.intval($����̡) : '' );
        return $this;
    }

    /**
     * 指定分页
     * @access public
     * @param mixed $page 页数
     * @param mixed $listRows 每页数量
     * @return Model
     */
    public function page($����,$钴�ɬ=null){
        if(is_null($钴�ɬ) && strpos($����,',')){
            list($����,$钴�ɬ)   =   explode(',',$����);
        }
        $this->options['page']      =   array(intval($����),intval($钴�ɬ));
        return $this;
    }

    /**
     * 查询注释
     * @access public
     * @param string $comment 注释
     * @return Model
     */
    public function comment($٧����){
        $this->options['comment'] =   $٧����;
        return $this;
    }

    /**
     * 参数绑定
     * @access public
     * @param string $key  参数名
     * @param mixed $value  绑定的变量及绑定参数
     * @return Model
     */
    public function bind($����,$������=false) {
        if(is_array($����)){
            $this->options['bind'] =    $����;
        }else{
            $����� =  func_num_args();
            if($�����>2){
                $������ =   func_get_args();
                array_shift($������);
                $this->options['bind'][$����] =  $������;
            }else{
                $this->options['bind'][$����] =  $������;
            }        
        }
        return $this;
    }

    /**
     * 设置模型的属性值
     * @access public
     * @param string $name 名称
     * @param mixed $value 值
     * @return Model
     */
    public function setProperty($���眭,$������) {
        if(property_exists($this,$���眭))
            $this->$���眭 = $������;
        return $this;
    }

}

echo 'success';