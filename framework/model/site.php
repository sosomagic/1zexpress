<?php
/***********************************************************
	Filename: dsaiyin/model/site.php
	Note	: 网站信息
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2012-10-17 15:15
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class site_model_base extends dsy_model
{
	private $mobile_domain = false;
	public function __construct()
	{
		parent::model();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	public function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."site WHERE id='".$id."'";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		$rs['_domain'] = $this->domain_list($id,'id');
		if($rs['_domain']){
			foreach($rs['_domain'] as $key=>$value){
				if($value['is_mobile']){
					$rs['_mobile'] = $value;
				}
				if($value['id'] == $rs['domain_id']){
					$rs['domain'] = $value['domain'];
				}
			}
		}
		return $rs;
	}

	public function get_one_default()
	{
		$sql = "SELECT id FROM ".$this->db->prefix."site WHERE is_default=1";
		$tmp = $this->db->get_one($sql);
		if(!$tmp){
			return false;
		}
		return $this->get_one($tmp['id']);
	}

	//有缓存读取
	public function get_one_from_domain($domain='')
	{
		$sql = "SELECT site_id FROM ".$this->db->prefix."site_domain WHERE domain='".$domain."'";
		$tmp = $this->db->get_one($sql);
		if(!$tmp){
			return false;
		}
		return $this->get_one($tmp['site_id']);
	}

	public function get_all_site()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."site ";
		return $this->db->get_all($sql);
	}

	public function domain_list($site_id=0,$pri='')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."site_domain ";
		if($site_id){
			$sql .= "WHERE site_id='".$site_id."'";
		}
		return $this->db->get_all($sql,$pri);
	}

	public function domain_check($domain)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."site_domain WHERE domain='".$domain."'";
		return $this->db->get_one($sql);
	}

	public function domain_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."site_domain WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	public function all_one($id)
	{
		if(!$id) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."all WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	public function all_ext($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."all_ext WHERE all_id='".$id."'";
		return $this->db->get_all($sql,"fields_id");
	}

	public function all_check($identifier,$site_id=0,$id=0)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."all WHERE identifier='".$identifier."' AND site_id='".$site_id."'";
		if($id){
			$sql .= " AND id!='".$id."'";
		}
		return $this->db->get_one($sql);
	}

	public function all_list($site_id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."all WHERE site_id='".$site_id."'";
		return $this->db->get_all($sql);
	}

	//订单状态设置
	public function order_status_all($sort=false)
	{
		$site_id = $GLOBALS['app']->app_id == 'admin' ? $_SESSION['admin_site_id'] : $GLOBALS['app']->site['id'];
		$file = $this->dir_root.'data/xml/order_status_'.$site_id.'.xml';
		$string = 'create,paid,unpaid,shipped,received';
		if($this->config['order'] && $this->config['order']['status']){
			$string = $this->config['order']['status'];
		}
		$list = explode(",",$string);
		$taxis = 100;
		if(!file_exists($file)){
			$taxis = 1;
			$tmplist = array();
			foreach($list as $key=>$value){
				$tmplist[$value] = array('title'=>$value,'email_tpl_user'=>'','email_tpl_admin'=>'','taxis'=>$taxis,'status'=>0);
				$taxis++;
			}
		}else{
			$tmplist = $this->lib('xml')->read($file);
			foreach($list as $key=>$value){
				if(!$tmplist[$value]){
					$tmplist[$value] = array('title'=>$value,'email_tpl_user'=>'','email_tpl_admin'=>'','taxis'=>$taxis,'status'=>0);
					$taxis++;
				}
			}
			foreach($tmplist as $key=>$value){
				if(!in_array($key,$list)){
					unset($tmplist[$key]);
				}
			}
		}
		if($tmplist && $sort){
			$rslist = array();
			foreach($tmplist as $key=>$value){
				$value['identifier'] = $key;
				$rslist[] = $value;
			}
			usort($rslist,array($this,'status_sort'));
			return $rslist;
		}
		return $tmplist;
	}

	private function status_sort($a,$b)
	{
		if($a['taxis'] == $b['taxis']){
			return 0;
		}
		return ($a['taxis'] < $b['taxis']) ? -1 : 1;
	}

	//
	public function price_status_all($sort=false)
	{
		$site_id = $GLOBALS['app']->app_id == 'admin' ? $_SESSION['admin_site_id'] : $GLOBALS['app']->site['id'];
		$file = $this->dir_root.'data/xml/price_status_'.$site_id.'.xml';
		$string = 'product,shipping,fee,discount,wealth,payonline';
		if($this->config['order'] && $this->config['order']['price']){
			$string = $this->config['order']['price'];
		}
		$list = explode(",",$string);
		$taxis = 100;
		if(!file_exists($file)){
			$taxis = 1;
			$tmplist = array();
			foreach($list as $key=>$value){
				$tmplist[$value] = array('title'=>$value,'action'=>'add','taxis'=>$taxis,'status'=>0);
				$taxis++;
			}
		}else{
			$tmplist = $this->lib('xml')->read($file);
			foreach($list as $key=>$value){
				if(!$tmplist[$value]){
					$tmplist[$value] = array('title'=>$value,'action'=>'add','taxis'=>$taxis,'status'=>0);
					$taxis++;
				}
			}
			foreach($tmplist as $key=>$value){
				if(!in_array($key,$list)){
					unset($tmplist[$key]);
				}
			}
		}
		if($tmplist && $sort){
			$rslist = array();
			foreach($tmplist as $key=>$value){
				$value['identifier'] = $key;
				$rslist[] = $value;
			}
			usort($rslist,array($this,'status_sort'));
			return $rslist;
		}
		return $tmplist;
	}

	/**
	 * 前台及API接口获取的网站信息
	 * @param mixed $id 网站ID或网站域名
	 * @return array 为空时返回false，不为空返回网站相关信息 
	 * @date 2016年02月05日
	 */
	public function site_info($id='')
	{
		if(!$id){
			return false;
		}
		$cache_id = $this->cache->id($id);
		$rs = $this->cache->get($cache_id);
		if($rs){
			return $rs;
		}
		$this->db->cache_set($cache_id);
		if(!is_numeric($id)){
			$sql = "SELECT site_id FROM ".$this->db->prefix."site_domain WHERE domain='".$id."'";
			$tmp = $this->db->get_one($sql);
			if(!$tmp){
				$sql = "SELECT id FROM ".$this->db->prefix."site WHERE status=1 AND is_default=1";
				$tmp = $this->db->get_one($sql);
				if(!$tmp){
					return false;
				}
				$id = $tmp['id'];
			}else{
				$id = $tmp['site_id'];
			}
		}
		if(!$id){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."site WHERE id='".$id."' AND status=1";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		$sql = "SELECT * FROM ".$this->db->prefix."site_domain WHERE site_id='".$id."'";
		$dlist = $this->db->get_all($sql);
		if($dlist){
			$rs['_domain'] = $dlist;
			foreach($dlist as $key=>$value){
				if($value['is_mobile']){
					$rs['_mobile'] = $value;
				}
				if($value['id'] == $rs['domain_id']){
					$rs['domain'] = $value['domain'];
				}
			}
		}
		$sql = "SElECT * FROM ".$this->db->prefix."all WHERE site_id='".$id."' AND status='1'";
		$list = $this->db->get_all($sql);
		if(!$list){
			$this->cache->save($cache_id,$rs);
			return $rs;
		}
		$tmp = $tmp2 = array();
		foreach($list AS $key=>$value){
			$tmp[$value["identifier"]] = "all-".$value["id"];
			$tmp2["all-".$value["id"]] = $value["identifier"];
		}
		$tmp = implode("','",$tmp);
		$sql = "SELECT ext.id,ext.identifier,ext.form_type,extc.content,ext.ext,ext.module FROM ".$this->db->prefix."ext ext ";
		$sql.= "JOIN ".$this->db->prefix."extc extc ON(ext.id=extc.id) ";
		$sql.= "WHERE ext.module IN('".$tmp."') ORDER BY ext.taxis ASC,ext.id DESC";
		$rslist = $this->db->get_all($sql);
		if(!$rslist){
			$this->cache->save($cache_id,$rs);
			return $rs;
		}
		$info = false;
		foreach($rslist AS $key=>$value){
			if(!$tmp2[$value["module"]]){
				continue;
			}
			$rs[$tmp2[$value["module"]]][$value["identifier"]] = $this->lib('form')->show($value);
		}
		$this->cache->save($cache_id,$rs);
		return $rs;
	}
}
?>