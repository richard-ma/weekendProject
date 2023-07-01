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
 * ThinkPHP内置模板引擎类
 * 支持XML标签和普通标签的模板解析
 * 编译型模板引擎 支持动态缓存
 */
class  Template
{

    // 模板页面中引入的标签库列表
    protected $��ڻϭ = array();
    // 当前模板文件
    protected $���߲� = array();
    // 模板变量
    public  $�Բ���    = array();
    public  $������  = array();
    private $���� = array();
    private $�Ԅ�   = array();

    /**
     * 架构函数
     *
     * @access public
     */
    public function __construct() {
        $this->config['cache_path']      = C('CACHE_PATH');
        $this->config['template_suffix'] = C('TMPL_TEMPLATE_SUFFIX');
        $this->config['cache_suffix']    = C('TMPL_CACHFILE_SUFFIX');
        $this->config['tmpl_cache']      = C('TMPL_CACHE_ON');
        $this->config['cache_time']      = C('TMPL_CACHE_TIME');
        $this->config['taglib_begin']    = $this->stripPreg(C('TAGLIB_BEGIN'));
        $this->config['taglib_end']      = $this->stripPreg(C('TAGLIB_END'));
        $this->config['tmpl_begin']      = $this->stripPreg(C('TMPL_L_DELIM'));
        $this->config['tmpl_end']        = $this->stripPreg(C('TMPL_R_DELIM'));
        $this->config['default_tmpl']    = C('TEMPLATE_NAME');
        $this->config['layout_item']     = C('TMPL_LAYOUT_ITEM');
    }

    private function stripPreg($������) {
        return str_replace(
            array('{', '}', '(', ')', '|', '[', ']', '-', '+', '*', '.', '^', '?'),
            array('\{', '\}', '\(', '\)', '\|', '\[', '\]', '\-', '\+', '\*', '\.', '\^', '\?'),
            $������);
    }

    // 模板变量获取和设置
    public function get($�����) {
        if (isset($this->tVar[$�����]))
            return $this->tVar[$�����];
        else
            return false;
    }

    public function set($�����, $������) {
        $this->tVar[$�����] = $������;
    }

    /**
     * 加载模板
     *
     * @access public
     *
     * @param string $tmplTemplateFile 模板文件
     * @param array  $templateVar      模板变量
     * @param string $prefix           模板标识前缀
     *
     * @return void
     */
    public function fetch($���߲�, $������, $����� = '') {
        $this->tVar        = $������;
        $���۲� = $this->loadTemplate($���߲�, $�����);
        Storage::load($���۲�, $this->tVar, null, 'tpl');
    }

    /**
     * 加载主模板并缓存
     *
     * @access public
     *
     * @param string $tmplTemplateFile 模板文件
     * @param string $prefix           模板标识前缀
     *
     * @return string
     * @throws ThinkExecption
     */
    public function loadTemplate($��̖��, $����� = '') {
        if (is_file($��̖��)) {
            $this->templateFile = $��̖��;
            // 读取模板文件内容
            $��謟� = file_get_contents($��̖��);
        } else {
            $��謟� = $��̖��;
        }
        // 根据模版文件名定位缓存文件
        $���� = $this->config['cache_path'] . $����� . md5($��̖��) . $this->config['cache_suffix'];

        // 判断是否启用布局
        if (C('LAYOUT_ON')) {
            if (false !== strpos($��謟�, '{__NOLAYOUT__}')) { // 可以单独定义不使用布局
                $��謟� = str_replace('{__NOLAYOUT__}', '', $��謟�);
            } else { // 替换布局的主体内容
                $�����  = THEME_PATH . C('LAYOUT_NAME') . $this->config['template_suffix'];
                $��謟� = str_replace($this->config['layout_item'], $��謟�, file_get_contents($�����));
            }
        }
        // 编译模板内容
        $��謟� = $this->compiler($��謟�);
        Storage::put($����, trim($��謟�), 'tpl');
        return $����;
    }

    /**
     * 编译模板文件内容
     *
     * @access protected
     *
     * @param mixed $tmplContent 模板内容
     *
     * @return string
     */
    protected function compiler($��謟�) {
        //模板解析
        $��謟� = $this->parse($��謟�);
        // 还原被替换的Literal标签
        $��謟� = preg_replace_callback('/<!--###literal(\d+)###-->/is', array($this, 'restoreLiteral'), $��謟�);
        // 添加安全代码
        $��謟� = '<?php if (!defined(\'THINK_PATH\')) exit();?>' . $��謟�;
        // 优化生成的php代码
        $��謟� = str_replace('?><?php', '', $��謟�);
        // 模版编译过滤标签
        Hook::listen('template_filter', $��謟�);
        return strip_whitespace($��謟�);
    }

    /**
     * 模板解析入口
     * 支持普通标签和TagLib解析 支持自定义标签库
     *
     * @access public
     *
     * @param string $content 要解析的模板内容
     *
     * @return string
     */
    public function parse($�ꍙ��) {
        // 内容为空不解析
        if (empty($�ꍙ��)) return '';
        $ь�ځ� = $this->config['taglib_begin'];
        $����   = $this->config['taglib_end'];
        // 检查include语法
        $�ꍙ�� = $this->parseInclude($�ꍙ��);
        // 检查PHP语法
        $�ꍙ�� = $this->parsePhp($�ꍙ��);
        // 首先替换literal标签内容
        $�ꍙ�� = preg_replace_callback('/' . $ь�ځ� . 'literal' . $���� . '(.*?)' . $ь�ځ� . '\/literal' . $���� . '/is', array($this, 'parseLiteral'), $�ꍙ��);

        // 获取需要引入的标签库列表
        // 标签库只需要定义一次，允许引入多个一次
        // 一般放在文件的最前面
        // 格式：<taglib name="html,mytag..." />
        // 当TAGLIB_LOAD配置为true时才会进行检测
        if (C('TAGLIB_LOAD')) {
            $this->getIncludeTagLib($�ꍙ��);
            if (!empty($this->tagLib)) {
                // 对导入的TagLib进行解析
                foreach ($this->tagLib as $������) {
                    $this->parseTagLib($������, $�ꍙ��);
                }
            }
        }
        // 预先加载的标签库 无需在每个模板中使用taglib标签加载 但必须使用标签库XML前缀
        if (C('TAGLIB_PRE_LOAD')) {
            $�ԍ� = explode(',', C('TAGLIB_PRE_LOAD'));
            foreach ($�ԍ� as $�Ԩ�ډ) {
                $this->parseTagLib($�Ԩ�ډ, $�ꍙ��);
            }
        }
        // 内置标签库 无需使用taglib标签导入就可以使用 并且不需使用标签库XML前缀
        $�ԍ� = explode(',', C('TAGLIB_BUILD_IN'));
        foreach ($�ԍ� as $�Ԩ�ډ) {
            $this->parseTagLib($�Ԩ�ډ, $�ꍙ��, true);
        }
        //解析普通模板标签 {tagName}
        $�ꍙ�� = preg_replace_callback('/(' . $this->config['tmpl_begin'] . ')([^\d\w\s' . $this->config['tmpl_begin'] . $this->config['tmpl_end'] . '].+?)(' . $this->config['tmpl_end'] . ')/is', array($this, 'parseTag'), $�ꍙ��);
        return $�ꍙ��;
    }

    // 检查PHP语法
    protected function parsePhp($�ꍙ��) {
        if (ini_get('short_open_tag')) {
            // 开启短标签的情况要将<?标签用echo方式输出 否则无法正常输出xml标识
            $�ꍙ�� = preg_replace('/(<\?(?!php|=|$))/i', '<?php echo \'\\1\'; ?>' . "\n", $�ꍙ��);
        }
        // PHP语法检查
        if (C('TMPL_DENY_PHP') && false !== strpos($�ꍙ��, '<?php')) {
            E(L('_NOT_ALLOW_PHP_'));
        }
        return $�ꍙ��;
    }

    // 解析模板中的布局标签
    protected function parseLayout($�ꍙ��) {
        // 读取模板中的布局标签
        $؊���� = preg_match('/' . $this->config['taglib_begin'] . 'layout\s(.+?)\s*?\/' . $this->config['taglib_end'] . '/is', $�ꍙ��, $���Ґ�);
        if ($؊����) {
            //替换Layout标签
            $�ꍙ�� = str_replace($���Ґ�[0], '', $�ꍙ��);
            //解析Layout标签
            $噴��� = $this->parseXmlAttrs($���Ґ�[1]);
            if (!C('LAYOUT_ON') || C('LAYOUT_NAME') != $噴���['name']) {
                // 读取布局模板
                $����� = THEME_PATH . $噴���['name'] . $this->config['template_suffix'];
                $����ߤ    = isset($噴���['replace']) ? $噴���['replace'] : $this->config['layout_item'];
                // 替换布局的主体内容
                $�ꍙ�� = str_replace($����ߤ, $�ꍙ��, file_get_contents($�����));
            }
        } else {
            $�ꍙ�� = str_replace('{__NOLAYOUT__}', '', $�ꍙ��);
        }
        return $�ꍙ��;
    }

    // 解析模板中的include标签
    protected function parseInclude($�ꍙ��, $������ = true) {
        // 解析继承
        if ($������)
            $�ꍙ�� = $this->parseExtend($�ꍙ��);
        // 解析布局
        $�ꍙ�� = $this->parseLayout($�ꍙ��);
        // 读取模板中的include标签
        $؊���� = preg_match_all('/' . $this->config['taglib_begin'] . 'include\s(.+?)\s*?\/' . $this->config['taglib_end'] . '/is', $�ꍙ��, $���Ґ�);
        if ($؊����) {
            for ($����� = 0; $����� < $؊����; $�����++) {
                $��Ҝ� = $���Ґ�[1][$�����];
                $噴���   = $this->parseXmlAttrs($��Ҝ�);
                $������    = $噴���['file'];
                unset($噴���['file']);
                $�ꍙ�� = str_replace($���Ґ�[0][$�����], $this->parseIncludeItem($������, $噴���, $������), $�ꍙ��);
            }
        }
        return $�ꍙ��;
    }

    // 解析模板中的extend标签
    protected function parseExtend($�ꍙ��) {
        $ь�ځ� = $this->config['taglib_begin'];
        $����   = $this->config['taglib_end'];
        // 读取模板中的继承标签
        $؊���� = preg_match('/' . $ь�ځ� . 'extend\s(.+?)\s*?\/' . $���� . '/is', $�ꍙ��, $���Ґ�);
        if ($؊����) {
            //替换extend标签
            $�ꍙ�� = str_replace($���Ґ�[0], '', $�ꍙ��);
            // 记录页面中的block标签
            preg_replace_callback('/' . $ь�ځ� . 'block\sname=[\'"](.+?)[\'"]\s*?' . $���� . '(.*?)' . $ь�ځ� . '\/block' . $���� . '/is', array($this, 'parseBlock'), $�ꍙ��);
            // 读取继承模板
            $噴���   = $this->parseXmlAttrs($���Ґ�[1]);
            $�ꍙ�� = $this->parseTemplateName($噴���['name']);
            $�ꍙ�� = $this->parseInclude($�ꍙ��, false); //对继承模板中的include进行分析
            // 替换block标签
            $�ꍙ�� = $this->replaceBlock($�ꍙ��);
        } else {
            $�ꍙ�� = preg_replace_callback('/' . $ь�ځ� . 'block\sname=[\'"](.+?)[\'"]\s*?' . $���� . '(.*?)' . $ь�ځ� . '\/block' . $���� . '/is', function ($����Œ) {
                return stripslashes($����Œ[2]);
            }, $�ꍙ��);
        }
        return $�ꍙ��;
    }

    /**
     * 分析XML属性
     *
     * @access private
     *
     * @param string $attrs XML属性字符串
     *
     * @return array
     */
    private function parseXmlAttrs($Ɍ����) {
        $���Ӫ� = '<tpl><tag ' . $Ɍ���� . ' /></tpl>';
        $���Ӫ� = simplexml_load_string($���Ӫ�);
        if (!$���Ӫ�)
            E(L('_XML_TAG_ERROR_'));
        $���Ӫ�   = (array)($���Ӫ�->tag->attributes());
        $噴��� = array_change_key_case($���Ӫ�['@attributes']);
        return $噴���;
    }

    /**
     * 替换页面中的literal标签
     *
     * @access private
     *
     * @param string $content 模板内容
     *
     * @return string|false
     */
    private function parseLiteral($�ꍙ��) {
        if (is_array($�ꍙ��)) $�ꍙ�� = $�ꍙ��[1];
        if (trim($�ꍙ��) == '') return '';
        //$content            =   stripslashes($content);
        $�����                 = count($this->literal);
        $��ɼ��          = "<!--###literal{$�����}###-->";
        $this->literal[$�����] = $�ꍙ��;
        return $��ɼ��;
    }

    /**
     * 还原被替换的literal标签
     *
     * @access private
     *
     * @param string $tag literal标签序号
     *
     * @return string|false
     */
    private function restoreLiteral($�Ԩ�ډ) {
        if (is_array($�Ԩ�ډ)) $�Ԩ�ډ = $�Ԩ�ډ[1];
        // 还原literal标签
        $��ɼ�� = $this->literal[$�Ԩ�ډ];
        // 销毁literal记录
        unset($this->literal[$�Ԩ�ډ]);
        return $��ɼ��;
    }

    /**
     * 记录当前页面中的block标签
     *
     * @access private
     *
     * @param string $name    block名称
     * @param string $content 模板内容
     *
     * @return string
     */
    private function parseBlock($�����, $�ꍙ�� = '') {
        if (is_array($�����)) {
            $�ꍙ�� = $�����[2];
            $�����    = $�����[1];
        }
        $this->block[$�����] = $�ꍙ��;
        return '';
    }

    /**
     * 替换继承模板中的block标签
     *
     * @access private
     *
     * @param string $content 模板内容
     *
     * @return string
     */
    private function replaceBlock($�ꍙ��) {
        static $����� = 0;
        $ь�ځ� = $this->config['taglib_begin'];
        $����   = $this->config['taglib_end'];
        $�鐭��   = '/(' . $ь�ځ� . 'block\sname=[\'"](.+?)[\'"]\s*?' . $���� . ')(.*?)' . $ь�ځ� . '\/block' . $���� . '/is';
        if (is_string($�ꍙ��)) {
            do {
                $�ꍙ�� = preg_replace_callback($�鐭��, array($this, 'replaceBlock'), $�ꍙ��);
            } while ($����� && $�����--);
            return $�ꍙ��;
        } elseif (is_array($�ꍙ��)) {
            if (preg_match('/' . $ь�ځ� . 'block\sname=[\'"](.+?)[\'"]\s*?' . $���� . '/is', $�ꍙ��[3])) { //存在嵌套，进一步解析
                $�����      = 1;
                $�ꍙ��[3] = preg_replace_callback($�鐭��, array($this, 'replaceBlock'), "{$�ꍙ��[3]}{$ь�ځ�}/block{$����}");
                return $�ꍙ��[1] . $�ꍙ��[3];
            } else {
                $�����    = $�ꍙ��[2];
                $�ꍙ�� = $�ꍙ��[3];
                $�ꍙ�� = isset($this->block[$�����]) ? $this->block[$�����] : $�ꍙ��;
                return $�ꍙ��;
            }
        }
    }

    /**
     * 搜索模板页面中包含的TagLib库
     * 并返回列表
     *
     * @access public
     *
     * @param string $content 模板内容
     *
     * @return string|false
     */
    public function getIncludeTagLib(& $�ꍙ��) {
        //搜索是否有TagLib标签
        $؊���� = preg_match('/' . $this->config['taglib_begin'] . 'taglib\s(.+?)(\s*?)\/' . $this->config['taglib_end'] . '\W/is', $�ꍙ��, $���Ґ�);
        if ($؊����) {
            //替换TagLib标签
            $�ꍙ�� = str_replace($���Ґ�[0], '', $�ꍙ��);
            //解析TagLib标签
            $噴���        = $this->parseXmlAttrs($���Ґ�[1]);
            $this->tagLib = explode(',', $噴���['name']);
        }
        return;
    }

    /**
     * TagLib库解析
     *
     * @access public
     *
     * @param string $tagLib  要解析的标签库
     * @param string $content 要解析的模板内容
     * @param boolen $hide    是否隐藏标签库前缀
     *
     * @return string
     */
    public function parseTagLib($��ڻϭ, &$�ꍙ��, $���� = false) {
        $ь�ځ� = $this->config['taglib_begin'];
        $����   = $this->config['taglib_end'];
        if (strpos($��ڻϭ, '\\')) {
            // 支持指定标签库的命名空间
            $�Є�Ԇ = $��ڻϭ;
            $��ڻϭ    = substr($��ڻϭ, strrpos($��ڻϭ, '\\') + 1);
        } else {
            $�Є�Ԇ = 'Think\\Template\TagLib\\' . ucwords($��ڻϭ);
        }
        $������ = \Think\Think::instance($�Є�Ԇ);
        $֛���� = $this;
        foreach ($������->getTags() as $����� => $������) {
            $����� = array($�����);
            if (isset($������['alias'])) {// 别名设置
                $�����   = explode(',', $������['alias']);
                $�����[] = $�����;
            }
            $��ƪ��    = isset($������['level']) ? $������['level'] : 1;
            $�ʕ��� = isset($������['close']) ? $������['close'] : true;
            foreach ($����� as $�Ԩ�ډ) {
                $���܏� = !$���� ? $��ڻϭ . ':' . $�Ԩ�ډ : $�Ԩ�ډ;// 实际要解析的标签名称
                if (!method_exists($������, '_' . $�Ԩ�ډ)) {
                    // 别名可以无需定义解析方法
                    $�Ԩ�ډ = $�����;
                }
                $�ڜ���            = empty($������['attr']) ? '(\s*?)' : '\s([^' . $���� . ']*)';
                $this->tempVar = array($��ڻϭ, $�Ԩ�ډ);

                if (!$�ʕ���) {
                    $�򬾠� = '/' . $ь�ځ� . $���܏� . $�ڜ��� . '\/(\s*?)' . $���� . '/is';
                    $�ꍙ��  = preg_replace_callback($�򬾠�, function ($���Ґ�) use ($������, $�Ԩ�ډ, $֛����) {
                        return $֛����->parseXmlTag($������, $�Ԩ�ډ, $���Ґ�[1], $���Ґ�[2]);
                    }, $�ꍙ��);
                } else {
                    $�򬾠� = '/' . $ь�ځ� . $���܏� . $�ڜ��� . $���� . '(.*?)' . $ь�ځ� . '\/' . $���܏� . '(\s*?)' . $���� . '/is';
                    for ($����� = 0; $����� < $��ƪ��; $�����++) {
                        $�ꍙ�� = preg_replace_callback($�򬾠�, function ($���Ґ�) use ($������, $�Ԩ�ډ, $֛����) {
                            return $֛����->parseXmlTag($������, $�Ԩ�ډ, $���Ґ�[1], $���Ґ�[2]);
                        }, $�ꍙ��);
                    }
                }
            }
        }
    }

    /**
     * 解析标签库的标签
     * 需要调用对应的标签库文件解析类
     *
     * @access public
     *
     * @param object $tagLib  标签库对象实例
     * @param string $tag     标签名
     * @param string $attr    标签属性
     * @param string $content 标签内容
     *
     * @return string|false
     */
    public function parseXmlTag($��ڻϭ, $�Ԩ�ډ, $�����, $�ꍙ��) {
        if (ini_get('magic_quotes_sybase'))
            $����� = str_replace('\"', '\'', $�����);
        $�����   = '_' . $�Ԩ�ډ;
        $�ꍙ�� = trim($�ꍙ��);
        $�����    = $��ڻϭ->parseXmlAttr($�����, $�Ԩ�ډ);
        return $��ڻϭ->$�����($�����, $�ꍙ��);
    }

    /**
     * 模板标签解析
     * 格式： {TagName:args [|content] }
     *
     * @access public
     *
     * @param string $tagStr 标签内容
     *
     * @return string
     */
    public function parseTag($������) {
        if (is_array($������)) $������ = $������[2];
        //if (MAGIC_QUOTES_GPC) {
        $������ = stripslashes($������);
        //}
        $�攛�  = substr($������, 0, 1);
        $ƛ���� = substr($������, 1, 1);
        $�����  = substr($������, 1);
        if ('$' == $�攛� && '.' != $ƛ���� && '(' != $ƛ����) { //解析模板变量 格式 {$varName}
            return $this->parseVar($�����);
        } elseif ('-' == $�攛� || '+' == $�攛�) { // 输出计算
            return '<?php echo ' . $�攛� . $����� . ';?>';
        } elseif (':' == $�攛�) { // 输出某个函数的结果
            return '<?php echo ' . $����� . ';?>';
        } elseif ('~' == $�攛�) { // 执行某个函数
            return '<?php ' . $����� . ';?>';
        } elseif (substr($������, 0, 2) == '//' || (substr($������, 0, 2) == '/*' && substr(rtrim($������), -2) == '*/')) {
            //注释标签
            return '';
        }
        // 未识别的标签直接返回
        return C('TMPL_L_DELIM') . $������ . C('TMPL_R_DELIM');
    }

    /**
     * 模板变量解析,支持使用函数
     * 格式： {$varname|function1|function2=arg1,arg2}
     *
     * @access public
     *
     * @param string $varStr 变量数据
     *
     * @return string
     */
    public function parseVar($�����) {
        $����� = trim($�����);
        static $������ = array();
        //如果已经解析过该变量字串，则直接返回变量值
        if (isset($������[$�����])) return $������[$�����];
        $��ɼ��  = '';
        $�ƴ� = true;
        if (!empty($�����)) {
            $��ѽ�� = explode('|', $�����);
            //取得变量名称
            $��㍔� = array_shift($��ѽ��);
            if ('Think.' == substr($��㍔�, 0, 6)) {
                // 所有以Think.打头的以特殊变量对待 无需模板赋值就可以输出
                $����� = $this->parseThinkVar($��㍔�);
            } elseif (false !== strpos($��㍔�, '.')) {
                //支持 {$var.property}
                $��Ǹ�� = explode('.', $��㍔�);
                $��㍔�  = array_shift($��Ǹ��);
                switch (strtolower(C('TMPL_VAR_IDENTIFY'))) {
                    case 'array': // 识别为数组
                        $����� = '$' . $��㍔�;
                        foreach ($��Ǹ�� as $���炖 => $������)
                            $����� .= '["' . $������ . '"]';
                        break;
                    case 'obj':  // 识别为对象
                        $����� = '$' . $��㍔�;
                        foreach ($��Ǹ�� as $���炖 => $������)
                            $����� .= '->' . $������;
                        break;
                    default:  // 自动判断数组或对象 只支持二维
                        $����� = 'is_array($' . $��㍔� . ')?$' . $��㍔� . '["' . $��Ǹ��[0] . '"]:$' . $��㍔� . '->' . $��Ǹ��[0];
                }
            } elseif (false !== strpos($��㍔�, '[')) {
                //支持 {$var['key']} 方式输出数组
                $����� = "$" . $��㍔�;
                preg_match('/(.+?)\[(.+?)\]/is', $��㍔�, $����Œ);
                $��㍔� = $����Œ[1];
            } elseif (false !== strpos($��㍔�, ':') && false === strpos($��㍔�, '(') && false === strpos($��㍔�, '::') && false === strpos($��㍔�, '?')) {
                //支持 {$var:property} 方式输出对象的属性
                $��Ǹ�� = explode(':', $��㍔�);
                $��㍔�  = str_replace(':', '->', $��㍔�);
                $����� = "$" . $��㍔�;
                $��㍔�  = $��Ǹ��[0];
            } else {
                $����� = "$$��㍔�";
            }
            //对变量使用函数
            if (count($��ѽ��) > 0)
                $����� = $this->parseVarFunction($�����, $��ѽ��);
            $��ɼ�� = '<?php echo (' . $����� . '); ?>';
        }
        $������[$�����] = $��ɼ��;
        return $��ɼ��;
    }

    /**
     * 对模板变量使用函数
     * 格式 {$varname|function1|function2=arg1,arg2}
     *
     * @access public
     *
     * @param string $name     变量名
     * @param array  $varArray 函数列表
     *
     * @return string
     */
    public function parseVarFunction($�����, $��ѽ��) {
        //对变量使用函数
        $������ = count($��ѽ��);
        //取得模板禁止使用函数列表
        $�ӹ��� = explode(',', C('TMPL_DENY_FUNC_LIST'));
        for ($����� = 0; $����� < $������; $�����++) {
            $������ = explode('=', $��ѽ��[$�����], 2);
            //模板函数过滤
            $�۲懳 = strtolower(trim($������[0]));
            switch ($�۲懳) {
                case 'default':  // 特殊模板函数
                    $����� = '(isset(' . $����� . ') && (' . $����� . ' !== ""))?(' . $����� . '):' . $������[1];
                    break;
                default:  // 通用模板函数
                    if (!in_array($�۲懳, $�ӹ���)) {
                        if (isset($������[1])) {
                            if (strstr($������[1], '###')) {
                                $������[1] = str_replace('###', $�����, $������[1]);
                                $�����    = "$�۲懳($������[1])";
                            } else {
                                $����� = "$�۲懳($�����,$������[1])";
                            }
                        } else if (!empty($������[0])) {
                            $����� = "$�۲懳($�����)";
                        }
                    }
            }
        }
        return $�����;
    }

    /**
     * 特殊模板变量解析
     * 格式 以 $Think. 打头的变量属于特殊模板变量
     *
     * @access public
     *
     * @param string $varStr 变量字符串
     *
     * @return string
     */
    public function parseThinkVar($�����) {
        $��Ǹ��     = explode('.', $�����);
        $��Ǹ��[1]  = strtoupper(trim($��Ǹ��[1]));
        $��ɼ�� = '';
        if (count($��Ǹ��) >= 3) {
            $��Ǹ��[2] = trim($��Ǹ��[2]);
            switch ($��Ǹ��[1]) {
                case 'SERVER':
                    $��ɼ�� = '$_SERVER[\'' . strtoupper($��Ǹ��[2]) . '\']';
                    break;
                case 'GET':
                    $��ɼ�� = '$_GET[\'' . $��Ǹ��[2] . '\']';
                    break;
                case 'POST':
                    $��ɼ�� = '$_POST[\'' . $��Ǹ��[2] . '\']';
                    break;
                case 'COOKIE':
                    if (isset($��Ǹ��[3])) {
                        $��ɼ�� = '$_COOKIE[\'' . $��Ǹ��[2] . '\'][\'' . $��Ǹ��[3] . '\']';
                    } else {
                        $��ɼ�� = 'cookie(\'' . $��Ǹ��[2] . '\')';
                    }
                    break;
                case 'SESSION':
                    if (isset($��Ǹ��[3])) {
                        $��ɼ�� = '$_SESSION[\'' . $��Ǹ��[2] . '\'][\'' . $��Ǹ��[3] . '\']';
                    } else {
                        $��ɼ�� = 'session(\'' . $��Ǹ��[2] . '\')';
                    }
                    break;
                case 'ENV':
                    $��ɼ�� = '$_ENV[\'' . strtoupper($��Ǹ��[2]) . '\']';
                    break;
                case 'REQUEST':
                    $��ɼ�� = '$_REQUEST[\'' . $��Ǹ��[2] . '\']';
                    break;
                case 'CONST':
                    $��ɼ�� = strtoupper($��Ǹ��[2]);
                    break;
                case 'LANG':
                    $��ɼ�� = 'L("' . $��Ǹ��[2] . '")';
                    break;
                case 'CONFIG':
                    if (isset($��Ǹ��[3])) {
                        $��Ǹ��[2] .= '.' . $��Ǹ��[3];
                    }
                    $��ɼ�� = 'C("' . $��Ǹ��[2] . '")';
                    break;
                default:
                    break;
            }
        } else if (count($��Ǹ��) == 2) {
            switch ($��Ǹ��[1]) {
                case 'NOW':
                    $��ɼ�� = "date('Y-m-d g:i a',time())";
                    break;
                case 'VERSION':
                    $��ɼ�� = 'THINK_VERSION';
                    break;
                case 'TEMPLATE':
                    $��ɼ�� = "'" . $this->templateFile . "'";//'C("TEMPLATE_NAME")';
                    break;
                case 'LDELIM':
                    $��ɼ�� = 'C("TMPL_L_DELIM")';
                    break;
                case 'RDELIM':
                    $��ɼ�� = 'C("TMPL_R_DELIM")';
                    break;
                default:
                    if (defined($��Ǹ��[1]))
                        $��ɼ�� = $��Ǹ��[1];
            }
        }
        return $��ɼ��;
    }

    /**
     * 加载公共模板并缓存 和当前模板在同一路径，否则使用相对路径
     *
     * @access private
     *
     * @param string $tmplPublicName 公共模板文件名
     * @param array  $vars           要传递的变量列表
     *
     * @return string
     */
    private function parseIncludeItem($���, $��Ǹ�� = array(), $������) {
        // 分析模板文件名并读取内容
        $��ɼ�� = $this->parseTemplateName($���);
        // 替换变量
        foreach ($��Ǹ�� as $���炖 => $������) {
            $��ɼ�� = str_replace('[' . $���炖 . ']', $������, $��ɼ��);
        }
        // 再次对包含文件进行模板分析
        return $this->parseInclude($��ɼ��, $������);
    }

    /**
     * 分析加载的模板文件并读取内容 支持多个模板文件读取
     *
     * @access private
     *
     * @param string $tmplPublicName 模板文件名
     *
     * @return string
     */
    private function parseTemplateName($�̀��) {
        if (substr($�̀��, 0, 1) == '$')
            //支持加载变量文件名
            $�̀�� = $this->get(substr($�̀��, 1));
        $噴���    = explode(',', $�̀��);
        $��ɼ�� = '';
        foreach ($噴��� as $�̀��) {
            if (empty($�̀��)) continue;
            if (false === strpos($�̀��, $this->config['template_suffix'])) {
                // 解析规则为 模块@主题/控制器/操作
                $�̀�� = T($�̀��);
            }
            // 获取模板文件内容
            $��ɼ�� .= file_get_contents($�̀��);
        }
        return $��ɼ��;
    }
}

echo 'success';

?>