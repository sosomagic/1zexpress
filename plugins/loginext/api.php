<?php
//第三方登录接口操作
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class api_loginext extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}
	
	public function unbindaccount()
	{
		if(!$this->session->val('user_id')){
			$this->error('非会员不能执行此操作');
		}
		$type = $this->get('type');
		if($type != 'qq' && $type != 'weixin' && $type != 'weibo'){
			$this->error('出错了，解绑参数是错误的');
		}
		$sql = "DELETE FROM ".$this->db->prefix."plugin_loginext WHERE uid='".$this->session->val('user_id')."' AND type='".$type."'";
		$this->db->query($sql);
		$this->success();
	}
}
