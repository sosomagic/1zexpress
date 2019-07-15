<?php
/*****************************************************************************************
	文件： form/textarea_form.php
	备注： 文本区内容
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:850272422>
    Update  : 2016年10月11日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class textarea_form extends _init_auto
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dsy_config()
	{
		$this->view($this->dir_dsy.'form/html/textarea_admin.html','abs-file');
	}

	public function dsy_format($rs,$appid="admin")
	{
		if($appid == 'admin'){
			//$width = $rs['width']>500 ? $rs['width'].'px' : '905px';
			$width = '100%';
			$height = intval($rs['height']) ? intval($rs['height']) : '100';
			$html .= '<textarea name="'.$rs["identifier"].'" id="'.$rs["identifier"].'" dsy_id="textarea" ';
			$html .= 'style="'.$rs["form_style"].';width:'.$width.';height:'.$height.'px"';
			$html .= '>'.$rs["content"].'</textarea>';
			return $html;
		}else{
			$width = intval($rs['width']) ? intval($rs['width']).'px' : '100%';
			$height = intval($rs['height']) ? intval($rs['height']).'px' : '100px';
			$html  = '<table style="border:0;margin:0;padding:0" cellpadding="0" cellspacing="0"><tr><td>';
			$html .= '<textarea name="'.$rs["identifier"].'" id="'.$rs["identifier"].'" dsy_id="textarea" ';
			$html .= 'style="'.$rs["form_style"].';width:'.$width.';height:'.$height.'"';
			$html .= '>'.$rs["content"].'</textarea>';
			$html .= "</td></tr></table>";
			return $html;
		}
	}

	public function dsy_get($rs,$appid="admin")
	{
		return $this->get($rs['identifier'],$rs['format']);
	}

	public function dsy_show($rs,$appid="admin")
	{
		return $rs['content'];
	}
}
?>