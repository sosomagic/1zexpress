<?php
/*****************************************************************************************
	文件： model/express.php
	备注： 物流管理
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2015年09月07日 11时51分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class express_model_base extends dsy_model
{
	public function __construct()
	{
		parent::model();
	}

	public function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."express WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
    public function get_express($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_express WHERE express_id='".$id."'";
        return $this->db->get_one($sql);
    }
	public function get_all()
	{
		$sql = "SELECT id,title,company,homepage,code FROM ".$this->db->prefix."express";
		return $this->db->get_all($sql);
	}
    public function express_id($title)
    {
        $sql = "select * FROM ".$this->db->prefix."express WHERE title like '%".$title."%'";
        return $this->db->get_one($sql);
    }
}

?>