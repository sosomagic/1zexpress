<?php
/***********************************************************
	Filename: {$dsy}/model/user.php
	Note	: 会员模块
	Version : 3.0
	Author  : dsaiyin
	Update  : 2013年5月4日
***********************************************************/
class user_model extends user_model_base
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

	//邮箱登录
	function user_email($email,$uid=0)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE email='".$email."'";
		if($uid){
			$sql .= " AND id != '".$uid."'";
		}
		return $this->db->get_one($sql);
	}

	//手机登录
	public function user_mobile($mobile,$uid=0)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE mobile='".$mobile."'";
		if($uid){
			$sql .= " AND id != '".$uid."'";
		}
		return $this->db->get_one($sql);
	}

	//更新会员验证串
	function update_code($code,$id)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET code='".$code."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//更新会员密码
	function update_password($pass,$id)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET pass='".$pass."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//更新会员手机
	function update_mobile($mobile,$id)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET mobile='".$mobile."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//更新会员邮箱
	function update_email($email,$id=0,$chk=0)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET email='".$email."',email_chk='".$chk."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}
   //更新会员信息
 /*   function update_user($email,$mobile,$ucode,$user='',$id=0)
    {
        $sql = "UPDATE ".$this->db->prefix."user SET email='".$email."',mobile='".$mobile."',ucode='".$ucode."', user='".$user."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }*/
    public function update_user($data,$id)
    {
        if(!$data || !is_array($data) || !$id){
            return false;
        }
        return $this->db->update_array($data,"user",array("id"=>$id));
    }

    //发票类型
	function update_invoice_type($invoice_type,$id)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET invoice_type='".$invoice_type."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//发票抬头
	function update_invoice_title($invoice_title,$id)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET invoice_title='".$invoice_title."' WHERE id='".$id."'";
		return $this->db->query($sql);
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

	function uid_from_email($email,$id="")
	{
		if(!$email) return false;
		$sql = "SELECT id FROM ".$this->db->prefix."user WHERE email='".$email."'";
		if($id)
		{
			$sql.= " AND id !='".$id."'";
		}
		$rs = $this->db->get_one($sql);
		if(!$rs) return false;
		return $rs['id'];
	}

	function uid_from_mobile($mobile,$id="")
	{
		if(!$mobile) return false;
		$sql = "SELECT id FROM ".$this->db->prefix."user WHERE mobile='".$mobile."'";
		if($id)
		{
			$sql.= " AND id !='".$id."'";
		}
		$rs = $this->db->get_one($sql);
		if(!$rs) return false;
		return $rs['id'];
	}

	function uid_from_chkcode($code)
	{
		$sql = "SELECT id FROM ".$this->db->prefix."user WHERE code='".$code."'";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		return $rs['id'];
	}

	//更新会员头像
	function update_avatar($file,$uid)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET avatar='".$file."' WHERE id='".$uid."'";
		return $this->db->query($sql);
	}

	//更新会员登录操作
	public function update_session($uid)
	{
		$rs = $this->get_one($uid);
		if(!$rs || $rs['status'] != 1){
			return false;
		}
		$_SESSION["user_id"] = $uid;
		$_SESSION["user_rs"] = $rs;
		$_SESSION["user_name"] = $rs["user"];
		$_SESSION["user_ucode"] = $rs["ucode"];
		return true;
	}
	public function set_status($id,$status=0)
	{
		$sql = "UPDATE ".$this->db->prefix."user SET status='".$status."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}
    //通过user_id
    /*public function address_save($data,$id=0)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        if($id){
            return $this->db->update_array($data,'user_address',array('user_id'=>$id));
        }else{
            return $this->db->insert_array($data,'user_address');
        }
    }*/
}
?>