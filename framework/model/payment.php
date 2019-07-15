<?php
/***********************************************************
	Filename: /model/payment.php
	Note	: 付款管理器
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年11月23日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class payment_model_base extends dsy_model
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

	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."payment WHERE id=".intval($id);
		return $this->db->get_one($sql);
	}

	function get_code($code)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."payment WHERE code='".$code."'";
		return $this->db->get_one($sql);
	}

	//更新状态
	function status($id=0,$status=0,$is_id=false)
	{
		$sql = "UPDATE ".$this->db->prefix."payment SET status='".$status."' WHERE ";
		$sql.= $is_id ? " id='".$id."'" : " code='".$id."'";
		return $this->db->query($sql);
	}

	//更新手机端状态
	function wap($id=0,$wap=0,$is_id=false)
	{
		$sql = "UPDATE ".$this->db->prefix."payment SET wap='".$wap."' WHERE ";
		$sql.= $is_id ? " id='".$id."'" : " code='".$id."'";
		return $this->db->query($sql);
	}

	//删除支付接口
	function delete_code($code)
	{
		if(!$code) return false;
		$sql = "DELETE FROM ".$this->db->prefix."payment WHERE code='".$code."'";
		return $this->db->query($sql);
	}

	public function log_check($sn)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."payment_log WHERE sn='".$sn."'";
		return $this->db->get_one($sql);
	}

	public function log_update($data,$id=0)
	{
		if(!$id || !$data || !is_array($data)){
			return false;
		}
		return $this->db->update_array($data,'payment_log',array('id'=>$id));
	}

	public function log_create($data)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		return $this->db->insert_array($data,'payment_log');
	}

	public function log_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."payment_log WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	public function log_delete($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."payment_log WHERE id='".$id."'";
		return $this->db->query($sql);
	}
    function get_list($condition='',$offset=0,$psize=20)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."payment_log";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= " ORDER BY dateline DESC,id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
    function get_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."payment_log";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
    /*function pay()
    {
        return array('账户余额','现金','支票','paypal','applepay','信用卡','挂账','银行转账','支付宝','微信','人工充值','扫码充值');
    }*/
	function get_export($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."payment_log";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " ORDER BY user_id DESC,dateline DESC,id DESC";
        return $this->db->get_all($sql);
    }
    function type()
    {
        return array('recharge'=>'充值','order'=>'扣款','point'=>'积分');
    }
	//财务统计
    function statistical($condition='')
    {
        $sql = "SELECT sum(price) AS orderPrice,  min(balance) as balanceprice, FROM_UNIXTIME(dateline,'%e') as day FROM ".$this->db->prefix."payment_log";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " GROUP BY FROM_UNIXTIME(dateline,'%Y-%m-%d')";
		//echo $sql;
		//exit;
        return $this->db->get_all($sql);
    }
	//获取指定时间余额 统计
	public function balance_val($uid,$date)
	{
		$sql = "SELECT balance FROM ".$this->db->prefix."payment_log WHERE user_id='".$uid."'";
		if($date){
			$sql.= " and FROM_UNIXTIME(dateline,'%Y-%m-%d')='".$date."'";
		}
		$sql.= " order by id desc";
		$rs = $this->db->get_one($sql);
		if($rs){
			return $rs['balance'];
		}
		return 0;
	}
	public function balance_val2($uid,$date)
	{
		$sql = "SELECT balance FROM ".$this->db->prefix."payment_log WHERE user_id='".$uid."'";
		if($date){
			$sql.= " and FROM_UNIXTIME(dateline,'%Y-%m-%d')<'".$date."' and balance<>''";
		}
		$sql.= " order by id desc";
		$rs = $this->db->get_one($sql);
		if($rs){
			return $rs['balance'];
		}
		//return 0;
	}
    //支付方式
    function paymentMethod(){
        return array(
            'balance'   => '账户余额'
            ,'cash'     => '现金'
            ,'alipay'   => '支付宝'
            ,'wechat'   => '微信'
            ,'paypal'   => 'paypal'
            ,'bank'     => '银行转账'
            ,'scan'     => '扫码充值'
            ,'account'  => '挂账'
        );
    }
    /**
     * 检查订单是否有未支付日志
     * @参数 $sn 订单标识
     * @参数 $type 类型
     **/
    public function log_check_notstatus($sn,$type='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."payment_log WHERE sn='".$sn."' AND status=0";
        if($type){
            $sql .= " AND type='".$type."'";
        }
        return $this->db->get_one($sql);
    }
}
?>