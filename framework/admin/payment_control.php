<?php
/*****************************************************************************************
	文件： admin/payment_control.php
	备注： 支付管理工具，用于管理接口信息
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2014年4月22日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class payment_control extends dsy_control
{
	public $popedom;
	
	function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("payment");
		$this->assign("popedom",$this->popedom);
	}

	//读取所有可用的支付接口
	function index_f()
	{
		if(!$this->popedom["list"]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		//取得符合要求的全部组
		$rslist = $this->model('payment')->group_all($_SESSION['admin_site_id']);
		if($rslist)
		{
			foreach($rslist AS $key=>$value)
			{
				$rslist[$key]['paylist'] = $this->model('payment')->get_all("p.gid=".$value['id']);
			}
			$this->assign('rslist',$rslist);
		}
		$codelist = $this->model('payment')->code_all();
		$this->assign('codelist',$codelist);
		
		$this->view('payment_index');
	}

	//设置支付组信息
	function groupset_f()
	{
		$id = $this->get('id','int');
		if($id){
			if(!$this->popedom["groupedit"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$rs = $this->model('payment')->group_one($id);
			$this->assign('rs',$rs);
			$this->assign('id',$id);
		}else{
			if(!$this->popedom["groupadd"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
		}
		$this->view('payment_groupset');
	}

	//存储支付组信息
	function groupsave_f()
	{
		$id = $this->get('id','int');
		$popedom_id = $id ? 'groupedit' : 'groupadd';
		if(!$this->popedom[$popedom_id]){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$title = $this->get('title');
		if(!$title)
		{
			$this->json(P_Lang('名称不能为空'));
		}
		$data = array('site_id'=>$_SESSION['admin_site_id'],'title'=>$title);
		$data['taxis'] = $this->get('taxis','int');
		$data['status'] = $this->get('status','int');
		$data['is_wap'] = $this->get('is_wap','int');
		$insert = $this->model('payment')->groupsave($data,$id);
		if(!$insert){
			$tip = $id ? P_Lang('编辑失败') : P_Lang('添加失败');
			$this->json($tip);
		}
		if($id){
			$insert = $id;
		}
		$default = $this->get('is_default','int');
		if($default){
			$this->model('payment')->group_set_default($insert,$_SESSION['admin_site_id']);
		}
		$this->json(true,true);
	}


	//删除所有支付组
	function groupdel_f()
	{
		if(!$this->popedom['groupdelete']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$rslist = $this->model('payment')->get_all("p.gid='".$id."'");
		if($rslist){
			$this->json(P_Lang('已存在支付方案，请先移除'));
		}
		$this->model('payment')->group_delete($id);
		$this->json(P_Lang('删除成功'),true);
	}
	
	public function set_f()
	{
		$gid = $this->get('gid','int');
		$id = $this->get('id','int');
		$code = $this->get('code');
		if($id)
		{
			$rs = $this->model('payment')->get_one($id);
			$gid = $rs['gid'];
			$code = $rs['code'];
			if($rs['param'])
			{
				$rs['param'] = unserialize($rs['param']);
			}
			$this->assign('rs',$rs);
			$this->assign('id',$id);
		}
		if(!$code)
		{
			error(P_Lang('未指定支付接口'),$this->url('payment'),'error');
		}
		$this->assign('gid',$gid);
		$this->assign('code',$code);
		//读取支付组
		$grouplist = $this->model('payment')->group_all($_SESSION['admin_site_id']);
		$this->assign('grouplist',$grouplist);
		//扩展信息
		$extlist = $this->model('payment')->code_one($code);
		$this->assign('extlist',$extlist);
		$this->lib('form')->cssjs();
		//可使用的货币列表
		$currency_list = $this->model('currency')->get_list();
		$this->assign("currency_list",$currency_list);
		$this->lib('form')->cssjs(array('form_type'=>'editor'));
		$this->view('payment_set');
	}

	//存储支付方案
	public function save_f()
	{
		$gid = $this->get('gid','int');
		$code = $this->get('code');
		$id = $this->get('id','int');
		if($id)
		{
			$rs = $this->model('payment')->get_one($id);
			$gid = $rs['gid'];
			$code = $rs['code'];
		}
		if(!$gid)
		{
			error(P_Lang('未指定支付组'),$this->url('payment'),'error');
		}
		if(!$code)
		{
			error(P_Lang('未指定支付接口'),$this->url('payment'),'error');
		}
		$error_url = $id ? $this->url('payment','set','id='.$id) : $this->url('payment','set','gid='.$gid."&code=".$code);
		$codeinfo = $this->model('payment')->code_one($code);
		$title = $this->get('title');
		if(!$title)
		{
			error(P_Lang('支付名称不能为空'),$error_url,'error');
		}
		$data = array('title'=>$title,'code'=>$code,'gid'=>$gid);
		$data['currency'] = $this->get("currency");
		$data['logo1'] = $this->get('logo1');
		$data['logo2'] = $this->get('logo2');
		$data['logo3'] = $this->get('logo3');
		$data['taxis'] = $this->get('taxis','int');
		$data['status'] = $this->get('status','int');
		$data['wap'] = $this->get('wap','int');
		$data['note'] = $this->get('note','html');
		//读取扩展信息
		if($codeinfo['code'] && is_array($codeinfo['code']))
		{
			$ext = array();
			foreach($codeinfo['code'] AS $key=>$value)
			{
				if($value['type'] != 'checkbox')
				{
					$ext[$key] = $this->get($code."_".$key);
				}
				else
				{
					$tmp = array();
					foreach($value['option'] AS $k=>$v)
					{
						$tmp_name = $code."_".$k;
						if(isset($_POST[$tmp_name]))
						{
							$tmp[] = $k;
						}
					}
					$ext[$key] = implode(",",$tmp);
				}
			}
			$data['param'] = serialize($ext);
		}
		$this->model('payment')->save($data,$id);
		$tip = $id ? P_Lang('编辑成功') : P_Lang('添加成功');
		error($tip,$this->url('payment'),'ok');
	}

	public function delete_f()
	{
		if(!$this->popedom['delete']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$this->model('payment')->delete($id);
		$this->json(P_Lang('删除成功'),true);
	}

	public function taxis_f()
	{
		$id = $this->get('id','int');
		$type = $this->get('type');
		$taxis = $this->get('taxis','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		if($type == 'group'){
			$this->model('payment')->groupsave(array('taxis'=>$taxis),$id);
		}else{
			$this->model('payment')->save(array('taxis'=>$taxis),$id);
		}
		$this->json(true);
	}
    public function show_f()
    {
        if(!$this->popedom["show"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config["pageid"],"int");
        if(!$pageid) $pageid = 1;
        $psize = $this->config["psize"];
        if(!$psize) $psize = 30;
        //$page_url = $this->url("payment","show");
        $pageurl = $this->url('payment','show');
        $offset = ($pageid-1) * $psize;
        if($_SESSION["admin_rs"]["if_system"]){
			$condition = "status > 0";
		}else{
			$condition = "status > 0 and admin_id=".$_SESSION['admin_id'];
		}
        $type = $this->get("type");
        if($type){
            $condition .= " AND type='".$type."'";
            $pageurl .= "&type=".rawurlencode($type);
        }
        $this->assign('type',$type);
        $method = $this->get("method");
        if($method){
            $condition .= " AND paymentMethod='".$method."'";
            $pageurl .= "&paymentMethod=".rawurlencode($method);
            $this->assign('method',$method);
        }
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
        $user = trim($this->get('user'));
        if($user){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
            $pageurl .= "&user=".rawurlencode($user);
            $this->assign('user',$user);
        }
        $ucode = trim($this->get('ucode'));
        if($ucode){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
            $pageurl .= "&ucode=".rawurlencode($ucode);
            $this->assign('ucode',$ucode);
        }
        $total = $this->model('payment')->get_count($condition);
        if($total>0){
            $rslist = $this->model('payment')->get_list($condition,$offset,$psize);
            if(!$rslist) $rslist = array();
			//$yePrice = $xfPrice = $czPrice = 0;
            foreach($rslist AS $key=>$value){
                $value['user'] = $this->model('user')->get_one($value['user_id']);
                $value['order'] = $this->model('order')->get_one($value['order_id']);
                $value['channel'] = $this->model('channel')->get_one($value['channel_id']);
                $value['adm'] = $this->model('admin')->get_one($value['admin_id']);
                $rslist[$key] = $value;
				/*if($value['type']=='recharge'){
                    $czPrice += $value['price'];
                }
                if($value['type']=='order'){
                    $xfPrice += $value['price'];
                }
				$val = $this->model('wealth')->get_val($value['user_id'],2);
                $yePrice += $val;*/
            }
			/*$this->assign('yePrice',$yePrice);
            $this->assign('czPrice',$czPrice);
            $this->assign('xfPrice',$xfPrice);*/
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
        //交易类型
        $typelist = $this->model('payment')->type();
        $this->assign('typelist',$typelist);
        //支付方式
        $paymentMethod = $this->model('payment')->paymentMethod();
        $this->assign('paymentMethod',$paymentMethod);
        //获取默认货币单位
        $currency = $this->model('currency')->get_one($this->site['currency_id']);
        $this->assign('currency',$currency);
        $this->view('payment_show');
    }
    public function bank_set_f()
    {
        if(!$this->popedom['bank']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('bank')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $this->view('bank_set');
    }
    public function bank_list_f()
    {
        if(!$this->popedom['bank']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $offset = ($pageid-1)*$psize;
        $pageurl = $this->url('payment','bank_list');
        $condition = " 1=1";
        $user = $this->get('user');
        if($user){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
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
        $total = $this->model('bank')->get_count($condition);
        if($total>0){
            $rslist = $this->model('bank')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['user'] =  $this->model('user')->get_one($value['user_id']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
            $this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
        }
        $this->view('bank_list');
    }
    public function bank_delete_f()
    {
        if(!$this->popedom['bank']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $id = $this->get("id",'int');
        if(!$id){
            $this->json('未指定ID');
        }
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $this->model('bank')->del($id);
        $this->json("ok",true);
    }
    public function setok_f()
    {
        $id = $this->get('id','int');
        $rs = $this->model('bank')->get_one($id);
        if(!$rs || $rs['status']==1) error(P_Lang('非法操作'),$this->url('payment','bank_list'),'error',10);
        $user_id = $this->get('user_id','int');
        $array = array();
        $array["money"] = $this->get("money");
        $array["status"] = $this->get("status","int");
        $this->model('bank')->save($array,$id);
        if($array["status"]==1){
            //$currency = $this->model('currency')->get_one(1);//人民币汇率
            //$val = round(($array["money"] / $currency['val']),2);
            //转换后台设置默认货币
            $price = price_format_val($array["money"],1,$this->site['currency_id']);
            $val = $this->model('wealth')->get_val($user_id,2);
            $data = array('wid'=>'2','uid'=>$user_id,'lasttime'=>$this->time);
            $data['val'] = round(($val+$price),2);
            $this->model('wealth')->save_info($data);
            $sn = uniqid('CZ');
            $type = 'recharge';
            if($rs['type']=='yh'){
                $title = '银行转账';
                $payment_id = 7;
            }else{
                $title = '扫码充值';
                $payment_id = 11;
            }
            $arr = array('sn'=>$sn,'type'=>$type,'payment_id'=>$payment_id,'title'=>$title,'content'=>$title,'status'=>'1');
            $arr['dateline'] = $this->time;
            $arr['user_id'] = $user_id;
            $arr['price'] = $array["money"];
            $arr['balance'] = $data['val'];
            $arr['currency_id'] = 1;
			$arr['admin_id'] = $_SESSION["admin_id"];
            $this->model('payment')->log_create($arr);
        }
        error('会员转账入款成功！',$this->url("payment","bank_list"));
    }
    /*private function _create_sn()
    {
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_str = '';
        for($i=0;$i<3;$i++){
            $rand_str .= $a[rand(0,25)];
        }
        $rand_str .= rand(1000,9999);
        $rand_str .= date("YmdHis",$this->time);
        return $rand_str;
    }*/
	/*public function export_f(){
        $this->view('payment_export');
    }*/
    public function export_data_f()
    {
        $error_url = $this->url("payment","export");
        $ext = array("sn","type","price","unit","balance","user","ucode","title","dateline");
        $condition = " 1=1";
        $type = $this->get("type");
        if($type){
            $condition .= " AND type='".$type."'";
        }
        $sn = $this->get('sn');
        if($sn){
            $condition .= " AND sn = '".$sn."'";
        }
        $user = $this->get('user');
        if($user){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE user = '".$user."')";
        }
        $ucode = $this->get('ucode');
        if($ucode){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
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
        $first_list = array("sn"=>"支付编号","type"=>"交易类型","price"=>"金额","unit"=>"单位","balance"=>"余额","user"=>"用户名","ucode"=>"用户编号","title"=>"主题","dateline"=>"记录时间");
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
                if($v=='unit'){
                    $currency = $this->model('currency')->get_one($value['currency_id']);
                    $val = $currency['title'];
                }
                if($v=='user'){
                    $user = $this->model('user')->get_one($value['user_id'],id,false,false);
                    $val = $user['user'];
                }
                if($v=='ucode'){
                    $user = $this->model('user')->get_one($value['user_id'],id,false,false);
                    $val = $user['ucode'];
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
}

?>