<?php
/***********************************************************
	Filename: /model/package.php
	Note	: 包裹信息及管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2016年5月17日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class package_model_base extends dsy_model
{
	function __construct()
	{
		parent::model();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	//取得包裹列表
	function get_list($condition='',$offset=0,$psize=20)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."package ";
		if($condition){
			$sql .= " WHERE ".$condition;
		}
		$offset = intval($offset);
		$psize = intval($psize);
		$sql .= " ORDER BY addtime DESC,id DESC LIMIT ".$offset.",".$psize;
		return $this->db->get_all($sql);
	}
    //自定义快递公司
    function express(){
        return array('UPS','FEDEX','USPS','DHL','ESENDA','EMS','ONTRAC','其他');
    }
    //自定义单位
    function unit(){
        return array('个','包','套','罐','件','瓶','双','把','盒','只','支','台','条','对','袋','张','箱','卡','桶','组','排','打','卷','令','萝','组','其他');
    }

	function get_count($condition="")
	{
		$sql = "SELECT count(o.id) FROM ".$this->db->prefix."package o ";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		return $this->db->count($sql);
	}
    //包裹状态
    public function status()
    {
       // return array('未入库','待创建运单','待付入库费','问题包裹','已生成运单');
        return array('unstored'=>'未入库包裹','stored'=>'已入库包裹','generated'=>'已生成运单');
    }
	/*//取得订单的最大ID号，再此基础上+1
	function maxid()
	{
		$sql = "SELECT MAX(id) id FROM ".$this->db->prefix."order";
		$rs = $this->db->get_one($sql);
		if(!$rs) return '1';
		return ($rs['id']+1);
	}*/

	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"package",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"package");
		}
	}

	//通过订单号取得单个订单信息
	function get_one_from_sn($sn,$user='')
	{
		if(!$sn){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."package WHERE sn='".$sn."'";
		if($user){
			$sql .= " AND user_id='".$user."'";
		}
		return $this->db->get_one($sql);
	}

	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."package WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
    //取得包裹下的产品信息
    function product_list($id)
    {
        if(!$id){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."package_product WHERE package_id='".$id."' order by id asc";
        $rslist = $this->db->get_all($sql);
        if(!$rslist){
            return false;
        }
        return $rslist;
    }
    function pro_list($ids)
    {
        if(!$ids){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."package_product WHERE package_id in (".$ids.") order by id asc";
        $rslist = $this->db->get_all($sql);
        if(!$rslist){
            return false;
        }
        return $rslist;
    }
    //删除产品
    function product_delete($id)
    {
        $id = intval($id);
        if(!$id) return false;
        //删除产品
        $sql = "DELETE FROM ".$this->db->prefix."package_product WHERE id='".$id."'";
        $this->db->query($sql);
    }
    //包裹删除操作
    public function delete($id)
    {
        $id = intval($id);
        if(!$id){
            return false;
        }
        //删除订单主表
        $sql = "DELETE FROM ".$this->db->prefix."package WHERE id=".$id;
        $this->db->query($sql);
        //删除订单产品信息
        $sql = "DELETE FROM ".$this->db->prefix."package_product WHERE package_id=".$id;
        $this->db->query($sql);
        return true;
    }
    /*public function package_payment($package_id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_payment WHERE package_id='".$package_id."'";
        return $this->db->get_one($sql);
    }*/
    function product_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."package_product";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
   //通过包裹取得单号
    public function get_order($package_id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE FIND_IN_SET($package_id,package_id)";
        return $this->db->get_all($sql);
    }
    //存储包裹商品信息
    public function save_product($data,$id=0)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        if($id){
            return $this->db->update_array($data,"package_product",array("id"=>$id));
        }else{
            return $this->db->insert_array($data,"package_product");
        }
    }
}

?>