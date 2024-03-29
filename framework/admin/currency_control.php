<?php
/***********************************************************
	Filename: admin/currency_control.php
	Note	: 货币管理
	Version : 3.0
	Author  : dsaiyin
	Update  : 2011-07-16 07:15
***********************************************************/
class currency_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("currency");
		$this->assign("popedom",$this->popedom);
	}

	public function index_f()
	{
		if(!$this->popedom['list']){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$rslist = $this->model('currency')->get_list();
		$this->assign("rslist",$rslist);
		$this->view("currency_list");
	}

	public function set_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('currency'),'error',10);
		}
		if($id){
			$rs = $this->model('currency')->get_one($id);
			$this->assign("rs",$rs);
			$this->assign("id",$id);
		}
		$this->view("currency_set");
	}

	public function setok_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('currency'),'error',10);
		}
		$array = array();
		$array["code"] = $this->get('code');
		$array["code_num"] = $this->get('code_num');
		$array["val"] = $this->get("val","float");
		$array["title"] = $this->get("title");
		$array["symbol_left"] = $this->get("symbol_left");
		$array["symbol_right"] = $this->get("symbol_right");
		$array["taxis"] = $this->get("taxis","int");
		$array["status"] = $this->get("status","int");
		$array["hidden"] = $this->get("hidden","int");
		$error_url = $this->url('currency','set');
		if($id) $error_url = $this->url('currency','set','id='.$id);
		if(!$array["title"]){
			error(P_Lang('名称不允许为空'),$error_url,'error');
		}
		if(!$array["code"]){
			error(P_Lang('编码不允许为空'),$error_url,'error');
		}
		$this->model('currency')->save($array,$id);
		error(P_Lang('货币设置操作成功'),$this->url("currency"));
	}

	public function delete_f()
	{
		$id = $this->get("id",'int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		if(!$this->popedom['delete']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$this->model('currency')->del($id);
		$this->json("ok",true);
	}

	public function sort_f()
	{
		$sort = $this->get('sort');
		if(!$sort || !is_array($sort)){
			$this->json(P_Lang('更新排序失败'));
		}
		foreach($sort AS $key=>$value){
			$key = intval($key);
			$value = intval($value);
			$this->model('currency')->update_sort($key,$value);
		}
		json_exit(P_Lang('更新排序成功'),true);
	}

	public function status_f()
	{
		if(!$this->popedom['status']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定要操作的ID'));
		}
		$rs = $this->model('currency')->get_one($id);
		$status = $rs['status'] ? '0' : '1';
		$this->model('currency')->update_status($id,$status);
		$this->json($status,true);
	}
	//获取默认货币
    public function get_currency_f(){
        $currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->json($currency,true);
    }
}
?>
