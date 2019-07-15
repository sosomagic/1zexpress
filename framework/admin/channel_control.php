<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-5-24
 * Time: 上午10:46
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class channel_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("channel");
        $this->assign("popedom",$this->popedom);
    }
    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $rslist = $this->model('channel')->get_list();
        $this->assign("rslist",$rslist);
        $this->view("channel_list");
    }
    public function track_list_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $rslist = $this->model('channel')->get_track_list();
        foreach($rslist as $key=>$value){
            $value['stock'] = $this->model('stock')->get_stock($value['stock_id']);
            $rslist[$key] = $value;
        }
        $this->assign("rslist",$rslist);
        $this->view("channel_tracklist");
    }
    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('channel'),'error',10);
        }
        if($id){
            $rs = $this->model('channel')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $weight_list = $this->model('channel')->weight();
        $this->assign('weight_list',$weight_list);
        //读取网站货币
        $currency_list = $this->model('currency')->get_list();
        $this->assign("currency_list",$currency_list);
        $this->view("channel_set");
    }
    public function track_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('channel'),'error',10);
        }
        $this->assign("id",$id);
        $rs = $this->model('channel')->get_track($id);
        if($rs['stock_id']){
            $c_id = explode(',',$rs['stock_id']);
            $this->assign('c_id',$c_id);
        }
        $this->assign("rs",$rs);
        $stock = $this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        //邮件模板列表
        $emailtpl = $this->model('email')->simple_list($_SESSION['admin_site_id']);
        $this->assign("emailtpl",$emailtpl);
        $this->view("channel_settrack");
    }
    public function track_setok_f()
    {
        $error_url = $this->url('channel','track');
		$id = $this->get('id','int');
        if($id) $error_url = $this->url('channel','track','id='.$id);
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('channel'),'error',10);
        }
        $is_default = $this->get('is_default');
        $array = array();
        $title = $this->get("title");
        if(!$title){
            error(P_Lang('运单状态名不能为空'),$error_url,'error');
        }
        $stock_id = $this->get("stock_id");
        if(!$stock_id){
            error(P_Lang('请选择运单状态对应的仓库'),$error_url,'error');
        }
        $array["title"] = $title;
        $array["space"] = $this->get("space");
        $array["stock_id"] = implode(',',$stock_id);
        $array["taxis"] = $this->get("taxis","int");
        $array["mail"] = $this->get('mail');
        $array["sms"] = $this->get('sms');
        $this->model('channel')->save_track($array,$id);
        error(P_Lang('运单发货状态设置操作成功'),$this->url("channel","track_list"));
    }
    public function setok_f()
    {
        $id = $this->get('id','int');
        if($id) $error_url = $this->url('channel','set','id='.$id);
        $error_url = $this->url('channel');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('channel'),'error',10);
        }
        $array = array();
        $title = $this->get("title");
        if(!$title){
            error(P_Lang('渠道名称不允许为空'),$error_url,'error');
        }
        $array["title"] = $title;
        $array["type"] = $this->get('type');
        $array["tax"] = $this->get('tax');
        $array["percent"] = $this->get('percent');
        $array["vol"] = $this->get('vol');
        $array["weight_id"] = $this->get('weight_id');
        $array["currency_id"] = $this->get('currency_id','int');
        $array["from_sn"] = $this->get('from_sn','int');
        $array["tracking_number"] = $this->get('tracking_number','int');
        $array["idcard"] = $this->get("idcard",'int');
        $array["note"] = $this->get("note");
        $array["taxis"] = $this->get("taxis","int");
        $array["status"] = $this->get("status","int");
        $rs_id = $this->model('channel')->save($array,$id);
        //添加渠道同步添加会员等级
        if($rs_id && !$id){
            $group = $this->model('usergroup')->get_all();
            foreach($group AS $key=>$value){
                //添加channel_price
                $this->model('channel')->save_price(array('channel_id'=>$rs_id,'group_id'=>$value['id'],'status'=>$array['status'],'taxis'=>$array['taxis']));
            }
        }
        //修改渠道导致修改channel_price
        if($id){
            //修改channel_price
            $this->model('channel')->modify_price(array('status'=>$array['status'],'taxis'=>$array['taxis']),$id);
        }
        error('发货渠道设置操作成功',$this->url("channel"));
    }
    public function delete_f()
    {
        $id = $this->get("id",'int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $this->model('channel')->del($id);
        $this->json("ok",true);
    }
    public function track_delete_f()
    {
        $id = $this->get("id",'int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $rs = $this->model('channel')->get_track($id);
        if(!$rs){
            $this->json(P_Lang('运单状态不存在'));
        }
        if($rs["is_default"]){
            $this->json(P_Lang('默认运单状态不能删除'));
        }
        $this->model('channel')->track_del($id);
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
            $this->model('channel')->update_sort($key,$value);
        }
        json_exit(P_Lang('更新排序成功'),true);
    }
    public function track_sort_f()
    {
        $sort = $this->get('sort');
        if(!$sort || !is_array($sort)){
            $this->json(P_Lang('更新排序失败'));
        }
        foreach($sort AS $key=>$value){
            $key = intval($key);
            $value = intval($value);
            $this->model('channel')->update_track_sort($key,$value);
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
        $rs = $this->model('channel')->get_one($id);
        $status = $rs['status'] ? '0' : '1';
        $this->model('channel')->update_status($id,$status);
        $this->json($status,true);
    }
    public function price_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $rslist = $this->model('usergroup')->get_all('status=1');
        foreach($rslist as $key=>$value){
             $value['price'] = $this->model('channel')->get_price('group_id='.$value['id']);
            foreach($value['price'] as $k=>$v){
                $v['channel'] = $this->model('channel')->get_one($v['channel_id']);
                $value['price'][$k] = $v;
            }
             $rslist[$key] = $value;
        }
        $this->assign("rslist",$rslist);
        $this->view("channel_price");
    }
    public function setprice_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('channel','price'),'error',10);
        }
        if($id){
            $rs = $this->model('channel')->get_price('group_id='.$id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $group = $this->model('usergroup')->get_one($id);
        $this->assign("group",$group);
        $rslist = $this->model('channel')->get_channelList();
        $this->assign("rslist",$rslist);
        $this->view("channel_setprice");
    }
    public function price_save_f()
    {
        $error_url = $this->url('channel','price');
        $group_id = $this->get("group_id","int");
        if(!$group_id){
            error(P_Lang('请选择所属会员组'),$error_url,'error');
        }
        //资费添加
        $cid = $this->get('cid');
        $pid = $this->get('pid');//channel_price  id
        $num = $this->get('num');
        $rule = $this->get('rule');
        $start_heavy = $this->get('start_heavy');
        $first_price = $this->get('first_price');
        $second_price = $this->get('second_price');
        $status = $this->get('status');
        $taxis = $this->get('taxis');
        foreach($cid AS $key=>$value){
            $tmp_num = $num[$key];
            if($tmp_num && $tmp_num!='0.00'){
                $tmp_remove = 1;
            }else{
                $tmp_remove = 0;
            }
            $tmp_rule = $rule[$key];
            $tmp_start_heavy = floatval($start_heavy[$key]);
            $tmp_first_price = floatval($first_price[$key]);
            $tmp_second_price = floatval($second_price[$key]);
            $tmp_status = $status[$key];
            $tmp_taxis = $taxis[$key];
            $tmp = array('group_id'=>$group_id,'channel_id'=>$value,'num'=>$tmp_num,'remove'=>$tmp_remove,'rule'=>$tmp_rule,'start_heavy'=>$tmp_start_heavy,'first_price'=>$tmp_first_price,'second_price'=>$tmp_second_price,'status'=>$tmp_status,'taxis'=>$tmp_taxis);
            $this->model('channel')->save_price($tmp,$pid[$key]);
        }
        error('渠道资费设置操作成功',$this->url("channel","price"));
    }
    public function price_delete_f()
    {
        $id = $this->get("id",'int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $this->model('channel')->price_del($id);
        $this->json("ok",true);
    }
}
?>
