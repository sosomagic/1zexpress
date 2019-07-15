<?php
/***********************************************************
	Filename: www/search_control.php
	Note	: 搜索
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013-04-20 23:51
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class search_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}
	public function index_f(){
	    $this->view("search_index");
	}
	public function show_f()
	{
		//查询
		$keywords = $this->get('keywords');
		if($keywords){
			$keywords = str_replace(array("　","，",",","｜","|","、","/","\\","／","＼","+","＋","-","－","_","＿")," ",$keywords);
			$keywords = trim($keywords);
			$keywords = explode(";",$keywords);
			$keywords = array_unique($keywords);
			$now = $this->time;
			$sn = $rslist = array();
			foreach($keywords as $value){
				$order=$this->model('order')->get_one_from_sn($value);
				if(!$order){
					//error(P_Lang('你查询的运单'.$value.'不存在'),$this->url,'error');
					//continue;
					$sn[]=$value;
                    $rslist[] ='';
				}else{
					if($order['express'] && $order['status']=='received'){
						//读express信息
						$rs = $this->model('order')->express_oid($order['id']);
						$express = $this->model('express')->get_one($rs['express_id']);
						$rate = $express['rate'] ? $express['rate'] : 6;
						$time = strtotime(date("Y-m-d H:i",$rs['last_query_time']));
						$time += $rate * 3600;
						//如果未超出系统限制，不查询直接返回查询结果
						if($time < $now && !$rs['is_end']){
							$file = $this->dir_root.'gateway/express/'.$express['code'].'/index.php';
							$info = include $file;
							//更新操作时间
							$this->model('order')->update_last_query_time($rs['id']);
							//删除旧的获取查询结果的数据
							$this->model('order')->log_del($rs['order_id'],$rs['id'],$express['title']);
							//保存新的
							if($info['content']){
								foreach($info['content'] as $k=>$v){
									$data = array('order_id'=>$rs['order_id'],'order_express_id'=>$rs['id']);
									$data['addtime'] = strtotime($v['time']);
									$data['who'] = $express['title'];
									$data['note'] = $v['content'];
									$this->model('order')->log_save($data);
								}
							}
							if($info['is_end']){
								$this->model('order')->update_end($rs['id']);
								$this->model('order')->update_order_status($rs['id'],'finished');
							}
						}
					}
					$sn[] = $value;
					$rslist[] = $this->model('order')->log_list($order['id']);
				}
			}
		}
		$this->assign('sn',$sn);
		$this->assign('rslist',$rslist);
		$this->view("search_show");
	}

	/*private function load_search($keywords)
	{
		if(!$keywords) return false;
		//取得符合搜索的项目
		$condition = "status=1 AND hidden=0 AND is_search !=0 AND module>0";
		$list = $this->model('project')->project_all($this->site['id'],'id',$condition);
		if(!$list){
			error(P_Lang('您的网站没有允许可以搜索的信息'),$this->url,"error",10);
		}
		$pids = $mids = $projects = array();
		foreach($list AS $key=>$value){
			$pids[] = $value["id"];
			$mids[] = $value['module'];
			$projects[$value['id']] = $value['identifier'];
		}
		$mids = array_unique($mids);
		$condition = "l.project_id IN(".implode(",",$pids).") AND l.module_id IN(".implode(",",$mids).") ";
		$klist = explode(" ",$keywords);
		$kc = array();
		$kwlist = array();
		foreach($klist AS $key=>$value){
			$kwlist[] = '<i>'.$value.'</i>';
			$kc[] = " l.seo_title LIKE '%".$value."%'";
			$kc[] = " l.seo_keywords LIKE '%".$value."%'";
			$kc[] = " l.seo_desc LIKE '%".$value."%'";
			$kc[] = " l.title LIKE '%".$value."%'";
			$kc[] = " l.tag LIKE '%".$value."%'";
		}
		$condition.= "AND (".implode(" OR ",$kc).") ";
		$total = $this->model('search')->get_total($condition);
		$pageid = $this->get($this->config['pageid'],'int');
		if(!$pageid){
			$pageid = 1;
		}
		$psize = $this->config['psize'] ? $this->config['psize'] : 30;
		$offset = ($pageid-1) * $psize;
		$idlist = $this->model('search')->id_list($condition,$offset,$psize);
		if($idlist){
			$rslist = array();
			foreach($idlist AS $key=>$value){
				$info = $this->call->dsy('_arc',array('title_id'=>$value['id'],'site'=>$this->site['id']));
				if($info){
					$info['_title'] = str_replace($klist,$kwlist,$info['title']);
					$rslist[] = $info;
				}
			}
			$this->assign("rslist",$rslist);
		}
		$pageurl = $this->url('search','','keywords='.rawurlencode($keywords));
		$this->assign("pageurl",$pageurl);
		$this->assign("total",$total);
		$this->assign("pageid",$pageid);
		$this->assign("psize",$psize);
		$this->assign("keywords",$keywords);
		$this->view("search_list");
		exit;
	}*/
}
?>