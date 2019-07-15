<?php
/*****************************************************************************************
	文件： form/url_form.php
	备注： 网址
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:850272422>
    Update  : 2016年10月11日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class url_form extends _init_auto
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dsy_config()
	{
		$this->view($this->dir_dsy.'form/html/url_admin.html','abs-file');
	}

	public function dsy_format($rs,$appid="admin")
	{
		if($rs["content"] && is_string($rs["content"])){
			$rs['content'] = unserialize($rs['content']);
		}
		if(!$rs['content'] && !is_array($rs['content'])){
			$rs['content'] = array('default'=>'','rewrite'=>'');
		}
		if($rs['content']){
			if(!$rs['content']['default'] && $rs['content'][0]){
				$rs['content']['default'] = $rs['content'][0];
			}
			if(!$rs['content']['rewrite'] && $rs['content'][1]){
				$rs['content']['rewrite'] = $rs['content'][1];
			}
		}
		$this->assign("_rs",$rs);
		$file = $appid == 'admin' ? $this->dir_dsy.'form/html/url_admin_tpl.html' : $this->dir_dsy.'form/html/url_www_tpl.html';
		if(!is_file($file)){
			$file = $this->dir_dsy.'form/html/url_admin_tpl.html';
		}
		return $this->fetch($file,'abs-file');
	}

	public function dsy_get($rs,$appid="admin")
	{
		$info = $this->get($rs['identifier'],$rs['format']);
		if($info){
			$info = serialize($info);
		}
		return $info;
	}

	public function dsy_show($rs,$appid="admin")
	{
		$info = $rs['content'] ? unserialize($rs['content']) : false;
		if(!$info){
			return false;
		}
		if(!$info['default'] && $info[0]){
			$info['default'] = $info[0];
		}
		if(!$info['rewrite'] && $info[1]){
			$info['rewrite'] = $info[1];
		}
		if($appid == 'admin'){
			$str = '';
			if($info['default']){
				$str = $info['default'];
			}
			if($info['rewrite']){
				if($str){
					$str .= '<br />';
				}
				$str .= $info['rewrite'];
			}
			return $str;
		}else{
			$url = $GLOBALS['app']->site['url_type'] == 'rewrite' ? $info['rewrite'] : $info['default'];
			if(!$url){
				$url = $info['default'];
			}
			return $url;
		}
	}
}
?>