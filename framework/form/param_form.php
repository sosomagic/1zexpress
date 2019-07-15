<?php
/*****************************************************************************************
	文件： form/param_form.php
	备注： 自定义参数
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:850272422>
    Update  : 2016年10月11日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class param_form extends _init_auto
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dsy_config()
	{
		$this->view($this->dir_dsy."form/html/param_admin.html","abs-file");
	}

	public function dsy_format($rs,$appid="admin")
	{
		if(!$rs){
			return false;
		}
		if($rs['p_name']){
			$pname = explode("\n",$rs['p_name']);
			$this->assign('_pname',$pname);
		}
		if($rs['content']){
			$list = unserialize($rs['content']);
		}else{
			$list = array();
			if($pname){
				foreach($pname as $key=>$value){
					$tmp = array($value);
					$list['title'][] = $value;
				}
			}else{
				$list = array('title'=>array(),'content'=>array());
			}
		}
		if($rs['p_type']){
			$rs['p_width'] = intval($rs['p_width']) ? intval($rs['p_width']) : '80';
		}else{
			$rs['p_width'] = intval($rs['p_width']) ? intval($rs['p_width']) : '300';
		}
		$this->assign('_rslist',$list);
		$this->assign('_rs',$rs);
		$this->assign('_ptype',$rs['p_type']);
		$file = $appid == 'admin' ? $this->dir_dsy.'form/html/param_admin_tpl.html' : $this->dir_dsy.'form/html/param_www_tpl.html';
		if(!is_file($file)){
			$file = $this->dir_dsy.'form/html/param_admin_tpl.html';
		}
		return $this->fetch($file,'abs-file');
	}

	public function dsy_get($rs,$appid="admin")
	{
		if(!$rs){
			return false;
		}
		if($rs['ext']){
			$ext = is_string($rs['ext']) ? unserialize($rs['ext']) : $rs['ext'];
		}
		$list = array();
		if($ext && $ext['p_type']){
			$list['title'] = $this->get($rs['identifier'].'_title');
			if($list['title'] && count($list['title'])>0){
				$tmp = $GLOBALS['app']->get($rs['identifier'].'_body');
				$list['content'] = array_chunk($tmp,count($list['title']));
				return serialize($list);
			}
			return false;
		}else{
			$list['title'] = $this->get($rs['identifier'].'_title');
			$list['content'] = $this->get($rs['identifier'].'_body');
			return serialize($list);
		}
	}

	public function dsy_show($rs,$appid="admin")
	{
		if(!$rs || !$rs['content']){
			return false;
		}
		$info = is_string($rs['content']) ? unserialize($rs['content']) : $rs['content'];
		if(!$info || !$info['title'] || !is_array($info['title'])){
			return false;
		}
		if($appid == 'admin'){
			return implode("/",$info['title']);
		}else{
			$ext = false;
			if($rs['ext']){
				if(is_string($rs['ext'])){
					$ext = unserialize($rs['ext']);
				}else{
					$ext = $rs['ext'];
				}
			}
			if($ext && $ext['p_type']){
				return $info;
			}else{
				$list = array();
				if(!$info['title']){
					$info['title'] = array();
				}
				foreach($info['title'] as $key=>$value){
					$list[$value] = $info['content'][$key];
				}
				return $list;
			}
		}
	}
}
?>