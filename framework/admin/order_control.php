<?php
/***********************************************************
Filename: /admin/order_control.php
Note	: 订单管理，编辑和删除等相关操作
Version : 2.0
Web		: www.dsaiyin.com
Author  : dsaiyin <QQ:17189095>
Update  : 2013年12月18日
 ***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("order");
        $this->assign("popedom",$this->popedom);
    }
    //显示订单列表
    public function index_f()
    {
        if(!$this->popedom["list"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $offset = ($pageid-1)*$psize;
        $pageurl = $this->url('order');
        $condition = "1=1";
        $status = $this->get("status");
        if($status){
            $condition .= " AND o.status='".$status."'";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }else{
			$condition .= " and o.status!='check'";
		}
        $stock_id = $this->get("stock_id",'int');
        if($stock_id){
            $condition .= " AND o.stock_id=".$stock_id;
            $pageurl .= "&stock_id=".rawurlencode($stock_id);
            $this->assign('stock_id',$stock_id);
        }
        $stocks = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stocks);
        $channel_list = $this->model('channel')->get_channelList();
        $this->assign('channel_list',$channel_list);
        $channel_id = $this->get("channel_id",'int');
        if($channel_id){
            $condition .= " AND o.channel='".$channel_id."'";
            $pageurl .= "&channel_id=".rawurlencode($channel_id);
            $this->assign('channel_id',$channel_id);
        }
        $batch = trim($this->get("batch"));
        if($batch){
            $batch_arr = $this->model('batch')->get_one($batch,'title');
            $condition .= " AND cate_id='".$batch_arr['id']."'";
            $pageurl .= "&batch=".rawurlencode($batch);
            $this->assign('batch',$batch);
        }
        //批次需要
        $cid = $this->get("cid","int");
        if($cid){
            $condition .= " AND cate_id='".$cid."'";
            $pageurl .= "&cid=".rawurlencode($cid);
            $this->assign('cid',$cid);
        }
        /*$type = $this->get("type","int");
        if($type){
            $condition .= " AND type='".$type."'";
            $pageurl .= "&type=".rawurlencode($type);
            $this->assign('type',$type);
        }*/
        $express_sn = trim($this->get('express_sn'));
        if($express_sn){
            $condition .= " AND o.express_sn ='".$express_sn."'";
			$pageurl .= "&express_sn=".rawurlencode($express_sn);
            $this->assign('express_sn',$express_sn);
        }
        $sn = trim($this->get('sn'));
        if($sn){
            $condition .= " AND o.sn like '%".$sn."%'";
			$pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $user = trim($this->get('user'));
        if($user){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
            $pageurl .= "&user=".rawurlencode($user);
            $this->assign('user',$user);
        }
        $consignor = trim($this->get('consignor'));
        if($consignor){
            $condition .= " AND o.consignor like '%".$consignor."%'";
            $pageurl .= "&consignor=".rawurlencode($consignor);
            $this->assign('consignor',$consignor);
        }
        $ucode = trim($this->get('ucode'));
        if($ucode){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
            $pageurl .= "&ucode=".rawurlencode($ucode);
            $this->assign('ucode',$ucode);
        }
        $consignee = trim($this->get('consignee'));
        if($consignee){
            $condition .= " AND o.id in(SELECT order_id FROM ".$this->db->prefix."order_address WHERE fullname like '%".$consignee."%')";
            $pageurl .= "&consignee=".rawurlencode($consignee);
            $this->assign('consignee',$consignee);
        }
        $consignee_mobile = trim($this->get('consignee_mobile'));
        if($consignee_mobile){
            $condition .= " AND o.id IN(SELECT order_id FROM ".$this->db->prefix."order_address WHERE mobile like '%".$consignee_mobile."%')";
            $pageurl .= "&consignee_mobile=".rawurlencode($consignee_mobile);
            $this->assign('consignee_mobile',$consignee_mobile);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
		//判断管理员管理仓库权限
		if($_SESSION["admin_rs"]["if_system"]){
			$wh =" and remove=0";
		}else{
			$wh = " and remove=0 and o.stock_id in(".$_SESSION["admin_stock"].")";
		}
		$statuslist = $this->model('order')->status_list();
        foreach($statuslist as $key => $value){
            $count[$key] = $this->model('order')->get_count("status='".$key."'".$wh);
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign('statuslist',$statuslist);
        $total = $this->model('order')->get_count($condition.$wh);
        if($total>0){
            $rslist = $this->model('order')->get_list($condition.$wh,$offset,$psize);
            if(!$rslist) $rslist = array();
            foreach($rslist AS $key=>$value){
                $channel[] = $this->model('channel')->get_one($value['channel']);
                $address[] = $this->model('order')->address($value['id']);
                $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
                $value['batch'] = $this->model('batch')->get_one($value['cate_id']);
                $value['weight_units'] = $this->model('channel')->weight();
                $value['currency'] = $this->model('currency')->get_one($value['currency_id']);
                $value['track'] = $this->model('order')->log_list_one($value['id']);
                $value['pros'] = $this->model('order')->product_list($value['id']);
                if($value['custom']){
                    $custom = array();
                    $custom_temp = explode(',',$value['custom']);
                    foreach($custom_temp as $k =>$v){
                        $custom[] = $this->model('custom')->get_one($v);
                    }
                    $value['custom'] = $custom;
                }
                if($value['package_id']){
                    $value['package'] = $this->model('package')->get_list('o.id in ('.$value['package_id'].')');
                }
                $rslist[$key] = $value;
            }
            $this->assign('channel',$channel);
            $this->assign('address',$address);
            $this->assign('rslist',$rslist);
            $this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('共有').'(total)条'.P_Lang('，').P_Lang('当前').'(num)/(total_page)页&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
        }
        $batch_list = $this->model('batch')->get_chk_all();
        $this->assign("batch_list",$batch_list);
        $work = $this->model('order')->work();
        $this->assign('work',$work);
        if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view("order_list");
    }
    //发货管理
    public function delivery_f()
    {
        if(!$this->popedom['delivery']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('未指定ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('运单信息不存在'));
        }
        $this->assign('rs',$rs);
        $rslist = $this->model('order')->log_list($id);
        $this->assign('rslist',$rslist);
        $track_list = $this->model('channel')->track_list("FIND_IN_SET(".$rs['stock_id'].",stock_id)");
        foreach($track_list as $key=>$value){
            //去掉unpaid
            if($value['status']=='unpaid') continue;
            //if (strpos($value['stock_id'],$rs['stock_id']) !== false) {
                $delivery_list[] = $value;
            //}
        }
        $this->assign('delivery_list',$delivery_list);
        //判断前台状态是否已更新
        //$this->assign('rlist',array_reverse($rslist));
        $this->view('order_delivery');
    }
    public function delivery_save_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $note_tmp = explode('|',$this->get('note'));
        $note =$note_tmp[0];
        if(!$note){
            $this->json(P_Lang('请选择发货信息'));
        }
        $rs = $this->model('order')->log_one_from_note($note,$id);
        if($rs){
            $this->json(P_Lang('发货信息已存在，请选择下一步发货信息'));
        }
        $addtime = $this->get('addtime');
        $addtime = $addtime ? strtotime($addtime) : $this->time;
		//增加订单日志
		$data = array('order_id'=>$id,'status'=>$note_tmp[1],'note'=>$note,'addtime'=>$addtime);
		$this->model('order')->log_save($data);
		if($note_tmp[1]){
			$this->model('order')->update_order_status($id,$note_tmp[1]);
			$this->json($note_tmp[1],true);
		}else{
			$this->json(true);
		}
    }
    //批量发货
    /*public function set_track_f(){
        $id = $this->get('id');
        $title = $this->get('title');
        $received = $this->get('received');
        $addtime = $this->get('addtime');
        $addtime = $addtime ? strtotime($addtime) : $this->time;
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs=$this->model('order')->get_one($value);
            //判断扣款成功运单批量发货
            if($rs['status']=='paid'){
                $data = array('order_id'=>$value,'note'=>$title,'addtime'=>$addtime);
                $this->model('order')->log_save($data);
                if($received=='received'){
                    //更新订单状态
                    $this->model('order')->update_order_status($value,'received');
                }
            }else{
                $this->json(P_Lang('运单未扣款不允许批量发货'));
            }
        }
        $this->json(P_Lang('批量发货成功'),true);
    }*/
    public function delivery_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $rs = $this->model('order')->log_one($id);
        if(!$rs){
            $this->json(P_Lang('发货信息不存在'));
        }
        $this->model('order')->log_delete($id);
        $this->json(true);
    }
    //物流维护管理
    public function express_f()
    {
        if(!$this->popedom['express']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        //$status = $this->get('status');
        //$this->assign('status',$status);
        if(!$id){
            $this->error(P_Lang('未指定ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
        $this->assign('rs',$rs);
        //判断订单是否需要物流
        $address = $this->model('order')->address($id);
        if(!$address){
            $this->error(P_Lang('该订单未设置收货地址，请先设置'));
        }
        $rslist = $this->model('order')->express_all($id);
        $loglist = $this->model('order')->log_list($id);
        if($rslist && $loglist){
            foreach($rslist as $key=>$value){
                foreach($loglist as $k=>$v){
                    if($v['order_express_id'] == $value['id']){
                        $rslist[$key]['invoicelist'][] = $v;
                    }
                }
                $rslist[$key]['invoice_total'] = $rslist[$key]['invoicelist'] ? (count($rslist[$key]['invoicelist']) + 1) : 1;
            }
        }
        $this->assign('rslist',$rslist);
        $expresslist = $this->model('express')->get_all();
        $this->assign('expresslist',$expresslist);
        $this->view('order_express');
    }
    public function express_save_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $express_id = $this->get('express_id','int');
        if(!$express_id){
            $this->json(P_Lang('未指定物流公司'));
        }
        $code = trim($this->get('code'));
        if(!$code){
            $this->json(P_Lang('未填写物流单号'));
        }
		$addtime = $this->get('addtime');
		$addtime = $addtime ? strtotime($addtime) : $this->time;
        //判断是否有快递
        $rs = $this->model('order')->get_one($id);
        if($rs['express_sn']){
            $this->json(P_Lang('该运单已有国内快递单号，不能再添加快递信息'));
        }
        $express = $this->model('express')->get_one($express_id);
        $array = array('order_id'=>$id,'express_id'=>$express_id,'code'=>$code,'addtime'=>$addtime);
        $array['title'] = $express['title'];
        $array['homepage'] = $express['homepage'];
        $array['company'] = $express['company'];
        //$this->json(P_Lang(print_r($array,true)));
        $insert = $this->model('order')->express_save($array);
        if(!$insert){
            $this->json(P_Lang('写入失败'));
        }
        //增加订单日志
        $data = array('order_id'=>$id,'order_express_id'=>$insert,'status'=>'received','note'=>P_Lang('国内{title}已揽件，快递单号:{code}',array('title'=>$express['title'],'code'=>$code)),'addtime'=>$addtime);
        $this->model('order')->log_save($data);
        //更新订单状态
        $this->model('order')->save(array('express_sn'=>$code,'express'=>$express['title']),$id);
        $this->json(true);
    }
    public function express_delete_f()
    {
        $id = $this->get('id','int');
        $oid = $this->get('oid','int');
        if(!$id){
            $this->json('未指定ID');
        }
        $rs = $this->model('order')->express_one($id);
        if(!$rs){
            $this->json('物流信息不存在');
        }
        if($rs['is_end']){
            $this->json('物流数据已完成，不能执行删除');
        }
        $this->model('order')->deleteExpress($id);
        $this->model('order')->save(array('express_sn'=>'','express'=>''),$oid);
        $this->json(true);
    }
    //删除订单操作
    public function remove_f()
    {
        $id = $this->get('id');
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        if(!$id) $this->json('未指定运单ID号');
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json('运单不存在');
        }
        //移除订单
        $this->model('order')->remove($id,1);
        $this->json('删除成功',true);
    }
    //批量删除包裹操作
    public function removes_f()
    {
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $id = $this->get('id');
        if(!$id) $this->json('未指定ID');
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            //$rs=$this->model('order')->get_one($value);
            $this->model('order')->remove($value,1);
        }
        $this->json(P_Lang('删除成功'),true);
    }
    //更新订单状态
    public function status_f()
    {
        $id = $this->get('id');
        if(!$this->popedom['status']) $this->json(P_Lang('您没有权限执行此操作'));
        $array = array('status'=>'CHECKED');
        $this->model('order')->save($array,$id);
        $this->json(P_Lang('审核成功'),true);
    }
    //查看订单信息
    public function info_f()
    {
        $id = $this->get('id','int');
        if(!$id) error_open(P_Lang('未指定ID'));
        $rs = $this->model('order')->get_one($id);
        if(!$rs) error_open(P_Lang('订单信息不存在'));
        if($rs['custom']){
            $custom_temp = explode(',',$rs['custom']);
            foreach($custom_temp as $key =>$val){
                $custom[] = $this->model('custom')->get_one($val);
                $custom_currency[] = $this->model('currency')->get_one($custom[$key]['currency_id']);
            }
            $this->assign('custom_currency',$custom_currency);
            $this->assign('custom_temp',$custom_temp);
            $this->assign('custom',$custom);
        }
        $rs['channel'] = $this->model('channel')->get_one($rs['channel']);
		$rs['stock'] = $this->model('stock')->get_one($rs['stock_id']);
        $rs['address'] = $this->model('order')->address($rs['id']);
        //$rs['weight_unit'] = $this->model('channel')->weight();
        $rs['currency'] = $this->model('currency')->get_one($rs['channel']['currency_id']);
        $this->assign('rs',$rs);
        //订单状态
        //$statuslist = $this->model('order')->status_list();
        //$this->assign('statuslist',$statuslist);
		$log = $this->model('order')->log_list_one($rs['id']);
        $this->assign('log',$log);
        $user = $this->model('user')->get_one($rs['user_id']);
        $this->assign('user',$user);
        //订单下的产品列表
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        if($rs['package_id']){
            $package = $this->model('package')->get_package('id in ('.$rs['package_id'].')');
            foreach($package as $key=>$value){
                $value['stock_title'] = $this->model('stock')->get_one($value['stock']);
                $value['weight_unit'] = $this->model('channel')->weight();
                $package[$key] = $value;
            }
            $this->assign('package',$package);
        }
        $this->view("order_info");
    }
    public function set_f()
    {
        $id = $this->get('id','int');
        $this->assign('id',$id);
        $pageurl = $this->get("pageurl");
        $this->assign('pageurl',$pageurl);
        if(!$id){
            if(!$this->popedom['add']){
                error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
            }
        }else{
            if(!$this->popedom['modify']){
                error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
            }
            $rs = $this->model('order')->get_one($id);
            //读取ucode
            $user = $this->model('user')->get_one($rs['user_id']);
            $rs['ucode'] = $user['ucode'];
            $rslist = $this->model('order')->product_list($id);
            $this->assign('rslist',$rslist);
            $address = $this->model('order')->address($id);
            $this->assign('shipping',$address);
            if($rs['custom']){
                $custom_array = explode(',',$rs['custom']);
                $this->assign('custom_array',$custom_array);
            }
            $channel=$this->model('channel')->channel_list($user['group_id']);
            if($channel){
                foreach($channel as $val){
                    $currency_id[] = $val['currency_id'];
                }
            }
            $this->assign('channel',$channel);
        }
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city'],'a'=>$address['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            //编辑的时候有图片显示
            //$idlist[] = strtolower($value["identifier"]);
            if($address[$value["identifier"]]){
                $value["content"] = $address[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->assign('rs',$rs);
        //产品大类
        $catelist = $this->model('cate')->get_all(1,1,70);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        $this->view("order_set");
    }
    public function pay_f()
    {
        if(!$this->popedom['charged']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        $this->assign('id',$id);
        $pageurl = $this->get("pageurl");
        $this->assign('pageurl',$pageurl);
        $rs = $this->model('order')->get_one($id);
        $user = $this->model('user')->get_one($rs['user_id']);
        $rs['ucode'] = $user['ucode'];
        $channel=$this->model('channel')->channel_list($user['group_id']);
        $this->assign('channel',$channel);
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        $address = $this->model('order')->address($id);
        $this->assign('shipping',$address);
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city'],'a'=>$address['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            if($address[$value["identifier"]]){
                $value["content"] = $address[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        if($rs['custom']){
            $custom_array = explode(',',$rs['custom']);
            $this->assign('custom_array',$custom_array);
        }
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->assign('rs',$rs);
        //产品大类
        $catelist = $this->model('cate')->get_all(1,1,70);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        $pay = $this->model('payment')->paymentMethod();
        unset($pay['scan']);
        $this->assign("pay",$pay);
		$currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->assign("currency",$currency);
        $this->view("order_pay");
    }
    //删除产品信息
    function product_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定产品ID'));
        }
        if(!$this->popedom['modify']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $this->model('order')->product_delete($id);
        $this->json(true);
    }
    //存储订单信息
    function save_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            if(!$this->popedom['add']){
                $this->json(P_Lang('您没有权限执行此操作'));
            }
            $this->add_save();
        }
        if(!$this->popedom['modify']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $this->modify_save($id);
    }
	//扣款
    public function pay_save_f()
    {
        $id = $this->get('id','int');
        $old = $this->model('order')->get_one($id);
        if(!$old){
            $this->json(P_Lang('订单信息不存在'));
        }
        $main = array();
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }/*else{
            $consign = $this->model('stock')->get_one($stock);
        }*/
		$user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json(P_Lang('请填写会员编号'));
        }
        /*$main['consignor'] = $consign['consignor'];
        $main['consignor_tel'] = $consign['tel'];
        $main['consignor_address'] = $consign['address'];*/
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(P_Lang('请选择发货渠道！'));
        }
        $channel_arr = explode('|',$channel);
        $jingzhong = trim($this->get('jingzhong'));
        if(!$jingzhong || $jingzhong == '0.00'){
            $this->json(P_Lang('称重重量不能为空！'));
        }
        if(!preg_match('/^[+-]?\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('称重重量输入格式不对！'));
        }
        $main['stock_id'] = $stock;
        $main['position'] = $this->get('position');
        //判断会员等级折扣信息
        $group = $this->model('user')->get_one($old['user_id']);
        $main['channel'] = $channel_arr[0];
        $main['currency_id'] = $channel_arr[6];
        $main['weight_id'] = $channel_arr[7];
        $main['val'] = $this->get('val');
        $main['tax'] = $this->get('tax');
        $main['safe'] = $this->get('safe');
        $main['safe_price'] = $this->get('safe_price');
        $main['weight'] = $this->get('wt');
        $main['jingzhong'] = $jingzhong;
        if($channel_arr[13]){
            $main['length'] = $this->get('length');
            $main['width'] = $this->get('width');
            $main['height'] = $this->get('height');
            $main['volume'] = $this->get('volume');
        }
        $main['price'] =$this->get('pay_price');
        $main['channel_price'] =$this->get('channel_price');
        $main['custom_price'] =$this->get('custom_price');
        $custom = $this->get('custom');
        $main['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
        $main['note'] = $this->get('note');
        $main['pay_weight']= $this->get('pay_weight');
        $order_id = $this->model('order')->save($main,$id);
        if(!$order_id){
            $this->json('订单修改失败，请检查');
        }
        //添加物品信息
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        $unit = $this->get('unit');
        $weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json('运单物品信息不能为空');
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            $tmp_unit = $unit[$key];
            $tmp_weight = floatval($weight[$key]);
            $tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'unit'=>$tmp_unit,'weight'=>$tmp_weight,'currency'=>$tmp_currency,'order_id'=>$id);
            // $this->json(print_r($tmp));
            if($value && $value != 'add'){
                $this->model('order')->save_product($tmp,$value);
            }else{
                $this->model('order')->save_product($tmp);
            }
        }
        //存储收货地址
        $fullname = trim($this->get('fullname'));
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = trim($this->get('mobile'));
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        $pca_a = $this->get('pca_a');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = $this->get('idcard');
        /*if(!$idcard){
            $this->json(P_Lang('请填写收件人身份证号码'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        $tmp_address = array('province'=>$pca_p,'city'=>$pca_c,'county'=>$pca_a,'address'=>$address,'mobile'=>$mobile,'zipcode'=>$zipcode,'fullname'=>$fullname,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back,'order_id'=>$id);
        $sid = $this->get('s-id','int');
        $this->model('order')->save_address($tmp_address,$sid);
        //插入收件人
        $rs = $this->model('user')->get_address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$rs){
            $tmp_address['user_id'] = $user_id;
            unset($tmp_address['order_id']);
            $this->model('user')->address_save($tmp_address);
        }
        //没有身份证，再添加身份证，更新收件人信息及以前订单
        if($rs && !$rs['idcard_front'] && $idcard_front ){
            //更新收件人
            $this->model('user')->address_save(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$rs['id']);
            //更新订单收件人信息
            $order_address = $this->model('order')->get_address("fullname='".$fullname."' and mobile='".$mobile."'");
            foreach($order_address as $key => $value){
                if(!$value['idcard_front']){
                    $this->model('order')->save_address(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$value['id']);
                }
            }
        }
        $pay = $this->get('pay');
        if($pay == 'account'){
            //计算会员余额
            $balance = $this->model('wealth')->get_val($old['user_id'],2);
            $price = price_format_val($main['price'],$channel_arr[6],$this->site['currency_id']);
            $data = array('wid'=>2,'uid'=>$old['user_id'],'lasttime'=>$this->time);
            $data['val'] = round(($balance-$price),2);
            $this->model('wealth')->save_info($data);
        }
        if($pay == 'balance'){
            //计算会员余额
            $balance = $this->model('wealth')->get_val($old['user_id'],2);
            $price = price_format_val($main['price'],$channel_arr[6],$this->site['currency_id']);
            $data = array('wid'=>2,'uid'=>$old['user_id'],'lasttime'=>$this->time);
            $data['val'] = round(($balance-$price),2);
            if($data['val']>=0){
                $this->model('wealth')->save_info($data);
            }else{
                $main['status'] = 'unpaid';
                $ship = $this->model('channel')->get_track($main['status'],'status');
                $main['ext'] = $ship['title'];
                $this->model('order')->save($main,$id);
                if(($ship['mail'] && $group['mail']) || ($ship['sms'] && $group['sms'])){
                    $param = 'id='.$id."&status=".$main['status'];
                    $this->model('task')->add_once('order',$param);
                }
                $this->json('订单'.$old['sn'].'余额不足');
            }
        }
        $sn = $this->_create_sn();
        $title = P_Lang('订单扣款：{ydsn}',array('ydsn'=>$old['sn']));
        $array = array(
             'sn'             => $sn
             ,'type'          => 'order'
             ,'paymentMethod' => $pay
             ,'title'         => $title
             ,'content'       => $title
             ,'order_id'      => $id
             ,'channel_id'    => $channel_arr[0]
             ,'weight'        => $main['pay_weight']
             ,'status'        => '1'
             ,'dateline'      => $this->time
             ,'user_id'       => $old['user_id']
             ,'price'         => $main['price']
             ,'balance'       => $data['val']
             ,'currency_id'   => $channel_arr[6]
             ,'admin_id'      => $_SESSION["admin_id"]
        );
        $insert_id = $this->model('payment')->log_create($array);//payment_log
        if(!$insert_id){
            $this->json(P_Lang('支付记录创建失败'));
        }
        //修改运单扣款
        $main['pay_sn'] = $sn;
        $main['status']='paid';
        $ship = $this->model('channel')->get_track($main['status'],'status');
        //增加订单日志
        $this->model('order')->log_save(array('order_id'=>$id,'status'=>$main['status'],'note'=>$ship['title'],'addtime'=>$main['addtime']));
        $this->model('order')->save($main,$id);
        $this->json(true);
    }
    //编辑存储信息
    private function modify_save($id)
    {
        $old = $this->model('order')->get_one($id);
        if(!$old){
            $this->json(P_Lang('订单信息不存在'));
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('运单号不能为空'));
        }
        if($sn != $old['sn']){
            $rs_sn = $this->model('order')->get_one_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('运单号已存在'));
            }
        }
        $main = array();
		$channel = $this->get('channel');
        if(!$channel){
            $this->json(P_Lang('请选择发货渠道！'));
        }
        $channel_arr = explode('|',$channel);
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json(P_Lang('请填写会员编号'));
        }
        $main['user_id'] = $user_id;
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }
        $jingzhong = $this->get('jingzhong');
		if($jingzhong>0 && !preg_match('/^[+-]?\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('称重重量输入格式不对！'));
        }
		//防止扣款之后再修改重量
        if($old['jingzhong']<>$jingzhong && !in_array($old['status'],array('check','create','pickup'))){
            $this->json(P_Lang('运单已扣款，不能修改重量'));
        }
        $main['stock_id'] = $stock;
        //收件人
        $fullname = trim($this->get('fullname'));
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = trim($this->get('mobile'));
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        $pca_a = $this->get('pca_a');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = $this->get('idcard');
        /*if(!$idcard){
            $this->json(P_Lang('请填写收件人身份证号码'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
		//更改渠道
        if($channel_arr[0]!=$old['channel']){
            //报关条码
            if($old['customer_sn']){
                //恢复旧报关条码
                $result = $this->model('code')->get_one($old['customer_sn'],'title');
                $this->model('code')->save(array('status'=>0),$result['id']);
            }
            if($channel_arr[10]){
                $code = $this->model('code')->get_code('channel_id='.$channel_arr[0].' and status=0');
                if($code){
                    $main['customer_sn'] = $code['title'];
                    $this->model('code')->save(array('status'=>1),$code['id']);
                }else{
                    $this->json(P_Lang('请导入报关条码'));
                }
            }else{
                $main['customer_sn'] = '';
            }
            //国内快递单号
            if($old['express_sn']){
                //恢复
                $result = $this->model('number')->get_one($old['express'],'title');
                $this->model('number')->save(array('status'=>0),$result['id']);
            }
            if($channel_arr[11]){
                $number = $this->model('number')->get_number('channel_id='.$channel_arr[0].' and status=0');
                if($number){
                    $express = $this->model('express')->get_one($number['express_id']);
                    $main['express_sn'] = $number['title'];
                    $main['express'] = $express['title'];
                    $this->model('number')->save(array('status'=>1),$number['id']);
                }else{
                    $this->json(P_Lang('请导入国内快递单号'));
                }
            }else{
                $main['express_sn'] = '';
                $main['express'] = '';
            }
        }
        $main['sn'] = $sn;
        $main['channel'] = $channel_arr[0];
        $main['currency_id'] = $channel_arr[6];
        $main['weight_id'] = $channel_arr[7];
        $main['val'] = $this->get('val');
        $main['tax'] = $this->get('tax');
        $main['safe'] = $this->get('safe');
        $main['safe_price'] = $this->get('safe_price');
        $main['weight'] = $this->get('wt');
        $main['jingzhong'] = $jingzhong;
        if($channel_arr[1]<>'0.00'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn <= $channel_arr[1]){
                $val_weight = floor($jingzhong);
            }else{
				$val_weight = $jingzhong;
			}
        }else{
			$val_weight = $jingzhong;
		}
        if($channel_arr[2]=='ceil'){
            $pay_weight = ceil($val_weight);
        }
        /*if($channel_arr[2]=='round'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn >0 && $fn < 0.5){
                $jingzhong = floor($jingzhong)+0.5;
            }elseif($fn > 0.5){
                $jingzhong = floor($jingzhong)+1;
            }
        }*/
		if($channel_arr[2]=='round'){
            $fn=round($val_weight-floor($val_weight),2);
            if($fn>0.5){
                if(($fn-0.5) > $channel_arr[1]){
                    $pay_weight = floor($val_weight)+1;
                }else{
                    $pay_weight = floor($val_weight)+0.5;
                }
            }else{
                if($fn > $channel_arr[1]){
                    $pay_weight = floor($val_weight)+0.5;
                }else{
                    $pay_weight = floor($val_weight);
                }
            }
        }
		if($channel_arr[2]=='intval'){
			 $pay_weight = $jingzhong;
		}
        if($pay_weight > $channel_arr[3]){
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($pay_weight - 1);
        }else{
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($channel_arr[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $main['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
        //$main['email'] = $this->get('email');
        $main['note'] = $this->get('note');
        $pay_price = round(($channel_price + $main['safe_price'] + $main['tax'] + $custom_price),2);
        $main['price'] =$pay_price;
        $main['custom_price'] =$custom_price;
        $main['channel_price'] =$channel_price;
        $main['pay_weight'] =$pay_weight;
        $order_id = $this->model('order')->save($main,$id);
        if(!$order_id){
            $this->json('运单修改失败，请检查');
        }
        //添加物品信息
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json('运单物品信息不能为空');
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$id);
            if($value && $value != 'add'){
                $this->model('order')->save_product($tmp,$value);
            }else{
                $this->model('order')->save_product($tmp);
            }
        }
        //存储收货地址
        $tmp_address = array('province'=>$pca_p,'city'=>$pca_c,'county'=>$pca_a,'address'=>$address,'mobile'=>$mobile,'zipcode'=>$zipcode,'fullname'=>$fullname,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back,'order_id'=>$id);
        $sid = $this->get('s-id','int');
        $this->model('order')->save_address($tmp_address,$sid);
        //插入收件人
        $rs = $this->model('user')->get_address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$rs){
            $tmp_address['user_id'] = $user_id;
            unset($tmp_address['order_id']);
            $this->model('user')->address_save($tmp_address);
        }
		//没有身份证，再添加身份证，更新收件人信息及以前订单
		if($rs && !$rs['idcard_front'] && $idcard_front ){
			//更新收件人
			$this->model('user')->address_save(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$rs['id']);
			//更新订单收件人信息
			$order_address = $this->model('order')->get_address("fullname='".$fullname."' and mobile='".$mobile."'");
			foreach($order_address as $key => $value){
				if(!$value['idcard_front']){
					$this->model('order')->save_address(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$value['id']);
				}
			}
		}
        $this->json(true);
    }
    //添加存储信息
    private function add_save()
    {
        $main = array();
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }else{
            $consign = $this->model('stock')->get_one($stock);
        }
        $main['stock_id'] = $stock;
        $sn = $this->get('sn');
        if(!$sn){
            $sn = $this->model('order')->create_sn();
        }else{
            $rs_sn = $this->model('order')->get_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('运单号已存在'));
            }
        }
        $main['type'] = 4;
        $main['sn'] = $sn;
        $main['status'] = 'create';
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(P_Lang('请选择发货渠道！'));
        }
        $channel_arr = explode('|',$channel);
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json('请指定会员');
        }
        $user_info = $this->model('user')->get_one($user_id);
        $main['user_id'] = $user_id;
        $main['consignor'] = $consign['consignor'];
        $main['consignor_tel'] = $consign['tel'];
        $main['consignor_address'] = $consign['address'];
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong || $jingzhong == '0.00'){
            $this->json(P_Lang('称重重量不能为空！'));
        }
        if(!preg_match('/^[+-]?\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('称重重量输入格式不对！'));
        }
        //收件人
        $fullname = trim($this->get('fullname'));
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = trim($this->get('mobile'));
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        $pca_a = $this->get('pca_a');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = $this->get('idcard');
        /*if(!$idcard){
            $this->json(P_Lang('请填写收件人身份证号码'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        //导入清关编码
        if($channel_arr[10]){
            $code = $this->model('code')->get_code('channel_id='.$channel_arr[0].' and status=0');
            if($code){
                $main['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }else{
                $this->json(P_Lang('请导入报关条码！'));
            }
        }
        //导入国内快递单号
        if($channel_arr[11]){
            $number = $this->model('number')->get_number('channel_id='.$channel_arr[0].' and status=0');
            if($number){
                $express = $this->model('express')->get_one($number['express_id']);
                $main['express_sn'] = $number['title'];
                $main['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }else{
                $this->json(P_Lang('请导入国内快递单号！'));
            }
        }
        $main['channel'] = $channel_arr[0];
        $main['currency_id'] = $channel_arr[6];
        $main['weight_id'] = $channel_arr[7];
        $main['val'] = $this->get('val');
        $main['tax'] = $this->get('tax');
        $main['safe'] = $this->get('safe');
        $main['safe_price'] = $this->get('safe_price');
        $main['jingzhong'] = $jingzhong;
        $main['weight'] = $this->get('wt');
        if($channel_arr[1]<>'0.00'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn <= $channel_arr[1]){
                $val_weight = floor($jingzhong);
            }else{
				$val_weight = $jingzhong;
			}
        }else{
			$val_weight = $jingzhong;
		}
        if($channel_arr[2]=='ceil'){
            $pay_weight = ceil($val_weight);
        }
        /*if($channel_arr[2]=='round'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn >0 && $fn < 0.5){
                $jingzhong = floor($jingzhong)+0.5;
            }elseif($fn > 0.5){
                $jingzhong = floor($jingzhong)+1;
            }
        }*/
		if($channel_arr[2]=='round'){
            $fn=round($val_weight-floor($val_weight),2);
            if($fn>0.5){
                if(($fn-0.5) > $channel_arr[1]){
                    $pay_weight = floor($val_weight)+1;
                }else{
                    $pay_weight = floor($val_weight)+0.5;
                }
            }else{
                if($fn > $channel_arr[1]){
                    $pay_weight = floor($val_weight)+0.5;
                }else{
                    $pay_weight = floor($val_weight);
                }
            }
        }
		if($channel_arr[2]=='intval'){
			$pay_weight = $jingzhong;
		}
        if($pay_weight > $channel_arr[3]){
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($pay_weight - 1);
        }else{
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($channel_arr[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $main['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
        //$main['email'] = $this->get('email');
        $main['note'] = $this->get('note');
        $pay_price = round(($channel_price + $main['safe_price'] + $main['tax'] + $custom_price),2);
        $main['price'] =$pay_price;
        $main['channel_price'] =$channel_price;
        $main['custom_price'] =$custom_price;
        $main['pay_weight'] =$jingzhong;
        //$this->json(P_Lang($main['currency_id']));
        $main['addtime'] = $this->time;
        $order_id = $this->model('order')->save($main);
        if(!$order_id){
            $this->json(P_Lang('运单创建失败，请检查'));
        }
        //增加快递order_erpress,一单到底
        if($number && $express){
            $ex_array = array('order_id'=>$order_id,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$main['addtime']);
            $ex_array['title'] = $express['title'];
            $ex_array['homepage'] = $express['homepage'];
            $ex_array['company'] = $express['company'];
            $this->model('order')->express_save($ex_array);
        }
        //保存相应的产品信息
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json(P_Lang('运单物品信息不能为空'));
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            // = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$order_id);
            $this->model('order')->save_product($tmp);
        }
        //保存运单地址信息
        $tmp_address = array('province'=>$pca_p,'city'=>$pca_c,'county'=>$pca_a,'address'=>$address,'mobile'=>$mobile,'zipcode'=>$zipcode,'fullname'=>$fullname,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back,'order_id'=>$order_id);
        $this->model('order')->save_address($tmp_address);
        if($user_id){
            //插入收件人
            $rs = $this->model('user')->get_address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
            if(!$rs){
                $tmp_address['user_id'] = $user_id;
                unset($tmp_address['order_id']);
                $this->model('user')->address_save($tmp_address);
            }
			//没有身份证，再添加身份证，更新收件人信息及以前订单
			if($rs && !$rs['idcard_front'] && $idcard_front ){
				//更新收件人
				$this->model('user')->address_save(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$rs['id']);
				//更新订单收件人信息
				$order_address = $this->model('order')->get_address("fullname='".$fullname."' and mobile='".$mobile."'");
				foreach($order_address as $key => $value){
					if(!$value['idcard_front']){
						$this->model('order')->save_address(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$value['id']);
					}
				}
			}
        }
        $ship = $this->model('channel')->get_track($main['status'],'status');
        //增加订单日志
        $this->model('order')->log_save(array('order_id'=>$order_id,'status'=>$main['status'],'note'=>$ship['title'],'addtime'=>$this->time));
        if($ship['sms'] && $user_info['sms']){
            $param = 'id='.$order_id."&status=".$main['status'];
            $this->model('task')->add_once('order',$param);
        }
        $this->json(true);
    }
    /*public function payment_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        if(!$_SESSION['admin_rs']['if_system']){
            $this->json(P_Lang('您没有权限，仅限系统管理员操作'));
        }
        $this->model('order')->order_payment_delete($id);
        $this->json(true);
    }*/
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
    public function print_f()
    {
        if(!$this->popedom['print']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('请扫描运单打印'));
        }
        $rs = $this->model('order')->get_from_sn($sn);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        $rslist = $this ->model('order')->product_list($rs['id']);
        if(!$rslist){
            $this->json(P_Lang('运单没有产品信息'));
        }
        $userinfo = $this->model('user')->get_one($rs['user_id']);
        if(!$userinfo){
            $this->json(P_Lang('运单没有收件人信息'));
        }
        $address = $this->model('order')->address($rs['id']);
        if(!$address){
            $this->json(P_Lang('运单没有收件地址信息'));
        }
        $this->json(array('rs'=>$rs,'rslist'=>$rslist,'userinfo'=>$userinfo,'address'=>$address),true);
    }
    public function scan_f(){
        $this->view('order_print');
    }
    //装袋功能
    public function move_cate_f(){
        $ids = $this->get("ids");
        $cate_id = $this->get("cate_id");
        $type = $this->get('type');
        if(!$cate_id || !$ids || !$type){
            $this->json(P_Lang('参数传递不完整'));
        }
        $list = explode(",",$ids);
        /*//判断是否同一个仓库
        $order = $this->model('order')->get_order($ids);
        foreach($order as $key=>$value){
            $stock[$key] = $value['stock_id'];
        }
        if(count(array_unique($stock))!=1){
            $this->json(P_Lang('请选择同一仓库下运单绑定批次'));
        }*/
		//判断是否同一渠道
        $order = $this->model('order')->get_order($ids);
        foreach($order as $key=>$value){
            $channel[$key] = $value['channel'];
        }
        if(count(array_unique($channel))!=1){
            $this->json(P_Lang('请选择同一线路渠道下运单绑定批次'));
        }
        $delete_ok = true;
        foreach($list AS $key=>$value){
            $value = intval($value);
            if(!$value){
                continue;
            }
            /*if($type == 'add'){
                $this->model('list')->list_cate_add($cate_id,$value);
            }
            if($type == 'delete'){
                $act = $this->model('list')->list_cate_delete($cate_id,$value);
                if(!$act){
                    $delete_ok = false;
                }
            }*/
            if($type == 'move'){
                $this->model('order')->save(array("cate_id"=>$cate_id),$value);
            }
            if($type == 'cancel'){
                $this->model('order')->save(array("cate_id"=>0),$value);
            }
        }
        /*if(!$delete_ok){
            $this->json(P_Lang('主分类不允许删除'));
        }*/
        $this->json(P_Lang('更新成功'),true);
    }
    public function execute_f()
    {
		if(!$this->popedom['execute']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $ids = $this->get('ids');
        if(!$ids){
            $this->json(P_Lang('未指定ID'));
        }
        $status = $this->get('status');
        $list = explode(',',$ids);
        $time = $this->time;
        foreach($list AS $key=>$value){
            $value = intval($value);
            if(!$value){
                continue;
            }
			$this->model('order')->update_order_status($value,$status);
        }
        $this->json(P_Lang('操作成功'),true);
    }
    //打印运单
    public function dayin_f()
    {
        $id = $this->get('id','int');
        $mb = $this->get('mb');
        if(!$id){
            if(!$this->popedom['add']){
                error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
            }
            $rs = array('status'=>'create');
        }else{
            if(!$this->popedom['modify']){
                error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
            }
            $this->assign('id',$id);
            $rs = $this->model('order')->get_one($id);
            $product = $this ->model('order')->product_list($id);
            $this->assign('product',$product);
            $userinfo = $this->model('user')->get_one($rs['user_id']);
            $this->assign('userinfo',$userinfo);
            $address = $this->model('order')->address($rs['id']);
            $this->assign('address',$address);
        }
        $this->assign('rs',$rs);
        if($mb){
            $this->assign('mb',$mb);
        }
        $this->view("order_dayin");
    }
    public function export_f(){
        if(!$this->popedom["export"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $statuslist = $this->model('order')->status_list();
        $this->assign('statuslist',$statuslist);
        $stocks = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stocks);
        $channel_list = $this->model('channel')->get_channelList();
        $this->assign('channel_list',$channel_list);
        //$from = $this->model('order')->from();
        //$this->assign('from',$from);
        $batch = $this->model('batch')->get_list();
        $this->assign('batch',$batch);
        $this->view('order_export');
    }
    public function modify_f(){
        if(!$this->popedom["update"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view('order_modify');
    }
    public function import_f(){
        if(!$this->popedom["import"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->view('order_import');
    }
    public function modify_data_f(){
		$addtime = $this->get('addtime');
		$addtime = $addtime ? strtotime($addtime) : $this->time;
		$file = $this->get('dfile','int');
		if(!$file)
		{
			$this->json(P_Lang("未导入文件"));
		}
		$res = $this->model('res')->get_one($file);
		if(!$res)
		{
			$this->json(P_Lang("文件不存在"));
		}
		if(!is_file($this->dir_root.$res["filename"]))
		{
			$this->json(P_Lang("文件不存在"));
		}
		 //读取运单状态
		 $ship = $this->model('channel')->get_track('received','status');
		//通过excel
		include_once $this->dir_root.'extension/phpexcel/PHPExcel.php';
		$filetype = $res["ext"] == "xlsx" ? "Excel2007" : "Excel5";
		$objReader = PHPExcel_IOFactory::createReader($filetype);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($this->dir_root.$res["filename"]);
		$currentSheet = $objPHPExcel->getSheet(0);
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();
		$ColumnNum = PHPExcel_Cell::columnIndexFromString($allColumn);
		//取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
		$filed = array();
		for($i=0; $i<$ColumnNum;$i++){
			$cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
			$cellVal = $currentSheet->getCell($cellName)->getValue();//取得列内容
			$filed []= $cellVal;
		}
		//开始取出数据并存入数组
		$data = array();
		for($i=2;$i<=$allRow;$i++){
			$row = array();
			for($j=0; $j<$ColumnNum;$j++){
				$cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
				$cellVal = $currentSheet->getCell($cellName)->getValue();
				$row[$filed[$j]] = $cellVal;
			}
			$data = $row;
			$sn = trim($data['运单号']);
			if(!$sn){
				$this->json(P_Lang('未填写运单号'));
			}
			$rs = $this->model('order')->get_from_sn($sn);
			if(!$rs){
				$this->json('你好，运单号['.$sn.']不存在，请查证后再导入！');
			}
			$com = trim($data['快递名']);
			if(!$com){
				$this->json('运单号['.$sn.']未填写快递名');
			}
			$express = $this->model('express')->getExpress($com);
			if(!$express){
				$this->json(P_Lang('运单号['.$sn.']快递名与后台设置的快递名不一致！'));
			}
			$code = trim($data['快递单号']);
			if(!$code){
				$this->json(P_Lang('运单号['.$sn.']未填写快递单号'));
			}
			/*if($rs['express_sn']){
				$this->json('快递单号['.$code.']已存在，请修改删除后重新导入！');
			}*/
			$customer_sn = trim($data['报关条码']);
			if($customer_sn && ($rs['customer_sn'] == $customer_sn)){
				$this->json('报关条码['.$customer_sn.']已存在，请修改删除后重新导入！');
			}
		}
		for($i=2;$i<=$allRow;$i++){
			$row = array();
			for($j=0; $j<$ColumnNum;$j++){
				$cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
				$cellVal = $currentSheet->getCell($cellName)->getValue();
				$row[$filed[$j]] = $cellVal;
			}
			$data = $row;
			$sn = trim($data['运单号']);
			$rs = $this->model('order')->get_from_sn($sn);
			$com = trim($data['快递名']);
			$code = trim($data['快递单号']);
			$customer_sn = trim($data['报关条码']);
			if($rs['express_sn']){
                $this->model('order')->express_delete($rs['id']);
            }
			$arr = array('express'=>$com,'express_sn'=>$code,'status'=>'received');
			if($customer_sn){
				$arr['customer_sn'] = $customer_sn;
			}
			$oid = $this->model('order')->save($arr,$rs['id']);
			if(!$oid){
				$this->json(P_Lang('派送信息导入失败'));
			}
			$express = $this->model('express')->getExpress($com);
			$array = array('order_id'=>$rs['id'],'express_id'=>$express['id'],'code'=>$code,'addtime'=>$addtime);
			$array['title'] = $express['title'];
			$array['homepage'] = $express['homepage'];
			$array['company'] = $express['company'];
			$this->model('order')->express_save($array);
			$this->model('order')->log_save(array('order_id'=>$rs['id'],'order_express_id'=>$express['id'],'addtime'=>$addtime,'who'=>$com,'status'=>'received','note'=>$com.'已揽件，快递单号:'.$code));
			//$this->model('order')->log_save(array('order_id'=>$rs['id'],'status'=>'received','note'=>$ship['title'],'addtime'=>$this->time));
			$user = $this->model('user')->get_one($rs['user_id']);
			if($ship['sms'] && $user['sms']){
				$param = 'id='.$rs['id']."&status=received";
				$this->model('task')->add_once('order',$param);
			}
		}
		$this->json(true);
	}
    //批量创建运单
    public function import_data_f()
    {
        $file = $this->get('dfile','int');
        if(!$file)
        {
            $this->json(P_Lang("未导入批量运单文件"));
        }
        $res = $this->model('res')->get_one($file);
        if(!$res)
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        if(!is_file($this->dir_root.$res["filename"]))
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        $user_id = $this->get('user_id','int');
        /*if(!$user_id){
            $this->json(P_Lang('请指定会员ID！'));
        }*/
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }else{
            $consign = $this->model('stock')->get_one($stock);
        }
        if($user_id){
            //读取会员信息
            $user_info = $this->model('user')->get_one($user_id);
            $group_id = $user_info['group_id'];
        }else{
            $user_id = 0;
            $group_id = 3;//游客组
        }
        //通过excel
        include_once $this->dir_root.'extension/phpexcel/PHPExcel.php';
        $filetype = $res["ext"] == "xlsx" ? "Excel2007" : "Excel5";
        $objReader = PHPExcel_IOFactory::createReader($filetype);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->dir_root.$res["filename"]);
        $currentSheet = $objPHPExcel->getSheet(0);
        $allColumn = $currentSheet->getHighestColumn();
        $allRow = $currentSheet->getHighestRow();
        $ColumnNum = PHPExcel_Cell::columnIndexFromString($allColumn);
        //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $filed = array();
        for($i=0; $i<$ColumnNum;$i++){
            $cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $cellVal = $currentSheet->getCell($cellName)->getValue();//取得列内容
            $filed []= $cellVal;
        }
        //开始取出数据并存入数组
        $data = array();
        $tips = array();
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
            if(!$data['包裹类别']){
                $tips[] = "第".$i."行包裹类别不能为空";
            }
            if(!$data['发件人姓名']){
                $tips[] = "第".$i."行发件人姓名不能为空";
            }
            if(!$data['发件人电话']){
                $tips[] = "第".$i."行发件人电话不能为空";
            }
            if(!$data['收件人姓名']){
                $tips[] = "第".$i."行收件人姓名不能为空";
            }
            if(!$data['收件人手机']){
                $tips[] = "第".$i."行收件人手机不能为空";
            }
            if(!$data['省']){
                $tips[] = "第".$i."行省不能为空";
            }
            if(!$data['市']){
                $tips[] = "第".$i."行市不能为空";
            }
            if(!$data['具体地址']){
                $tips[] = "第".$i."行具体地址不能为空";
            }
            /*if(!$data['收件人身份证号']){
                $tips[] = "第".$i."行收件人身份证号不能为空";
            }*/
            if(!$data['内件1品牌']){
                $tips[] = "第".$i."行内件1品牌不能为空";
            }
            if(!$data['内件1品名']){
                $tips[] = "第".$i."行内件1品名不能为空";
            }
            if(!$data['内件1数量']){
                $tips[] = "第".$i."行内件1数量不能为空";
            }
            $sn = $data['客户单号'] ? $data['客户单号'] : $this->model('order')->create_sn();
            $rs = $this->model('order')->get_from_sn($sn);
            if($rs){
                $tips[] = "第".$i."行，单号[".$sn."]已存在，请查证后再导入";
            }
            $type = trim($data['包裹类别']);
            $channel = $this->model('channel')->get_one($type,'type');
            if(!$channel){
                $tips[] = "第".$i."行，请在后台渠道管理设置对应的包裹类别";
            }
            if(!$channel['status']){
                $tips[] = "第".$i."行，请开启包裹类别对应的渠道";
            }
            //导入清关编码
            if($channel['from_sn']){
                $code_count = $this->model('code')->get_count('status=0');
                if($code_count<$allRow){
                    $this->json('请导入报关条码！');
                }
            }
            //导入国内快递单号
            if($channel['tracking_number']){
                $number_count = $this->model('number')->get_count('status=0');
                if($number_count<$allRow){
                    $this->json('请导入国内快递单号！');
                }
            }
        }
		if($tips){
			$info = implode("<br>",$tips);
			$this->json($info);
		}
		$ship = $this->model('channel')->get_track('create','status');
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
            $consignor = $data['发件人姓名'];
            $consignor_tel = $data['发件人电话'];
            $consignor_address = $data['发件人地址'];
            if(!$data['发件人地址']){
                $consignor_address = $consign['address'];
            }
                $type = trim($data['包裹类别']);
                $tmp_channel = $this->model('channel')->get_one($type,'type');
                $channel = $this->model('channel')->get_onechannelprice('channel_id='.$tmp_channel['id'].' and group_id='.$group_id);
            $wt = $data['包裹总重量'] ? $data['包裹总重量'] : 0;
            if($wt){
                if($channel['remove']){
                    $fn=round($wt-floor($wt),2);
                    if($fn <= $channel['num']){
                        $val_weight = floor($wt);
                    }else{
                        $val_weight = $wt;
                    }
                }else{
                    $val_weight = $wt;
                }
                if($channel['rule']=='ceil'){
                    $pay_weight = ceil($val_weight);
                }
                if($channel['rule']=='round'){
                    $fn=round($val_weight-floor($val_weight),2);
                    if($fn >0 && $fn < 0.5){
                        $pay_weight = floor($val_weight)+0.5;
                    }elseif($fn > 0.5){
                        $pay_weight = floor($val_weight)+1;
                    }
                }
                if($channel['rule']=='intval'){
                    $pay_weight = $val_weight;
                }
                if($pay_weight > $channel['start_heavy']){
                    $channel_price = $channel['first_price'] + $channel['second_price'] * ($pay_weight - 1);
                }else{
                    $channel_price = $channel['first_price'] + $channel['second_price'] * ($channel['start_heavy'] - 1);
                }
                $channel_price = round($channel_price,2);
            }else{
                $channel_price = 0;
            }
            $sn = $this->model('order')->create_sn();
            $arr = array('user_id'=>$user_id,'channel'=>$tmp_channel['id'],'currency_id'=>$tmp_channel['currency_id'],'weight_id'=>$tmp_channel['weight_id'],'sn'=>$sn,'stock_id'=>$stock,'price'=>$channel_price,'pay_weight'=> $pay_weight,'consignor'=>$consignor,'consignor_tel'=>$consignor_tel,'consignor_address'=>$consignor_address,'jingzhong'=>$wt,'type'=>5,'status'=>'create','ext'=>$ship['title'],'val'=>$data['保险金额($)'],'addtime'=>$this->time);
            //导入清关编码
            if($tmp_channel['from_sn']){
                $code = $this->model('code')->get_code('channel_id='.$tmp_channel['id'].' and status=0');
                $arr['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }
            //导入国内快递单号
            if($tmp_channel['tracking_number']){
                $number = $this->model('number')->get_number('channel_id='.$tmp_channel['id'].' and status=0');
                $express = $this->model('express')->get_one($number['express_id']);
                $arr['express_sn'] = $number['title'];
                $arr['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }
            $oid = $this->model('order')->save($arr);
            if(!$oid){
                $this->json(P_Lang('运单创建失败'));
            }
            //增加快递order_erpress,一单到底
            if($number && $express){
                $ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
                $ex_array['title'] = $express['title'];
                $ex_array['homepage'] = $express['homepage'];
                $ex_array['company'] = $express['company'];
                $this->model('order')->express_save($ex_array);
            }
            //增加运单日志
            $this->model('order')->log_save(array('order_id'=>$oid,'note'=>$ship['title'],'addtime'=>$this->time));
            if($ship['sms'] && $this->user['sms']){
                $param = 'id='.$oid."&status=create";
                $this->model('task')->add_once('order',$param);
            }
            //添加收件人信息
            $fullname = trim($data['收件人姓名']);
            $mobile = trim($data['收件人手机']);
            $user_address = array('fullname'=>$fullname,'user_id'=>$user_id,'province'=>$data['省'],'city'=>$data['市'],'address'=>$data['具体地址'],'mobile'=>$mobile,'idcard'=>$data['收件人身份证号']);
            $address = $this->model('user')->get_address("fullname='".$fullname."' and mobile = '".$mobile."' and user_id=".$user_id);
            if(!$address['id']){
                $this->model('user')->address_save($user_address);
            }
            $order_address = array('order_id'=>$oid,'fullname'=>$fullname,'province'=>$data['省'],'city'=>$data['市'],'address'=>$data['具体地址'],'mobile'=>$mobile,'idcard'=>$data['收件人身份证号']);
            $this->model('order')->save_address($order_address);
            //运单物品添加
            $title = array($data['内件1品名'],$data['内件2品名'],$data['内件3品名'],$data['内件4品名'],$data['内件5品名'],$data['内件6品名']);
            $brand = array($data['内件1品牌'],$data['内件2品牌'],$data['内件3品牌'],$data['内件4品牌'],$data['内件5品牌'],$data['内件6品牌']);
            $size = array($data['内件1规格'],$data['内件2规格'],$data['内件3规格'],$data['内件4规格'],$data['内件5规格'],$data['内件6规格']);
            $qty = array($data['内件1数量'],$data['内件2数量'],$data['内件3数量'],$data['内件4数量'],$data['内件5数量'],$data['内件6数量']);
            $price = array($data['内件1商品单价($)'],$data['内件2商品单价($)'],$data['内件3商品单价($)'],$data['内件4商品单价($)'],$data['内件5商品单价($)'],$data['内件6商品单价($)']);
            foreach($title AS $key=>$value){
                $tmp_title = $title[$key];
                if(!$tmp_title || !trim($tmp_title)){
                    continue;
                }
                $tmp_brand = htmlspecialchars($brand[$key],ENT_QUOTES);//解决单引号插入
                $tmp_size = $size[$key];
                $tmp_qty = intval($qty[$key]);
                $tmp_price = floatval($price[$key]);
                $tmp_total_price = round($tmp_price*$tmp_qty,2);
                $tmp = array('brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'order_id'=>$oid);
                $this->model('order')->save_product($tmp);
            }
        }
        $this->json(true);
    }
    //数据导出操作
    public function export_data_f()
    {
        $error_url = $this->url("order","export");
        //$ext = isset($_POST["ext"]) ? $_POST["ext"] : array();
        $ext = $this->get('ext') ? $this->get('ext') : array("sn","express_sn","addtime","idcard","catename","consignor","consignor_tel","fullname","mobile","address","package_title","idcardStatus","status","jingzhong","pay_weight","batchNo");
        $condition = " o.remove=0";
        $status = $this->get("status");
        if($status){
            $condition .= " AND o.status='".$status."'";
        }
        $stock_id = $this->get("stock_id",'int');
        if($stock_id){
            $condition .= " AND o.stock_id=".$stock_id;
        }else{
            //管理员所属仓库运单
            $condition .= " AND o.stock_id in(".$_SESSION["admin_stock"].")";
        }
        $channel_id = $this->get("channel_id",'int');
        if($channel_id){
            $condition .= " AND o.channel='".$channel_id."'";
        }
        $batch = $this->get("batch","int");
        if($batch){
            $condition .= " AND cate_id='".$batch."'";
        }
        $sn =trim($this->get('sn'));
        if($sn){
            $condition .= " AND o.sn = '".$sn."'";
        }
        $order_sn = trim($this->get('order_sn'));
        if($order_sn){
            $sn = explode(",",$order_sn);
            foreach($sn as $key=>$value){
                if(!$value) continue;
                $order = $this->model('order')->get_one_from_sn($value);
                if(!$order){
                    continue;
                }
                $rs_id[] = $order['id'];
            }
            $rs_id = implode(',',$rs_id);
            $condition .= " AND o.id in(".$rs_id.")";
        }
        $user = trim($this->get('user'));
        if($user){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
        }
        $ucode = trim($this->get('ucode'));
        if($ucode){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
        }
        $consignee = trim($this->get('consignee'));
        if($consignee){
            $condition .= " AND o.id IN(SELECT order_id FROM ".$this->db->prefix."order_address WHERE fullname = '".$consignee."')";
        }
        $consignee_mobile = trim($this->get('consignee_mobile'));
        if($consignee_mobile){
            $condition .= " AND o.id IN(SELECT order_id FROM ".$this->db->prefix."order_address WHERE mobile = '".$consignee_mobile."')";
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
        }
        $rslist = $this->model('order')->get_import_list($condition);
        if(!$rslist)
        {
            error('没有查询到要导出的运单',$error_url,"error");
        }
        //重组数组
        $temp_arr = array();
        foreach($rslist as $k=>&$e){
            $sn=&$e['sn'];
            if(!isset($temp_arr[$sn])){
                $temp_arr[$sn]=$e;
                unset($temp_arr[$sn]['brand'],$temp_arr[$sn]['title'],$temp_arr[$sn]['qty'],$temp_arr[$sn]['catename']);
            }
            $temp_arr[$sn]['brand'][]= htmlspecialchars_decode($e['brand'],ENT_QUOTES);
            $temp_arr[$sn]['title'][]= htmlspecialchars_decode($e['title'],ENT_QUOTES);
            $temp_arr[$sn]['qty'][]= $e['qty'];
            $temp_arr[$sn]['catename'][]= $e['catename'];
        }
        $rslist=array_values($temp_arr);
        unset($temp_arr);
        $first_list = array("sn"=>"订单号","express_sn"=>"转运单号","addtime"=>"创建时间","idcard"=>"身份证号",
            "catename"=>"货物品类","consignor"=>"发件人姓名","consignor_tel"=>"发件人电话","fullname"=>"收件人姓名",
            "mobile"=>"收件人电话","address"=>"收件人地址","package_title"=>"内件汇总","idcardStatus"=>"身份证状态",
            "status"=>"包裹状态","jingzhong"=>"包裹重量","pay_weight"=>"仓库复重","batchNo"=>"提单号");
        include_once $this->dir_root."extension/phpexcel/PHPExcel.php";
        @set_time_limit(0);#[设置防止超时]
        $phpexcel = new PHPExcel();
        $row = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        $filename = date("Ymd-His");
        $idlist = $ext;
        $row_array = explode(",",$row);
        $width_array = array();
        $ifpic = false;
        $list = $tmplist = array();
        foreach($idlist AS $key=>$value)
        {
            $char = $row_array[$key];
            $phpexcel->getActiveSheet()->getColumnDimension($char)->setWidth("18");
            $list[$char] = $value;
            $val = $first_list[$value];
            $phpexcel->getActiveSheet()->getStyle($char."1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $phpexcel->getActiveSheet()->getStyle($char."1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $phpexcel->getActiveSheet()->setCellValueExplicit($char."1",$val,PHPExcel_Cell_DataType::TYPE_STRING);
        }
        //现在存储内容数据
        $i=0;
        foreach($rslist AS $key=>$value)
        {
            $m = $i+2;
            //if($ifpic) $this->set_height($m,"80");
            foreach($list AS $k=>$v)
            {
                $val = $value[$v];  //对应数据库字段
                if($v=='addtime'){
                    $val = date("Y-m-d H:i:s",$v);
                }
                if($v =='address'){
                    $val = $value['province']." ".$value['city']." ".$value['county']." ".$value['address'];
                }
                if($v =='package_title'){
                    $arr_packages = array();
                    foreach($value['title'] as $a=>$b){
                        $arr_packages[] = $value['brand'][$a].$b.'*'.$value['qty'][$a];
                    }
                    $val = implode(",",$arr_packages);
                }
                if($v=='idcardStatus' && $value['idcard_front']){
                    $val = "身份证已经上传";
                }
                if($v=='status'){
                    $statusList = $this->model('order')->status_list();
                    $val = $statusList[$value[$v]];
                }
                $char = $k."".$m;
                $phpexcel->getActiveSheet()->getStyle($char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $phpexcel->getActiveSheet()->getStyle($char)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $phpexcel->getActiveSheet()->setCellValueExplicit($char,$val,PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $i++;
        }
        $phpexcel->createSheet();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $XLS_W = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
        $XLS_W->save('php://output');
    }
    public function print2_f()
    {
        if(!$this->popedom['print']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        //$this->assign('id',$id);
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        $product = $this ->model('order')->product_list($id);
        $this->assign('product',$product);
        $address = $this->model('order')->address($rs['id']);
        $this->assign('address',$address);
        $this->assign('rs',$rs);
		//条码
        $this->lib('barcode')->font($this->dir_data.'font/Arial.ttf',12);
        $barcode = 'data/cache/'.$rs['sn'].'.png';
        if(!file_exists($this->dir_root.$barcode)){
            $this->lib('barcode')->create($rs['sn'],$this->dir_root.$barcode);
        }
        $this->assign('barcode',$barcode);
        $this->view("order_print2");
    }
    //扣款出错
    public function error_f(){
        $sn = $this->get('sn');
        if($sn){
            $rs = $this->model('order')->get_from_sn($sn);
            if(!$rs){
                $this->error('运单信息不存在');
            }
            $rs['weight_unit'] = $this->model('channel')->weight();
            $this->assign('rs',$rs);
        }
        $pay = $this->model('payment')->paymentMethod();
        unset($pay['scan']);
        $this->assign("pay",$pay);
        $this->view('order_error');
    }
    public function out_setting_f(){
        $sn = $this->get('sn');
        if(!$sn){
            $this->json('运单号不能为空');
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json('未指定ID');
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json('运单信息不存在');
        }
        $cid = $this->get('cid','int');
        if($cid){
            $this->model('order')->save(array('cate_id'=>$cid),$id);
        }
        //读取运单状态
        $ship = $this->model('channel')->get_track('shipped','status');
        $this->model('order')->update_order_status($id,'shipped');
        $this->model('order')->log_save(array('order_id'=>$id,'status'=>'shipped','note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }
    //重新扣款
    public function pay_error_f(){
        $id = $this->get('id','int');
        if(!$id){
            $this->json('未指定ID');
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json('运单信息不存在');
        }
        if(!$rs['user_id']){
            $this->json('游客运单，不支持验重操作');
        }
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong || $jingzhong == '0.00'){
            $this->json('包裹入库重量不能为空！');
        }
        /*if(!preg_match('/^[+-]?\d+(\.\d+)?$/',$jingzhong)){
            $this->json('包裹入库重量输入格式不对！');
        }*/
        $tax = $this->get('tax');
        //判断会员等级折扣信息
        $group = $this->model('user')->get_one($rs['user_id']);
		$channel = $this->model('channel')->get_one($rs['channel']);
        $channel_arr = $this->model('channel')->get_onechannelprice('channel_id='.$rs['channel'].' and group_id='.$group['group_id']);
        if($channel_arr['num']<>'0.00'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn <= $channel_arr['num']){
                $val_weight = floor($jingzhong);
            }else{
				$val_weight = $jingzhong;
			}
        }else{
			$val_weight = $jingzhong;
		}
        if($channel_arr['rule']=='ceil'){
            $pay_weight = ceil($val_weight);
        }
        /*if($channel_arr['rule']=='round'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn >0 && $fn < 0.5){
                $pay_weight = floor($jingzhong)+0.5;
            }elseif($fn > 0.5){
                $pay_weight = floor($jingzhong)+1;
            }else{
				$pay_weight = $jingzhong;
			}
        }*/
		if($channel_arr['rule']=='round'){
			$fn=round($val_weight-floor($val_weight),2);
			if($fn>0.5){
				if(($fn-0.5) > $channel_arr['num']){
					$pay_weight = floor($val_weight)+1;
				}else{
					$pay_weight = floor($val_weight)+0.5;
				}
			}else{
				if($fn > $channel_arr['num']){
					$pay_weight = floor($val_weight)+0.5;
				}else{
					$pay_weight = floor($val_weight);
				}
			}
		}
        if($channel_arr['rule']=='intval'){
            $pay_weight = $jingzhong;
        }
        if($pay_weight > $channel_arr['start_heavy']){
            $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($pay_weight - 1);
        }else{
            $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($channel_arr['start_heavy'] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = explode(',',$rs['custom']);
        if($custom || is_array($custom)){
            $custom_price = 0;
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $pay_price = round(($channel_price + $rs['safe_price'] + $tax + $custom_price),2);
        if($pay_price==$rs['price'])  $this->json('费用一致');
        $pay = $this->get('pay');
        if($pay_price > $rs['price']){
            $new_price = $pay_price - $rs['price'];
            $ing = "补扣操作";
            $type = 'order';
            if($pay=='balance'){
                //计算会员余额
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
                $price = price_format_val($new_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                if($data['val']>=0){
                    $this->model('wealth')->save_info($data);
                }else{
                    $this->json('余额不足，请选择其他支付方式补扣费用');
                }
            }
            if($pay == 'account'){
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
                $price = price_format_val($new_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                $this->model('wealth')->save_info($data);
            }
        }
        if($pay_price < $rs['price']){
            $new_price = $rs['price']- $pay_price;
            $ing = "退款操作";
            $type = 'recharge';
            if($pay== 'balance'){
                //计算会员余额
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
                $price = price_format_val($new_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance+$price),2);
                $this->model('wealth')->save_info($data);
            }
            if($pay == 'account'){
                $this->json('退款不支持挂账支付方式');
            }
        }
        $pay_sn = $this->_create_sn();
        $title = P_Lang('运单'.$ing.'：'.$rs['sn']);
        $array = array(
         'sn'            => $pay_sn
        ,'type'          => $type
        ,'paymentMethod' => $pay
        ,'title'         => $title
        ,'content'       => $title
        ,'order_id'      => $id
        ,'channel_id'    => $rs['channel']
        ,'weight'        => $pay_weight
        ,'status'        => '1'
        ,'dateline'      => $this->time
        ,'user_id'       => $rs['user_id']
        ,'price'         => $new_price
        ,'balance'       => $data['val']
        ,'currency_id'   => $channel['currency_id']
		,'admin_id'      => $_SESSION["admin_id"]
        );
        $insert_id = $this->model('payment')->log_create($array);//payment_log
        if(!$insert_id){
            $this->json(P_Lang('支付记录创建失败'));
        }
        $main['price'] =$pay_price;
        $main['jingzhong'] = $jingzhong;
        $main['pay_weight'] = $pay_weight;
        $main['status'] = 'shipped';
        $main['ext'] = '已验重，等待发货';
        $this->model('order')->save($main,$id);
        $this->json(true);
    }
    public function charged_f(){
        if(!$this->popedom['charged']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        /*$batch_list = $this->model('batch')->get_all();
        $this->assign("batch_list",$batch_list);*/
        $pay = $this->model('payment')->paymentMethod();
        unset($pay['scan']);
        $this->assign("pay",$pay);
        $this->view('order_charged');
    }
    //扫描扣款
    public function save_charged_f(){
        $sn = $this->get('sn');
        $jingzhong = $this->get('jingzhong');
        //$batch = $this->get('batch','int');
        //if(!$batch) $this->json('请选择批次');
        $pay = $this->get('pay');
        foreach($sn AS $key=>$value){
            if(!$value) continue;
            $jingzhong = floatval($jingzhong[$key]);
            $rs = $this->model('order')->get_from_sn($value);
            if(!$rs){
                $this->json('运单'.$value.'不存在');
            }
            if(!$rs['user_id']){
                $this->json('游客运单'.$value.'不支持扫描扣款');
            }
            if($rs['pay_sn']) continue;
            if(!$jingzhong){
                $this->json('运单'.$value.'没有填写称重重量');
            }
            //判断会员等级折扣信息
            $group = $this->model('user')->get_one($rs['user_id']);
			$channel = $this->model('channel')->get_one($rs['channel']);
			$channel_arr = $this->model('channel')->get_onechannelprice('channel_id='.$rs['channel'].' and group_id='.$group['group_id']);
            if($channel_arr['num']<>'0.00'){
                $fn=round($jingzhong-floor($jingzhong),2);
                if($fn <= $channel_arr['num']){
                    $val_weight = floor($jingzhong); //$pay_weight计费重量
                }
            }else{
				$val_weight = $jingzhong;
			}
            if($channel_arr['rule']=='ceil'){
                $pay_weight = ceil($val_weight);
            }
            /*if($channel_arr['rule']=='round'){
                $fn=round($val_weight-floor($val_weight),2);
                if($fn >0 && $fn < 0.5){
                    $pay_weight = floor($val_weight)+0.5;
                }elseif($fn > 0.5){
                    $pay_weight = floor($val_weight)+1;
                }else{
					$pay_weight = $val_weight;
				}
            }*/
			if($channel_arr['rule']=='round'){
                $fn=round($val_weight-floor($val_weight),2);
                if($fn>0.5){
                    if(($fn-0.5) > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+1;
                    }else{
                        $pay_weight = floor($val_weight)+0.5;
                    }
                }else{
                    if($fn > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+0.5;
                    }else{
                        $pay_weight = floor($val_weight);
                    }
                }
            }
			if($channel_arr['rule']=='intval'){
				$pay_weight = $jingzhong;
			}
            if($pay_weight > $channel_arr['start_heavy']){
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($pay_weight - 1);
            }else{
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($channel_arr['start_heavy'] - 1);
            }
            $channel_price = round($channel_price,2);
            $custom = explode(',',$rs['custom']);
            if($custom || is_array($custom)){
                $custom_price = 0;
                foreach($custom as $k => $v){
                    $custom_tmp= $this->model('custom')->get_one($v);
                    $custom_price += $custom_tmp['price'];
                }
            }
            $pay_price = round(($channel_price + $rs['safe_price'] + $rs['tax'] + $custom_price),2);
            if($pay== 'balance'){
                //计算会员余额
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
				$price = price_format_val($pay_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                if($data['val']>=0){
                    $this->model('wealth')->save_info($data);
                }else{
                    $main['status'] = 'unpaid';
                    $ship = $this->model('channel')->get_track($main['status'],'status');
                    $main['ext'] = $ship['title'];
                    $main['currency_id'] = $channel['currency_id'];
                    $this->model('order')->save($main,$rs['id']);
                    if(($ship['mail'] && $group['mail']) || ($ship['sms'] && $group['sms'])){
                        $param = 'id='.$rs['id']."&status=".$main['status'];
                        $this->model('task')->add_once('order',$param);
                    }
                    $this->json('运单'.$value.'余额不足');
                }
            }
            if($pay== 'account'){
                //计算会员余额
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
				$price = price_format_val($pay_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                $this->model('wealth')->save_info($data);
            }
            $pay_sn = $this->_create_sn();
            $title = P_Lang('运单扣款：'.$value);
            $array = array(
                'sn'             => $pay_sn
                ,'type'          => 'order'
                ,'paymentMethod' => $pay
                ,'title'         => $title
                ,'content'       => $title
                ,'order_id'      => $rs['id']
                ,'channel_id'    => $rs['channel']
                ,'weight'        => $pay_weight
                ,'status'        => '1'
                ,'dateline'      => $this->time
                ,'user_id'       => $rs['user_id']
                ,'price'         => $pay_price
                ,'balance'       => $data['val']
                ,'currency_id'   => $channel['currency_id']
                ,'admin_id'      => $_SESSION["admin_id"]
            );
            $insert_id = $this->model('payment')->log_create($array);//payment_log
            if(!$insert_id){
                $this->json(P_Lang('支付记录创建失败'));
            }
            //修改运单扣款
            $main['pay_sn'] = $pay_sn;
            $main['status'] = 'paid';
            $main['jingzhong'] = $jingzhong;
            $main['pay_weight'] = $pay_weight;
            $main['price'] = $pay_price;
            $main['currency_id'] = $channel['currency_id'];
            //$main['cate_id'] = $batch;
            $this->model('order')->save($main,$rs['id']);
            $ship = $this->model('channel')->get_track($main['status'],'status');
            //增加订单日志
            $this->model('order')->log_save(array('order_id'=>$rs['id'],'status'=>$main['status'],'note'=>$ship['title'],'addtime'=>$this->time));
        }
        $this->json(true);
    }
    public function front_print_f()
    {
        if(!$this->popedom['print']){
            $this->json('您没有权限执行此操作');
        }
        $ids = $this->get("ids");
        if(!$ids){
            $this->json(P_Lang('请选择要批量打印的运单'));
        }
        //$list = explode(",",$ids);
        $rs = $this->model('order')->get_order($ids);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        foreach($rs as $key=>$value){
            $value['product'] = $this ->model('order')->product_list($value['id']);
            // $value['userinfo'] = $this->model('user')->get_one($value['user_id']);
            $value['address'] = $this->model('order')->address($value['id']);
            $rs[$key] = $value;
        }
        $this->json($rs,true);
    }
    public function payment_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json('未指定ID');
        }
        $this->assign('id',$id);
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        $this->assign('rs',$rs);
        $this->view("order_payment");
    }
    //显示回收站运单列表
    public function recycle_f()
    {
        if(!$this->popedom['recycle']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $offset = ($pageid-1)*$psize;
        $pageurl = $this->url('order','recycle');
        $condition = "1=1";
        $stock_id = $this->get("stock_id",'int');
        if($stock_id){
            $condition .= " AND o.stock_id=".$stock_id;
            $pageurl .= "&stock_id=".rawurlencode($stock_id);
            $this->assign('stock_id',$stock_id);
        }
        $stocks = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stocks);
        $channel_list = $this->model('channel')->get_channelList();
        $this->assign('channel_list',$channel_list);
        $channel_id = $this->get("channel_id",'int');
        if($channel_id){
            $condition .= " AND o.channel='".$channel_id."'";
            $pageurl .= "&channel_id=".rawurlencode($channel_id);
            $this->assign('channel_id',$channel_id);
        }
		$batch = trim($this->get("batch"));
        if($batch){
            $batch_arr = $this->model('batch')->get_one($batch,'title');
            $condition .= " AND cate_id='".$batch_arr['id']."'";
            $pageurl .= "&batch=".rawurlencode($batch);
            $this->assign('batch',$batch);
        }
        /*$type = $this->get("type","int");
        if($type){
            $condition .= " AND type='".$type."'";
            $pageurl .= "&type=".rawurlencode($type);
            $this->assign('type',$type);
        }*/
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND o.sn like '%".$sn."%'";
            $this->assign('sn',$sn);
        }
        $user = $this->get('user');
        if($user){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
            $pageurl .= "&user=".rawurlencode($user);
            $this->assign('user',$user);
        }
        $consignor = $this->get('consignor');
        if($consignor){
            $condition .= " AND o.consignor like '%".$consignor."%'";
            $pageurl .= "&consignor=".rawurlencode($consignor);
            $this->assign('consignor',$consignor);
        }
        $ucode = $this->get('ucode');
        if($ucode){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
            $pageurl .= "&ucode=".rawurlencode($ucode);
            $this->assign('ucode',$ucode);
        }
        $consignee = $this->get('consignee');
        if($consignee){
            $condition .= " AND o.id in(SELECT order_id FROM ".$this->db->prefix."order_address WHERE fullname like '%".$consignee."%')";
            $pageurl .= "&consignee=".rawurlencode($consignee);
            $this->assign('consignee',$consignee);
        }
        $consignee_mobile = $this->get('consignee_mobile');
        if($consignee_mobile){
            $condition .= " AND o.id IN(SELECT order_id FROM ".$this->db->prefix."order_address WHERE mobile like '%".$consignee_mobile."%')";
            $pageurl .= "&consignee_mobile=".rawurlencode($consignee_mobile);
            $this->assign('consignee_mobile',$consignee_mobile);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
		//判断管理员管理仓库权限
		if($_SESSION["admin_rs"]["if_system"]){
			$wh =" and remove=1";
		}else{
			$wh = " and remove=1 and o.stock_id in(".$_SESSION["admin_stock"].")";
		}
        $total = $this->model('order')->get_count($condition.$wh);
        if($total>0){
            $rslist = $this->model('order')->get_list($condition.$wh,$offset,$psize);
            if(!$rslist) $rslist = array();
            foreach($rslist AS $key=>$value){
                $channel[] = $this->model('channel')->get_one($value['channel']);
                $address[] = $this->model('order')->address($value['id']);
                $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
                //$value['batch'] = $this->model('batch')->get_one($value['cate_id']);
                $value['weight_units'] = $this->model('channel')->weight();
                $value['currency'] = $this->model('currency')->get_one($value['currency_id']);
                $value['track'] = $this->model('order')->log_list_one($value['id']);
                $value['pros'] = $this->model('order')->product_list($value['id']);
                $rslist[$key] = $value;
            }
            $this->assign('channel',$channel);
            $this->assign('address',$address);
            $this->assign('rslist',$rslist);
            $this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
        }
        //$from = $this->model('order')->from();
        //$this->assign('from',$from);
        //$this->assign('pageid',$pageid);
        if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view("order_recycle");
    }
    //批量删除回收站包裹操作
    public function deletes_f()
    {
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $id = $this->get('id');
        if(!$id) $this->json('未指定ID');
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs=$this->model('order')->get_one($value);
            //恢复报关条码和国内快递单号
            $code = $this->model('code')->get_code("title='".$rs['customer_sn']."'");
            if($code){
                $this->model('code')->save(array('status'=>'0'),$code['id']);
            }
            $number = $this->model('number')->get_number("title='".$rs['express_sn']."'");
            if($number){
                $this->model('number')->save(array('status'=>'0'),$number['id']);
            }
            //删除转运包裹
            if($rs['package_id']){
                $package = $this->model('package')->get_package('id in ('.$rs['package_id'].')');
                foreach($package as $k=>$v){
                    $this->model('package')->delete($v['id']);
                }
            }
			$this->model('order')->delete($value);
        }
        $this->json(P_Lang('删除成功'),true);
    }
    //回收站单个删除订单操作
    public function delete_f()
    {
        $id = $this->get('id');
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        //删除订单
        if(!$id) $this->json('未指定运单ID号');
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json('运单不存在');
        }
        
        //恢复报关条码和国内快递单号
        $code = $this->model('code')->get_code("title='".$rs['customer_sn']."'");
        if($code){
            $this->model('code')->save(array('status'=>'0'),$code['id']);
        }
        $number = $this->model('number')->get_number("title='".$rs['express_sn']."'");
        if($number){
            $this->model('number')->save(array('status'=>'0'),$number['id']);
        }
        //删除转运包裹
        if($rs['package_id']){
            $package = $this->model('package')->get_package('id in ('.$rs['package_id'].')');
            foreach($package as $key=>$value){
                $this->model('package')->delete($value['id']);
            }
        }
		$this->model('order')->delete($id);
        $this->json('删除成功',true);
    }
    public function renew_f()
    {
        $id = $this->get('id','int');
        $this->model('order')->remove($id,'0');
        $this->json(P_Lang('运单恢复成功'),true);
    }
   public function pays_f()
    {
        if(!$this->popedom['charged']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id');
        if(!$id) error(P_Lang('未指定ID'),'','error');
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs = $this->model('order')->get_one($value);
            //判断是否游客
            if(!$rs['user_id']) error(P_Lang('游客运单不允许批量扣款'),'','error');
            //判断已扣款
            if($rs['pay_sn']){
                error(P_Lang('运单「'.$rs['sn'].'」已扣过款，请勿重复扣款'),'','error');
            }else{
                $list[] = $rs;
            }
        }
        $pay = $this->model('payment')->paymentMethod();
        unset($pay['scan']);
        $this->assign("pay",$pay);
        $this->assign("list",$list);
        $this->view("order_pays");
    }
    //批量扣款
    public function pays_data_f(){
        $id = $this->get('id');
        $jingzhong = $this->get('jingzhong');
        $pay = $this->get('pay');
        foreach($id AS $key=>$value){
            $jingzhong = floatval(trim($jingzhong[$key]));
            $rs = $this->model('order')->get_one($value);
            //判断会员等级折扣信息
            $group = $this->model('user')->get_one($rs['user_id']);
            if(!$jingzhong)  continue;
			$channel = $this->model('channel')->get_one($rs['channel']);
			$channel_arr = $this->model('channel')->get_onechannelprice('channel_id='.$rs['channel'].' and group_id='.$group['group_id']);
			if($channel_arr['num']<>'0.00'){
				$fn=round($jingzhong-floor($jingzhong),2);
				if($fn <= $channel_arr['num']){
                    $val_weight = floor($jingzhong); //$pay_weight计费重量
				}else{
                    $val_weight = $jingzhong;
                }
			}else{
				$val_weight = $jingzhong;
			}
			if($channel_arr['rule']=='ceil'){
				$pay_weight = ceil($val_weight);
			}
			/*if($channel_arr['rule']=='round'){
				$fn=round($pay_weight - floor($pay_weight),2);
				if($fn >0 && $fn < 0.5){
					$pay_weight = floor($pay_weight)+0.5;
				}elseif($fn > 0.5){
					$pay_weight = floor($pay_weight)+1;
				}
			}*/
			if($channel_arr['rule']=='round'){
                $fn=round($val_weight-floor($val_weight),2);
                if($fn>0.5){
                    if(($fn-0.5) > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+1;
                    }else{
                        $pay_weight = floor($val_weight)+0.5;
                    }
                }else{
                    if($fn > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+0.5;
                    }else{
                        $pay_weight = floor($val_weight);
                    }
                }
            }
			if($channel_arr['rule']=='intval'){
				$pay_weight = $jingzhong;
			}
            if($pay_weight > $channel_arr['start_heavy']){
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($pay_weight - 1);
            }else{
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($channel_arr['start_heavy'] - 1);
            }
            $channel_price = round($channel_price,2);
            $pay_price = round(($channel_price + $rs['safe_price'] + $rs['tax'] + $rs['custom_price']),2);
            $status = 'paid';
            if($pay== 'balance'){
                //计算会员余额
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
				$price = price_format_val($pay_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                if($data['val']>=0){
                    $this->model('wealth')->save_info($data);
                }else{
                    $status = 'unpaid';
                    //$ship = $this->model('channel')->get_track($main['status'],'status');
					$main['status'] = $status;
					$main['currency_id'] = $channel['currency_id'];
					$this->model('order')->save($main,$value);
                    /*if(($ship['mail'] && $group['mail']) || ($ship['sms'] && $group['sms'])){
                        $param = 'id='.$value."&status=".$main['status'];
                        $this->model('task')->add_once('order',$param);
                    }
					$this->json('运单'.$rs['sn'].'余额不足');*/
                }
            }
            if($pay== 'account'){
                $balance = $this->model('wealth')->get_val($rs['user_id'],2);
				$price = price_format_val($pay_price,$channel['currency_id'],$this->site['currency_id']);
                $data = array('wid'=>2,'uid'=>$rs['user_id'],'lasttime'=>$this->time);
                $data['val'] = round(($balance-$price),2);
                $this->model('wealth')->save_info($data);
            }
            if($status == 'paid'){
                $sn = $this->_create_sn();
                $title = P_Lang('运单扣款：'.$rs['sn']);
                $array = array(
                    'sn'             => $sn
                    ,'type'          => 'order'
                    ,'paymentMethod' => $pay
                    ,'title'         => $title
                    ,'content'       => $title
                    ,'order_id'      => $value
                    ,'status'        => '1'
                    ,'dateline'      => $this->time
                    ,'user_id'       => $rs['user_id']
                    ,'price'         => $pay_price
                    ,'balance'       => $data['val']
                    ,'currency_id'   => $channel['currency_id']
                    ,'admin_id'      => $_SESSION["admin_id"]
                );
                $insert_id = $this->model('payment')->log_create($array);//payment_log
                if(!$insert_id){
                    $this->json(P_Lang('支付记录创建失败'));
                }
                //修改运单扣款
                $main['pay_sn'] = $sn;
                $main['status'] = $status;
                $main['jingzhong'] = $jingzhong;
                $main['pay_weight'] = $pay_weight;
                $main['currency_id'] = $channel['currency_id'];
                $main['price'] = $pay_price;
                $this->model('order')->save($main,$value);
            }
            //增加订单日志
            $ship = $this->model('channel')->get_track($status,'status');
			$this->model('order')->log_save(array('order_id'=>$value,'status'=>$status,'note'=>$ship['title'],'addtime'=>$this->time));
        }
        $this->json(true);
    }
	public function weight_f(){
        if(!$this->popedom['charged']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view('order_weight');
    }
	public function weight_data_f()
    {
        $file = $this->get('dfile','int');
        if(!$file)
        {
            $this->json(P_Lang("未导入批量运单文件"));
        }
        $res = $this->model('res')->get_one($file);
        if(!$res)
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        if(!is_file($this->dir_root.$res["filename"]))
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        //通过excel
        include_once $this->dir_root.'extension/phpexcel/PHPExcel.php';
        $filetype = $res["ext"] == "xlsx" ? "Excel2007" : "Excel5";
        $objReader = PHPExcel_IOFactory::createReader($filetype);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->dir_root.$res["filename"]);
        $currentSheet = $objPHPExcel->getSheet(0);
        $allColumn = $currentSheet->getHighestColumn();
        $allRow = $currentSheet->getHighestRow();
        $ColumnNum = PHPExcel_Cell::columnIndexFromString($allColumn);
        //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $filed = array();
        for($i=0; $i<$ColumnNum;$i++){
            $cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $cellVal = $currentSheet->getCell($cellName)->getValue();//取得列内容
            $filed []= $cellVal;
        }
        //开始取出数据并存入数组
        $data = array();
		for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            // $data []= $row;
            $data = $row;
            if(!trim($data['称重单号'])){
                //$this->json('称重单号不能为空！');
				$tips[] = '第'.$i.'行，称重单号不能为空';
            }
            $rs = $this->model('order')->get_from_sn(trim($data['称重单号']));
            if(!$rs){
                //$this->json('你好，单号['.$data['称重单号'].']不存在，请查证后再导入！');
				$tips[] = '第'.$i.'行，单号不存在，请查证后再导入';
            }
			$jingzhong =trim($data['重量']);
			if(!$jingzhong){
                //$this->json('你好，单号['.$data['称重单号'].']重量不能为空！');
				$tips[] = '第'.$i.'行，重量不能为空';
            }
        }
		if($tips){
            $info = implode("<br>",$tips);
            $this->json($info);
        }
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            // $data []= $row;
            $data = $row;
            $jingzhong =trim($data['重量']);
            $rs = $this->model('order')->get_from_sn(trim($data['称重单号']));
            if($rs['user_id']){
                $group = $this->model('user')->get_one($rs['user_id']);
                $groupId = $group['group_id'];
            }else{
                $groupId = 3;
            }
			$channel_arr = $this->model('channel')->get_onechannelprice('channel_id='.$rs['channel'].' and group_id='.$groupId);
            if($channel_arr['remove']){
                $fn=round($jingzhong-floor($jingzhong),2);
                if($fn <= $channel_arr['num']){
                    $val_weight = floor($jingzhong); //$pay_weight计费重量
                }else{
                    $val_weight = $jingzhong;
                }
            }else{
                $val_weight = $jingzhong;
            }
            if($channel_arr['rule']=='ceil'){
                $pay_weight = ceil($val_weight);
            }
            /*if($channel_arr['rule']=='round'){
                $fn=round($pay_weight - floor($pay_weight),2);
                if($fn >0 && $fn < 0.5){
                    $pay_weight = floor($pay_weight)+0.5;
                }elseif($fn > 0.5){
                    $pay_weight = floor($pay_weight)+1;
                }
            }*/
			if($channel_arr['rule']=='round'){
                $fn=round($val_weight-floor($val_weight),2);
                if($fn>0.5){
                    if(($fn-0.5) > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+1;
                    }else{
                        $pay_weight = floor($val_weight)+0.5;
                    }
                }else{
                    if($fn > $channel_arr['num']){
                        $pay_weight = floor($val_weight)+0.5;
                    }else{
                        $pay_weight = floor($val_weight);
                    }
                }
            }
			if($channel_arr['rule']=='intval'){
				$pay_weight = $jingzhong;
			}
            if($pay_weight > $channel_arr['start_heavy']){
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($pay_weight - 1);
            }else{
                $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($channel_arr['start_heavy'] - 1);
            }
            $channel_price = round($channel_price,2);
			//其他费用
			$price = round(($channel_price + $rs['custom_price'] + $rs['tax'] + $rs['safe_price']),2);
            $arr = array('jingzhong'=>$jingzhong,'pay_weight'=>$pay_weight,'price'=>$price,'channel_price'=>$channel_price);
            $this->model('order')->save($arr,$rs['id']);
        }
        $this->json(true);
    }
    function copy_f()
    {
        if(!$this->popedom['modify']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        $this->assign('id',$id);
        $pageurl = $this->get("pageurl");
        $this->assign('pageurl',$pageurl);
        $rs = $this->model('order')->get_one($id);
        $user = $this->model('user')->get_one($rs['user_id']);
        $rs['ucode'] = $user['ucode'];
        $channel=$this->model('channel')->channel_list($user['group_id']);
        if($channel){
            foreach($channel as $val){
                $currency_id[] = $val['currency_id'];
            }
        }
        $this->assign('channel',$channel);
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        $address = $this->model('order')->address($id);
        $this->assign('shipping',$address);
        if($rs['custom']){
            $custom_array = explode(',',$rs['custom']);
            $this->assign('custom_array',$custom_array);
        }
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city'],'a'=>$address['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            //编辑的时候有图片显示
            //$idlist[] = strtolower($value["identifier"]);
            if($address[$value["identifier"]]){
                $value["content"] = $address[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->assign('rs',$rs);
        //产品大类
        $catelist = $this->model('cate')->get_all(1,1,70);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        $this->view("order_copy");
    }
	public function out_single_f(){
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定ID'));
        $rs = $this->model('order')->get_one($id);
        if(!$rs) $this->json(P_Lang('订单信息不存在'));
        //批次判断
        // if($rs['cate_id']!=$cid) $this->error('包裹不属于该批次运单，不能进行出库操作');
        //判断是否扣款或已出库
        if($rs['status']!='paid') $this->json(P_Lang('包裹未扣款或已出库，请检查运单'));
        //读取运单状态
        $ship = $this->model('channel')->get_track('shipped','status');
        //$this->model('order')->save(array('ext'=>$ship['title'],'status'=>'shipped'),$id);
        $this->model('order')->update_order_status($id,'shipped');
        $this->model('order')->log_save(array('order_id'=>$id,'status'=>'shipped','note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }
    public function pickup_f(){
        if(!$this->popedom['pickup']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $this->view("order_pickup");
    }
    public function pickup_save_f(){
        $sn = $this->get('sn');
        $rs = $this->model('order')->get_from_sn($sn);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        if($rs['status']!='create'){
            $this->json(P_Lang('运单已揽收，请不要重复操作'));
        }
        $this->model('order')->update_order_status($rs['id'],'pickup');
        $this->json(true);
    }
    function copy_save_f()
    {
        $main = array();
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }/*else{
            $consign = $this->model('stock')->get_one($stock);
        }*/
        $sn = $this->get('sn');
        if(!$sn){
            $sn = $this->model('order')->create_sn();
        }else{
            $rs_sn = $this->model('order')->get_one_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('运单号已存在'));
            }
        }
        $main['sn'] = $sn;
        $main['stock_id'] = $stock;
        //$main['position'] = $this->get('position');
        $main['type'] = 4;
        $main['status'] = 'create';
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(P_Lang('请选择发货渠道！'));
        }
        $channel_arr = explode('|',$channel);
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json('请填写正确的会员编号');
        }
        $user_info = $this->model('user')->get_one($user_id);
        $main['user_id'] = $user_id;
        /*$main['consignor'] = $consign['consignor'];
        $main['consignor_tel'] = $consign['tel'];
        $main['consignor_address'] = $consign['address'];*/
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong || $jingzhong == '0.00'){
            $this->json(P_Lang('称重重量不能为空！'));
        }
        if(!preg_match('/^[+-]?\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('称重重量输入格式不对！'));
        }
        //收件人
        $fullname = $this->get('fullname');
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = $this->get('mobile');
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        $pca_a = $this->get('pca_a');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = $this->get('idcard');
        /*if(!$idcard){
            $this->json(P_Lang('请填写收件人身份证号码'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        //导入清关编码
        if($channel_arr[10]){
            $code = $this->model('code')->get_code('channel_id='.$channel_arr[0].' and status=0');
            if($code){
                $main['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }else{
                $this->json(P_Lang('请导入报关条码！'));
            }
        }
        //导入国内快递单号
        if($channel_arr[11]){
            $number = $this->model('number')->get_number('channel_id='.$channel_arr[0].' and status=0');
            if($number){
                $express = $this->model('express')->get_one($number['express_id']);
                $main['express_sn'] = $number['title'];
                $main['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }else{
                $this->json(P_Lang('请导入国内快递单号！'));
            }
        }
        $main['channel'] = $channel_arr[0];
        $main['currency_id'] = $channel_arr[6];
        $main['weight_id'] = $channel_arr[7];
        $main['val'] = $this->get('val');
        $main['tax'] = $this->get('tax');
        $main['safe'] = $this->get('safe');
        $main['safe_price'] = $this->get('safe_price');
        $main['jingzhong'] = $jingzhong;
        $main['weight'] = $this->get('wt');
        if($channel_arr[1]<>'0.00'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn <= $channel_arr[1]){
                $val_weight = floor($jingzhong);
            }else{
				$val_weight = $jingzhong;
			}
        }else{
			$val_weight = $jingzhong;
		}
        if($channel_arr[2]=='ceil'){
            $pay_weight = ceil($val_weight);
        }
        /*if($channel_arr[2]=='round'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn >0 && $fn < 0.5){
                $jingzhong = floor($jingzhong)+0.5;
            }elseif($fn > 0.5){
                $jingzhong = floor($jingzhong)+1;
            }
        }*/
		if($channel_arr[2]=='round'){
			$fn=round($val_weight-floor($val_weight),2);
			if($fn>0.5){
				if(($fn-0.5) > $channel_arr['num']){
					$pay_weight = floor($val_weight)+1;
				}else{
					$pay_weight = floor($val_weight)+0.5;
				}
			}else{
				if($fn > $channel_arr['num']){
					$pay_weight = floor($val_weight)+0.5;
				}else{
					$pay_weight = floor($val_weight);
				}
			}
		}
		if($channel_arr[2]=='intval'){
			$pay_weight = $jingzhong;
		}
        if($pay_weight > $channel_arr[3]){
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($pay_weight - 1);
        }else{
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($channel_arr[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $main['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
        //$main['email'] = $this->get('email');
        $main['note'] = $this->get('note');
        $pay_price = round(($channel_price + $main['safe_price'] + $main['tax'] + $custom_price),2);
        $main['price'] =$pay_price;
        $main['channel_price'] =$channel_price;
        $main['custom_price'] =$custom_price;
        $main['pay_weight'] =$pay_weight;
        //$this->json(P_Lang($main['currency_id']));
        $main['addtime'] = $this->time;
        $order_id = $this->model('order')->save($main);
        if(!$order_id){
            $this->json(P_Lang('运单创建失败，请检查'));
        }
        //增加快递order_express,一单到底
        if($number && $express){
            $ex_array = array('order_id'=>$order_id,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$main['addtime']);
            $ex_array['title'] = $express['title'];
            $ex_array['homepage'] = $express['homepage'];
            $ex_array['company'] = $express['company'];
            $this->model('order')->express_save($ex_array);
        }
        //保存相应的产品信息
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json(P_Lang('运单物品信息不能为空'));
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            // = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$order_id);
            $this->model('order')->save_product($tmp);
        }
        //保存运单地址信息
        $tmp_address = array('province'=>$pca_p,'city'=>$pca_c,'county'=>$pca_a,'address'=>$address,'mobile'=>$mobile,'zipcode'=>$zipcode,'fullname'=>$fullname,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back,'order_id'=>$order_id);
        $this->model('order')->save_address($tmp_address);
        if($user_id){
            //插入收件人
            $rs = $this->model('user')->address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
            if(!$rs){
                $tmp_address['user_id'] = $user_id;
                unset($tmp_address['order_id']);
                $this->model('user')->address_save($tmp_address);
            }
        }
        $ship = $this->model('channel')->get_track($main['status'],'status');
        //增加订单日志
        $this->model('order')->log_save(array('order_id'=>$order_id,'status'=>$main['status'],'note'=>$ship['title'],'addtime'=>$this->time));
        if($ship['sms'] && $user_info['sms']){
            $param = 'id='.$order_id."&status=".$main['status'];
            $this->model('task')->add_once('order',$param);
        }
        $this->json(true);
    }
    //审核订单
    public function check_f()
    {
        if(!$this->popedom['modify']){
            error(P_Lang('您没有权限执行此操作'),$this->url('order'),'error');
        }
        $id = $this->get('id','int');
        $this->assign('id',$id);
        $pageurl = $this->get("pageurl");
        $this->assign('pageurl',$pageurl);
        $rs = $this->model('order')->get_one($id);
        //读取ucode
        $user = $this->model('user')->get_one($rs['user_id']);
        $rs['ucode'] = $user['ucode'];
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        $address = $this->model('order')->address($id);
        $this->assign('shipping',$address);
        if($rs['custom']){
            $custom_array = explode(',',$rs['custom']);
            $this->assign('custom_array',$custom_array);
        }
        //$channel = $this->model('channel')->get_channelList();
		$channel=$this->model('channel')->channel_list($user['group_id']);
        if($channel){
            foreach($channel as $val){
                $currency_id[] = $val['currency_id'];
            }
        }
        $this->assign('channel',$channel);
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city'],'a'=>$address['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            //编辑的时候有图片显示
            if($address[$value["identifier"]]){
                $value["content"] = $address[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->assign('rs',$rs);
        //产品大类
        $catelist = $this->model('cate')->get_all(1,1,70);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $this->view("order_check");
    }
    /*function check_save_f()
    {
        if(!$this->popedom['modify']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $id = $this->get('id','int');
        $old = $this->model('order')->get_one($id);
        if(!$old){
            $this->json(P_Lang('订单信息不存在'));
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('运单号不能为空'));
        }
        if($sn != $old['sn']){
            $rs_sn = $this->model('order')->get_one_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('运单号已存在'));
            }
        }
        $main = array();
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(P_Lang('请选择发货渠道！'));
        }
        $channel_arr = explode('|',$channel);
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json(P_Lang('请指定会员ID！'));
        }
        $main['user_id'] = $user_id;
        $group = $this->model('user')->get_one($user_id);
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }
        $jingzhong = $this->get('jingzhong');
        $main['stock_id'] = $stock;
        //收件人
        $fullname = $this->get('fullname');
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = $this->get('mobile');
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $pca_a = $this->get('pca_a');
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        if(!$zipcode){
            $this->json(P_Lang('邮编不能为空'));
        }
        $idcard = $this->get('idcard');
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        $main['sn'] = $sn;
        $main['channel'] = $channel_arr[0];
        $main['currency_id'] = $channel_arr[6];
        $main['weight_id'] = $channel_arr[7];
        $main['val'] = $this->get('val');
        $main['tax'] = $this->get('tax');
        $main['safe'] = $this->get('safe');
        $main['safe_price'] = $this->get('safe_price');
        $main['weight'] = $this->get('wt');
        $main['jingzhong'] = $jingzhong;
        if($channel_arr[1]<>'0.00'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn <= $channel_arr[1]){
                $jingzhong = floor($jingzhong);
            }
        }
        if($channel_arr[2]=='ceil'){
            $jingzhong = ceil($jingzhong);
        }
        if($channel_arr[2]=='round'){
            $fn=round($jingzhong-floor($jingzhong),2);
            if($fn >0 && $fn < 0.5){
                $jingzhong = floor($jingzhong)+0.5;
            }elseif($fn > 0.5){
                $jingzhong = floor($jingzhong)+1;
            }
        }
        if($jingzhong>$channel_arr[3]){
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($jingzhong - 1);
        }else{
            $channel_price = $channel_arr[4] + $channel_arr[5] * ($channel_arr[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $main['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
        $main['note'] = $this->get('note');
        $pay_price = round(($channel_price + $main['safe_price'] + $main['tax'] + $custom_price),2);
        $main['price'] =$pay_price;
        $main['custom_price'] =$custom_price;
        $main['channel_price'] =$channel_price;
        $main['pay_weight'] =$jingzhong;
        $main['consignor'] = $this->get('consignor');
        $main['consignor_tel'] = $this->get('consignor_tel');
        $main['consignor_address'] = $this->get('consignor_address');
        $main['status'] = 'paid';
        $order_id = $this->model('order')->save($main,$id);
        if(!$order_id){
            $this->json('运单审核失败，请检查');
        }
        //添加物品信息
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json('运单物品信息不能为空');
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$id);
            if($value && $value != 'add'){
                $this->model('order')->save_product($tmp,$value);
            }else{
                $this->model('order')->save_product($tmp);
            }
        }
        //存储收货地址
        $tmp_address = array('province'=>$pca_p,'city'=>$pca_c,'county'=>$pca_a,'address'=>$address,'mobile'=>$mobile,'zipcode'=>$zipcode,'fullname'=>$fullname,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back,'order_id'=>$id);
        $sid = $this->get('s-id','int');
        $this->model('order')->save_address($tmp_address,$sid);
        //插入收件人
        $rs = $this->model('user')->address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$rs){
            $tmp_address['user_id'] = $user_id;
            unset($tmp_address['order_id']);
            $this->model('user')->address_save($tmp_address);
        }
		//增加订单日志
		$ship = $this->model('channel')->get_track($main['status'],'status');
        $this->model('order')->log_save(array('order_id'=>$id,'status'=>$main['status'],'note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }*/
	//待审核订单删除
    public function chkdelete_f()
    {
        $id = $this->get('id');
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        if(!$id) $this->json('未指定运单ID号');
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json('运单不存在');
        }
        //改变包裹状态
        $package = $this->model('package')->get_package("id in(".$rs['package_id'].")");
        foreach($package as $key => $value){
            $this->model('package')->save(array('status'=>'stored'),$value['id']);
        }
        $this->model('order')->delete($id);
        $this->json('删除成功',true);
    }
    public function search_f()
    {
        if(!$this->popedom['list']){
            $this->json('您没有权限执行此操作');
        }
        $this->view('order_search');
    }
}
?>