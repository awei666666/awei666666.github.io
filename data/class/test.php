<?php class test {
	private   $a    = 1;
	private   $b    = 1;
	private   $c    = 1;
	public    $saa  = 1;
	protected $aaaa = 1;
	static $aaa2 = 1;


	public function __construct() {

		//print_r(get_class_vars());
		print_r(get_class_vars(get_class($this)));
	}
}
new test();
exit; ?>