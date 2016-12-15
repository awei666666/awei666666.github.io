<?php
class indexAction {
	public function index() {
		$str = file_get_contents('新建文本文档.txt');
		var_dump($str);
	}
}