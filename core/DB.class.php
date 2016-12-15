<?php
/**
 * 连接数据库操作类
 * write_time:2016年6月9日 16:24:25
 * writer:韩威兵
 * 主要功能：连接数据库，执行sql语句，打印最近的sql语句,获取最后执行sql的id值
 */
class DB{
    //主机
    private $host;
    //用户
    private $user;
    //密码
    private $password;
    //数据库名字
    private $db_name;
    //数据库字符集
    private $charset;
    //连接
    private $conne;
    //数据库名称
    private $table_name;
    //sql语句
    private $sql;
    //静态对象，做单态使用
    private static $new;
    //执行sql结果集    
    public $res;
    /**
     * 构造函数
     */
    public function __construct($con='config'){
        //echo "public/common/db/$con.php";exit;
        $config=require_once "public/common/db/$con.php";
        $this->host=$config['host'];
        $this->user=$config['user'];
        $this->password=$config['password'];
        $this->db_name=$config['db_name'];
        $this->charset=$config['charset'];
        $this->dbconne();
        
    }
    /**
     * 单例模式
     */
    public static function new_db(){
        if( ! isset( self::$new ) ){
            self::$new=new self;
        }
        return self::$new;
    }
    /**
     * 数据库前三部
     */
    public function dbconne(){
        $this->conne=mysqli_connect($this->host,$this->user,$this->password);
        if($this->conne==null){
            echo 'mysql数据库连接失败！错误位置：'.$this->host.$this->user.$this->password;
        }
        $db_name=mysqli_select_db($this->conne,$this->db_name);
        if (!$db_name){
            echo $this->db_name.'数据库打开失败！';
        }
        mysqli_set_charset($this->conne,$this->charset);
    }
    /**
     * 执行sql语句
     */
    public function sql_query($sql){
        //echo $sql;exit;
        $res='';
        $this->sql=$sql;
        $result=mysqli_query($this->conne,$this->sql);
        if($result===false){
            echo 'sql语句错误'.mysqli_error($this->conne).'<br/>';
        }
        if (substr($sql, 0,6) == 'select' ){
           
            $row=mysqli_affected_rows($this->conne);
            for ($i=0;$i<$row;$i++){
                $res[]=mysqli_fetch_assoc($result);
            }
        }else{
            if($result){
                $res=mysqli_affected_rows($this->conne);
            }
        }
        return $res;
    }
    /**
     * 打印最近的sql语句
     */
    public function echo_sql(){
        echo $this->sql;
    }
    /**
     * 获取最后执行sql的id值
     */
    public function get_id(){
        $id=mysql_insert_id();
        return $id;
    }
    /**
     * 关闭数据库
     */
    public function __destruct(){
        mysqli_close($this->conne);
        $this->table_name='';
        $this->db_name='';
    }
    
}