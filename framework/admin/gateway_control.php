<?php
/*****************************************************************************************
	文件： admin/gateway_control.php
	备注： 第三方接入网关管理工具
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2015年09月29日 23时56分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class gateway_control extends dsy_control
{
	private $popedom;
	public function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom('gateway');
		$this->assign("popedom",$this->popedom);
	}

	public function index_f()
	{
		if(!$this->popedom["list"]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$rslist = $this->model('gateway')->get_all();
		if($rslist){
			$id = $this->get('id');
			if($id){
				foreach($rslist as $key=>$value){
					if($key != $id){
						unset($rslist[$key]['list']);
					}
				}
			}
			$this->assign('rslist',$rslist);
		}
		$this->view('gateway_index');
	}

	public function getlist_f()
	{
		$id = $this->get('id');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$codelist = $this->model('gateway')->code_all($id);
		if(!$codelist || count($codelist)<1){
			$this->json(P_Lang('没有相关网关可用'));
		}
		$this->json($codelist,true);
	}

	public function set_f()
	{
		$id = $this->get('id');
		if($id){
			if(!$this->popedom["modify"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$rs = $this->model('gateway')->get_one($id);
			$this->assign('rs',$rs);
			$this->assign('id',$id);
			$type = $rs['type'];
			$code = $rs['code'];
		}else{
			if(!$this->popedom["add"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$type = $this->get('type');
			$code = $this->get('code');
			if(!$type || !$code){
				error(P_Lang('未指网关类型或接口引挈'),$this->url('gateway'),'error');
			}
			$taxis = $this->model('gateway')->next_taxis($type,$code);
			$this->assign('rs',array('taxis'=>$taxis));
		}
		$groupall = $this->model('gateway')->group_all();
		$this->assign('group',array('code'=>$type,'title'=>$groupall[$type]));
		$this->assign('code',$code);
		$this->assign('type',$type);
		$extlist = $this->model('gateway')->code_one($type,$code);
		$this->assign('extlist',$extlist);
		$this->lib('form')->cssjs();
		$this->view('gateway_set');
	}

	public function save_f()
	{
		$id = $this->get('id');
		$array = array('site_id'=>$_SESSION['admin_site_id']);
		if($id){
			if(!$this->popedom["modify"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$rs = $this->model('gateway')->get_one($id);
			$type = $rs['type'];
			$code = $rs['code'];
		}else{
			if(!$this->popedom["add"]){
				error(P_Lang('您没有权限执行此操作'),'','error');
			}
			$type = $this->get('type');
			$code = $this->get('code');
			if(!$type || !$code){
				error(P_Lang('未指网关类型或引挈类型'),$this->url('gateway'),'error');
			}
			$array['type'] = $type;
			$array['code'] = $code;
		}
		$array['status'] = $this->get('status','int');
		$array['title'] = $this->get('title');
		$array['taxis'] = $this->get('taxis','int');
		$array['note'] = $this->get('note','html');
		$codeinfo = $this->model('gateway')->code_one($type,$code);
		if($codeinfo['code'] && is_array($codeinfo['code'])){
			$ext = array();
			foreach($codeinfo['code'] AS $key=>$value){
				if($value['type'] != 'checkbox'){
					$ext[$key] = $this->get($code."_".$key);
				}else{
					$tmp = array();
					foreach($value['option'] AS $k=>$v){
						$tmp_name = $code."_".$k;
						if(isset($_POST[$tmp_name])){
							$tmp[] = $k;
						}
					}
					$ext[$key] = implode(",",$tmp);
				}
			}
			$array['ext'] = serialize($ext);
		}
		
		if($id){
			$this->model('gateway')->save($array,$id);
			$tip = P_Lang('更新成功');
		}else{
			$id = $this->model('gateway')->save($array);
			$tip = P_Lang('添加成功');
		}
		$is_default = $this->get('is_default','int');
		if($is_default){
			$this->model('gateway')->update_default($id);
		}
		error($tip,$this->url('gateway'),'ok');
	}

	public function sort_f()
	{
		$sort = $this->get('sort');
		if(!$sort || !is_array($sort)){
			$this->json(P_Lang('未指定排序'));
		}
		foreach($sort as $key=>$value){
			$this->model('gateway')->save(array('taxis'=>intval($value)),$key);
		}
		$this->json(true);
	}

	public function status_f()
	{
		if(!$this->popedom['status']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get('id','int');
		$status = $this->get('status','int');
		$this->model('gateway')->save(array('status'=>$status),$id);
		$this->json(true);
	}

	public function default_f()
	{
		if(!$this->popedom['isdefault']){
			$this->json(P_Lang('您没有权限执行此操作'));
		}
		$id = $this->get('id','int');
		$this->model('gateway')->update_default($id);
		$this->json(true);
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
		$this->model('gateway')->delete($id);
		$this->json(true);
	}
}

?>