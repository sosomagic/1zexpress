<?php
/**
 * 网址格式生成规范
 */
if(!defined("DSAIYIN_SET")){
	exit("<h1>Access Denied</h1>");
}
class url_model extends url_model_base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function url($ctrl='index',$func='index',$ext='')
	{
		return $this->url_ctrl($ctrl,$func,$ext);
	}
}

?>