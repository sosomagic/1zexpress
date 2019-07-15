<?php
/*****************************************************************************************
	文件： model/admin/task_model.php
	备注： 计划任务
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class task_model extends task_model_base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function save($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,'task',array('id'=>$id));
		}else{
			return $this->db->insert_array($data,'task');
		}
	}
}

?>