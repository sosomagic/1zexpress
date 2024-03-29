<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class rescate_model_base extends dsy_model
{
	public function __construct()
	{
		parent::model();
	}

	public function get_all()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."res_cate ORDER BY id ASC";
		$rslist = $this->db->get_all($sql);
		if(!$rslist){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."gd ORDER BY id ASC";
		$gdlist = $this->db->get_all($sql,'id');
		if(!$gdlist){
			$gdlist = array();
		}
		foreach($rslist as $key=>$value){
			$gds = false;
			if($value['gdall']){
				foreach($gdlist as $k=>$v){
					$gds[] = $v['identifier'];
				}
			}else{
				$types = $value['gdtypes'] ? explode(',',$value['gdtypes']) : array();
				foreach($types as $k=>$v){
					if($gdlist[$v]){
						$gds[] = $gdlist[$v]['identifier'];
					}
				}
			}
			$value['gdtypes'] = $gds ? implode('/',$gds) : '';
			$rslist[$key] = $value;
		}
		return $rslist;
	}

	public function get_one($id='')
	{
		if(!$id){
			return $this->get_default();
		}
		$sql = "SELECT * FROM ".$this->db->prefix."res_cate WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	public function get_default()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."res_cate WHERE is_default=1";
		return $this->db->get_one($sql);
	}

	/**
	 * 获取分类信息，分类ID内容不存在时读默认分类
	 * @参数 $id 分类ID，为空读默认分类
	 * @返回 false 或 array
	**/
	public function cate_info($id='')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."res_cate WHERE is_default=1";
		if($id && intval($id)>0){
			$sql .= " OR id='".intval($id)."'";
		}
		$sql .= " ORDER BY is_default ASC LIMIT 1";
		return $this->db->get_one($sql);
	}

	/**
	 * 取得附件下的全部分类
	 * @返回 数组
	**/
	public function cate_all()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."res_cate ORDER BY id ASC";
		return $this->db->get_all($sql);
	}
}
?>