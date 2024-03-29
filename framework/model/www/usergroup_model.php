<?php
/*****************************************************************************************
	文件： model/www/usergroup_model.php
	备注： 用户组前端相应操作
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2014年10月6日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class usergroup_model extends usergroup_model_base
{
	function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	function group_rs($uid)
	{
		$sql = "SELECT g.* FROM ".$this->db->prefix."user_group g ";
		$sql.= "JOIN ".$this->db->prefix."user u ON(g.id=u.group_id) WHERE u.id='".$uid."'";
		$rs = $this->db->get_one($sql);
		if(!$rs)
		{
			$sql = "SELECT * FROM ".$this->db->prefix."user_group WHERE is_default=1 AND status=1";
			$rs = $this->db->get_one($sql);
		}
		return $rs;
	}

	//取得开放的会员组列表
	public function opened_grouplist($pri='')
	{
		$sql = "SELECT id,title,register_status,tbl_id,fields FROM ".$this->db->prefix."user_group ";
		$sql.= "WHERE status=1 AND (is_open=1 OR is_default=1) AND is_guest!=1 ";
		$sql.= "ORDER BY taxis ASC,id DESC";
		return $this->db->get_all($sql,$pri);
	}
}

?>