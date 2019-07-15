<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-8-11
 * Time: 下午7:27
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class claim_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("claim");
        $this->assign("popedom",$this->popedom);
    }

    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
		$pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $offset = ($pageid-1)*$psize;
        $pageurl = $this->url('claim');
		$condition = "1=1";
        $status = $this->get('status');
        if($status<>''){
            $condition .= " AND status = '".$status."'";
            $pageurl.="&status=".rawurlencode($status);
            $this->assign("status",$status);
        }
        $sn = trim($this->get('sn'));
        if($sn){
            $condition .= " AND sn like '%".$sn."%'";
            $pageurl.="&sn=".rawurlencode($sn);
            $this->assign("sn",$sn);
        }
		$total = $this->model('claim')->get_count($condition);
        if($total>0){
			$rslist = $this->model('claim')->get_list($condition,$offset,$psize);
			if(!$rslist) $rslist = array();
			foreach($rslist AS $key=>$value){
				$value['user'] = $this->model('user')->get_one($value['user_id'],'id',false,false);
				$rslist[$key] = $value;
			}
			$this->assign("rslist",$rslist);
			$this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
		}
        $this->view("claim_list");
    }

    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('claim'),'error',10);
        }
        if($id){
            $rs = $this->model('claim')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->assign("currency",$currency);
        $this->view("claim_set");
    }
    public function setok_f()
    {
        $id = $this->get('id','int');
        $user_id = $this->get('user_id','int');
        $ydsn = $this->get('sn');
        $title = '理赔：'.$ydsn;
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('claim'),'error',10);
        }
        $array = array();
        $array["money"] = $this->get("money");
        $array["replay"] = $this->get("replay");
        $array["status"] = $this->get("status","int");
        if($array["status"] == 1){
            $balance = $this->model('wealth')->get_val($user_id,2);
		    $data = array('wid'=>2,'uid'=>$user_id,'lasttime'=>$this->time);
			$data['val'] = round(($balance+$array["money"]),2);
            $this->model('wealth')->save_info($data);
            $sn = $this->_create_sn();
            $main = array('sn'=>$sn,'type'=>'recharge','payment_id'=>'0','title'=>$title,'content'=>$title,'status'=>'1');
            $main['dateline'] = $this->time;
            $main['user_id'] = $user_id;
            $main['price'] = $array["money"];
            $main['balance'] = $data['val'];
            $main['currency_id'] = $this->site['currency_id'];
            $this->model('payment')->log_create($main);
        }
        $this->model('claim')->save($array,$id);
        error(P_Lang('理赔审核成功！'),$this->url("claim"));
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
        $this->model('claim')->del($id);
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
        $rs = $this->model('claim')->get_one($id);
        $status = $rs['status'] ? '0' : '1';
        $this->model('claim')->update_status($id,$status);
        $this->json($status,true);
    }
    public function show_pic_f()
    {
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('res')->get_one($id,true);
            if(!$rs)
            {
                $this->json(P_Lang("没有理赔凭证！"));
            }
            $this->assign('rs',$rs);
            $this->view('claim_pic');
        }
    }
    private function _create_sn()
    {
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_str = '';
        for($i=0;$i<3;$i++){
            $rand_str .= $a[rand(0,25)];
        }
        $rand_str .= rand(1000,9999);
        $rand_str .= date("YmdHis",$this->time);
        return $rand_str;
    }
}
?>