<?php
/***********************************************************
	Filename: admin/delivery_control.php
	Note	: 上门取件管理
	Version : 2.0
	Update  : 22:55 2016-8-4
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class delivery_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("delivery");
		$this->assign("popedom",$this->popedom);
	}

	public function index_f()
	{
		if(!$this->popedom['list']){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
        $psize = $this->config['psize'] ? $this->config['psize'] : 20;
        $pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
		//管理员所属仓库运单
        $condition = "stock_id in(".$_SESSION["admin_stock"].")";
        $pageurl = $this->url('delivery');
        $total = $this->model('delivery')->get_count($condition);
        if($total){
            $rslist = $this->model('delivery')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
                $rslist[$key] = $value;
            }
            $this->assign("rslist",$rslist);
            $this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
	    }
        if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view("delivery_list");
    }
	public function set_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('delivery'),'error',10);
		}
		if($id){
			$rs = $this->model('delivery')->get_one($id);
			$this->assign("rs",$rs);
			$this->assign("id",$id);
		}
		$stock = $this->model('stock')->get_list();
        $this->assign('stock',$stock);
		$this->view("delivery_set");
	}

	public function setok_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),$this->url('delivery'),'error',10);
		}
        $visitTime = $this->get('visitTime');
        $visitTime = $visitTime ? strtotime($visitTime) : $this->time;
		$array = array();
		$array["name"] = $this->get("name");
		$array["mobile"] = $this->get("mobile");
		$array["address"] = $this->get("address");
        $array["visitTime"] = $visitTime;
        $array["weight"] = $this->get("weight");
        $array["note"] = $this->get("note");
		$array["status"] = $this->get("status","int");
		$error_url = $this->url('delivery','set');
		if($id) $error_url = $this->url('delivery','set','id='.$id);
		$this->model('delivery')->save($array,$id);
		error(P_Lang('上门取件设置操作成功'),$this->url("delivery"));
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
		$this->model('delivery')->del($id);
		$this->json("ok",true);
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
		$rs = $this->model('delivery')->get_one($id);
		$status = $rs['status'] ? '0' : '1';
		$this->model('delivery')->update_status($id,$status);
		$this->json($status,true);
	}
    public function dels_f()
    {
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $ids = $this->get("ids");
        if(!$ids){
            $this->json(P_Lang('未指定ID'));
        }
        $idlist=explode(",",$ids);
        foreach($idlist AS $key=>$value){
            $this->model('delivery')->del($value);
        }
        $this->json("ok",true);
    }
    //批量打印
    public function print_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $ids = $this->get('ids');
        if(!$ids){
            $this->json(P_Lang('未指定ID'));
        }
        $idlist = explode(",",$ids);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs = $this->model('delivery')->get_one($value);
            if(!$rs){
                $this->json(P_Lang('上门取件信息不存在'));
            }
            $rslist[] = $rs;
        }
        $this->assign('rslist',$rslist);
        $this->view("delivery_print");
    }
}
?>