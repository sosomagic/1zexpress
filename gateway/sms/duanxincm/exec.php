<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
if(!$rs['ext'] || !$rs['ext']['password'] || !$rs['ext']['account']){
	if($this->config['debug']){
		dsy_log(print_r($rs,true));
	}
	return false;
}
if(!$extinfo['mobile'] || !$extinfo['content']){
	if($this->config['debug']){
		dsy_log(print_r($extinfo,true));
	}
	return false;
}
$url = $rs['ext']['server'] ? $rs['ext']['server'] : "http://api.momingsms.com/";
$data = array(
	'action'=>'send',
	'username'=>$rs['ext']['account'],
	//'password'=>strtolower(md5($rs['ext']['password'])),
	'password'=>strtolower($rs['ext']['password']),
	'phone'=>$extinfo['mobile'],
	'content'=>$extinfo['content'],
	'encode'=>'utf8'
);
$url .= "?";
foreach($data as $key=>$value){
	$url .= $key.'='.rawurlencode($value).'&';
}
$info = $this->lib('html')->get_content($url);
if($info != '100'){
	if($this->config['debug']){
		dsy_log($info);
	}
	return false;
}
return true;