<?php
/*****************************************************************************************
	文件： admin/wealth_control.php
	备注： 财富规则管理
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2015年07月16日 08时13分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class wealth_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom('wealth');
		$this->assign("popedom",$this->popedom);
	}

	public function index_f()
	{
		$rslist = $this->model('wealth')->get_all();
		$this->assign('rslist',$rslist);
		$this->view('wealth_index');
	}

	public function info_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			error(P_Lang('未指定ID'),$this->url('wealth'),'error');
		}
		$rs = $this->model('wealth')->get_one($id);
		$this->assign('rs',$rs);
		$condition = "w.wid=".$id;
		$pageurl = $this->url('wealth','list','id='.$id);
		$keywords = $this->get('keywords');
		if($keywords){
			$condition .= " AND u.user LIKE '%".$keywords."%'";
			$pageurl .= "&keywords=".rawurlencode($keywords);
		}
		$total = $this->model('wealth')->info_total($condition);
		if($total){
			$psize = $this->config['psize'];
			$pageid = $this->get($this->config['pageid'],'int');
			if(!$pageid){
				$pageid = 1;
			}
			$offset = ($pageid-1) * $psize;
			$rslist = $this->model('wealth')->info_list($condition,$offset,$psize);
			$string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
			$string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
			$pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
			$this->assign('pagelist',$pagelist);
			$this->assign('rslist',$rslist);
			$this->assign('offset',$offset);
			$this->assign('psize',$psize);
		}
		$this->view('wealth_info');
	}

	public function log_f()
	{
		$wid = $this->get('wid','int');
		$uid = $this->get('uid','int');
		if(!$wid){
			error(P_Lang('未指定财富ID'),'','error');
		}
		if(!$uid){
			error(P_Lang('未指定会员ID'),'','error');
		}
		$rs = $this->model('wealth')->get_one($wid);
		$this->assign('rs',$rs);
		$pageurl = $this->url('wealth','log','wid='.$wid.'&uid='.$uid);
		$condition = "wid='".$wid."' AND goal_id='".$uid."' AND status=1";
		$total = $this->model('wealth')->log_total($condition);
		if($total){
			$psize = $this->config['psize'];
			$pageid = $this->get($this->config['pageid'],'int');
			if(!$pageid){
				$pageid = 1;
			}
			$offset = ($pageid-1) * $psize;
			$rslist = $this->model('wealth')->log_list($condition,$offset,$psize);
			$string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3&always=1';
			$pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
			$this->assign('pagelist',$pagelist);
			$this->assign('rslist',$rslist);
			$this->assign('offset',$offset);
			$this->assign('psize',$psize);
		}
		$this->view('wealth_log');
	}
    public function val_f()
	{
		$wid = $this->get('wid','int');
		$uid = $this->get('uid','int');
		if(!$wid){
			$this->json(P_Lang('未指定财富ID'));
		}
		if(!$uid){
			$this->json(P_Lang('未指定会员ID'));
		}
        $user = $this->model('user')->get_one($uid);
		$note = $this->get('note');
		$val = $this->get('val');
		if(!$val){
			$this->json(P_Lang('未指定数值'));
		}
		$val = abs($val);
		$type = $this->get('type');
		if($type && $type == '-'){
            $data = array('wid'=>$wid,'uid'=>$uid,'lasttime'=>$this->time);
            $user_val = $this->model('wealth')->get_val($uid,$wid);
            $data['val'] = round(($user_val-$val),2);
			$this->model('wealth')->save_info($data);
            $type = 'order';
            $method = '扣款';
		}else{
			$data = array('wid'=>$wid,'uid'=>$uid,'lasttime'=>$this->time);
			//更新统计
			$user_val = $this->model('wealth')->get_val($uid,$wid);
			if($user_val){
				$data['val'] = round(($user_val+$val),2);
			}else{
				$data['val'] = round($val,2);
			}
			$this->model('wealth')->save_info($data);
            $type = 'recharge';
            $method = '充值';
		}
        //充值记录
        $sn = $this->_create_sn();
        $title = $note ? P_Lang('管理员【'.$_SESSION["admin_account"].'】操作：').$note : P_Lang('管理员【'.$_SESSION["admin_account"].'】操作');
        if($wid==1){
            $type = 'point';
        }
        $array = array('sn'=>$sn,'payment_id'=>'10','type'=>$type,'title'=>$title,'content'=>$title,'status'=>'1');
        $array['dateline'] = $this->time;
        $array['user_id'] = $uid;
        $array['price'] = $val;
        $array['balance'] = $data['val'];
        $array['currency_id'] = $this->site['currency_id'];
		$array['admin_id'] = $_SESSION["admin_id"];
        $this->model('payment')->log_create($array);
		//$this->json(true);
        $tip = P_Lang('会员{user}后台{method}{money}元',array('user'=>$user['user'],'method'=>$method,'money'=>$val));
        $this->success($tip);
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
	public function set_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			if(!$this->popedom["add"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
		}else{
			if(!$this->popedom["modify"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$rs = $this->model('wealth')->get_one($id);
			$this->assign('rs',$rs);
			$this->assign('id',$id);
		}
		$this->view('wealth_set');
	}

	public function save_f()
	{
		$id = $this->get('id','int');
		$array = array();
		if($id){
			if(!$this->popedom['modify']){
				$this->json(P_Lang('您没有权限执行此操作'));
			}
		}else{
			if(!$this->popedom['add']){
				$this->json(P_Lang('您没有权限执行此操作'));
			}
			$array['site_id'] = $_SESSION['admin_site_id'];
		}
		$array['title'] = $this->get('title');
		if(!$array['title']){
			$this->json(P_Lang('名称不能为空'));
		}
		$array['identifier'] = $this->get('identifier');
		if(!$array['identifier']){
			$this->json(P_Lang('标识不能为空'));
		}
		$array['identifier'] = $this->format($array['identifier'],'system');
		if(!$array['identifier']){
			$this->json(P_Lang('标识不符合系统要求'));
		}
		$chk = $this->model('wealth')->chk_identifier($array['identifier'],$id);
		if($chk){
			$this->json(P_Lang('标识已被使用'));
		}
		$array['unit'] = $this->get('unit');
		if(!$array['unit']){
			$this->json(P_Lang('计量单位不能为空'));
		}
		$array['dnum'] = $this->get('dnum','int');
		$array['ifpay'] = $this->get('ifpay');
		if($array['ifpay']){
			$array['pay_ratio'] = $this->get('pay_ratio','float');
			if(!$array['pay_ratio']){
				$array['pay_ratio'] = 1;
			}
		}else{
			$array['pay_ratio'] = 0;
		}
		$array['ifcash'] = $this->get('ifcash');
		if($array['ifcash']){
			$array['cash_ratio'] = $this->get('cash_ratio','float');
			if(!$array['cash_ratio']){
				$array['cash_ratio'] = 1;
			}
		}else{
			$array['cash_ratio'] = 0;
		}
		$array['ifcheck'] = $this->get('ifcheck','int');
		$array['taxis'] = $this->get('taxis','int');
		$this->model('wealth')->save($array,$id);
		$this->json(true);
	}

	public function status_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		if(!$this->popedom['status']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$rs = $this->model('wealth')->get_one($id);
		$status = $rs['status'] ? 0 : 1;
		$this->model('wealth')->update_status($id,$status);
		$this->json($status,true);
	}

	public function delete_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		if(!$this->popedom['delete']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$this->model('wealth')->delete($id);
		$this->json(true);
	}

	public function rule_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			error(P_Lang('未指定ID'),$this->url('wealth'),'error');
		}
		if(!$this->popedom["setting"]){
			error(P_Lang('您没有权限执行此操作'),$this->url('wealth'),'error');
		}
		$rs = $this->model('wealth')->get_one($id);
		$this->assign('rs',$rs);
		$rslist = $this->model('wealth')->rule_all("wid='".$id."'");
		$this->assign('rslist',$rslist);
		$alist = array('register'=>P_Lang('会员注册'),'login'=>P_Lang('会员登录'),'payment'=>P_Lang('购物付款'));
		$alist['content'] = P_Lang('分享链接');
		$alist['comment'] = P_Lang('评论文章');
		$alist['post'] = P_Lang('发布文章');
		$alist['content'] = P_Lang('阅读文章');
		$this->assign('alist',$alist);
		$repeatlist = array(0=>P_Lang('不支持重复'),1=>P_Lang('重复一次'),2=>P_Lang('重复两次'),3=>P_Lang('重复三次'));
		$this->assign('repeatlist',$repeatlist);
		$this->view('wealth_rule');
	}

	public function delete_rule_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		if(!$this->popedom['setting']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$this->model('wealth')->delete_rule($id);
		$this->json(true);
	}

	public function save_rule_f()
	{
		$wid = $this->get('wid','int');
		if(!$wid){
			$this->json(P_Lang('未指定ID'));
		}
		if(!$this->popedom['setting']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$action = $this->get('action');
		if(!$action){
			$this->json(P_Lang('动作未指定'));
		}
		$repeat = $this->get('repeat','int');
		if($repeat){
			$mintime = $this->get('mintime','int');
			if(!$mintime){
				$mintime = 30;
			}
		}else{
			$mintime = 0;
		}
		$val = $this->get('val');
		$goal = $this->get('goal');
		$efunc = $this->get('efunc');
		$taxis = $this->get('taxis','int');
		$linkid = $this->get('linkid','int');
		$data = array('wid'=>$wid,'action'=>$action,'repeat'=>$repeat,'mintime'=>$mintime,'val'=>$val,'goal'=>$goal,'efunc'=>$efunc,'taxis'=>$taxis);
		$data['linkid'] = $linkid;
		$this->model('wealth')->save_rule($data);
		$this->json(true);
	}

	public function notcheck_f()
	{
		$pageurl = $this->url('wealth','notcheck');
		$condition = "l.status=0";
		$total = $this->model('wealth')->log_total_notcheck($condition);
		if($total){
			$psize = $this->config['psize'];
			$pageid = $this->get($this->config['pageid'],'int');
			if(!$pageid){
				$pageid = 1;
			}
			$offset = ($pageid-1) * $psize;
			$rslist = $this->model('wealth')->log_list_notcheck($condition,$offset,$psize);
			$string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3&always=1';
			$pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
			$this->assign('pagelist',$pagelist);
			$this->assign('rslist',$rslist);
			$this->assign('offset',$offset);
			$this->assign('psize',$psize);
		}
		$this->view('wealth_notcheck');
	}

	public function action_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$action = $this->get('action');
		if($action == 'ok'){
			$this->model('wealth')->setok($id);
		}else{
			$this->model('wealth')->log_delete($id);
		}
		$this->json(true);
	}
    public function action_user_f()
    {
        $id = $this->get('wid','int');
        if(!$id){
            $this->error(P_Lang('未指定ID'));
        }
        $uid = $this->get('uid','int');
        if(!$uid){
            $this->error(P_Lang('未指定会员ID'));
        }
        $rs = $this->model('wealth')->get_one($id);
        $this->assign('rs',$rs);
        $this->assign('uid',$uid);
        $backurl = $this->get('backurl');
        $this->assign('backurl',$backurl);
        $this->view("wealth_action");
    }
}

?>