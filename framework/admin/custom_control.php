<?php
/***********************************************************
	Filename: admin/custom_control.php
	Note	: 特殊自定义服务管理
	Version : 2.0
	Update  : 16:46 2016-1-9
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class custom_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("custom");
		$this->assign("popedom",$this->popedom);
	}

	public function index_f()
	{
		if(!$this->popedom['list']){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$rslist = $this->model('custom')->get_list();

        foreach($rslist as $key=>$value){
            //$currency[] = $this->model('currency')->get_one($value['currency_id']);
            $value['currency'] = $this->model('currency')->get_one($value['currency_id']);
            $value['cate_name'] = $this->model('cate')->cate_info($value['cate_id']);
            $rslist[$key] = $value;
        }
        $this->assign("rslist",$rslist);
        //$this->assign('currency',$currency);
		$this->view("custom_list");
	}

	public function set_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('custom'),'error',10);
		}
		if($id){
			$rs = $this->model('custom')->get_one($id);
			$this->assign("rs",$rs);
			$this->assign("id",$id);
		}
        //读取网站货币
       /* $currency_list = $this->model('currency')->get_list();
        $this->assign("currency_list",$currency_list);*/
        //读取运单服务大类
        /*$catelist = $this->model('cate')->get_all(1,1,597);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);*/
		$this->view("custom_set");
	}

	public function setok_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('custom'),'error',10);
		}
		$array = array();
		$array["title"] = $this->get("title");
        $array["note"] = $this->get("note");
		$array["price"] = $this->get("price");
		//$array["is_number"] = $this->get("is_number","int");
		$array["taxis"] = $this->get("taxis","int");
		$array["status"] = $this->get("status","int");
		$error_url = $this->url('custom','set');
		if($id) $error_url = $this->url('custom','set','id='.$id);
		if(!$array["title"]){
			error(P_Lang('服务名称不允许为空'),$error_url,'error');
		}
		$this->model('custom')->save($array,$id);
		error(P_Lang('服务设置操作成功'),$this->url("custom"));
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
		$this->model('custom')->del($id);
		$this->json("ok",true);
	}

    //批量删除服务操作
    public function deletes_f()
    {

        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定ID'));
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $this->model('custom')->del($value);
        }
        $this->json('删除成功',true);
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
			$this->model('custom')->update_sort($key,$value);
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
		$rs = $this->model('custom')->get_one($id);
		$status = $rs['status'] ? '0' : '1';
		$this->model('custom')->update_status($id,$status);
		$this->json($status,true);
	}
}
?>