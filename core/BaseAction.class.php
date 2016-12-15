<?php
/**
 * 基础控制器
 * write_time:2016年6月9日 16:24:25
 * writer:韩威兵
 */
class BaseAction{
    /**
     * 传入模版变量名
     * @var string
     */
    public  $template_name;
    /**
     * 传入模版变量值
     * @var unknown
     */
    public  $template_value;
    /**
     * 测试index
     */
    public function index() {
        echo '我是基础控制器';
    }
    /**
     * 把变量值和变量名传入模版
     * @param unknown $template_name
     * @param unknown $this_name
     */
    public function assign($template_name,$this_name){
        $this->template_name=$template_name;
        $this->template_value=$this_name;
    }
    
    /**
     * 调用模版
     * @param string $html_name
     */
    public function display($html_name=''){
        $value=$this->template_value;
        $name=$this->template_name;
        //把变量值赋予给模版变量名
        $$name=$value;
        
        //判断是否引用自身名模版
         if ($html_name==''){
             $file=view.home.ds.way_name.'.php';
             //判断是否有文件存在
             if(!is_file($file)){
                 echo not_find_template; exit();
             }
             require_once $file;
         }else {
             $file=view.home.ds.$html_name.'.php';
             //判断是否有文件存在
             if(!is_file($file)){
                 echo not_find_template;
                 exit();
             }
             require_once $file;
         }
    }
    
    
    /**
     * 通过get方式接收数据，并以某种方式过滤
     * @param unknown $name 接受变量名称
     * @param string $filtrate  以何种方式过滤，目前支持整型和浮点型
     * @param number $default 如果为空的默认值
     * @return int|string   最后数据
     */
    public function _get($name,$filtrate='intval',$default=0){
        if($filtrate=='intval'){
            $data=intval($_GET[$name]) ? intval($_GET[$name]) :$default;
        }else if($filtrate=='floatval'){
            $data=floatval($_GET[$name]) ? floatval($_GET[$name]) : $default;
        }else{
            $data=$name;
        }
        return $data;
    }
    /**
     * 判断某个值是否设置，并赋予不同的值
     * @param unknown $name  判断的值
     * @param string $true_data   如果为真赋给的值，默认它本身
     * @param string $default   如果为假赋给的值，默认为空
     * @return 最后数据
     */
    public function is_set($name,$default='',$true_data='') {
        if(!$true_data){
            $true_data=$name;
        }
        $data=isset($name) ? $true_data : $default;
        return $data;
    }
}