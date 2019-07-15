<?php
/*****************************************************************************************
	文件： plugins/loginext/setting.php
	备注： 后台管理员操作
	版本： 2.x
	网站： www.dsaiyin.com
	作者： dsaiyin <850272422@qq.com>
	时间： 2016年06月26日 20时39分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class setting_loginext extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->plugin_info();
		$this->tpl->assign('plugin',$this->me);
	}

	public function index()
	{
		return $this->plugin_tpl('setting.html');
	}

	public function save()
	{
		$id = $this->plugin_id();
		$ext = array();
		$ext['qq_appid'] = $this->get('qq_appid');
		$ext['qq_appkey'] = $this->get('qq_appkey');
		$ext['wx_appid'] = $this->get('wx_appid');
		$ext['wx_secret'] = $this->get('wx_secret');
		$ext['wb_appkey'] = $this->get('wb_appkey');
		$ext['wb_secret'] = $this->get('wb_secret');
		$this->plugin_save($ext,$id);
	}
}
?>