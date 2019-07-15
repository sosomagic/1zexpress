<?php
/***********************************************************
	Filename: /www/usercp_control.php
	Note	: 用户控制面板
	Version : 2.0
	Author  : dsaiyin
	Update  : 2016年05月16日 11时14分
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class usercp_control extends dsy_control
{
    public $group_rs;
    private $user;
    public function __construct()
    {
        parent::control();
        $user_id = $this->session->val('user_id');
        if(!$user_id){
            $errurl = $this->url('login','',$this->url('usercp'));
            $this->error(P_Lang('未登录会员不能执行此操作'),$errurl);
        }
        $this->user = $this->model('user')->get_one($user_id);
        $this->group_rs = $this->model('usergroup')->group_rs($user_id);
        if(!$this->group_rs){
            $this->error(P_Lang('您的账号有异常：无法获取相应的会员组信息，请联系管理员'));
        }
    }
	//会员个人中心
	public function index_f()
	{
        $user = $this->model('user')->get_one($this->session->val('user_id'));
        $this->assign('rs',$user);
		$this->assign('group',$this->group_rs);
        $user_id = $this->session->val('user_id');
        $stock = $this->model('stock')->get_stockList();
        $this->assign('stocks',$stock);
        $weiruku = $this->model('package')->get_count("user_id=".$user_id." and status='unstored'");
        $this->assign('weiruku',$weiruku);
        $yiruku = $this->model('package')->get_count("user_id=".$user_id." and status='stored'");
        $this->assign('yiruku',$yiruku);
        $yishengcheng = $this->model('package')->get_count("user_id=".$user_id." and status='generated'");
        $this->assign('yishengcheng',$yishengcheng);
        $create = $this->model('order')->get_count("user_id=".$user_id." and status='create' and remove=0");
        $this->assign('create',$create);
        $unpaid = $this->model('order')->get_count("user_id=".$user_id." and status='unpaid' and remove=0");
        $this->assign('unpaid',$unpaid);
        $paid = $this->model('order')->get_count("user_id=".$user_id." and status='paid' and remove=0");
        $this->assign('paid',$paid);
        $shipped = $this->model('order')->get_count("user_id=".$user_id." and status='shipped' and remove=0");
        $this->assign('shipped',$shipped);
        $received = $this->model('order')->get_count("user_id=".$user_id." and status='received' and remove=0");
        $this->assign('received',$received);
        $delivery = $this->model('delivery')->get_count("user_id=".$user_id." and status=0");
        $this->assign('delivery',$delivery);
        //前台会员中心网站公告
        $rslist = $this->model('list')->notice_list('cate_id=8 and status=1');
        $this->assign('rslist',$rslist);
        //会员中心弹出公告
        $notice = $this->model('list')->get_notice();
        $this->assign('notice',$notice);
        $currency = $this->model('currency')->get_one(1);
        $this->assign('currency',$currency);
        //发货统计
        $year = $this->get('year');
        if(!$year){
            $year = date('Y');
        }
        $condition = "FROM_UNIXTIME(addtime,'%Y')='".$year."' and (status ='paid' or status ='shipped' or status ='received' or status ='finished') and user_id=".$user_id." and remove=0";
        $this->assign('year',$year);
        $rslist = $this->model('order')->statistical($condition);
        for($i=1;$i<=12;$i++){
            $k=0;
            foreach($rslist as $key => $value){
                if($i==$value['month']){
                    $k=1;
                    $sumprice[] = floatval($value['sumprice']);
                    $sumweight[] = floatval($value['sumweight']);
                    $sumchannelprice[] = floatval($value['sumchannelprice']);
                }
            }
            if($k==0){
                $sumprice[] = 0;
                $sumweight[] = 0;
                $sumchannelprice[] = 0;
            }
        }
        $price = json_encode($sumprice);
        $weight = json_encode($sumweight);
        $sumchannelprice = json_encode($sumchannelprice);
        $this->assign('price',$price);
        $this->assign('weight',$weight);
        $this->assign('sumchannelprice',$sumchannelprice);
        //财务统计
		$this->assign('balance',$balance);
        $starttime = $this->get('starttime');
        if(!$starttime){
           $endtime = date('Y-m-d',$this->time);
           $begintime = date('Y-m-d',($this->time-6*24*3600));
           $starttime = $begintime.' - '.$endtime;
        }
        $this->assign('starttime',$starttime);
        $str1 = substr($starttime,0,10);
        $str2 = substr($starttime,-10);
        $count = (strtotime($str2)-strtotime($str1))/(24*3600);
		$count = $count+1;
        if($count>7){
            error(P_Lang('时间周期不能超过7天'),$this->url('usercp'),'error');
        }
        $dayArr[0] = strtotime($str1);
        for($i=1;$i<$count;$i++){
            $dayArr[$i] = $dayArr[0] + 24*3600*$i;
        }
        foreach($dayArr as $key =>$value){
            $value = floatval($value);
            $newDay[] = date('m-d',$value);
			$tmpDay[] = date('j',$value);
			$tmpYear[] = date('Y-m-d',$value);
        }
		//创建x轴
        $condition = "FROM_UNIXTIME(dateline,'%Y-%m-%d') between '".$str1."' and '".$str2."' and status=1 and user_id=".$user_id." and type='order'";
        $list = $this->model('payment')->statistical($condition);
		foreach($tmpDay as $key =>$value){
			$i=0;
            foreach($list as $k => $v){
                if($value==$v['day']){
                    $i=1;
                    $orderPrice[] = floatval($v['orderPrice']);
					$balancePrice[] = floatval($v['balanceprice']);
                }
            }
            if($i==0){
				$balance = $this->model('payment')->balance_val($user_id,$tmpYear[$key]);
				if($balance){
					$balancePrice[] = floatval($balance);
				}else{
					$balance2 = $this->model('payment')->balance_val2($user_id,$tmpYear[$key]);
					$balancePrice[] = floatval($balance2);
				}
                $orderPrice[] = 0;
				
            }
		}
        $newDay = json_encode($newDay);
        $this->assign('newDay',$newDay);
        $orderPrice = json_encode($orderPrice);
        $this->assign('orderPrice',$orderPrice);
		$balancePrice = json_encode($balancePrice);
        $this->assign('balancePrice',$balancePrice);
        $this->view('usercp');
	}

	//修改个人资料
	public function info_f()
	{
        $rs = $this->model('user')->get_one($this->session->val('user_id'));
        $this->assign("rs",$rs);
        $this->view("usercp_info");
	}
	//修改密码
	public function passwd_f()
	{
		$this->view("usercp_passwd");
	}
	//获取项目列表
	public function list_f()
	{
		$id = $this->get("id");
		if(!$id){
			error(P_Lang('未指定项目'),$this->url('usercp'),'notice',10);
		}
		$this->assign('id',$id);
		$pid = $this->model('id')->project_id($id,$this->site['id']);
		if(!$pid){
			error(P_Lang('项目信息不存在'),$this->url('usercp'),'error');
		}
		if(!$this->model('popedom')->check($pid,$this->group_rs['id'],'post')){
			error(P_Lang('您没有这个权限功能，请联系网站管理员'),$this->url('usercp'),'error');
		}
		$project_rs = $this->model('project')->get_one($pid);
		if(!$project_rs || !$project_rs['status']){
			error(P_Lang('项目不存在或未启用'),$this->url('usercp'),'error');
		}
		$tplfile = 'usercp_'.$id;
		$tplfile.= $project_rs['module'] ? '_list' : '_page';
		//非列表项目直接指定
		$this->assign("page_rs",$project_rs);
		if(!$project_rs['module']){
			$this->view($tplfile);
			exit;
		}
		$dt = array('pid'=>$project_rs['id'],'user_id'=>$_SESSION['user_id']);
		//读取符合要求的内容
		$pageurl = $this->url('usercp','list','id='.$id);
		$pageid = $this->get($this->config['pageid'],'int');
		if(!$pageid) $pageid = 1;
		$psize = $project_rs['psize'] ? $project_rs['psize'] : $this->config['psize'];
		if(!$psize){
			$psize = 20;
		}
		$offset = ($pageid-1) * $psize;
		$tpl = $this->get('tpl');
		if($tpl){
			$pageurl .= "&tpl=".rawurlencode($tpl);
			$tplfile = $tpl;
		}
		$dt['psize'] = $psize;
		$dt['offset'] = $offset;
		$keywords = $this->get('keywords');
		if($keywords){
			$dt['keywords'] = $keywords;
			$pageurl .= "&keywords=".$keywords;
			$this->assign("keywords",$keywords);
		}
		$dt['not_status'] = 1;
		$status = $this->get('status');
		if($status){
			if($status == 1){
				$dt['sqlext'] = "l.status=1";
			}else{
				$dt['sqlext'] = "l.status=0";
			}
		}
		
		$dt['is_list'] = true;
		$list = $this->call->dsy('_arclist',$dt);
		if($list['total']){
			$this->assign("pageid",$pageid);
			$this->assign("psize",$psize);
			$this->assign("pageurl",$pageurl);
			$this->assign("total",$list['total']);
			$this->assign("rslist",$list['rslist']);
		}
		if(!$this->tpl->check_exists($tplfile)){
			$tplfile = "usercp_list";
		}
		$this->view($tplfile);
	}

	//收货地址管理
	public function address_f()
	{
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $condition = "user_id='".$_SESSION['user_id']."'";
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('usercp','address');
        $this->assign('pageurl',$pageurl);
        $fullname = trim($this->get("fullname"));
        if($fullname){
            $condition .= " AND fullname like '%".$fullname."%'";
            $this->assign('fullname',$fullname);
        }
        $mobile = trim($this->get("mobile"));
        if($mobile){
            $condition .= " AND mobile like '%".$mobile."%'";
            $this->assign('mobile',$mobile);
        }
		$idcard = trim($this->get("idcard"));
        if($idcard){
            $condition .= " AND idcard like '%".$idcard."%'";
            $this->assign('idcard',$idcard);
        }
        $total = $this->model('address')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('user')->address($condition,$offset,$psize);
            $this->assign('rslist',$rslist);
        }
        $this->view("usercp_address");
	}

	public function address_setting_f()
	{
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('user')->address_one($id);
            if(!$rs || $rs['user_id'] != $_SESSION['user_id']){
                $this->error(P_Lang('地址信息不存在或您没有权限修改此地址'));
            }
            $this->assign('id',$id);
            $this->assign('rs',$rs);
        }
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
             //$idlist[] = strtolower($value["identifier"]);
             if($rs[$value["identifier"]]){
                 $value["content"] = $rs[$value["identifier"]];
             }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $country = $this->model('address')->country();
        $this->assign('country',$country);
		$this->view('usercp_address_setting');
	}
    public function address_setting1_f()
    {
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('user')->address_one($id);
            if(!$rs || $rs['user_id'] != $_SESSION['user_id']){
                $this->error(P_Lang('地址信息不存在或您没有权限修改此地址'));
            }
            $this->assign('id',$id);
            $this->assign('rs',$rs);
        }
		$tid = $this->get('tid','int');
		$this->assign('tid',$tid);
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
            //$idlist[] = strtolower($value["identifier"]);
            if($rs[$value["identifier"]]){
                $value["content"] = $rs[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $country = $this->model('address')->country();
        $this->assign('country',$country);
        $this->view('usercp_address_setting1');
    }
    //导入收件人
    public function address_import_f()
    {
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $country = $this->model('address')->country();
        $this->assign('country',$country);
        $this->view('address_import');
    }
    //发件人管理
    public function sender_f()
    {
        $rslist = $this->model('user')->sender($_SESSION['user_id']);
        if($rslist){
            $this->assign('rslist',$rslist);
            $this->assign('total',count($rslist));
        }
        //$this->assign('rs',$this->user);
        $this->view("usercp_sender");
    }
    public function sender_setting_f()
    {
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('user')->sender_one($id);
            if(!$rs || $rs['user_id'] != $_SESSION['user_id']){
                $this->error(P_Lang('地址信息不存在或您没有权限修改此地址'));
            }
            $this->assign('id',$id);
            $this->assign('rs',$rs);
        }else{
            $rs = array();
        }
        $this->view('usercp_sender_setting');
    }
    public function avatar_f()
    {
        $this->assign('rs',$this->user);
        $tplfile = $this->model('site')->tpl_file($this->ctrl,$this->func);
        if(!$tplfile){
            $tplfile = 'usercp_avatar';
        }
        $this->view($tplfile);
    }

    public function avatar_cut_f()
    {
        $id = $this->get('thumb_id','int');
        $x1 = $this->get("x1");
        $y1 = $this->get("y1");
        $x2 = $this->get("x2");
        $y2 = $this->get("y2");
        $w = $this->get("w");
        $h = $this->get("h");
        $rs = $this->model('res')->get_one($id);
        $new = $rs["folder"]."_tmp_".$id."_.".$rs["ext"];
        if($rs['attr']['width'] > 500){
            $beis = round($rs['attr']['width']/500,2);
            $w = round($w * $beis);
            $h = round($h * $beis);
            $x1 = round($x1 * $beis);
            $y1 = round($y1 * $beis);
            $x2 = round($x2 * $beis);
            $y2 = round($y2 * $beis);
        }
        $cropped = $this->create_img($new,$this->dir_root.$rs["filename"],$w,$h,$x1,$y1,1);
        $this->lib('file')->mv($this->dir_root.$new,$this->dir_root.$rs['filename']);
        $this->model('user')->update_avatar($rs['filename'],$_SESSION['user_id']);
        $this->json(true);
    }

    private function create_img($thumb_image_name, $image, $width, $height, $x1, $y1,$scale=1)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        switch($imageType) {
            case "image/gif":
                $source=imagecreatefromgif($image);
                break;
            case "image/pjpeg":
                $source=imagecreatefromjpeg($image);
                break;
            case "image/jpeg":
                $source=imagecreatefromjpeg($image);
                break;
            case "image/jpg":
                $source=imagecreatefromjpeg($image);
                break;
            case "image/png":
                $source=imagecreatefrompng($image);
                break;
            case "image/x-png":
                $source=imagecreatefrompng($image);
                break;
        }
        $nWidth = ceil($width * $scale);
        $nHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($nWidth,$nHeight);
        imagecopyresampled($newImage,$source,0,0,$x1,$y1,$nWidth,$nHeight,$width,$height);
        switch($imageType) {
            case "image/gif":
                imagegif($newImage,$thumb_image_name);
                break;
            case "image/pjpeg":
                imagejpeg($newImage,$thumb_image_name,100);
                break;
            case "image/jpeg":
                imagejpeg($newImage,$thumb_image_name,100);
                break;
            case "image/jpg":
                imagejpeg($newImage,$thumb_image_name,100);
                break;
            case "image/png":
                imagepng($newImage,$thumb_image_name);
                break;
            case "image/x-png":
                imagepng($newImage,$thumb_image_name);
                break;
        }
        return $thumb_image_name;
    }
    //手机端
    /*public function wap_f()
    {
        $this->view('setting');
    }*/
    public function set_f()
    {
        $rs = $this->model('user')->get_one($this->session->val('user_id'));
        $this->assign("rs",$rs);
        //设置默认仓库，渠道
        $stockList = $this->model('stock')->get_stockList();
        foreach($stockList as $key => $value){
            $stock[]= array('id'=>$value['id'],'value'=>$value['title']);
        }
        $stock = $this->encode_json($stock);
        $this->assign('stock',$stock);
        $this->assign('stockCount',count($stock));
        $channelList=$this->model('channel')->get_channelList();
        foreach($channelList as $key => $value){
            $channel[]= array('id'=>$value['id'],'value'=>$value['title']);
        }
        $channel = $this->encode_json($channel);
        $this->assign('channel',$channel);
        $this->assign('channelCount',count($channel));
        $this->view('usercp_set');
    }
    ////解决PHP5.2乱码
    /*private function encode_json($array){
        if (version_compare(PHP_VERSION, '5.4.0', '<')){
            $str = json_encode($array);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function ($matchs) {
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1])); }, $str);
            return $str;
        }else{
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }*/
    //手机端end
}
?>