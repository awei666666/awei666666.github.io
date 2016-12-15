<?php
/**
 * mvc路由
 * write_time:2016年6月9日 16:24:25
 * writer:韩威兵
 */
class main{
   
    /**
     * 跑起来的入口
     */
    static function init(){
        self::lang();
        self::run();
        self::autoload();
        self::do_run();
    }
    /**
     * 引入扩展文件
     * @lang 语言包
     */
    static function lang(){
        $url='public/lang/zn.php';
        if(!is_file($url)){
            echo '语言包引入错误！';
        }
        $lang=include $url;
       foreach ($lang as $k=>$v){
           define($k, $v,true);
       }
    }

    /**
     * 定义常量和引入基础控制器和基础model
     */
    static function run(){
        define('DS', DIRECTORY_SEPARATOR,true);
        define('ACTION','action'.DS,true);
        define('CORE','core'.DS,true);
        define('MODEL','model'.DS,true);
        define('VIEW', 'view'.DS,true);

        define('HOME', isset($_REQUEST['h'])?$_REQUEST['h']:'home',true);
        define('ACTION_NAME', isset($_REQUEST['a'])?$_REQUEST['a']:'index',true);
        define('WAY_NAME',isset($_REQUEST['w'])?$_REQUEST['w']:'index',true);

       require_once CORE.'BaseAction.class.php';
       require_once 'core/use_function.php';
       require_once CORE.'Basemodel.class.php';
    }
    /**
     * 自动加载类
     */
    static function autoload(){
        spl_autoload_register(array('main','load'));
    }
    /**
     * 自动加载类中类
     * @param unknown $class
     */
    static function load($class){
        $file=$class.'.class.php';
        if (!is_file(ACTION.HOME.DS.$file)){
            echo '该控制器没有找到！请查看名称是否输错！';
            exit;
        }
        if (substr($class, -6)=='Action'){
            include_once ACTION.HOME.DS.$file;
        }else if(substr($class,-5)=='model'){
            include_once MODEL.DS.$file;
        }
        
    }
    static function do_run(){
        $class_name=ACTION_NAME.'Action';
        $new=new $class_name;
        $arr=array($new,WAY_NAME);
        $bool=is_callable($arr,false,$name);
        if (!$bool){
            echo '该方法不存在！';
            exit;
        }
        $new->{WAY_NAME}();
        
       
        /* 失败的案例
         * if(!function_exists(WAY_NAME)){
            echo '该方法不存在！';
            exit;
        }
           try {
               $new=new $class_name;
            throw new Exception('请核实方法名核实');
            $new->{WAY_NAME}();
        }catch(Exception $e) {
            echo $e->getMessage();
        }   */
       
    }
    
    
    
    
    
    
   
}