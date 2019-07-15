<?php
class setting_ordertopdf extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}
	//插件配置参数时，增加的扩展表单输出项
	public function index()
	{
		//return $this->_tpl('setting.html');
	}
	//插件配置参数时，保存扩展参数
	public function save()
	{
		$id = $this->_id();
		$ext = array();
		//$ext['扩展参数字段名'] = $this->get('表单字段名');
		$this->_save($ext,$id);
	}
	//插件执行审核动作时，执行的操作
	public function status()
	{
		//执行一些自定义的动作
	}
	
}