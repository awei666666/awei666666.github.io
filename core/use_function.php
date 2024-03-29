<?php
require_once 'core/BaseModel.class.php';
/**
 * 实例化模型基本类
 * @param model $name
 */
function M($name,$con='config') {

    static $new_db=array();
    if(!isset($new_db['user'])){
        $new_db['user']=new Basemodel($name,$con);
    }
    return $new_db['user'];
}
function M2($name,$con='config') {

    static $new_db=array();
    if(!isset($new_db['user'])){
        $new_db['user']=new Basemodel($name,$con);
    }
    return $new_db['user'];
}


/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}