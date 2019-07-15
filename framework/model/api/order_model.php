<?php

/*****************************************************************************************

	文件： model/api/order_model.php

	备注： 订单接口相关操作

	版本： 2.0

	网站： www.dsaiyin.com

	作者： dsaiyin <QQ:850272422>

	时间： 2015年09月07日 16时12分

*****************************************************************************************/

if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_model extends order_model_base
{
	public function __construct()
	{
		parent::__construct();
	}
	public function express_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order_express WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
	public function log_all($order_id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' ORDER BY addtime ASC,id ASC";
		return $this->db->get_all($sql);
	}
	public function log_delete($order_id,$order_express_id,$who='')
	{
		$sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' ";
		$sql.= " AND order_express_id='".$order_express_id."' ";
		$sql.= " AND who='".$who."'";
		return $this->db->query($sql);
	}
	public function log_save($data)
	{
		if(!$data){
			return false;
		}
		if(!$data['who'] && $_SESSION['user_id']){
			$user = $this->model('user')->get_one($_SESSION['user_id']);
			$data['who'] = $user['user'];
		}
		if(!$data['addtime']){
			$data['addtime'] = $this->time;
		}
		return $this->db->insert_array($data,'order_log');
	}
	public function update_last_query_time($id)
	{
		$sql = "UPDATE ".$this->db->prefix."order_express SET last_query_time='".$this->time."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}
	public function update_end($id)
	{
		$sql = "UPDATE ".$this->db->prefix."order_express SET is_end=1 WHERE id='".$id."'";
		return $this->db->query($sql);
	}
	//保存订单各种状态下的价格
	public function save_order_price($data)
	{
		return $this->db->insert_array($data,'order_price');
	}
    //批量导入通过客户运单号判断数据是否重复
    function get_from_sn($sn,$user='')
    {
        if(!$sn) return false;
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE sn ='".$sn."'";
        if($user)
        {
            $sql .= " AND user_id='".$user."'";
        }
        return $this->db->get_one($sql);
    }
    function get_max($condition="")
    {
        $sql = "SELECT MAX(right(sn,5)) sn FROM ".$this->db->prefix."order";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        $rs = $this->db->get_one($sql);
        return ($rs['sn']);
    }
    function address_save($data,$id=0)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        if($id){
            return $this->db->update_array($data,"order_address",array("order_id"=>$id));
        }else{
            return $this->db->insert_array($data,"order_address");
        }
    }
}
?>