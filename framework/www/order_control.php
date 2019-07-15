<?php
/***********************************************************
	Filename: /www/order_control.php
	Note	: 订单信息管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年12月8日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_control extends dsy_control
{
	function __construct()
	{
		parent::control();
	}
    //创建订单
    function create_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $user = $this->user;
        /*if(!$user['name']){
            error(P_Lang('请先完善会员详细资料'),$this->url('usercp','info'));
        }*/
        $id = $this->get('id');
        if($id){
            $rslist = $this->model('package')->get_package($id);
            $weight = 0;
            $count_price = 0;
            foreach($rslist as $key=>$value){
                if($this->session->val('user_id')!=$value['user_id']){
                    $this->error(P_Lang('非法操作'));
                }
                $stock[$key] = $value['stock'];
                $weight += $value['jingzhong'];
                $pros = $this->model('package')->product_list($value['id']);
                foreach($pros as $v){
                    $count_price += $v['total_price'];
                }
                if($value['status']=='unstored'){
                    error(P_Lang('包裹单号：'.$value['sn'].'未入库，不能提交订单'),$this->url('package','index','_back='.rawurlencode($backurl)));
                }
                if($value['status']=='generated'){
                    error(P_Lang('包裹单号：'.$value['sn'].'已生成订单，不能再提交订单'),$this->url('package','index','_back='.rawurlencode($backurl)));
                }
                if(!$pros){
                    error(P_Lang('包裹单号：'.$value['sn'].'没有物品信息，请先编辑包裹，完善物品信息，再提交订单'),$this->url('package','index','_back='.rawurlencode($backurl)));
                }
            }
            if(count(array_unique($stock))!=1){
                $this->error(P_Lang('请选择同一仓库里包裹'));
            }
            $this->assign('ids',$id);
            $this->assign('stock',$stock[0]);
            $stock_list = $this->model('stock')->get_one($stock[0]);
            $this->assign('stock_list',$stock_list);
            $this->assign('wt',$weight);
            $this->assign('val',$count_price);
            $this->assign('rslist',$rslist);
        }
        $channel_list=$this->model('channel')->channel_list($this->session->val('user_gid'));
        $this->assign('channel_list',$channel_list);
        $express = $this->model('express')->get_all();
        $this->assign('express',$express);
        $work = $this->model('order')->work();
        $work = array_slice($work, 0, 3,true);
        $this->assign('work',$work);
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $this->view('order_create');
    }
    function apply_f()
    {
        $backurl = $this->url('order');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $user = $this->user;
        /*if(!$user['name']){
            error(P_Lang('请先完善会员详细资料'),$this->url('usercp','info'));
        }*/
        //收件人处下单
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('user')->address_one($id);
            $this->assign('rs',$rs);
        }
        //$channel_list=$this->model('channel')->get_channelList();
        $channel_list=$this->model('channel')->channel_list($user['group_id']);
        $this->assign('channel_list',$channel_list);
        //产品大类
        $prolist = $this->model('cate')->get_procate(1,1,70);//(网站id,1,根目录id)
        $this->assign("prolist",$prolist);
        //运单服务
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $info = form_edit('pca',array('p'=>$rs['province'],'c'=>$rs['city']),'pca');
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
            if($rs[$value["identifier"]]){
                $value["content"] = $rs[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
		$sender = $this->model('user')->getSender('user_id='.$user['id'].' and is_default=1');
		$this->assign("sender",$sender);
        $this->view('order_apply');
    }
    function copy_f()
    {
        $backurl = $this->url('order');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('未指定订单ID'));
        }
        $this->assign('id',$id);
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
		if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        $this->assign('rs',$rs);
        if($rs['custom']){
            $custom_array = explode(',',$rs['custom']);
            $this->assign('custom_array',$custom_array);
        }
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        //$channel_list=$this->model('channel')->get_channelList();
        $channel_list=$this->model('channel')->channel_list($this->session->val('user_gid'));
        $this->assign('channel_list',$channel_list);
        //产品大类
        $prolist = $this->model('cate')->get_procate(1,1,70);//(网站id,1,根目录id)
        $this->assign("prolist",$prolist);
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        //收件人信息处理模块
        $address = $this->model('order')->address($id);
        $this->assign('address',$address);
        //$express = $this->model('express')->get_all();
        //$this->assign('express',$express);
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city']),'pca');
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
        $this->view('order_copy');
    }
    //批量创建运
    function import_f()
    {
        $backurl = $this->url('order','import');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $user = $this->user;
        /*if(!$user['name']){
            error(P_Lang('请先完善会员详细资料'),$this->url('usercp','info'));
        }*/
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
           $ext = unserialize($ext_list["ext"]);
           foreach($ext AS $k=>$v){
               $ext_list[$k] = $v;
           }
        $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $this->view('order_import');
    }
    function set_f()
    {
        $backurl = $this->url('order');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
		$pageurl = $this->get("pageurl");
        $this->assign('pageurl',$pageurl);
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('未指定订单ID'));
        }
        $this->assign('id',$id);
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
		//判断是否该会员名下订单
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        $this->assign('rs',$rs);
        if($rs['custom']){
            $custom_array = explode(',',$rs['custom']);
            $this->assign('custom_array',$custom_array);
        }
        $custom = $this->model('custom')->get_customList();
        $this->assign('custom',$custom);
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        //$channel_list=$this->model('channel')->get_channelList();
        $channel_list=$this->model('channel')->channel_list($this->session->val('user_gid'));
        $this->assign('channel_list',$channel_list);
        //产品大类
        $prolist = $this->model('cate')->get_procate(1,1,70);//(网站id,1,根目录id)
        $this->assign("prolist",$prolist);
        $rslist = $this->model('order')->product_list($id);
        $this->assign('rslist',$rslist);
        //收件人信息处理模块
        $address = $this->model('order')->address($id);
        $this->assign('address',$address);
        //$express = $this->model('express')->get_all();
        //$this->assign('express',$express);
        $info = form_edit('pca',array('p'=>$address['province'],'c'=>$address['city']),'pca');
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
        $this->view('order_set');
    }
    //取得订单列表
    function index_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 20;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $condition = "user_id='".$_SESSION['user_id']."' and remove ='0'";
        $pageurl = $this->url('order');
        $status = $this->get("status");
        if($status){
            $condition .= " AND status='".$status."'";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }else{
			$condition .= " and status!='check'";
		}
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn LIKE '%".$sn."%'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
		$stock_select = $this->get('stock','int');
		if($stock_select){
            $condition .= " AND stock_id='".$stock_select."'";
            $pageurl .= "&stock=".rawurlencode($stock_select);
            $this->assign('stock_select',$stock_select);
        }
        $channel_search = $this->get('channel','int');
        if($channel_search){
            $condition .= " AND channel='".$channel_search."'";
            $pageurl .= "&channel=".rawurlencode($channel_search);
            $this->assign('channel_search',$channel_search);
        }
        $type = $this->get('type');
        if($type){
            $condition .= " AND type='".$type."'";
            $pageurl .= "&type=".rawurlencode($type);
            $this->assign('type',$type);
        }
        $fullname = $this->get('fullname');
        if($fullname){
            $condition .= " AND id IN(SELECT order_id FROM ".$this->db->prefix."order_address WHERE fullname = '".$fullname."')";
            $pageurl .= "&fullname=".rawurlencode($fullname);
            $this->assign('fullname',$fullname);
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
		$this->assign('pageid',$pageid);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('order')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['channel'] = $this->model('channel')->get_one($value['channel']);
                $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
                $value['address'] = $this->model('order')->address($value['id']);
                $value['currency'] = $this->model('currency')->get_one($value['currency_id']);
                $value['track'] = $this->model('order')->log_list_one($value['id']);
				$value['pros'] = $this->model('order')->product_list($value['id']);
                if($value['package_id']){
                    $value['package'] = $this->model('package')->get_list("id in (".$value['package_id'].")");
                }
                $value['length'] = explode(',',$value['length']);
                $value['width'] = explode(',',$value['width']);
                $value['height'] = explode(',',$value['height']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
        }
        //订单状态
        $statuslist = $this->model('order')->status_list();
        foreach($statuslist as $key => $value){
            $count[$key] = $this->model('order')->get_count("user_id=".$this->session->val('user_id')." and status='".$key."' and remove=0");
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign('statuslist',$statuslist);
        $channel_list = $this->model('channel')->get_channelList();
        $this->assign('channel_list',$channel_list);
        $stock=$this->model('stock')->get_list();
        $this->assign('stock',$stock);
        $work = $this->model('order')->work();
        $this->assign('work',$work);
		if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view('order_list');
    }

    //查看订单信息
    function order_info_f()
    {
		$backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $rs = $this->auth_check();
		//判断是否该会员名下订单
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        $status_list = $this->model('order')->status_list();
        $rs['status_info'] = ($status_list && $status_list[$rs['status']]) ? $status_list[$rs['status']] : $rs['status'];
        $rs['channel'] = $this->model('channel')->get_one($rs['channel']);
        $rs['address'] = $this->model('order')->address($rs['id']);
        $rs['currency'] = $this->model('currency')->get_one($rs['channel']['currency_id']);
        $rs['stock'] = $this->model('stock')->get_one($rs['stock_id']);
        $this->assign('rs',$rs);
        $log = $this->model('order')->log_list_one($rs['id']);
        $this->assign('log',$log);
        $rslist = $this->model('order')->product_list($rs['id']);
        $this->assign('rslist',$rslist);
        if($rs['package_id']){
            $package = $this->model('package')->get_list('id in ('.$rs['package_id'].')');
            if($package){
                foreach($package as $key=>$value){
                    //$value['stock_list'] = $this->model('stock')->get_one($value['stock']);
                    $value['pros'] = $this->model('package')->product_list($value['id']);
                    $package[$key] = $value;
                }
            }
            $this->assign('package',$package);
        }

        if($rs['custom']){
            $way = explode(',',$rs['custom']);
            //获取特殊自定义服务
            foreach($way as $val){
                $custom[] = $this->model('custom')->get_one($val);
            }
        }
        $this->assign('custom',$custom);
        $this->view('order_info');
    }
    //包裹列表
    function package_f()
    {
        $backurl = $this->url('package');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 20;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $condition = "user_id='".$_SESSION['user_id']."'";
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('package');
        $this->assign('pageurl',$pageurl);
        $status = $this->get('status','int');
        if($status){
            $condition .= " AND o.status='".$status."'";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }else{ //默认显示未入库
            $condition .= " AND o.status= 1";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',1);
        }
        $express = $this->get("express");
        if($express){
            $condition .= " AND o.express='".$express."'";
            $pageurl .= "&express=".rawurlencode($express);
            $this->assign('express',$express);
        }
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND o.sn LIKE '%".$sn."%'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $stock = $this->get('stock');
        if($stock){
            $condition .= " AND o.stock='".$stock."'";
            $pageurl .= "&stock=".rawurlencode($stock);
            $this->assign('stockSelect',$stock);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND o.addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND o.addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
        $total = $this->model('forecast')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('forecast')->get_list($condition,$offset,$psize);
            $this->assign('rslist',$rslist);
        }
        $stock=$this->model('stock')->get_list();
        $this->assign('stock',$stock);
        $this->view('order_package');
    }
	private function auth_check()
	{
		$sn = $this->get('sn');
		$back = $this->get('back');
		if(!$back){
			$back = $this->url;
		}
		//判断订单是否存在
		if($sn){
			$rs = $this->model('order')->get_one_from_sn($sn);
		}
		if(!$rs){
			$id = $this->get('id','int');
            $package_id = $this->get('package_id','int');
			if($id){
                $rs = $this->model('order')->get_one($id);
            }
			if($package_id){
                $rs = $this->model('order')->get_one_from_package($package_id);
            }
			if(!$rs){
				error(P_Lang('订单信息不存在'),$back,'error');
			}
		}
		return $rs;
	}
    //显示所选渠道详细内容,返回json数据
    public function get_channel_f(){
        $id = $this->get('id');
        if($id){
            $channel = $this->model('channel')->get_one($id);
            $this->json($channel,true);
        }
    }
    public function pro_list_f(){
        $ids = $this->get('id');
        if($ids){
           // $ids = rtrim($ids,",");
            $pro_list = $this->model('package')->pro_list($ids);
            $this->json($pro_list,true);
        }
    }
    //订单进入回收站操作
    public function remove_f()
    {
		$backurl = $this->url('order');
        if(!$this->session->val('user_id')){
            $this->error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json(P_Lang('运单不存在'));
        }
		//判断是否该会员名下订单
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        //移除订单
        $this->model('order')->remove($id,1);
        $this->json('删除成功',true);
    }
    //打印运单
    public function print_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $rs = $this->model('order')->get_one($id);
		if(!$rs){
            $this->error(P_Lang('运单不存在'));
        }
		//判断是否该会员名下订单
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $product = $this ->model('order')->product_list($id);
        $this->assign('product',$product);
        $userinfo = $this->model('user')->get_one($rs['user_id']);
        $this->assign('userinfo',$userinfo);
        $address = $this->model('order')->address($rs['id']);
        $this->assign('address',$address);
        //$rs['jingzhong'] = $rs['jingzhong']*0.45;
        $rs['val'] = price_format_val($rs['val'],$rs['currency_id'],$this->site['currency_id']);
        $this->assign('rs',$rs);
		//条码
        $this->lib('barcode')->font($this->dir_data.'font/Arial.ttf',12);
        $barcode = 'data/cache/'.$rs['sn'].'.png';
        if(!file_exists($this->dir_root.$barcode)){
            $this->lib('barcode')->create($rs['sn'],$this->dir_root.$barcode);
        }
        $this->assign('barcode',$barcode);
        $this->view("order_print");
    }
    public function stock_f(){
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('未指定仓库'));
        }
        $consign = $this->model('stock')->get_one($stock);
        $this->json($consign,true);
    }
    public function idcard_f(){
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
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $this->view("idcard");
    }
    //删除产品信息
    function product_delete_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定产品ID'));
        }
        $this->model('order')->product_delete($id);
        $this->json(true);
    }
    public function currency_f(){
        $id = $this->get('id','int');
        //$pid = $this->get('pid','int');
        $currency = $this->model('currency')->get_one($id);
        //$stock = $this->model('stock')->get_one($pid);
       //$this->json(array('currency'=>$currency['title'],'position'=>$stock['position']),true);
       $this->json(array('currency'=>$currency['title']),true);
    }
    public function front_print_f()
    {
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
			if($this->session->val('user_id')!=$value['user_id']){
				$this->error(P_Lang('非法操作'));
			}
            $value['product'] = $this ->model('order')->product_list($value['id']);
            // $value['userinfo'] = $this->model('user')->get_one($value['user_id']);
            $value['address'] = $this->model('order')->address($value['id']);
            $rs[$key] = $value;
        }
        $this->json($rs,true);
    }
    public function add_package_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('open','address');
        $this->assign('pageurl',$pageurl);
        $condition = "user_id='".$_SESSION['user_id']."' and make='0'";
        $statuslist = $this->model('package')->status();
        $this->assign('statuslist',$statuslist);
        $express = $this->model('package')->express();
        $this->assign('express',$express);
        $stock_list = $this->model('stock')->get_stockList();
        $this->assign('stock_list',$stock_list);
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn LIKE '%".$sn."%'";
            $this->assign('sn',$sn);
        }
        $recipients = $this->get('recipients');
        if($recipients){
            $condition .= " AND recipients LIKE '%".$recipients."%'";
            $this->assign('recipients',$recipients);
        }
        $total = $this->model('package')->get_count($condition);
        if($total){
            $rslist = $this->model('package')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['stock_list'] = $this->model('stock')->get_one($value['stock']);
                $value['pros'] = $this->model('package')->product_list($value['id']);
                $value['order'] = $this->model('package')->get_order($value['id']);
                $rslist[$key] = $value;
            }
            $this->assign("total",$total);
            $this->assign('rslist',$rslist);
        }
        $this->assign("id",$id);
        $this->view('add_package');
    }
    /*public function pay_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定订单ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $balance = $this->model('wealth')->get_val($rs['user_id'],2);
        $this->assign("balance",$balance);
        $this->assign("id",$id);
        $this->assign("rs",$rs);
        $this->view('order_pay');
    }
    public function received_f(){
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定ID'));
        $rs = $this->model('order')->get_one($id);
        if(!$rs) $this->json(P_Lang('订单信息不存在'));
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->json(P_Lang('非法操作'));
		}
        //批次判断
        // if($rs['cate_id']!=$cid) $this->error('包裹不属于该批次运单，不能进行出库操作');
        //判断是否扣款或已出库
        //if($rs['status']!='paid') $this->json(P_Lang('包裹未扣款或已出库，请检查订单'));
        //读取运单状态
        //$ship = $this->model('channel')->get_track('shipped','status');
        $this->model('order')->update_order_status($id,'received');
        $this->model('order')->log_save(array('order_id'=>$id,'note'=>'确认签收','addtime'=>$this->time));
        //$this->model('order')->save(array('ext'=>'已发货','status'=>'shipped'),$id);
        $this->json(true);
    }
    public function redelivery_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定订单ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $this->assign("id",$id);
        //$channel_list=$this->model('channel')->get_channelList();
        $channel_list=$this->model('channel')->channel_list($this->session->val('user_gid'));
        $this->assign('channel_list',$channel_list);
        $this->view('order_redelivery');
    }
    public function repay_f()
    {
        $backurl = $this->url('order');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定订单ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'));
        }
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $balance = $this->model('wealth')->get_val($rs['user_id'],2);
        $this->assign("balance",$balance);
        $this->assign("id",$id);
        $this->assign("rs",$rs);
        $this->view('order_repay');
    }*/
    //签收
    public function end_f(){
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定ID'));
        $rs = $this->model('order')->get_one($id);
        if(!$rs) $this->json(P_Lang('订单信息不存在'));
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        //判断是否扣款或已出库
        if($rs['status']!='received') $this->json(P_Lang('国内派送运单才能完成签收'));
        //读取运单状态
        //$ship = $this->model('channel')->get_track('shipped','status');
        $this->model('order')->update_order_status($id,'finished');
        //$this->model('order')->log_save(array('order_id'=>$id,'note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }
	//待审核订单删除
    public function delete_f()
    {
        $backurl = $this->url('order');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定订单ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->json(P_Lang('订单信息不存在'));
        }
        //判断是否该会员名下订单
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        //改变包裹状态
        $package = $this->model('package')->get_package($rs['package_id']);
        foreach($package as $key => $value){
            $this->model('package')->save(array('status'=>'stored'),$value['id']);
        }
        $this->model('order')->delete($id);
        $this->json('删除成功',true);
    }
	public function edit_note_f()
    {
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login'));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('未指定运单ID'));
        }
        $rs = $this->model('order')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('运单信息不存在'));
        }
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $this->assign('id',$id);
        $this->assign('rs',$rs);
        $this->view("edit_note");
    }
}
?>