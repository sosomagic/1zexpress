<?php
/***********************************************************
	Filename: admin/index_control.php
	Note	: 后台首页控制台
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2012-10-19 13:03
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class index_control extends dsy_control
{
	function __construct()
	{
		parent::control();
	}
    public function index_f()
    {
        $this->view('index');
    }
	public function main_f()
	{
		$sitelist = $this->model('site')->get_all_site();
		if(!$_SESSION['admin_rs']['if_system']){
			foreach($sitelist as $key=>$value){
				$chk_popedom = $this->model('popedom')->site_popedom($value['id'],$_SESSION['admin_id']);
				if(!$chk_popedom){
					unset($sitelist[$key]);
				}
			}
			/*if(!$sitelist){
				error(P_Lang('没有找到相关权限，请联系管理员'));
			}*/
			$wh = " and remove=0 and o.stock_id in(".$_SESSION["admin_stock"].")";
		}else{
			$wh =" and remove=0";
		}
		$this->assign('sitelist',$sitelist);
        $weiruku = $this->model('package')->get_count("status='unstored'");
        $this->assign('weiruku',$weiruku);
        $yiruku = $this->model('package')->get_count("status='stored'");
        $this->assign('yiruku',$yiruku);
        $yishengcheng = $this->model('package')->get_count("status='generated'");
        $this->assign('yishengcheng',$yishengcheng);
        $create = $this->model('order')->get_count("status='create'".$wh);
        $this->assign('create',$create);
        $unpaid = $this->model('order')->get_count("status='unpaid'".$wh);
        $this->assign('unpaid',$unpaid);
        $paid = $this->model('order')->get_count("status='paid'".$wh);
        $this->assign('paid',$paid);
        $shipped = $this->model('order')->get_count("status='shipped'".$wh);
        $this->assign('shipped',$shipped);
        $received = $this->model('order')->get_count("status='received'".$wh);
        $this->assign('received',$received);
        $delivery = $this->model('delivery')->get_count("status=0");
        $this->assign('delivery',$delivery);
        $code = $this->model('code')->get_count("status=0");
        $this->assign('code',$code);
        $number = $this->model('number')->get_count("status=0");
        $this->assign('number',$number);
		$wenti = $this->model('book')->get_count("status=0");
        $this->assign('wenti',$wenti);
		$this->view("main");
	}

	public function all_setting_f()
	{
		$info = $this->all_info();
		if(!$info){
			$this->json(false);
		}
		$this->json($info,true);
	}
	private function all_info()
	{
		$all_popedom = appfile_popedom("all");
		if(!$all_popedom || !$all_popedom['list']){
			return false;
		}
		$this->assign('all_popedom',$all_popedom);
		$site_popedom = appfile_popedom('site');
		$this->assign('site_popedom',$site_popedom);
		$rslist = $this->model('site')->all_list($_SESSION["admin_site_id"]);
		$this->assign("all_rslist",$rslist);
		$rs = $this->model('site')->get_one($_SESSION['admin_site_id']);
		$this->assign("all_rs",$rs);
		return $this->fetch('index_block_allsetting');
	}

	public function list_setting_f()
	{
		$info = $this->list_setting();
		if(!$info){
			$this->json(false);
		}
		$this->json($info,true);
	}

	private function list_setting()
	{
		$site_id = $_SESSION["admin_site_id"];
		$rslist = $this->model('project')->get_all($site_id,0,"p.status=1 AND p.hidden=0");
		if(!$rslist){
			$rslist = array();
		}
		if(!$_SESSION["admin_rs"]["if_system"]){
			if(!$_SESSION["admin_popedom"]){
				return false;
			}
			$condition = "parent_id>0 AND appfile='list' AND func=''";
			$p_rs = $this->model('sysmenu')->get_one_condition($condition);
			if(!$p_rs){
				return false;
			}
			$gid = $p_rs["id"];
			$popedom_list = $this->model('popedom')->get_all("gid='".$gid."' AND pid>0",false,false);
			if(!$popedom_list){
				return false;
			}
			$popedom = array();
			foreach($popedom_list AS $key=>$value){
				if(in_array($value["id"],$_SESSION["admin_popedom"])){
					$popedom[$value["pid"]][$value["identifier"]] = true;
				}
			}
			foreach($rslist AS $key=>$value){
				if(!$popedom[$value["id"]] || !$popedom[$value["id"]]["list"]){
					unset($rslist[$key]);
					continue;
				}
			}
		}
		if(!$rslist || count($rslist)< 1){
			return false;
		}
		foreach($rslist as $key=>$value){
			$value['url'] = $this->url('list','action','id='.$value['id']);
			$rslist[$key] = $value;
		}
		//系统管理员
		if($_SESSION['admin_rs']['if_system']){
			$chk = $this->model('workflow')->chk();
			if($chk){
				$tmp = array('title'=>P_Lang('我授权的'),'ico'=>'images/ico/manage.png','id'=>'workflow');
				$tmp['url'] = $this->url('workflow','manage');
				$rslist[] = $tmp;
			}
		}else{
			$chk = $this->model('workflow')->chk("admin_id=".$_SESSION['admin_id']);
			if($chk){
				$tmp = array('title'=>P_Lang('我管理的'),'ico'=>'images/ico/manage.png','id'=>'workflow');
				$tmp['url'] = $this->url('workflow','list');
				$rslist[] = $tmp;
			}
		}
		$this->assign('list_rslist',$rslist);
		return $this->fetch('index_block_listsetting');
	}

	public function clear_f()
	{
		$this->lib('file')->rm($this->dir_root."data/tpl_www/");
		$this->lib('file')->rm($this->dir_root."data/tpl_admin/");
		$this->lib('file')->rm($this->dir_root."data/cache/");
		$this->cache->clear();
		$this->json(true);
	}

	public function site_f()
	{
		$siteid = $this->get("id","int");
		if(!$siteid){
			error(P_Lang('请选择要维护的站点'),$this->ur('index'));
		}
		$rs = $this->model("site")->get_one($siteid);
		if(!$rs){
			error(P_Lang('站点信息不存在'),$this->url("index"));
		}
		$_SESSION['admin_site_id'] = $siteid;
		$tip = P_Lang('您正在切换到网站：{sitename}，请稍候…',array('sitename'=>"<span style='color:red;font-weight:bold;'>".$rs['title']."</span>"));
		error($tip,$this->url("index"),"ok");
	}
    //获取待处理信息
   function pendding_f()
    {
        if($this->config['async']['status']){
            $taskurl = api_url('task','index','',true);
            if($this->config['async']['type']){
                $this->lib('async')->loadtype($this->config['async']['type']);
            }
            $this->lib('async')->start($taskurl);
        }
		//自动更新状态
		$track = $this->model('channel')->track_list('space>0'); //是否设置自动更新，0为手动更新
        if($track){
            //获取最后数组
            $end_track = end($track);
            $rslist =$this->model('order')->get_orders("status='shipped' and remove=0 and is_end=0");
            foreach($rslist as $key => $value){
                $log = $this->model('order')->log_list_one($value['id']);
				if($log['track_id']==$end_track['id']){
					//退出更新订单状态
					$this->model('order')->save(array('is_end'=>1),$value['id']);
					continue;//最后一条运单状态
				}
                $time = strtotime(date("Y-m-d H:i:s",$log['addtime']));//该订单轨迹最后一条时间
				foreach($track as $k => $v){
					//对应仓库状态
					if (strpos($v['stock_id'],$value['stock_id']) === false) continue;
					if($log['track_id']==$v['id']){
						$f = $k + 1;
						$tmp_track = $track[$f];
					}
				}
				if($log['track_id']== 0){
					$tmp_track = $track[0];
				}
				$time += $tmp_track['space']*24*3600;//间隔时间
				$time += rand(600,3600);//随机分秒
				if($this->time >= $time){
					$this->model('order')->log_save(array('order_id'=>$value['id'],'note'=>$tmp_track['title'],'track_id'=>$tmp_track['id'],'addtime'=>$time));
				}
            }
        }
        $this->json(true);
    }
}
?>