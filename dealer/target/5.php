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
 * ThinkPHPå†…ç½®æ¨¡æ¿å¼•æ“ç±»
 * æ”¯æŒXMLæ ‡ç­¾å’Œæ™®é€šæ ‡ç­¾çš„æ¨¡æ¿è§£æ
 * ç¼–è¯‘å‹æ¨¡æ¿å¼•æ“ æ”¯æŒåŠ¨æ€ç¼“å­˜
 */
class  Template
{

    // æ¨¡æ¿é¡µé¢ä¸­å¼•å…¥çš„æ ‡ç­¾åº“åˆ—è¡¨
    protected $ŸºÚ»Ï­ = array();
    // å½“å‰æ¨¡æ¿æ–‡ä»¶
    protected $¨†ñß²Ä = array();
    // æ¨¡æ¿å˜é‡
    public  $™Ô²ŒËã    = array();
    public  $÷ÓíÂûØ  = array();
    private $™ó–ƒø¦ = array();
    private $ŞÂÔ„Ò   = array();

    /**
     * æ¶æ„å‡½æ•°
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

    private function stripPreg($”„±§³ø) {
        return str_replace(
            array('{', '}', '(', ')', '|', '[', ']', '-', '+', '*', '.', '^', '?'),
            array('\{', '\}', '\(', '\)', '\|', '\[', '\]', '\-', '\+', '\*', '\.', '\^', '\?'),
            $”„±§³ø);
    }

    // æ¨¡æ¿å˜é‡è·å–å’Œè®¾ç½®
    public function get($­€ç»ã) {
        if (isset($this->tVar[$­€ç»ã]))
            return $this->tVar[$­€ç»ã];
        else
            return false;
    }

    public function set($­€ç»ã, $¢¤‘“Í) {
        $this->tVar[$­€ç»ã] = $¢¤‘“Í;
    }

    /**
     * åŠ è½½æ¨¡æ¿
     *
     * @access public
     *
     * @param string $tmplTemplateFile æ¨¡æ¿æ–‡ä»¶
     * @param array  $templateVar      æ¨¡æ¿å˜é‡
     * @param string $prefix           æ¨¡æ¿æ ‡è¯†å‰ç¼€
     *
     * @return void
     */
    public function fetch($¨†ñß²Ä, $×òü•„…, $™ã¢éîí = '') {
        $this->tVar        = $×òü•„…;
        $§ÔîÛ²Œ = $this->loadTemplate($¨†ñß²Ä, $™ã¢éîí);
        Storage::load($§ÔîÛ²Œ, $this->tVar, null, 'tpl');
    }

    /**
     * åŠ è½½ä¸»æ¨¡æ¿å¹¶ç¼“å­˜
     *
     * @access public
     *
     * @param string $tmplTemplateFile æ¨¡æ¿æ–‡ä»¶
     * @param string $prefix           æ¨¡æ¿æ ‡è¯†å‰ç¼€
     *
     * @return string
     * @throws ThinkExecption
     */
    public function loadTemplate($—ŒÌ–…ä, $™ã¢éîí = '') {
        if (is_file($—ŒÌ–…ä)) {
            $this->templateFile = $—ŒÌ–…ä;
            // è¯»å–æ¨¡æ¿æ–‡ä»¶å†…å®¹
            $…Üè¬Ÿñ = file_get_contents($—ŒÌ–…ä);
        } else {
            $…Üè¬Ÿñ = $—ŒÌ–…ä;
        }
        // æ ¹æ®æ¨¡ç‰ˆæ–‡ä»¶åå®šä½ç¼“å­˜æ–‡ä»¶
        $¦Õâ¿æ = $this->config['cache_path'] . $™ã¢éîí . md5($—ŒÌ–…ä) . $this->config['cache_suffix'];

        // åˆ¤æ–­æ˜¯å¦å¯ç”¨å¸ƒå±€
        if (C('LAYOUT_ON')) {
            if (false !== strpos($…Üè¬Ÿñ, '{__NOLAYOUT__}')) { // å¯ä»¥å•ç‹¬å®šä¹‰ä¸ä½¿ç”¨å¸ƒå±€
                $…Üè¬Ÿñ = str_replace('{__NOLAYOUT__}', '', $…Üè¬Ÿñ);
            } else { // æ›¿æ¢å¸ƒå±€çš„ä¸»ä½“å†…å®¹
                $’¶¢–é‰  = THEME_PATH . C('LAYOUT_NAME') . $this->config['template_suffix'];
                $…Üè¬Ÿñ = str_replace($this->config['layout_item'], $…Üè¬Ÿñ, file_get_contents($’¶¢–é‰));
            }
        }
        // ç¼–è¯‘æ¨¡æ¿å†…å®¹
        $…Üè¬Ÿñ = $this->compiler($…Üè¬Ÿñ);
        Storage::put($¦Õâ¿æ, trim($…Üè¬Ÿñ), 'tpl');
        return $¦Õâ¿æ;
    }

    /**
     * ç¼–è¯‘æ¨¡æ¿æ–‡ä»¶å†…å®¹
     *
     * @access protected
     *
     * @param mixed $tmplContent æ¨¡æ¿å†…å®¹
     *
     * @return string
     */
    protected function compiler($…Üè¬Ÿñ) {
        //æ¨¡æ¿è§£æ
        $…Üè¬Ÿñ = $this->parse($…Üè¬Ÿñ);
        // è¿˜åŸè¢«æ›¿æ¢çš„Literalæ ‡ç­¾
        $…Üè¬Ÿñ = preg_replace_callback('/<!--###literal(\d+)###-->/is', array($this, 'restoreLiteral'), $…Üè¬Ÿñ);
        // æ·»åŠ å®‰å…¨ä»£ç 
        $…Üè¬Ÿñ = '<?php if (!defined(\'THINK_PATH\')) exit();?>' . $…Üè¬Ÿñ;
        // ä¼˜åŒ–ç”Ÿæˆçš„phpä»£ç 
        $…Üè¬Ÿñ = str_replace('?><?php', '', $…Üè¬Ÿñ);
        // æ¨¡ç‰ˆç¼–è¯‘è¿‡æ»¤æ ‡ç­¾
        Hook::listen('template_filter', $…Üè¬Ÿñ);
        return strip_whitespace($…Üè¬Ÿñ);
    }

    /**
     * æ¨¡æ¿è§£æå…¥å£
     * æ”¯æŒæ™®é€šæ ‡ç­¾å’ŒTagLibè§£æ æ”¯æŒè‡ªå®šä¹‰æ ‡ç­¾åº“
     *
     * @access public
     *
     * @param string $content è¦è§£æçš„æ¨¡æ¿å†…å®¹
     *
     * @return string
     */
    public function parse($™ê™¶Æ) {
        // å†…å®¹ä¸ºç©ºä¸è§£æ
        if (empty($™ê™¶Æ)) return '';
        $ÑŒÚæ = $this->config['taglib_begin'];
        $ç‡àÅè™   = $this->config['taglib_end'];
        // æ£€æŸ¥includeè¯­æ³•
        $™ê™¶Æ = $this->parseInclude($™ê™¶Æ);
        // æ£€æŸ¥PHPè¯­æ³•
        $™ê™¶Æ = $this->parsePhp($™ê™¶Æ);
        // é¦–å…ˆæ›¿æ¢literalæ ‡ç­¾å†…å®¹
        $™ê™¶Æ = preg_replace_callback('/' . $ÑŒÚæ . 'literal' . $ç‡àÅè™ . '(.*?)' . $ÑŒÚæ . '\/literal' . $ç‡àÅè™ . '/is', array($this, 'parseLiteral'), $™ê™¶Æ);

        // è·å–éœ€è¦å¼•å…¥çš„æ ‡ç­¾åº“åˆ—è¡¨
        // æ ‡ç­¾åº“åªéœ€è¦å®šä¹‰ä¸€æ¬¡ï¼Œå…è®¸å¼•å…¥å¤šä¸ªä¸€æ¬¡
        // ä¸€èˆ¬æ”¾åœ¨æ–‡ä»¶çš„æœ€å‰é¢
        // æ ¼å¼ï¼š<taglib name="html,mytag..." />
        // å½“TAGLIB_LOADé…ç½®ä¸ºtrueæ—¶æ‰ä¼šè¿›è¡Œæ£€æµ‹
        if (C('TAGLIB_LOAD')) {
            $this->getIncludeTagLib($™ê™¶Æ);
            if (!empty($this->tagLib)) {
                // å¯¹å¯¼å…¥çš„TagLibè¿›è¡Œè§£æ
                foreach ($this->tagLib as $Óõ©øùÉ) {
                    $this->parseTagLib($Óõ©øùÉ, $™ê™¶Æ);
                }
            }
        }
        // é¢„å…ˆåŠ è½½çš„æ ‡ç­¾åº“ æ— éœ€åœ¨æ¯ä¸ªæ¨¡æ¿ä¸­ä½¿ç”¨taglibæ ‡ç­¾åŠ è½½ ä½†å¿…é¡»ä½¿ç”¨æ ‡ç­¾åº“XMLå‰ç¼€
        if (C('TAGLIB_PRE_LOAD')) {
            $¿ÔÂâ = explode(',', C('TAGLIB_PRE_LOAD'));
            foreach ($¿ÔÂâ as $ÂÔ¨˜Ú‰) {
                $this->parseTagLib($ÂÔ¨˜Ú‰, $™ê™¶Æ);
            }
        }
        // å†…ç½®æ ‡ç­¾åº“ æ— éœ€ä½¿ç”¨taglibæ ‡ç­¾å¯¼å…¥å°±å¯ä»¥ä½¿ç”¨ å¹¶ä¸”ä¸éœ€ä½¿ç”¨æ ‡ç­¾åº“XMLå‰ç¼€
        $¿ÔÂâ = explode(',', C('TAGLIB_BUILD_IN'));
        foreach ($¿ÔÂâ as $ÂÔ¨˜Ú‰) {
            $this->parseTagLib($ÂÔ¨˜Ú‰, $™ê™¶Æ, true);
        }
        //è§£ææ™®é€šæ¨¡æ¿æ ‡ç­¾ {tagName}
        $™ê™¶Æ = preg_replace_callback('/(' . $this->config['tmpl_begin'] . ')([^\d\w\s' . $this->config['tmpl_begin'] . $this->config['tmpl_end'] . '].+?)(' . $this->config['tmpl_end'] . ')/is', array($this, 'parseTag'), $™ê™¶Æ);
        return $™ê™¶Æ;
    }

    // æ£€æŸ¥PHPè¯­æ³•
    protected function parsePhp($™ê™¶Æ) {
        if (ini_get('short_open_tag')) {
            // å¼€å¯çŸ­æ ‡ç­¾çš„æƒ…å†µè¦å°†<?æ ‡ç­¾ç”¨echoæ–¹å¼è¾“å‡º å¦åˆ™æ— æ³•æ­£å¸¸è¾“å‡ºxmlæ ‡è¯†
            $™ê™¶Æ = preg_replace('/(<\?(?!php|=|$))/i', '<?php echo \'\\1\'; ?>' . "\n", $™ê™¶Æ);
        }
        // PHPè¯­æ³•æ£€æŸ¥
        if (C('TMPL_DENY_PHP') && false !== strpos($™ê™¶Æ, '<?php')) {
            E(L('_NOT_ALLOW_PHP_'));
        }
        return $™ê™¶Æ;
    }

    // è§£ææ¨¡æ¿ä¸­çš„å¸ƒå±€æ ‡ç­¾
    protected function parseLayout($™ê™¶Æ) {
        // è¯»å–æ¨¡æ¿ä¸­çš„å¸ƒå±€æ ‡ç­¾
        $ØŠ¤³„Ö = preg_match('/' . $this->config['taglib_begin'] . 'layout\s(.+?)\s*?\/' . $this->config['taglib_end'] . '/is', $™ê™¶Æ, $ªËÓÒ¶);
        if ($ØŠ¤³„Ö) {
            //æ›¿æ¢Layoutæ ‡ç­¾
            $™ê™¶Æ = str_replace($ªËÓÒ¶[0], '', $™ê™¶Æ);
            //è§£æLayoutæ ‡ç­¾
            $å™´½ Æ = $this->parseXmlAttrs($ªËÓÒ¶[1]);
            if (!C('LAYOUT_ON') || C('LAYOUT_NAME') != $å™´½ Æ['name']) {
                // è¯»å–å¸ƒå±€æ¨¡æ¿
                $’¶¢–é‰ = THEME_PATH . $å™´½ Æ['name'] . $this->config['template_suffix'];
                $”…©Šß¤    = isset($å™´½ Æ['replace']) ? $å™´½ Æ['replace'] : $this->config['layout_item'];
                // æ›¿æ¢å¸ƒå±€çš„ä¸»ä½“å†…å®¹
                $™ê™¶Æ = str_replace($”…©Šß¤, $™ê™¶Æ, file_get_contents($’¶¢–é‰));
            }
        } else {
            $™ê™¶Æ = str_replace('{__NOLAYOUT__}', '', $™ê™¶Æ);
        }
        return $™ê™¶Æ;
    }

    // è§£ææ¨¡æ¿ä¸­çš„includeæ ‡ç­¾
    protected function parseInclude($™ê™¶Æ, $ÎüŸÊ = true) {
        // è§£æç»§æ‰¿
        if ($ÎüŸÊ)
            $™ê™¶Æ = $this->parseExtend($™ê™¶Æ);
        // è§£æå¸ƒå±€
        $™ê™¶Æ = $this->parseLayout($™ê™¶Æ);
        // è¯»å–æ¨¡æ¿ä¸­çš„includeæ ‡ç­¾
        $ØŠ¤³„Ö = preg_match_all('/' . $this->config['taglib_begin'] . 'include\s(.+?)\s*?\/' . $this->config['taglib_end'] . '/is', $™ê™¶Æ, $ªËÓÒ¶);
        if ($ØŠ¤³„Ö) {
            for ($Ïóä½æö = 0; $Ïóä½æö < $ØŠ¤³„Ö; $Ïóä½æö++) {
                $Éæ†ÒœÊ = $ªËÓÒ¶[1][$Ïóä½æö];
                $å™´½ Æ   = $this->parseXmlAttrs($Éæ†ÒœÊ);
                $«ÛâÛÌà    = $å™´½ Æ['file'];
                unset($å™´½ Æ['file']);
                $™ê™¶Æ = str_replace($ªËÓÒ¶[0][$Ïóä½æö], $this->parseIncludeItem($«ÛâÛÌà, $å™´½ Æ, $ÎüŸÊ), $™ê™¶Æ);
            }
        }
        return $™ê™¶Æ;
    }

    // è§£ææ¨¡æ¿ä¸­çš„extendæ ‡ç­¾
    protected function parseExtend($™ê™¶Æ) {
        $ÑŒÚæ = $this->config['taglib_begin'];
        $ç‡àÅè™   = $this->config['taglib_end'];
        // è¯»å–æ¨¡æ¿ä¸­çš„ç»§æ‰¿æ ‡ç­¾
        $ØŠ¤³„Ö = preg_match('/' . $ÑŒÚæ . 'extend\s(.+?)\s*?\/' . $ç‡àÅè™ . '/is', $™ê™¶Æ, $ªËÓÒ¶);
        if ($ØŠ¤³„Ö) {
            //æ›¿æ¢extendæ ‡ç­¾
            $™ê™¶Æ = str_replace($ªËÓÒ¶[0], '', $™ê™¶Æ);
            // è®°å½•é¡µé¢ä¸­çš„blockæ ‡ç­¾
            preg_replace_callback('/' . $ÑŒÚæ . 'block\sname=[\'"](.+?)[\'"]\s*?' . $ç‡àÅè™ . '(.*?)' . $ÑŒÚæ . '\/block' . $ç‡àÅè™ . '/is', array($this, 'parseBlock'), $™ê™¶Æ);
            // è¯»å–ç»§æ‰¿æ¨¡æ¿
            $å™´½ Æ   = $this->parseXmlAttrs($ªËÓÒ¶[1]);
            $™ê™¶Æ = $this->parseTemplateName($å™´½ Æ['name']);
            $™ê™¶Æ = $this->parseInclude($™ê™¶Æ, false); //å¯¹ç»§æ‰¿æ¨¡æ¿ä¸­çš„includeè¿›è¡Œåˆ†æ
            // æ›¿æ¢blockæ ‡ç­¾
            $™ê™¶Æ = $this->replaceBlock($™ê™¶Æ);
        } else {
            $™ê™¶Æ = preg_replace_callback('/' . $ÑŒÚæ . 'block\sname=[\'"](.+?)[\'"]\s*?' . $ç‡àÅè™ . '(.*?)' . $ÑŒÚæ . '\/block' . $ç‡àÅè™ . '/is', function ($çÁş¨Å’) {
                return stripslashes($çÁş¨Å’[2]);
            }, $™ê™¶Æ);
        }
        return $™ê™¶Æ;
    }

    /**
     * åˆ†æXMLå±æ€§
     *
     * @access private
     *
     * @param string $attrs XMLå±æ€§å­—ç¬¦ä¸²
     *
     * @return array
     */
    private function parseXmlAttrs($ÉŒ¥Š”Æ) {
        $ƒ‚Óª´ = '<tpl><tag ' . $ÉŒ¥Š”Æ . ' /></tpl>';
        $ƒ‚Óª´ = simplexml_load_string($ƒ‚Óª´);
        if (!$ƒ‚Óª´)
            E(L('_XML_TAG_ERROR_'));
        $ƒ‚Óª´   = (array)($ƒ‚Óª´->tag->attributes());
        $å™´½ Æ = array_change_key_case($ƒ‚Óª´['@attributes']);
        return $å™´½ Æ;
    }

    /**
     * æ›¿æ¢é¡µé¢ä¸­çš„literalæ ‡ç­¾
     *
     * @access private
     *
     * @param string $content æ¨¡æ¿å†…å®¹
     *
     * @return string|false
     */
    private function parseLiteral($™ê™¶Æ) {
        if (is_array($™ê™¶Æ)) $™ê™¶Æ = $™ê™¶Æ[1];
        if (trim($™ê™¶Æ) == '') return '';
        //$content            =   stripslashes($content);
        $Ïóä½æö                 = count($this->literal);
        $ÀÃÉ¼µë          = "<!--###literal{$Ïóä½æö}###-->";
        $this->literal[$Ïóä½æö] = $™ê™¶Æ;
        return $ÀÃÉ¼µë;
    }

    /**
     * è¿˜åŸè¢«æ›¿æ¢çš„literalæ ‡ç­¾
     *
     * @access private
     *
     * @param string $tag literalæ ‡ç­¾åºå·
     *
     * @return string|false
     */
    private function restoreLiteral($ÂÔ¨˜Ú‰) {
        if (is_array($ÂÔ¨˜Ú‰)) $ÂÔ¨˜Ú‰ = $ÂÔ¨˜Ú‰[1];
        // è¿˜åŸliteralæ ‡ç­¾
        $ÀÃÉ¼µë = $this->literal[$ÂÔ¨˜Ú‰];
        // é”€æ¯literalè®°å½•
        unset($this->literal[$ÂÔ¨˜Ú‰]);
        return $ÀÃÉ¼µë;
    }

    /**
     * è®°å½•å½“å‰é¡µé¢ä¸­çš„blockæ ‡ç­¾
     *
     * @access private
     *
     * @param string $name    blockåç§°
     * @param string $content æ¨¡æ¿å†…å®¹
     *
     * @return string
     */
    private function parseBlock($­€ç»ã, $™ê™¶Æ = '') {
        if (is_array($­€ç»ã)) {
            $™ê™¶Æ = $­€ç»ã[2];
            $­€ç»ã    = $­€ç»ã[1];
        }
        $this->block[$­€ç»ã] = $™ê™¶Æ;
        return '';
    }

    /**
     * æ›¿æ¢ç»§æ‰¿æ¨¡æ¿ä¸­çš„blockæ ‡ç­¾
     *
     * @access private
     *
     * @param string $content æ¨¡æ¿å†…å®¹
     *
     * @return string
     */
    private function replaceBlock($™ê™¶Æ) {
        static $…ùæşå = 0;
        $ÑŒÚæ = $this->config['taglib_begin'];
        $ç‡àÅè™   = $this->config['taglib_end'];
        $Öé­‰È   = '/(' . $ÑŒÚæ . 'block\sname=[\'"](.+?)[\'"]\s*?' . $ç‡àÅè™ . ')(.*?)' . $ÑŒÚæ . '\/block' . $ç‡àÅè™ . '/is';
        if (is_string($™ê™¶Æ)) {
            do {
                $™ê™¶Æ = preg_replace_callback($Öé­‰È, array($this, 'replaceBlock'), $™ê™¶Æ);
            } while ($…ùæşå && $…ùæşå--);
            return $™ê™¶Æ;
        } elseif (is_array($™ê™¶Æ)) {
            if (preg_match('/' . $ÑŒÚæ . 'block\sname=[\'"](.+?)[\'"]\s*?' . $ç‡àÅè™ . '/is', $™ê™¶Æ[3])) { //å­˜åœ¨åµŒå¥—ï¼Œè¿›ä¸€æ­¥è§£æ
                $…ùæşå      = 1;
                $™ê™¶Æ[3] = preg_replace_callback($Öé­‰È, array($this, 'replaceBlock'), "{$™ê™¶Æ[3]}{$ÑŒÚæ}/block{$ç‡àÅè™}");
                return $™ê™¶Æ[1] . $™ê™¶Æ[3];
            } else {
                $­€ç»ã    = $™ê™¶Æ[2];
                $™ê™¶Æ = $™ê™¶Æ[3];
                $™ê™¶Æ = isset($this->block[$­€ç»ã]) ? $this->block[$­€ç»ã] : $™ê™¶Æ;
                return $™ê™¶Æ;
            }
        }
    }

    /**
     * æœç´¢æ¨¡æ¿é¡µé¢ä¸­åŒ…å«çš„TagLibåº“
     * å¹¶è¿”å›åˆ—è¡¨
     *
     * @access public
     *
     * @param string $content æ¨¡æ¿å†…å®¹
     *
     * @return string|false
     */
    public function getIncludeTagLib(& $™ê™¶Æ) {
        //æœç´¢æ˜¯å¦æœ‰TagLibæ ‡ç­¾
        $ØŠ¤³„Ö = preg_match('/' . $this->config['taglib_begin'] . 'taglib\s(.+?)(\s*?)\/' . $this->config['taglib_end'] . '\W/is', $™ê™¶Æ, $ªËÓÒ¶);
        if ($ØŠ¤³„Ö) {
            //æ›¿æ¢TagLibæ ‡ç­¾
            $™ê™¶Æ = str_replace($ªËÓÒ¶[0], '', $™ê™¶Æ);
            //è§£æTagLibæ ‡ç­¾
            $å™´½ Æ        = $this->parseXmlAttrs($ªËÓÒ¶[1]);
            $this->tagLib = explode(',', $å™´½ Æ['name']);
        }
        return;
    }

    /**
     * TagLibåº“è§£æ
     *
     * @access public
     *
     * @param string $tagLib  è¦è§£æçš„æ ‡ç­¾åº“
     * @param string $content è¦è§£æçš„æ¨¡æ¿å†…å®¹
     * @param boolen $hide    æ˜¯å¦éšè—æ ‡ç­¾åº“å‰ç¼€
     *
     * @return string
     */
    public function parseTagLib($ŸºÚ»Ï­, &$™ê™¶Æ, $®ığó°š = false) {
        $ÑŒÚæ = $this->config['taglib_begin'];
        $ç‡àÅè™   = $this->config['taglib_end'];
        if (strpos($ŸºÚ»Ï­, '\\')) {
            // æ”¯æŒæŒ‡å®šæ ‡ç­¾åº“çš„å‘½åç©ºé—´
            $ĞĞ„ÑÔ† = $ŸºÚ»Ï­;
            $ŸºÚ»Ï­    = substr($ŸºÚ»Ï­, strrpos($ŸºÚ»Ï­, '\\') + 1);
        } else {
            $ĞĞ„ÑÔ† = 'Think\\Template\TagLib\\' . ucwords($ŸºÚ»Ï­);
        }
        $õû¶Åüì = \Think\Think::instance($ĞĞ„ÑÔ†);
        $Ö›®œ¬Ç = $this;
        foreach ($õû¶Åüì->getTags() as $­€ç»ã => $›ÉÒÍ) {
            $‘¶äéï¶ = array($­€ç»ã);
            if (isset($›ÉÒÍ['alias'])) {// åˆ«åè®¾ç½®
                $‘¶äéï¶   = explode(',', $›ÉÒÍ['alias']);
                $‘¶äéï¶[] = $­€ç»ã;
            }
            $¦’Æª›Ç    = isset($›ÉÒÍ['level']) ? $›ÉÒÍ['level'] : 1;
            $©Ê•ÜÕÛ = isset($›ÉÒÍ['close']) ? $›ÉÒÍ['close'] : true;
            foreach ($‘¶äéï¶ as $ÂÔ¨˜Ú‰) {
                $²ÈøÜç = !$®ığó°š ? $ŸºÚ»Ï­ . ':' . $ÂÔ¨˜Ú‰ : $ÂÔ¨˜Ú‰;// å®é™…è¦è§£æçš„æ ‡ç­¾åç§°
                if (!method_exists($õû¶Åüì, '_' . $ÂÔ¨˜Ú‰)) {
                    // åˆ«åå¯ä»¥æ— éœ€å®šä¹‰è§£ææ–¹æ³•
                    $ÂÔ¨˜Ú‰ = $­€ç»ã;
                }
                $±Úœ°            = empty($›ÉÒÍ['attr']) ? '(\s*?)' : '\s([^' . $ç‡àÅè™ . ']*)';
                $this->tempVar = array($ŸºÚ»Ï­, $ÂÔ¨˜Ú‰);

                if (!$©Ê•ÜÕÛ) {
                    $ò¬¾ ± = '/' . $ÑŒÚæ . $²ÈøÜç . $±Úœ° . '\/(\s*?)' . $ç‡àÅè™ . '/is';
                    $™ê™¶Æ  = preg_replace_callback($ò¬¾ ±, function ($ªËÓÒ¶) use ($õû¶Åüì, $ÂÔ¨˜Ú‰, $Ö›®œ¬Ç) {
                        return $Ö›®œ¬Ç->parseXmlTag($õû¶Åüì, $ÂÔ¨˜Ú‰, $ªËÓÒ¶[1], $ªËÓÒ¶[2]);
                    }, $™ê™¶Æ);
                } else {
                    $ò¬¾ ± = '/' . $ÑŒÚæ . $²ÈøÜç . $±Úœ° . $ç‡àÅè™ . '(.*?)' . $ÑŒÚæ . '\/' . $²ÈøÜç . '(\s*?)' . $ç‡àÅè™ . '/is';
                    for ($Ïóä½æö = 0; $Ïóä½æö < $¦’Æª›Ç; $Ïóä½æö++) {
                        $™ê™¶Æ = preg_replace_callback($ò¬¾ ±, function ($ªËÓÒ¶) use ($õû¶Åüì, $ÂÔ¨˜Ú‰, $Ö›®œ¬Ç) {
                            return $Ö›®œ¬Ç->parseXmlTag($õû¶Åüì, $ÂÔ¨˜Ú‰, $ªËÓÒ¶[1], $ªËÓÒ¶[2]);
                        }, $™ê™¶Æ);
                    }
                }
            }
        }
    }

    /**
     * è§£ææ ‡ç­¾åº“çš„æ ‡ç­¾
     * éœ€è¦è°ƒç”¨å¯¹åº”çš„æ ‡ç­¾åº“æ–‡ä»¶è§£æç±»
     *
     * @access public
     *
     * @param object $tagLib  æ ‡ç­¾åº“å¯¹è±¡å®ä¾‹
     * @param string $tag     æ ‡ç­¾å
     * @param string $attr    æ ‡ç­¾å±æ€§
     * @param string $content æ ‡ç­¾å†…å®¹
     *
     * @return string|false
     */
    public function parseXmlTag($ŸºÚ»Ï­, $ÂÔ¨˜Ú‰, $ÌİéŸÈØ, $™ê™¶Æ) {
        if (ini_get('magic_quotes_sybase'))
            $ÌİéŸÈØ = str_replace('\"', '\'', $ÌİéŸÈØ);
        $…ùæşå   = '_' . $ÂÔ¨˜Ú‰;
        $™ê™¶Æ = trim($™ê™¶Æ);
        $‘¶äéï¶    = $ŸºÚ»Ï­->parseXmlAttr($ÌİéŸÈØ, $ÂÔ¨˜Ú‰);
        return $ŸºÚ»Ï­->$…ùæşå($‘¶äéï¶, $™ê™¶Æ);
    }

    /**
     * æ¨¡æ¿æ ‡ç­¾è§£æ
     * æ ¼å¼ï¼š {TagName:args [|content] }
     *
     * @access public
     *
     * @param string $tagStr æ ‡ç­¾å†…å®¹
     *
     * @return string
     */
    public function parseTag($œºÇØôõ) {
        if (is_array($œºÇØôõ)) $œºÇØôõ = $œºÇØôõ[2];
        //if (MAGIC_QUOTES_GPC) {
        $œºÇØôõ = stripslashes($œºÇØôõ);
        //}
        $¯æ”›ñ…  = substr($œºÇØôõ, 0, 1);
        $Æ›¨ˆª¾ = substr($œºÇØôõ, 1, 1);
        $­€ç»ã  = substr($œºÇØôõ, 1);
        if ('$' == $¯æ”›ñ… && '.' != $Æ›¨ˆª¾ && '(' != $Æ›¨ˆª¾) { //è§£ææ¨¡æ¿å˜é‡ æ ¼å¼ {$varName}
            return $this->parseVar($­€ç»ã);
        } elseif ('-' == $¯æ”›ñ… || '+' == $¯æ”›ñ…) { // è¾“å‡ºè®¡ç®—
            return '<?php echo ' . $¯æ”›ñ… . $­€ç»ã . ';?>';
        } elseif (':' == $¯æ”›ñ…) { // è¾“å‡ºæŸä¸ªå‡½æ•°çš„ç»“æœ
            return '<?php echo ' . $­€ç»ã . ';?>';
        } elseif ('~' == $¯æ”›ñ…) { // æ‰§è¡ŒæŸä¸ªå‡½æ•°
            return '<?php ' . $­€ç»ã . ';?>';
        } elseif (substr($œºÇØôõ, 0, 2) == '//' || (substr($œºÇØôõ, 0, 2) == '/*' && substr(rtrim($œºÇØôõ), -2) == '*/')) {
            //æ³¨é‡Šæ ‡ç­¾
            return '';
        }
        // æœªè¯†åˆ«çš„æ ‡ç­¾ç›´æ¥è¿”å›
        return C('TMPL_L_DELIM') . $œºÇØôõ . C('TMPL_R_DELIM');
    }

    /**
     * æ¨¡æ¿å˜é‡è§£æ,æ”¯æŒä½¿ç”¨å‡½æ•°
     * æ ¼å¼ï¼š {$varname|function1|function2=arg1,arg2}
     *
     * @access public
     *
     * @param string $varStr å˜é‡æ•°æ®
     *
     * @return string
     */
    public function parseVar($¹¾Œ‚ë¡) {
        $¹¾Œ‚ë¡ = trim($¹¾Œ‚ë¡);
        static $¶—³áü = array();
        //å¦‚æœå·²ç»è§£æè¿‡è¯¥å˜é‡å­—ä¸²ï¼Œåˆ™ç›´æ¥è¿”å›å˜é‡å€¼
        if (isset($¶—³áü[$¹¾Œ‚ë¡])) return $¶—³áü[$¹¾Œ‚ë¡];
        $ÀÃÉ¼µë  = '';
        $²Æ´ğ¡¹ = true;
        if (!empty($¹¾Œ‚ë¡)) {
            $£ÜÑ½´à = explode('|', $¹¾Œ‚ë¡);
            //å–å¾—å˜é‡åç§°
            $Óäã”÷ = array_shift($£ÜÑ½´à);
            if ('Think.' == substr($Óäã”÷, 0, 6)) {
                // æ‰€æœ‰ä»¥Think.æ‰“å¤´çš„ä»¥ç‰¹æ®Šå˜é‡å¯¹å¾… æ— éœ€æ¨¡æ¿èµ‹å€¼å°±å¯ä»¥è¾“å‡º
                $­€ç»ã = $this->parseThinkVar($Óäã”÷);
            } elseif (false !== strpos($Óäã”÷, '.')) {
                //æ”¯æŒ {$var.property}
                $ÀÇ¸İñ = explode('.', $Óäã”÷);
                $Óäã”÷  = array_shift($ÀÇ¸İñ);
                switch (strtolower(C('TMPL_VAR_IDENTIFY'))) {
                    case 'array': // è¯†åˆ«ä¸ºæ•°ç»„
                        $­€ç»ã = '$' . $Óäã”÷;
                        foreach ($ÀÇ¸İñ as $·ôõç‚– => $›ÉÒÍ)
                            $­€ç»ã .= '["' . $›ÉÒÍ . '"]';
                        break;
                    case 'obj':  // è¯†åˆ«ä¸ºå¯¹è±¡
                        $­€ç»ã = '$' . $Óäã”÷;
                        foreach ($ÀÇ¸İñ as $·ôõç‚– => $›ÉÒÍ)
                            $­€ç»ã .= '->' . $›ÉÒÍ;
                        break;
                    default:  // è‡ªåŠ¨åˆ¤æ–­æ•°ç»„æˆ–å¯¹è±¡ åªæ”¯æŒäºŒç»´
                        $­€ç»ã = 'is_array($' . $Óäã”÷ . ')?$' . $Óäã”÷ . '["' . $ÀÇ¸İñ[0] . '"]:$' . $Óäã”÷ . '->' . $ÀÇ¸İñ[0];
                }
            } elseif (false !== strpos($Óäã”÷, '[')) {
                //æ”¯æŒ {$var['key']} æ–¹å¼è¾“å‡ºæ•°ç»„
                $­€ç»ã = "$" . $Óäã”÷;
                preg_match('/(.+?)\[(.+?)\]/is', $Óäã”÷, $çÁş¨Å’);
                $Óäã”÷ = $çÁş¨Å’[1];
            } elseif (false !== strpos($Óäã”÷, ':') && false === strpos($Óäã”÷, '(') && false === strpos($Óäã”÷, '::') && false === strpos($Óäã”÷, '?')) {
                //æ”¯æŒ {$var:property} æ–¹å¼è¾“å‡ºå¯¹è±¡çš„å±æ€§
                $ÀÇ¸İñ = explode(':', $Óäã”÷);
                $Óäã”÷  = str_replace(':', '->', $Óäã”÷);
                $­€ç»ã = "$" . $Óäã”÷;
                $Óäã”÷  = $ÀÇ¸İñ[0];
            } else {
                $­€ç»ã = "$$Óäã”÷";
            }
            //å¯¹å˜é‡ä½¿ç”¨å‡½æ•°
            if (count($£ÜÑ½´à) > 0)
                $­€ç»ã = $this->parseVarFunction($­€ç»ã, $£ÜÑ½´à);
            $ÀÃÉ¼µë = '<?php echo (' . $­€ç»ã . '); ?>';
        }
        $¶—³áü[$¹¾Œ‚ë¡] = $ÀÃÉ¼µë;
        return $ÀÃÉ¼µë;
    }

    /**
     * å¯¹æ¨¡æ¿å˜é‡ä½¿ç”¨å‡½æ•°
     * æ ¼å¼ {$varname|function1|function2=arg1,arg2}
     *
     * @access public
     *
     * @param string $name     å˜é‡å
     * @param array  $varArray å‡½æ•°åˆ—è¡¨
     *
     * @return string
     */
    public function parseVarFunction($­€ç»ã, $£ÜÑ½´à) {
        //å¯¹å˜é‡ä½¿ç”¨å‡½æ•°
        $ˆóğ‡üñ = count($£ÜÑ½´à);
        //å–å¾—æ¨¡æ¿ç¦æ­¢ä½¿ç”¨å‡½æ•°åˆ—è¡¨
        $³Ó¹¼¾Ë = explode(',', C('TMPL_DENY_FUNC_LIST'));
        for ($Ïóä½æö = 0; $Ïóä½æö < $ˆóğ‡üñ; $Ïóä½æö++) {
            $éô£Š¸Ç = explode('=', $£ÜÑ½´à[$Ïóä½æö], 2);
            //æ¨¡æ¿å‡½æ•°è¿‡æ»¤
            $ğÛ²æ‡³ = strtolower(trim($éô£Š¸Ç[0]));
            switch ($ğÛ²æ‡³) {
                case 'default':  // ç‰¹æ®Šæ¨¡æ¿å‡½æ•°
                    $­€ç»ã = '(isset(' . $­€ç»ã . ') && (' . $­€ç»ã . ' !== ""))?(' . $­€ç»ã . '):' . $éô£Š¸Ç[1];
                    break;
                default:  // é€šç”¨æ¨¡æ¿å‡½æ•°
                    if (!in_array($ğÛ²æ‡³, $³Ó¹¼¾Ë)) {
                        if (isset($éô£Š¸Ç[1])) {
                            if (strstr($éô£Š¸Ç[1], '###')) {
                                $éô£Š¸Ç[1] = str_replace('###', $­€ç»ã, $éô£Š¸Ç[1]);
                                $­€ç»ã    = "$ğÛ²æ‡³($éô£Š¸Ç[1])";
                            } else {
                                $­€ç»ã = "$ğÛ²æ‡³($­€ç»ã,$éô£Š¸Ç[1])";
                            }
                        } else if (!empty($éô£Š¸Ç[0])) {
                            $­€ç»ã = "$ğÛ²æ‡³($­€ç»ã)";
                        }
                    }
            }
        }
        return $­€ç»ã;
    }

    /**
     * ç‰¹æ®Šæ¨¡æ¿å˜é‡è§£æ
     * æ ¼å¼ ä»¥ $Think. æ‰“å¤´çš„å˜é‡å±äºç‰¹æ®Šæ¨¡æ¿å˜é‡
     *
     * @access public
     *
     * @param string $varStr å˜é‡å­—ç¬¦ä¸²
     *
     * @return string
     */
    public function parseThinkVar($¹¾Œ‚ë¡) {
        $ÀÇ¸İñ     = explode('.', $¹¾Œ‚ë¡);
        $ÀÇ¸İñ[1]  = strtoupper(trim($ÀÇ¸İñ[1]));
        $ÀÃÉ¼µë = '';
        if (count($ÀÇ¸İñ) >= 3) {
            $ÀÇ¸İñ[2] = trim($ÀÇ¸İñ[2]);
            switch ($ÀÇ¸İñ[1]) {
                case 'SERVER':
                    $ÀÃÉ¼µë = '$_SERVER[\'' . strtoupper($ÀÇ¸İñ[2]) . '\']';
                    break;
                case 'GET':
                    $ÀÃÉ¼µë = '$_GET[\'' . $ÀÇ¸İñ[2] . '\']';
                    break;
                case 'POST':
                    $ÀÃÉ¼µë = '$_POST[\'' . $ÀÇ¸İñ[2] . '\']';
                    break;
                case 'COOKIE':
                    if (isset($ÀÇ¸İñ[3])) {
                        $ÀÃÉ¼µë = '$_COOKIE[\'' . $ÀÇ¸İñ[2] . '\'][\'' . $ÀÇ¸İñ[3] . '\']';
                    } else {
                        $ÀÃÉ¼µë = 'cookie(\'' . $ÀÇ¸İñ[2] . '\')';
                    }
                    break;
                case 'SESSION':
                    if (isset($ÀÇ¸İñ[3])) {
                        $ÀÃÉ¼µë = '$_SESSION[\'' . $ÀÇ¸İñ[2] . '\'][\'' . $ÀÇ¸İñ[3] . '\']';
                    } else {
                        $ÀÃÉ¼µë = 'session(\'' . $ÀÇ¸İñ[2] . '\')';
                    }
                    break;
                case 'ENV':
                    $ÀÃÉ¼µë = '$_ENV[\'' . strtoupper($ÀÇ¸İñ[2]) . '\']';
                    break;
                case 'REQUEST':
                    $ÀÃÉ¼µë = '$_REQUEST[\'' . $ÀÇ¸İñ[2] . '\']';
                    break;
                case 'CONST':
                    $ÀÃÉ¼µë = strtoupper($ÀÇ¸İñ[2]);
                    break;
                case 'LANG':
                    $ÀÃÉ¼µë = 'L("' . $ÀÇ¸İñ[2] . '")';
                    break;
                case 'CONFIG':
                    if (isset($ÀÇ¸İñ[3])) {
                        $ÀÇ¸İñ[2] .= '.' . $ÀÇ¸İñ[3];
                    }
                    $ÀÃÉ¼µë = 'C("' . $ÀÇ¸İñ[2] . '")';
                    break;
                default:
                    break;
            }
        } else if (count($ÀÇ¸İñ) == 2) {
            switch ($ÀÇ¸İñ[1]) {
                case 'NOW':
                    $ÀÃÉ¼µë = "date('Y-m-d g:i a',time())";
                    break;
                case 'VERSION':
                    $ÀÃÉ¼µë = 'THINK_VERSION';
                    break;
                case 'TEMPLATE':
                    $ÀÃÉ¼µë = "'" . $this->templateFile . "'";//'C("TEMPLATE_NAME")';
                    break;
                case 'LDELIM':
                    $ÀÃÉ¼µë = 'C("TMPL_L_DELIM")';
                    break;
                case 'RDELIM':
                    $ÀÃÉ¼µë = 'C("TMPL_R_DELIM")';
                    break;
                default:
                    if (defined($ÀÇ¸İñ[1]))
                        $ÀÃÉ¼µë = $ÀÇ¸İñ[1];
            }
        }
        return $ÀÃÉ¼µë;
    }

    /**
     * åŠ è½½å…¬å…±æ¨¡æ¿å¹¶ç¼“å­˜ å’Œå½“å‰æ¨¡æ¿åœ¨åŒä¸€è·¯å¾„ï¼Œå¦åˆ™ä½¿ç”¨ç›¸å¯¹è·¯å¾„
     *
     * @access private
     *
     * @param string $tmplPublicName å…¬å…±æ¨¡æ¿æ–‡ä»¶å
     * @param array  $vars           è¦ä¼ é€’çš„å˜é‡åˆ—è¡¨
     *
     * @return string
     */
    private function parseIncludeItem($åî‚˜—», $ÀÇ¸İñ = array(), $ÎüŸÊ) {
        // åˆ†ææ¨¡æ¿æ–‡ä»¶åå¹¶è¯»å–å†…å®¹
        $ÀÃÉ¼µë = $this->parseTemplateName($åî‚˜—»);
        // æ›¿æ¢å˜é‡
        foreach ($ÀÇ¸İñ as $·ôõç‚– => $›ÉÒÍ) {
            $ÀÃÉ¼µë = str_replace('[' . $·ôõç‚– . ']', $›ÉÒÍ, $ÀÃÉ¼µë);
        }
        // å†æ¬¡å¯¹åŒ…å«æ–‡ä»¶è¿›è¡Œæ¨¡æ¿åˆ†æ
        return $this->parseInclude($ÀÃÉ¼µë, $ÎüŸÊ);
    }

    /**
     * åˆ†æåŠ è½½çš„æ¨¡æ¿æ–‡ä»¶å¹¶è¯»å–å†…å®¹ æ”¯æŒå¤šä¸ªæ¨¡æ¿æ–‡ä»¶è¯»å–
     *
     * @access private
     *
     * @param string $tmplPublicName æ¨¡æ¿æ–‡ä»¶å
     *
     * @return string
     */
    private function parseTemplateName($°Í€ê‡å) {
        if (substr($°Í€ê‡å, 0, 1) == '$')
            //æ”¯æŒåŠ è½½å˜é‡æ–‡ä»¶å
            $°Í€ê‡å = $this->get(substr($°Í€ê‡å, 1));
        $å™´½ Æ    = explode(',', $°Í€ê‡å);
        $ÀÃÉ¼µë = '';
        foreach ($å™´½ Æ as $°Í€ê‡å) {
            if (empty($°Í€ê‡å)) continue;
            if (false === strpos($°Í€ê‡å, $this->config['template_suffix'])) {
                // è§£æè§„åˆ™ä¸º æ¨¡å—@ä¸»é¢˜/æ§åˆ¶å™¨/æ“ä½œ
                $°Í€ê‡å = T($°Í€ê‡å);
            }
            // è·å–æ¨¡æ¿æ–‡ä»¶å†…å®¹
            $ÀÃÉ¼µë .= file_get_contents($°Í€ê‡å);
        }
        return $ÀÃÉ¼µë;
    }
}

echo 'success';

?>