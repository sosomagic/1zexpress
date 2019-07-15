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
    public function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."user WHERE id='".$id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."user_ext WHERE id='".$id."'";
        $this->db->query($sql);
        //删除相应的积分
        $sql = "DELETE FROM ".$this->db->prefix."wealth_info WHERE uid='".$id."'";
        $this->db->query($sql);
        //删除积分日志
        $sql = "DELETE FROM ".$this->db->prefix."wealth_log WHERE goal_id='".$id."'";
        $this->db->query($sql);
        //删除地址库
        $sql = "DELETE FROM ".$this->db->prefix."user_address WHERE user_id='".$id."'";
        $this->db->query($sql);
        //删除会员设置的发票
       //$sql = "DELETE FROM ".$this->db->prefix."user_invoice WHERE user_id='".$id."'";
       //$this->db->query($sql);
        //删除包裹、包裹产品
        /*$sql = "DELETE FROM ".$this->db->prefix."package WHERE user_id='".$id."'";
        $this->db->query($sql);*/
         $sql = "delete a,b from ".$this->db->prefix."package as a join ".$this->db->prefix."order_product as b on a.id = b.package_id where a.user_id  ='".$id."'";
        //删除运单、运单产品、运单收件地址、运单快递记录
        /*$sql = "DELETE FROM ".$this->db->prefix."order WHERE user_id='".$id."'";
        $this->db->query($sql);*/
        $sql = "delete a,b,c,d from ".$this->db->prefix."order as a join ".$this->db->prefix."order_product as b on a.id = b.order_id join ".$this->db->prefix."order_address as c on a.id = c.order_id join ".$this->db->prefix."order_express as d on a.id = d.order_id where a.user_id  ='".$id."'";
        //删除扣款记录
        $sql = "DELETE FROM ".$this->db->prefix."payment_log WHERE user_id='".$id."'";
        $this->db->query($sql);
        return true;
    }
    public function set_status($id,$status=0)
    {
        $sql = "UPDATE ".$this->db->prefix."user SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    public function address_list($condition='',$offset=0,$psize=30)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user_address ";
        if($condition){
            $sql.= "WHERE".$condition." ";
        }
        $sql.= "ORDER BY id DESC limit ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
	public function field_delete($id)
	{
		if(!$id){
			return false;
		}
		$rs = $this->field_one($id);
		$field = $rs["identifier"];
		$sql = "ALTER TABLE ".$this->db->prefix."user_ext DROP `".$field."`";
		$this->db->query($sql);
		$sql = "DELETE FROM ".$this->db->prefix."user_fields WHERE id='".$id."'";
		return $this->db->query($sql);
	}
	//通过ucode查找uid
    function uid_from_ucode($ucode)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user WHERE ucode='".$ucode."'";
        return $this->db->get_one($sql);
    }
}
?>