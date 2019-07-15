<?php
/***********************************************************
	Filename: admin/usergroup_control.php
	Note	: 会员组管理
	Version : 3.1
	Author  : dsaiyin
	Update  : 2011-03-13
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class usergroup_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("usergroup");
		$this->assign("popedom",$this->popedom);
		$this->lib('form')->cssjs();
	}
	public function index_f()
	{
		if(!$this->popedom["list"]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$rslist = $this->model('usergroup')->get_all();
		$this->assign("rslist",$rslist);
		$this->view("usergroup_list");
	}
	public function set_f()
	{
		$id = $this->get("id","int");
		if($id){
			if(!$this->popedom["modify"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$rs = $this->model('usergroup')->get_one($id);
			$this->assign("rs",$rs);
			$this->assign('id',$id);
		}else{
			if(!$this->popedom["add"]){
				error('您没有权限执行此操作','','error');
			}
		}
		$this->view("usergroup_set");
	}
	//存储信息
	public function setok_f()
	{
		$array = array();
		$id = $this->get("id","int");
		$title = $this->get("title");
		$error_url = $this->url("usergroup","set");
		if($id){
			if(!$this->popedom["modify"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$error_url .= "&id=".$id;
		}else{
			if(!$this->popedom["add"]){
				error('您没有权限执行此操作','','error');
			}
		}
		if(!$title){
			error('会员等级名称不允许为空',$error_url,"error");
		}
		$array["title"] = $title;
		//$array["is_open"] = $this->get("is_open","int");
		$array["taxis"] = $this->get("taxis","int");
		$array["register_status"] = $this->get("register_status");
		if($id)
		{
			$this->model('usergroup')->save($array,$id);
		}
		else
		{
			$group_id = $this->model('usergroup')->save($array);
		}
		error(P_Lang('会员等级信息设置成功'),$this->url("usergroup"),"ok");
	}
	public function ajax_del_f()
	{
		if(!$this->popedom["delete"]) exit(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('未指定ID'));
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs){
			exit(P_Lang('数据记录不存在'));
		}
		if($rs["is_default"]){
			exit(P_Lang('默认会员组不能删除'));
		}
		if($rs["is_guest"]){
			exit(P_Lang('默认游客组不能删除'));
		}
		$this->model('usergroup')->del($id);
		exit("ok");
	}
	public function default_f()
	{
		if(!$_SESSION["admin_rs"]["if_system"]){
			exit(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('未指定ID'));
		}
		$this->model('usergroup')->set_default($id);
		exit("ok");
	}
	public function guest_f()
	{
		if(!$_SESSION["admin_rs"]["if_system"]){
			exit(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('未指定ID'));
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs){
			exit(P_Lang('数据记录不存在'));
		}
		if($rs["is_default"]){
			exit(P_Lang('默认会员组不能设为游客组'));
		}
		$this->model('usergroup')->set_guest($id);
		exit("ok");
	}
	public function status_f()
	{
		if(!$this->popedom['status']){
			exit(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('未指定ID'));
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs)
		{
			exit(P_Lang('会员组信息不存在'));
		}
		$status = $this->get("status","int");
		$this->model('usergroup')->set_status($id,$status);
		exit("ok");
	}
}
?>