<?php
/***********************************************************
	Filename: /model/order.php
	Note	: 订单信息及管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年12月8日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_model_base extends dsy_model
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
	//取得订单列表
	function get_list($condition='',$offset=0,$psize=20)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order ";
		if($condition){
			$sql .= " WHERE ".$condition;
		}
		$offset = intval($offset);
		$psize = intval($psize);
		$sql .= " ORDER BY addtime DESC,id DESC LIMIT ".$offset.",".$psize;
		return $this->db->get_all($sql);
	}
    /**
     * 查询订单数量
     * @参数 $condition 查询条件，仅限主表中使用
     * @返回 具体订单数量
     **/
    public function get_count($condition="")
    {
        $sql = "SELECT count(o.id) FROM ".$this->db->prefix."order o ";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"order",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"order");
		}
	}
	//存储商品信息
	public function save_product($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,"order_product",array("id"=>$id));
		}else{
			return $this->db->insert_array($data,"order_product");
		}
	}

	function save_address($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,"order_address",array("id"=>$id));
		}else{
			return $this->db->insert_array($data,"order_address");
		}
	}
	//通过订单号取得单个订单信息
	function get_one_from_sn($sn,$user='')
	{
		if(!$sn){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE sn='".$sn."'";
		if($user){
			$sql .= " AND user_id='".$user."'";
		}
		return $this->db->get_one($sql);
	}
	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	function address($id)
	{
		if(!$id){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."order_address WHERE order_id='".$id."'";
		return $this->db->get_one($sql);
	}

	//取得订单下的产品信息
	function product_list($id)
	{
		if(!$id){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."order_product WHERE order_id='".$id."' order by id asc";
		$rslist = $this->db->get_all($sql);
		if(!$rslist){
			return false;
		}
		/*foreach($rslist AS $key=>$value){
			if($value['ext']){
				$value['ext'] = unserialize($value['ext']);
			}
			$rslist[$key] = $value;
		}*/
		return $rslist;
	}
	public function product_delete($id)
	{
		if(!$id) return false;
		$rs = $this->product_one($id);
		if(!$rs) return false;
		//$oid = $rs['order_id'];
		$sql = "DELETE FROM ".$this->db->prefix."order_product WHERE id='".$id."'";
		$this->db->query($sql);
		return true;
	}

	public function product_one($id)
	{
		if(!$id){
			return false;
		}
		return $this->db->get_one("SELECT * FROM ".$this->db->prefix."order_product WHERE id='".$id."'");
	}

	public function update_order_status($id,$status='')
	{
		$sql = "UPDATE ".$this->db->prefix."order SET status='".$status."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	/*public function save_payment($data,$id=0)
	{
		if($id){
			return $this->db->update_array($data,'order_payment',array('id'=>$id));
		}else{
			return $this->db->insert_array($data,'order_payment');
		}
	}

	public function order_payment($order_id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order_payment WHERE order_id='".$order_id."'";
		return $this->db->get_one($sql);
	}

	public function order_payment_delete($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."order_payment WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	public function order_price($order_id)
	{
		$sql = "SELECT code,price FROM ".$this->db->prefix."order_price WHERE order_id='".$order_id."'";
		$rslist = $this->db->get_all($sql);
		if(!$rslist){
			return false;
		}
		$rs = array();
		foreach($rslist as $key=>$value){
			$rs[$value['code']] = $value['price'];
		}
		return $rs;
	}*/
	public function status_list(){
        return array('create'=>'已下单','pickup'=>'已揽收','paid'=>'已扣款','shipped'=>'已出库','received'=>'国内派送','finished'=>'已签收','unpaid'=>'余额不足');
    }
    public function from()
    {
        return array('1'=>'预报下单','2'=>'直接下单','3'=>'批量导入','4'=>'后台添加','5'=>'后台导入');
    }
	public function work()
    {
        return array('1'=>'原箱转运','2'=>'合箱转运','3'=>'分箱转运','4'=>'直接下单','5'=>'批量下单');
    }
    public function get_order($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE id in(".$id.") ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
   // 获取订单编号
    public function create_sn()
    {
        $sntype = $this->site['biz_sn'];
        if(!$sntype){
            $sntype = 'year-month-date-number';
        }
        $sn = '';
        $list = explode('-',$sntype);
        foreach($list AS $key=>$value){
            if($value == 'year'){
                $sn.= date("y",$this->time);
            }
            if($value == 'month'){
                $sn.= date("m",$this->time);
            }
            if($value == 'date'){
                $sn.= date("d",$this->time);
            }
            if($value == 'hour'){
                $sn.= date('H',$this->time);
            }
            if($value == 'minute' || $value == 'minutes'){
                $sn.= date("i",$this->time);
            }
            if($value == 'second' || $value == 'seconds'){
                $sn.= date("s",$this->time);
            }
            if($value == 'rand' || $value == 'rands'){
                $sn .= rand(1000,9999);
            }
            if($value == 'time' || $value == 'times'){
                $sn .= $this->time;
            }
            if($value == 'number'){
                $condition = "FROM_UNIXTIME(addtime,'%Y-%m-%d')='".date("Y-m-d",$this->time)."'";
                $total = $this->model('order')->get_count($condition);
                if(!$total){
                    $total = '0';
                }
                $total++;
                $sn .= str_pad($total,4,'0',STR_PAD_LEFT);
            }
            if($value == 'id'){
                $condition = "FROM_UNIXTIME(addtime,'%Y-%m-%d')='".date("Y-m-d",$this->time)."'";
                $maxid = $this->model('order')->maxid($condition);
                $sn .= str_pad($maxid,4,'0',STR_PAD_LEFT);
            }
            //包含会员信息
            if($value == 'user'){
                $sn .= $this->session->val('user_id') ? 'U'.str_pad($this->session->val('user_id'),5,'0',STR_PAD_LEFT) : 'G';
            }
            if(substr($value,0,6) == 'prefix'){
                $sn .= str_replace(array('prefix','[',']'),'',$value);
            }
        }
        return $sn;
    }
	public function remove($id,$char)
    {
        $sql = "UPDATE ".$this->db->prefix."order SET remove='".$char."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    public function get_address($condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_address";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->get_all($sql);
    }
    public function get_one_cate_list($cid)
    {
        $sql = "SELECT id,status FROM ".$this->db->prefix."order where cate_id='".$cid."' ORDER BY id DESC";
        return $this->db->get_one($sql);
    }
    public function log_list_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' order by addtime desc";
        return $this->db->get_one($sql);
    }
    public function get_cate_list($cid)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order where cate_id='".$cid."' and remove=0 ORDER BY id DESC";
        return $this->db->get_all($sql);
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
	public function express_save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"order_express",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"order_express","replace");
        }
    }
	function get_orders($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
	function get_one_order($condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
       return $this->db->get_one($sql);
    }
    //统计分析
    function statistical($condition='')
    {
        $sql = "SELECT sum(price) AS sumprice, sum(jingzhong) AS sumweight,sum(channel_price) AS sumchannelprice, FROM_UNIXTIME(addtime,'%c') as month FROM ".$this->db->prefix."order";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " GROUP BY FROM_UNIXTIME(addtime,'%Y-%m')";
        return $this->db->get_all($sql);
    }
	/**
	 * 取得订单的最大ID号，再此基础上+1
	**/
	public function maxid($condition='')
	{
		$sql = "SELECT MAX(id) id FROM ".$this->db->prefix."order";
		if($condition){
            $sql .= " WHERE ".$condition;
        }
		$rs = $this->db->get_one($sql);
		if(!$rs) return '1';
		return ($rs['id']+1);
	}
}
?>