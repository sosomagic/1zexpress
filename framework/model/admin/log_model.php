<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class log_model extends log_model_base
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 删除日志操作
	 * @参数 $condition 删除条件
	**/
	public function delete($condition='')
	{
		$sql = "DELETE FROM ".$this->db->prefix."log ";
		if($condition){
			$sql.= "WHERE ".$condition." ";
		}
		return $this->db->query($sql);
	}
}
