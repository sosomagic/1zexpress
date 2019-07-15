<?php
/***********************************************************
	Filename: /admin/package_control.php
	Note	: 包裹管理，编辑和删除等相关操作
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2016年5月19日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class package_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("package");
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
		$pageurl = $this->url('package');
		$condition = " 1=1";
        $status = $this->get("status");
        if($status){
            $condition.=" AND o.status='".$status."'";
            $pageurl.="&status=".rawurlencode($status);
            $this->assign('status',$status);
        }
        $stock = $this->get("stock",'int');
        if($stock){
            $condition.=" AND o.stock ='".$stock."'";
            $pageurl.="&stock=".rawurlencode($stock);
            $this->assign('stock',$stock);
        }
		$stocks = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stocks);
		$sn = trim($this->get('sn'));
        if($sn){
            $condition .= " AND o.sn like '%".$sn."%'";
			$pageurl.="&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $first_name = $this->get('first_name');
        if($first_name){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user_ext WHERE first_name='".$first_name."')";
            $pageurl .= "&first_name=".rawurlencode($first_name);
            $this->assign('first_name',$first_name);
        }
        $ucode = $this->get('ucode');
        if($ucode){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode='".$ucode."')";
            $pageurl .= "&ucode=".rawurlencode($ucode);
            $this->assign('ucode',$ucode);
        }
        $user = $this->get('user');
        if($user){
            $condition .= " AND o.user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user='".$user."')";
            $pageurl .= "&user=".rawurlencode($user);
            $this->assign('user',$user);
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
        if(!$_SESSION["admin_rs"]["if_system"]){
            $wh = " and o.stock in(".$_SESSION["admin_stock"].")";
        }
		$statuslist = $this->model('package')->status();
        foreach($statuslist as $key => $value){
            $count[$key] = $this->model('package')->get_count("status='".$key."'".$wh);
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
		$this->assign('statuslist',$statuslist);
		$total = $this->model('package')->get_count($condition.$wh);
		if($total>0){
			$rslist = $this->model('package')->get_list($condition.$wh,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['stock_list'] = $this->model('stock')->get_one($value['stock']);
                $value['weight_units'] = $this->model('channel')->weight();
				$value['pros'] = $this->model('package')->product_list($value['id']);
                $value['order'] = $this->model('package')->get_order($value['id']);
                $rslist[$key] = $value;
            }
			$this->assign('rslist',$rslist);
			$this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('共有').'(total)条'.P_Lang('，').P_Lang('当前').'(num)/(total_page)页&always=1';
			$pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
			$this->assign('pagelist',$pagelist);
		}
		$this->view("package_list");
	}
    //查看订单信息
    public function info_f()
    {
        if(!$this->popedom["list"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $id = $this->get('id','int');
        if(!$id) error_open(P_Lang('未指定ID'));
        $rs = $this->model('package')->get_one($id);
        if(!$rs) error_open(P_Lang('包裹信息不存在'));
        $rs['stock_title'] = $this->model('stock')->get_one($rs['stock']);
        $rs['weight_unit'] = $this->model('channel')->weight();
        $this->assign('rs',$rs);
        //订单状态
        $statuslist = $this->model('package')->status();
        $this->assign('statuslist',$statuslist);
        //取得订单的用户信息
        $user = $this->model('user')->get_one($rs['user_id']);
        $this->assign('user',$user);
        //订单下的产品列表
        $rslist = $this->model('package')->product_list($id);
        $this->assign('rslist',$rslist);
        $this->view("package_info");
    }
	public function set_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			if(!$this->popedom['add']){
				error(P_Lang('您没有权限执行此操作'),$this->url('package'),'error');
			}
		}else{
			if(!$this->popedom['modify']){
				error(P_Lang('您没有权限执行此操作'),$this->url('package'),'error');
			}
			$this->assign('id',$id);
			$rs = $this->model('package')->get_one($id);
            $this->assign('rs',$rs);
			$rslist = $this->model('package')->product_list($id);
			$this->assign('rslist',$rslist);

		}
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stock);
        $express = $this->model('package')->express();
        $this->assign('express',$express);
        //产品大类
        $catelist = $this->model('cate')->get_all(1,1,70);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        $statuslist = $this->model('package')->status();
        $this->assign('statuslist',$statuslist);
		$this->view("package_set");
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

	//编辑存储信息
	private function modify_save($id)
	{
		$old = $this->model('package')->get_one($id);
		if(!$old){
			$this->json(P_Lang('订单信息不存在'));
		}
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('包裹单号不能为空'));
        }
        if($sn != $old['sn']){
            $rs_sn = $this->model('package')->get_one_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('包裹单号已被使用，请检查'));
            }
        }
        $stock=$this->get('stock');
        if(!$stock){
            $this->json(p_Lang('请选择仓库'));
        }
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong){
            $this->json(P_Lang('入库重量不能为空！'));
        }
        if(!preg_match('/^\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('入库重量数字格式不对！'));
        }
		$main = array();
		$main['sn'] = $sn;
        $main['express'] = $this->get('express');
		$main['stock'] = $stock;
		$main['position'] = $this->get('position');
        $main['user_id'] = $this->get('user_id','int');
        if(!$main['user_id']){
            $this->json(P_Lang('请指定会员ID！'));
        }
        $main['jingzhong'] = $jingzhong;
        $main['note'] = $this->get('note');
		$pid = $this->model('package')->save($main,$id);
        if(!$pid){
            $this->json(P_Lang('包裹编辑失败'));
        }
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        if(!$prolist || !is_array($prolist)){
            $this->json(P_Lang('包裹物品信息不能为空'));
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
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'package_id'=>$id);
            if($value && $value != 'add'){
                $this->model('package')->save_product($tmp,$value);
            }else{
                $this->model('package')->save_product($tmp);
            }
        }
		$this->json(true);
	}

	//添加存储信息
	private function add_save()
	{
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('包裹单号不能为空'));
        }
        $rs = $this->model('package')->get_one_from_sn($sn);
        if($rs){
            $this->json(P_Lang('包裹单号已被使用，请换个单号'));
        }
        $stock=$this->get('stock');
        if(!$stock){
            $this->json(p_Lang('请选择到货仓库'));
        }
        //$wt = $this->get('wt');
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong){
            $this->json(P_Lang('入库重量不能为空'));
        }
        if(!preg_match('/^\d+(\.\d+)?$/',$jingzhong)){
            $this->json(P_Lang('入库重量输入格式不对！'));
        }
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json(P_Lang('请指定会员ID'));
        }
		$main = array();
        $main['sn'] = $sn;
        $main['express'] = $this->get('express');
        $main['stock'] = $stock;
        $main['position'] = $this->get('position');
        $main['jingzhong'] = $jingzhong;
        //$main['weight_id'] = $arr[1];
        $main['status'] = 'stored';
        $main['user_id'] = $user_id;
		$main['addtime'] = $this->time;
		$main['rukutime'] = $this->time;
		$main['note'] = $this->get('note');
        $package_id = $this->model('package')->save($main);
        if(!$package_id){
            $this->json(P_Lang('包裹创建失败'));
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
        //$currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->model('package')->delete($package_id);
            $this->json(P_Lang('物品信息不能为空'));
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
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size);
            $tmp['package_id'] = $package_id;
            $this->model('package')->save_product($tmp);
        }
		$this->json(true);
	}
    //删除订单操作
    public function delete_f()
    {
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $id = $this->get('id','int');
        if(!$id) $this->json(P_Lang('未指定包裹ID号'));
        $rs=$this->model('package')->get_one($id);
        //判断已入库包裹不允许删除
        if($rs['status']=='generated'){
            $this->json(P_Lang('已生成运单包裹不能删除'));
        }else{
            $this->model('package')->delete($id);
        }
        //删除订单
        $this->json(P_Lang('删除成功'),true);
    }
    //批量删除包裹操作
    public function deletes_f()
    {
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定包裹ID号'));
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs=$this->model('package')->get_one($value);
            //判断已入库包裹不允许删除
            if($rs['status']=='generated'){
                $this->json(P_Lang('已生成运单包裹不能删除'));
            }else{
                $this->model('package')->delete($value);
            }
        }
        $this->json(P_Lang('删除成功'),true);
    }
    //删除包裹产品信息
    function product_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定产品ID'));
        }
        if(!$this->popedom['modify']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $this->model('package')->product_delete($id);
        $this->json(true);
    }
    public function storage_f(){
        if(!$this->popedom['storage']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->view('package_scan');
    }
    public function currency_f(){
        $id = $this->get('id','int');
        $pid = $this->get('pid','int');
        $currency = $this->model('currency')->get_one($id);
        $stock = $this->model('stock')->get_one($pid);
        $this->json(array('currency'=>$currency['title'],'position'=>$stock['position']),true);
    }
    public function create_f()
    {
        $channel = $this->model('channel')->get_list();
        foreach($channel as $val){
            $currency_id[] = $val['currency_id'];
        }
        $this->assign('channel',$channel);
        /*$batch_list = $this->model('batch')->get_all();
        $this->assign("batch_list",$batch_list);*/
        $stock = $this->model('stock')->get_list();
        $this->assign('stock',$stock);
        $custom_list = $this->model('cate')->get_all(1,1,597);
        foreach($custom_list as $key=>$value){
            $value['custom'] = $this->model('custom')->get_custom($value['id']);
            $custom_list[$key] = $value;
        }
        $this->assign('custom_list',$custom_list);
        $this->view("package_create");
    }
    public function get_package_f()
    {
        $user_id = $this->get('user_id','int');
        if($user_id){
            $condition = " o.user_id='".$user_id."'";
            $condition .= " AND o.status='stored'";
            $rslist = $this->model('package')->get_list($condition);
            foreach($rslist as $key=>$value){
                //$value['pro_count'] = $this->model('package')->product_count("package_id=".$value['id']);//读取商品个数
                $value['pro'] = $this->model('package')->product_list($value['id']);
                $value['weight_units'] = $this->model('channel')->weight();
                $rslist[$key] = $value;
            }
            $this->json($rslist,true);

        }
    }
    public function pro_list_f(){
        $ids = $this->get('id');
        if($ids){
            $pro_list = $this->model('package')->pro_list($ids);
            $this->json($pro_list,true);
        }
    }
	public function storage_setting_f(){
        $sn = trim($this->get('sn'));
        if(!$sn){
            $this->json(P_Lang('请填写入包裹单号'));
        }
        $rs = $this->model('package')->get_one_from_sn($sn);
        if($rs['status']=='stored'){
            $this->json(P_Lang('包裹已入库'));
        }
        if($rs['status']=='generated'){
            $this->json(P_Lang('包裹已创建运单'));
        }
        $stock = $this->get('stock');
        $position = trim($this->get('position'));
        if(!$position){
            $this->json(P_Lang('请填写仓位'));
        }
        $jingzhong = trim($this->get('jingzhong'));
        if(!$jingzhong){
            $this->json(P_Lang('请填写入库重量'));
        }

        $ucode = trim($this->get('ucode'));
        if(!$ucode){
            $this->json(P_Lang('请填写会员编号'));
        }
        $user = $this->model('user')->uid_from_ucode($ucode);
        if(!$user){
            $this->json(P_Lang('会员编号不正确'));
        }
        if(!$rs['id']){
            $main['sn'] = $sn;
            $main['user_id'] = $user['id'];
        }
        $main['stock'] = $stock;
        $main['position'] = $position;
        $main['jingzhong'] = $jingzhong;
        $main['status'] = 'stored';
        $main['rukutime'] = $this->time;
        $package_id = $this->model('package')->save($main,$rs['id']);
        if(!$package_id){
            $this->json(P_Lang('包裹入库失败，请检查'));
        }
        $this->json(true);
    }
	public function get_ucode_f(){
        $ucode = trim($this->get('ucode'));
        $sn = trim($this->get('sn'));
        if(!$sn){
            $this->json(P_Lang('请填写包裹单号'));
        }
        $rs = $this->model('package')->get_one_from_sn($sn);
        /*if($rs['status']=='stored'){
            $this->json(P_Lang('包裹已入库'));
        }
        if($rs['status']=='generated'){
            $this->json(P_Lang('包裹已创建运单'));
        }*/
        if($rs){
            $user =$this->model('user')->get_one($rs['user_id']);
            $this->json($user,true);
        }else{
            if($ucode){
                $this->json(true);
            }else{
                $this->json(P_Lang('请填写会员编号'));
            }
        }
    }
    public function package_print_f()
    {
        $sn = $this->get('sn');
        $rs = $this->model('package')->get_one_from_sn($sn);
        //取得订单的用户信息
        $user = $this->model('user')->get_one($rs['user_id']);
        $rs['ucode'] = $user['ucode'];
        $this->json($rs,true);
    }
    //待审核运单
    public function chkorder_f()
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
        $pageurl = $this->url('package','chkorder');
        $condition = "o.remove=0 and o.status='check'";
        $stock_id = $this->get("stock_id",'int');
        if($stock_id){
            $condition .= " AND o.stock_id=".$stock_id;
            $pageurl .= "&stock_id=".rawurlencode($stock_id);
            $this->assign('stock_id',$stock_id);
        }else{
            //管理员所属仓库运单
            $condition .= " AND o.stock_id in(".$_SESSION["admin_stock"].")";
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
        $fullname = trim($this->get('fullname'));
        if($fullname){
            $condition .= " AND o.fullname like '%".$fullname."%'";
            $this->assign('fullname',$fullname);
        }
        $sn = trim($this->get('sn'));
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
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
        $total = $this->model('order')->get_count($condition);
        if($total>0){
            $rslist = $this->model('order')->get_list($condition,$offset,$psize);
            if(!$rslist) $rslist = array();
            foreach($rslist AS $key=>$value){
                $channel[] = $this->model('channel')->get_one($value['channel']);
                $address[] = $this->model('order')->address($value['id']);
                $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
                //$value['batch'] = $this->model('batch')->get_one($value['cate_id']);
                //$value['weight_units'] = $this->model('channel')->weight();
                $value['currency'] = $this->model('currency')->get_one($value['currency_id']);
                //$value['track'] = $this->model('order')->log_list_one($value['id']);
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
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
        }
        if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view("package_chkorder");
    }
    public function add_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('package'),'error');
        }
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stock);
        $express = $this->model('package')->express();
        $this->assign('express',$express);
        $this->view("package_add");
    }
    public function package_save_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('package'),'error');
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(P_Lang('包裹单号不能为空'));
        }
        $rs = $this->model('package')->get_one_from_sn($sn);
        if($rs){
            $this->json(P_Lang('包裹单号已被使用，请换个单号'));
        }
        $stock=$this->get('stock');
        if(!$stock){
            $this->json(p_Lang('请选择到货仓库'));
        }
        //$wt = $this->get('wt');
        $jingzhong = $this->get('jingzhong');
        if(!$jingzhong){
            $this->json(P_Lang('入库重量不能为空'));
        }
        $user_id = $this->get('user_id','int');
        if(!$user_id){
            $this->json(P_Lang('请确认会员编号是否正确'));
        }
        $main = array();
        $main['sn'] = $sn;
        $main['express'] = $this->get('express');
        $main['stock'] = $stock;
        $main['fullname'] = $this->get('fullname');
        $main['position'] = $this->get('position');
        $main['jingzhong'] = $jingzhong;
        //$main['weight_id'] = $arr[1];
        $main['status'] = 'stored';
        $main['user_id'] = $user_id;
        $main['rukutime'] = $this->time;
        $main['note'] = $this->get('note');
        $package_id = $this->model('package')->save($main);
        if(!$package_id){
            $this->json(P_Lang('包裹创建失败'));
        }
        $this->json(true);
    }
}
?>