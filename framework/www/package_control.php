<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-5-17
 * Time: 下午3:24
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class package_control extends dsy_control
{
    function __construct()
    {
        parent::control();
    }
    //取得包裹列表
    function index_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 20;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $condition = "user_id='".$this->session->val('user_id')."'";
        //$this->assign('pageid',$pageid);
        $pageurl = $this->url('package');
        //$this->assign('pageurl',$pageurl);
        $statuslist = $this->model('package')->status();
        //tongji
        foreach($statuslist as $key => $value){
            $count[$key] = $this->model('package')->get_count("user_id=".$this->session->val('user_id')." and status='".$key."'");
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign('statuslist',$statuslist);
        $status = $this->get('status');
        if($status){
            $condition.=" AND status='".$status."'";
            $pageurl.="&status=".rawurlencode($status);
            $this->assign('status',$status);
        }
        $express = $this->model('package')->express();
        $this->assign('express',$express);
        $stock_list = $this->model('stock')->get_stockList();
        $this->assign('stock_list',$stock_list);
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn LIKE '%".$sn."%'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $position = $this->get('position');
        if($position){
            $condition .= " AND position ='".$position."'";
            $pageurl .= "&sn=".rawurlencode($position);
            $this->assign('position',$position);
        }
        $stock = $this->get('stock');
        if($stock){
            $condition .= " AND stock='".$stock."'";
            $pageurl .= "&stock=".rawurlencode($stock);
            $this->assign('stock',$stock);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
        $total = $this->model('package')->get_count($condition);
		$this->assign('pageid',$pageid);
		$this->assign('pageurl',$pageurl);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('package')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['stock_list'] = $this->model('stock')->get_one($value['stock']);
                $value['pros'] = $this->model('package')->product_list($value['id']);
                //$value['weight_units'] = $this->model('channel')->weight();
                $value['order'] = $this->model('package')->get_order($value['id']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
        }
       // $this->assign('rs',$this->user);
        $this->view('package_list');
    }
    //转运预报
    public function forecast_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
		$user = $this->model('user')->get_one($this->session->val('user_id'));
        $stock = $user['stock_id'];
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $res = $this->model('package')->get_one($id);
			//判断是否该会员名下订单
            if($this->session->val('user_id')!=$res['user_id']){
                $this->error(P_Lang('非法操作'));
            }
            $rslist = $this->model('package')->product_list($id);
            $this->assign('rslist',$rslist);
            $this->assign('res',$res);
			$stock = $res['stock'];
        }
        $this->assign("stock",$stock);
        $this->assign('user',$user);
        $stock_list = $this->model('stock')->get_stockList();
        $this->assign('stock_list',$stock_list);
        $express = $this->model('package')->express();
        $this->assign('express',$express);
        //产品大类
        $prolist = $this->model('cate')->get_procate(1,1,70);//(网站id,1,根目录id)
        $this->assign("prolist",$prolist);
        $this->view("package_forecast");
    }
    //查看订单信息
    function info_f()
    {
        $backurl = $this->url('package','list');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        $res = $this->model('package')->get_one($id);
        if(!$res){
            error(P_Lang('包裹信息不存在'),$backurl,'error');
        }
		//判断是否该会员名下订单
		if($this->session->val('user_id')!=$res['user_id']){
			$this->error(P_Lang('非法操作'));
		}
        $res['stock_title'] = $this->model('stock')->get_one($res['stock']);
        $res['weight_unit'] = $this->model('channel')->weight();
        $res['currency'] = $this->model('currency')->get_one($res['currency_id']);
        $this->assign('res',$res);
        $rslist = $this->model('package')->product_list($id);
        $this->assign('rslist',$rslist);
        $statuslist = $this->model('package')->status();
        $this->assign('statuslist',$statuslist);
        $this->view('package_info');
    }
    //删除包裹操作
    public function delete_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //删除订单
        if(!$id) error(P_Lang('未指定包裹ID号'),$this->url('package','','_back='.rawurlencode($backurl)));
		$rs = $this->model('package')->get_one($id);
		//判断是否该会员名下订单
		if($this->session->val('user_id')!=$rs['user_id']){
			$this->json(P_Lang('非法操作'));
		}
		//判断已入库包裹不允许删除
		if($rs['status']!='stored'){
			$this->json(P_Lang('已入库包裹不能删除'));
		}
        $this->model('package')->delete($id);
        //$this->model('package')->product_delete($id);
        $this->json(true);
    }
    //批量删除包裹操作
    public function deletes_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定包裹ID号'));
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs = $this->model('package')->get_one($value);
			//判断是否该会员名下订单
			if($this->session->val('user_id')!=$rs['user_id']){
				$this->json(P_Lang('非法操作'));
			}
            //判断已入库包裹不允许删除
            if($rs['status']!='stored'){
                $this->json(P_Lang('已入库包裹不能删除'));
            }
            $this->model('package')->delete($value);
            
        }
        $this->json(P_Lang('删除成功'),true);
    }
    //编辑预报包裹的时候删除物品操作
    public function product_delete_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        if(!$id) error(P_Lang('未指定包裹ID号'),$this->url('package','','_back='.rawurlencode($backurl)));
        $this->model('package')->product_delete($id);
        $this->json(true);
    }
    public function currency_f(){
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        $currency = $this->model('currency')->get_one($id);
        $this->json($currency,true);
    }
    //批量导入包裹
    function import_f()
    {
        $backurl = $this->url('package','import');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
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
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $this->view('package_import');
    }
    //待审核运单
    public function chkorder_f()
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
        $condition = "user_id='".$_SESSION['user_id']."' and remove ='0' and status='check'";
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('order');
        $this->assign('pageurl',$pageurl);
        // $condition .= "1=1";
        $status = $this->get("status");
        if($status){
            $condition .= " AND status='".$status."'";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn LIKE '%".$sn."%'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
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
        $total = $this->model('order')->get_count($condition);
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
        /*$statuslist = $this->model('order')->status_list();
        foreach($statuslist as $key => $value){
            $count[$key] = $this->model('order')->get_count("user_id=".$this->session->val('user_id')." and status='".$key."' and remove=0");
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign('statuslist',$statuslist);*/
        //$channel_list = $this->model('channel')->get_channelList();
        $channel_list=$this->model('channel')->channel_list($this->session->val('user_gid'));
        $this->assign('channel_list',$channel_list);
        $stock=$this->model('stock')->get_list();
        $this->assign('stock',$stock);
        $work = $this->model('order')->work();
        $this->assign('work',$work);
        $this->view("package_chkorder");
    }
	//置顶/取消置顶操作
    public function settop_f()
    {
        $backurl = $this->url('package');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //////////////////////////////
        if(!$id) $this->json('未指定ID');
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            $rs=$this->model('package')->get_one($value);
            if(!$rs){
                $this->json('包裹单号不存在');
            }else{
                if($rs['top']==1){
                    $this->model('package')->save(array('top'=>0,'toptime'=>0),$value);
                }else{
                    $this->model('package')->save(array('top'=>1,'toptime'=>$this->time),$value);
                }
            }
        }
        $this->json(true);
    }
}
?>