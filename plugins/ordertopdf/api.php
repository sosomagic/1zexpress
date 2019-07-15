<?php
class api_ordertopdf extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}
	//全局运行插件，在执行当前方法运行前，调整参数
	public function dsy_before()
	{
		//编写代码;
	}
	//全局运行插件，在执行当前方法运行后，数据未输出前
	public function dsy_after()
	{
		//编写代码;
	}
	
}