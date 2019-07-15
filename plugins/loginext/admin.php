<?php
/*****************************************************************************************
	文件： plugins/loginext/admin.php
	备注： 后台管理员操作
	版本： 2.x
	网站： www.dsaiyin.com
	作者： dsaiyin <850272422@qq.com>
	时间： 2016年06月26日 20时39分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class admin_loginext extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->plugin_info();
		$this->tpl->assign('plugin',$this->me);
	}

	//后台删除会员时执行扩展表删除
	public function ap_user_ajax_del_after()
	{
		$id = $this->get('id','int');
		if($id){
			$sql = "DELETE FROM ".$this->db->prefix."plugin_loginext WHERE uid='".$id."'";
			$this->db->query($sql);
		}
		return true;
	}
}

?>