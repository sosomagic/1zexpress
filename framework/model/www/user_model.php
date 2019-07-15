<?php
/***********************************************************
    Filename: /model/www/user_model.php
    Note	: 用户信息及管理
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:17189095>
    Update  : 2016年5月17日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class user_model extends user_model_base
{
	var $psize = 20;
	function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	//取得全部会员ID
	function get_all_from_uid($uid,$pri="")
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE id IN(".$uid.")";
		return $this->db->get_all($sql,$pri);
	}

	function fields()
	{
		return $this->db->list_fields($this->db->prefix."user");
	}

	//更新会员头像
	function update_avatar($file,$uid)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET avatar='".$file."' WHERE id='".$uid."'";
		return $this->db->query($sql);
	}

}
?>