<?php
/**
 * 基础数据库
 * write_time:2016年6月9日 16:24:25
 * writer:韩威兵
 * 主要功能：
 * set_table_name()设置表名，add()增加数据，
 * where()操作数据库条件，delete()删除数据
 * （注意：）其他类调用DB基础基础类，需要通过$static_db调用
 */
class Basemodel {
    //表名称
    private $table_name='';
    //db类的静态对象
    public $static_db;
    //where条件
    private $where;
    //limit
    private $limit;
    //order
    private $order;
    //field
    private $field;
    //join
    private $join;
    /**
     * 构造方法
     * 引入连接数据库类
     */
   public function __construct($table_name,$con='config') {
       require_once 'core/DB.class.php';
       if(!$con){
           $this->static_db=db::new_db();
           $this->table_name=$table_name;
       }else {
           $this->static_db = new DB($con);
           $this->table_name=$table_name;
       }

       return $this;
   }
    /**
     * 设置表名称,有了更优化的解决方案，废除了这个方法
     * @param unknown $table_name
     */
    public function set_table_name($table_name){

        $this->table_name=$table_name;
        return $this;
    }
   /**
    * 表别名
    */
   public function alias($name){
       $this->table_name="{$this->table_name} {$name}";
       return $this;
   }
  
   /**
    * 增加数据
    * @param unknown $arrays  增加的数据源，目前只支持一位数组和二维数组
    * 且
    * @return Ambigous <string, number, multitype:>
    */
   public function add($arrays){
       //判断是一位数组还是二维数组
       //一位数组添加
       if($this->is_two_array($arrays) == 1){
           //使得键值分离，insert语句使用
           $arr_key=array_keys($arrays);
           $arr_value=array_values($arrays);
           $str_key=implode(',', $arr_key);
           $str_value=implode(',', $arr_value);
           $sql="insert into {$this->table_name} ({$str_key}) value ('{$str_value}')";
           $res= $this->static_db->sql_query($sql);
           //二维数组添加
       }else if ($this->is_two_array($arrays) == 2){
           //使得键值分离，insert语句使用
           $key_one=array_keys($arrays);
           //var_dump($key_one);exit;
           $arr_key=array_keys($arrays[$key_one[0]]);
           $str_key=implode(',', $arr_key);
           $str_value=null;
           foreach ($arrays as $k=>$v){
               if($str_value!=null){
                   $str_value=$str_value.',';
               }
               $arr_value=array_values($v);
               $str_value.='("'.implode('@+//', $arr_value).'")';
           }

           $str_value=str_replace('@+//','","',$str_value);
           //dump($str_key);exit;
           $sql="insert into {$this->table_name} ({$str_key}) values {$str_value}";
           $res=$this->static_db->sql_query($sql);
           //不是数组
       }else if($this->is_two_array($arrays) == 0){
           exit('insert数据源'.not_array);
       }
       return $res;
   }
 
   /**
    * 删除数据
    * @param string $ids 删除的数据源，同where条件一样，或者使用where()
    * @return Ambigous <string, number, multitype:>
    */
   public function delete($ids=''){
       if($ids!=''){
           //调用where条件
           $this->where($ids,1);
       }
       $sql="delete from {$this->table_name} {$this->where}";
       
       $res=$this->static_db->sql_query($sql);
       return $res;
   }
   
    /**
     * 保存数据
     * @param string||array $array  数据源，目前支持一位数组和字符串
     * @return Ambigous <string, number, multitype:>
     */
   public function save($array){
       if($this->is_two_array($array)==1){
           $str=$this->save_str($array);
       }else if($this->is_two_array($array)==0){
           $str=$array;
       }
       $sql="update $this->table_name set $str $this->where";
       $res=$this->static_db->sql_query($sql);
       return $res;
   }
   /**
    * limit语句
    */
   public function limit($start,$length){
       $this->limit="limit {$start},{$length}";
       return $this;
   }
   /**
    * order语句
    */
   public function order($order){
       $this->order="order by {$order}";
       return $this;
   }
   /**
    * field
    */
   public function field($field){
       $this->field=$field;
       return $this;
   }
   /**
    * join
    */
   public function join($join){
       $this->join=$join;
       return $this;
   }
   /**
    * select
    */
   public function select(){
       //echo $this->table_name;exit;
       $sql="select {$this->field} from {$this->table_name} {$this->join} {$this->where} {$this->order} {$this->limit}";
       $sql=trim($sql);
        //$sql=substr($sql,1);
       $res=$this->static_db->sql_query($sql);
       return $res;
   }
    /**
     * select_p
     */
    public function select_p(){
        //echo $this->table_name;exit;
        $sql="select {$this->field} from {$this->table_name} {$this->join} {$this->where} order by id asc {$this->limit}";
        //$sql=substr($sql,1);
        $res=$this->static_db->sql_query($sql);
        return $res;
    }
   
   
   
   /**
    * 返回save使用的字符串
    * @param array $array 一位数组
    * @return string
    */
   public function save_str($array){
       
       $str='';
       foreach ($array as $k=>$v){
          
           if($str){
               $str.=" , ";
           }
           if(is_string($v)){
               $str.="$k='{$v}'";
           }else{
               $str.="$k=$v";
           }
       }
       return $str;
   }
   
   
   
   
   
   /**
    * where条件   现在支持字符串和数组，数组可以是一位数组或二维数组或混合使用
    * @param unknown $arr_str 要查询的条件
    * @param string $type  外部调用不写，本类调用不为空即可，阻止return报错
    */
   public function where($arr_str,$type=''){
       $isarray=$this->is_two_array($arr_str);
       //数据为一位数组
       if($isarray==1){
           $str_where=$this->where_one_arr($arr_str);
           //数据为二维数组
       }else if ($isarray==2) {
           $str_where='';
           $str_other='';
           foreach ($arr_str as $k=>$v){
               //处理一位数组中单独的二维数组
               if($this->is_two_array($v) == 1){
                   if ($str_other){
                       $str_other=$str_other.'and';
                   }
                  $str_other.="$k $v[0] ({$v[1]})";
                  unset($arr_str[$k]);
               }
           }
           $str_where=$this->where_one_arr($arr_str);
          
           if($str_where!='' && $str_other!=''){
               
                $str_where=$str_where.' and '.$str_other;
           }else if($str_where=='' && $str_other){
               $str_where=$str_other;
           }
           //数据为字符串
       }else if (is_string($arr_str)){
           $str_where=$arr_str;
       }
       $this->where="where $str_where";
       if($type==''){
           return $this;
       }
   }

   
   /**
    * where条件中一位数组处理方法
    * @param unknown $arr  一位数组
    * @return string|boolean 返回拼接好的字符串
    */
   public function where_one_arr($arr){
       if($this->is_two_array($arr)==1){
           $str_where='';
           foreach ($arr as $k=>$v){
               if($str_where){
                   $str_where.=' and ';
               }
               if (is_string($v)){
                   $str_where.="$k='{$v}'";
               }else{
                   $str_where.="$k=$v";
               }
           }
           return $str_where;
       }else {
           return false;
       }
   }
   
   /**
    * 判断数组是否是二维数组
    * @param unknown $array
    * @return number：返回0：不是数组，1：一位数组，2：二维数组
    */
   public function is_two_array($array){
       if(is_array($array)){
           foreach ($array as $k=>$v){
               if(is_array($v)){
                   return 2;
               }else {
                   return 1;
               }
           }
       }else {
           return 0;
       }
   }
   
   /**
    * 继承DB基类方法，功能输出最近的sql语句
    * 供测试使用
    */
   public function echo_sql(){
       $this->static_db->echo_sql();
   }
   
   /**
    * 测试方法
    */
   public function test() {
       $sql='insert into user value("","hanweibing","123")';
       //$sql='delete from user where id=1';
       
        $new_db=db::new_db();
        $query=$new_db->sql_query($sql);
        $id=$new_db->get_id();
        var_dump($id);
        //var_dump($query);
       //$list=$new_db->echo_sql();
       //return $list;
   }

    /**
     * 关闭数据库
     */
    public function __destruct(){
        $this->table_name='';
        $this->db_name='';
    }
}
