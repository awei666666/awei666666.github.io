<?php
class my_upload{
    /**
     * 上传路径
     */
    private $path;
    
    function test(){
        $img=$_FILES;
        var_dump($img);
    }
}