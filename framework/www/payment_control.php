<?php
/***********************************************************
	Filename: /www/payment_control.php
	Note	: 付款操作
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年12月14日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class payment_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}
    public function details_f()
    {
        $backurl = $this->url('payment');
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
        $pageurl = $this->url('payment','details');
        $this->assign('pageurl',$pageurl);
        $condition = "user_id='".$_SESSION['user_id']."'";
        $type = $this->get("type");
        if($type){
            $condition .= " AND type='".$type."'";
            $pageurl .= "&type=".rawurlencode($type);
            //$this->assign('type',$type);
        }
        $this->assign('type',$type);
        $paymentMethod = $this->get("paymentMethod");
        if($paymentMethod){
            $condition .= " AND paymentMethod='".$paymentMethod."'";
            $pageurl .= "&paymentMethod=".rawurlencode($paymentMethod);
            $this->assign('paymentMethod',$paymentMethod);
        }
        $condition .= " AND status!=0";
        $order_sn = trim($this->get('order_sn'));
        if($order_sn){
            $condition .= " AND order_id IN(SELECT id FROM ".$this->db->prefix."order WHERE sn = '".$order_sn."')";
            $pageurl .= "&order_sn=".rawurlencode($order_sn);
            $this->assign('order_sn',$order_sn);
        }
        $sn = trim($this->get('sn'));
        if($sn){
            $condition .= " AND sn='".$sn."'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND dateline>=".strtotime($dateRangeArr[0]);
            $condition .= " AND dateline<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
        $total = $this->model('payment')->get_count($condition);
        if($total>0){
            $rslist = $this->model('payment')->get_list($condition,$offset,$psize);
            foreach($rslist as $key => $value){
                $value['order'] = $this->model('order')->get_one($value['order_id']);
                $value['channel'] = $this->model('channel')->get_one($value['channel_id']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
            $this->assign('total',$total);
            $this->assign('psize',$psize);
        }
        //交易类型
        $typelist = $this->model('payment')->type();
        $this->assign('typelist',$typelist);
        //获取默认货币单位
        $currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->assign('currency',$currency);
        //支付方式
        $paymentMethod = $this->model('payment')->paymentMethod();
        $this->assign('paymentMethod',$paymentMethod);
        $this->view('payment_details');
    }
    public function claim_f()
    {
        $backurl = $this->url('claim');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $rs = $this->model('claim')->get_one($id);
            if(!$rs){
                $this->error(P_Lang('数据不存在，请检查'));
            }
            if($this->session->val('user_id')!=$rs['user_id']){
                $this->error(P_Lang('非法操作'));
            }
            $this->assign('rs',$rs);
        }
        $ext_list = $this->model('fields')->get_one('141');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
			if($rs['pic']){
                $ext_list["content"] = $rs['pic'];
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view("claim");
    }
    function claim_list_f()
    {
        $backurl = $this->url('claim');
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
        $total = $this->model('claim')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('claim')->get_list($condition,$offset,$psize);
            $this->assign('rslist',$rslist);
        }
        $currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->assign("currency",$currency);
        $this->view('claim');
    }
    public function claim_show_f()
    {
        $backurl = $this->url('claim');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $rs = $this->model('claim')->get_one($id);
            if(!$rs){
                $this->error(P_Lang('数据不存在，请检查'));
            }
            if($this->session->val('user_id')!=$rs['user_id']){
                $this->error(P_Lang('非法操作'));
            }
            //读图片
            $res = $this->model('res')->get_one($rs['pic']);
            $rs['file'] = $res["filename"];
            $this->assign('rs',$rs);
        }
        $this->view("claim_show");
    }
    public function claim_delete_f()
    {
        $backurl = $this->url('claim');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //删除订单
        if(!$id) error(P_Lang('未指定上门取件ID号'),$this->url('order','','_back='.rawurlencode($backurl)));
        $rs = $this->model('claim')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('数据不存在，请检查'));
        }
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        $this->model('claim')->del($id);
        $this->json(true);
    }
    public function bank_f()
    {
        $backurl = $this->url('bank');
        if(!$_SESSION['user_id']){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $type = $this->get('type');
        $this->assign('type',$type);
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $rs = $this->model('bank')->get_one($id);
            if(!$rs){
                $this->error(P_Lang('数据不存在，请检查'));
            }
            if($this->session->val('user_id')!=$rs['user_id']){
                $this->error(P_Lang('非法操作'));
            }
            $this->assign('rs',$rs);
        }
        $ext_list = $this->model('fields')->get_one('141');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            //循环去掉开始上传按钮
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            //编辑的时候有图片显示
            //$idlist = strtolower($ext_list["identifier"]);
            if($rs[$ext_list["identifier"]]){
                $ext_list["content"] = $rs[$ext_list["identifier"]];
            }
            //$ext_list["content"] = $rs[$ext_list["identifier"]];//对应数据库字段是否跟identifier值是否一直
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view("bank");
    }
    function banklist_f()
    {
        $backurl = $this->url('bank');
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
        $total = $this->model('bank')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('bank')->get_list($condition,$offset,$psize);
            $this->assign('rslist',$rslist);
        }
        $this->view('bank_list');
    }
    public function bank_delete_f()
    {
        $backurl = $this->url('bank');
        if(!$_SESSION['user_id']){
            error('您还不是会员，请先登录',$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //删除订单
        if(!$id) error('未指定ID',$this->url('payment','bank_list','_back='.rawurlencode($backurl)));
        $rs = $this->model('bank')->get_one($id);
        if(!$rs){
            $this->error(P_Lang('数据不存在，请检查'));
        }
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        $this->model('bank')->del($id);
        $this->json(true);
    }
	public function export_f(){
        $this->view('payment_export');
    }
    public function export_data_f()
    {
        $error_url = $this->url("payment","export");
        $ext = $this->get('ext') ? $this->get('ext') : array();
        $condition = " user_id=".$_SESSION['user_id'];
        $type = $this->get("type");
        if($type){
            $condition .= " AND type='".$type."'";
        }
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn = '".$sn."'";
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND dateline>=".strtotime($dateRangeArr[0]);
            $condition .= " AND dateline<=".strtotime($dateRangeArr[1]);
        }
        $rslist = $this->model('payment')->get_export($condition);
        if(!$rslist)
        {
            error('没有查询到要导出的数据',$error_url,"error");
        }
        $first_list = array("sn"=>"支付编号","type"=>"交易类型","price"=>"金额","title"=>"主题","dateline"=>"记录时间");
        include_once $this->dir_root."extension/phpexcel/PHPExcel.php";
        @set_time_limit(0);#[设置防止超时]
        $phpexcel = new PHPExcel();
        $row = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        $filename = date("Ymd-His");
        $idlist = $ext;
        $row_array = explode(",",$row);
        //$width_array = array();
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
            //$phpexcel->getActiveSheet()->getStyle($char."1")->getAlignment()->setWrapText(true);
            $phpexcel->getActiveSheet()->setCellValueExplicit($char."1",$val,PHPExcel_Cell_DataType::TYPE_STRING);
        }
        //现在存储内容数据
        $i=0;
        foreach($rslist AS $key=>$value)
        {
            $m = $i+2;
            if($ifpic) $this->set_height($m,"80");
            foreach($list AS $k=>$v)
            {
                $val = $value[$v];  //对应数据库字段
                if($v=='type'){
                    if($value['type']=='order'){
                        $val = '扣款';
                    }elseif($value['type']=='recharge'){
                        $val = '充值';
                    }else{
                        $val = '其他费用';
                    }

                }
                if($v=='price'){
                    $currency = $this->model('currency')->get_one($value['currency_id']);
                    $unit = $currency['title'];
                    $val = $value['price'].$unit;

                }
				if($v=='dateline'){
                    $val = date("Y-m-d H:i:s",$value['dateline']);
                }
                $char = $k."".$m;
                $phpexcel->getActiveSheet()->getStyle($char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $phpexcel->getActiveSheet()->getStyle($char)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                //$phpexcel->getActiveSheet()->getStyle($char)->getAlignment()->setWrapText(true);
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
    public function recharge_f()
    {
        $back = $this->get('back');
        if(!$back){
            $back = $this->lib('server')->referer();
        }
        if(!$this->session->val('user_id')){
            $this->error(P_Lang('请先登录，非会员没有此权限'),$this->url('login','','_back='.rawurlencode($this->url('payment'))));
        }
        $currency = $this->model('currency')->get_one(1);
        $this->assign('currency',$currency);
        $paylist = $this->model('payment')->get_all($this->site['id'],1);
        $this->assign("paylist",$paylist);
        $this->view('payment_recharge');
    }
    //创建一条支付接口
    public function create_f()
    {
        if(!$this->session->val('user_id')){
            $this->error(P_Lang('请先登录，非会员没有此权限'),$this->url('login'),1);
        }
        $backurl = $this->lib('server')->referer();
        $userid = $this->session->val('user_id');
        $type = 'recharge';
        $wealth = 2;//钱包
		$currency_id = 1;
        $price = $this->get('price','float');
        if(!$price){
            $this->error(P_Lang('请输入充值金额'),$backurl);
        }
        if($price < 1){
            $this->error(P_Lang('充值金额不能少于1元'),$backurl);
        }
        $payment = $this->get('payment','int');
        if(!$payment){
            $this->error(P_Lang('未指定付款方式'),$backurl);
        }
        if($payment==1){
            $paymentMethod = 'alipay';
        }elseif($payment==4){
            $paymentMethod = 'wechat';
        }else{
            $paymentMethod = 'paypal';
			$currency_id = $this->site['currency_id'];
        }
        $sn = uniqid('CZ');
        $array = array(
            'type'           => 'recharge'
            ,'price'         => $price
            ,'currency_id'   => $currency_id
            ,'sn'            => $sn
            ,'paymentMethod' => $paymentMethod
            ,'payment_id'    => $payment
            ,'user_id'       => $userid
            ,'dateline'      => $this->time
            ,'title'         => P_Lang('在线充值')
            ,'content'       => P_Lang('在线充值')
            ,'status'        => 0
        );
        $tmp = array('goal'=>$wealth);
        $array['ext'] = serialize($tmp);
        $insert_id = $this->model('payment')->log_create($array);
        if(!$insert_id){
            $this->error(P_Lang('支付记录创建失败，请联系管理员'),$backurl);
        }
        $this->success(P_Lang('即将为您跳转到支付页面，请不要关闭窗口…'),$this->url('payment','submit','id='.$insert_id),1);
    }
    public function submit_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('未指定支付订单ID'));
        }
        $log = $this->model('payment')->log_one($id);
        if(!$log){
            $this->error(P_Lang('订单信息不存在'));
        }
        if($this->session->val('user_id')!=$log['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        if($log['status']){
            $this->error(P_Lang('订单已支付，请勿重复提交'));
        }
        if($log['payment_id'] && is_numeric($log['payment_id'])){
            $payment_rs = $this->model('payment')->get_one($log['payment_id']);
            if(!$payment_rs){
                $this->error(P_Lang('支付方式不存在'));
            }
            if(!$payment_rs['status']){
                $this->error(P_Lang('支付方式未启用'));
            }
            $file = $this->dir_root.'gateway/payment/'.$payment_rs['code'].'/submit.php';
            if(!file_exists($file)){
                $tmpfile = str_replace($this->dir_root,'',$file);
                $this->error(P_Lang('支付接口异常，文件{file}不存在',array('file'=>$tmpfile)));
            }
            include($file);
            $name = $payment_rs['code']."_submit";
            $payment = new $name($log,$payment_rs);
            $payment->submit();
            exit;
        }
    }
    public function notice_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->error(P_Lang('执行异常，请检查，缺少参数ID'));
        }
        $rs = $this->model('payment')->log_one($id);
        if(!$rs){
            $this->error(P_Lang('订单信息不存在'),$this->url('index'));
        }
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        if($rs['type'] == 'order'){
            //$order = $this->model('order')->get_one_from_sn($rs['sn']);
            $url = $this->url('order','info','sn='.$rs['sn']);
        }elseif($rs['type'] == 'recharge'){
            $url = $this->url('usercp','index','sn='.$rs['sn']);
        }else{
            $url = $this->url('payment','show','id='.$id);
        }
        //同步通知
        if($rs['status']){
            $this->success(P_Lang('您的订单付款成功，请稍候…'),$url,1);
        }
        $payment_rs = $this->model('payment')->get_one($rs['payment_id']);
        $file = $this->dir_root.'gateway/payment/'.$payment_rs['code'].'/notice.php';
        if(!file_exists($file)){
            $tmpfile = str_replace($this->dir_root,'',$file);
            $this->error(P_Lang('支付接口异常，文件{file}不存在',array('file'=>$tmpfile)));
        }
        include($file);
        $name = $payment_rs['code'].'_notice';
        $cls = new $name($rs,$payment_rs);
        $cls->submit();
        $this->success(P_Lang('您的订单付款成功，请稍候…'),$url,1);
    }
    public function show_f()
    {
        $id = $this->get('id');
        if(!$id){
            $this->error(P_Lang('未指定ID'));
        }
        $rs = $this->model('payment')->log_one($id);
        if(!$rs){
            $this->error(P_Lang('数据不存在，请检查'));
        }
        if($this->session->val('user_id')!=$rs['user_id']){
            $this->error(P_Lang('非法操作'));
        }
        if($this->session->val('user_id')){
            $this->_location($this->url('usercp'));
        }else{
            $this->_location($this->url);
        }
    }
    //异步通知方案
    //考虑到异步通知存在读不到$_SESSION问题，使用sn和pass组合
    public function notify_f()
    {
        $sn = $this->get('sn');
        if(!$sn){
            exit('error');
        }
        if(strpos($sn,'-') !== false){
            $tmp = explode("-",$sn);
            $sn = $tmp[0];
            $rs = $this->model('payment')->log_one($tmp[1]);
        }else{
            $rs = $this->model('payment')->log_check_notstatus($sn);
        }
        if(!$rs){
            exit('error');
        }
        $payment_rs = $this->model('payment')->get_one($rs['payment_id']);
        if(!$payment_rs){
            exit('error');
        }
        if(!$payment_rs['status']){
            exit('error');
        }
        $file = $this->dir_root.'gateway/payment/'.$payment_rs['code'].'/notify.php';
        if(!file_exists($file)){
            exit('error');
        }
        include($file);
        $name = $payment_rs['code'].'_notify';
        $cls = new $name($rs,$payment_rs);
        $cls->submit();
        exit('success');
    }
}
?>