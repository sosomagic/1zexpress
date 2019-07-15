<?php
if(!defined("DSAIYIN_SET")){
	exit("<h1>Access Denied</h1>");
}
class mobile_lib extends _init_lib
{
	private $obj;
	public function __construct()
	{
		parent::__construct();
		$file = $this->dir_extension().'mobile/Mobile_Detect.php';
		if(file_exists($file)){
			include_once($file);
		}
		$this->obj = new Mobile_Detect();
	}

	public function is_mobile()
	{
		return $this->obj->isMobile();
	}

	public function is_ios()
	{
		if(!$this->obj->is('iOS')){
			return false;
		}
		if($this->obj->version('iPad','float')>=4.3 || $this->obj->version('iPhone','float') >= 4.3 || $this->obj->version('iPod','float') >= 4.3){
			return true;
		}
		return false;
	}
}