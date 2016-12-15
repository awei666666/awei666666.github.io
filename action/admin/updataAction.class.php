<?php
/**
 *
 */
class updataAction {
	public function index(){
		//header("Content-Type:text/html;charset=utf-8");
		if(is_file('./pengfei.txt')){
			$str = file_get_contents('./pengfei.txt');
			//header("Content-Type:text/html;charset=utf-8");
			$keyworld=iconv("gb2312","utf-8",$str);
			echo $keyworld;
		}else{
			echo 2;
		}

	}
}
 