<?php
/***********************************************************
	Filename: model/user.php
	Note	: 会员模块
	Version : 3.0
	Author  : dsaiyin
	Update  : 2013年5月4日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class user_model_base extends dsy_model
{
	public $psize = 20;
	public function __construct()
	{
		parent::model();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}
    /**
     * 取得单条会员数组
     * @参数 $id 会员ID或其他唯一标识
     * @参数 $field 指定的标识，当为布尔值时表示是否格式化扩展数据
     * @参数 $ext 布尔值或1或0，当$field为布尔值时这里表示是否显示财富
     * @参数 $wealth 布尔值或1或0，表示财富
     **/
    public function get_one($id,$field='id',$ext=true,$wealth=true)
    {
        if(!$id){
            return false;
        }
        if(is_bool($field) || is_numeric($field)){
            $wealth = $ext;
            $ext = $field;
            $field = 'id';
        }
        if(!$field){
            $field = 'id';
        }
        $flist = $this->fields_all();
        $ufields = "u.*";
        $condition = "u.".$field."='".$id."'";
        if($flist){
            foreach($flist as $key=>$value){
                $ufields .= ",e.*";
                if($value['identifier'] == $value){
                    $condition = "e.".$field."='".$id."'";
                }
            }
        }
        $sql = " SELECT ".$ufields." FROM ".$this->db->prefix."user u ";
        $sql.= " LEFT JOIN ".$this->db->prefix."user_ext e ON(u.id=e.id) ";
        $sql.= " WHERE ".$condition;
        $rs = $this->db->get_one($sql);
        if(!$rs){
            return false;
        }
        if($wealth){
            $rs['wealth'] = $this->wealth($rs['id']);
        }
        if($ext && $flist){
            foreach($flist AS $key=>$value){
                $rs[$value['identifier']] = $this->lib('form')->show($value,$rs[$value['identifier']]);
            }
        }
        return $rs;
    }

    /**
     * 取得会员的财富信息
     * @参数 $uid 会员ID
     * @参数 $wid 指定的财富ID，为0或空时返回会员下的所有财富信息
     * @参数 $return 返回，仅在$wid大于0时有效，支持两个参数，一个是value，返回值，一个是array，返回数组
     **/
    public function wealth($uid,$wid=0,$return='value')
    {
        $wlist = $this->model('wealth')->get_all(1,'id');
        if(!$wlist){
            return false;
        }
        $wealth = array();
        foreach($wlist as $key=>$value){
            $val = number_format(0,$value['dnum']);
            $wealth[$value['identifier']] = array('id'=>$value['id'],'title'=>$value['title'],'val'=>$val,'unit'=>$value['unit']);
        }
        $condition = "uid='".$uid."'";
        $tlist = $this->model('wealth')->vals($condition);
        if($tlist){
            foreach($tlist as $key=>$value){
                $tmp = $wlist[$value['wid']];
                $val = round($value['val'],$tmp['dnum']);
                $wealth[$tmp['identifier']]['val'] = $val;
            }
        }
        if($wid){
            if(is_numeric($wid)){
                $tmp = false;
                foreach($wealth as $key=>$value){
                    if($value['id'] == $wid){
                        $tmp = $value;
                        break;
                    }
                }
                if(!$tmp){
                    return false;
                }
                if($return == 'array'){
                    return $tmp;
                }
                return $tmp['val'];
            }
            //字串
            if(!$wealth[$wid]){
                return false;
            }
            if($return == 'array'){
                return $wealth[$wid];
            }
            return $wealth[$wid]['val'];
        }
        return $wealth;
    }
	public function get_list($condition="",$offset=0,$psize=30)
	{
		$sql = " SELECT u.*,e.* FROM ".$this->db->prefix."user u ";
		$sql.= " LEFT JOIN ".$this->db->prefix."user_ext e ON(u.id=e.id) ";
		if($condition){
			$sql .= " WHERE ".$condition;
		}
		$offset = intval($offset);
		$psize = intval($psize);
		$sql.= " ORDER BY u.id DESC ";
		if($psize){
			$offset = intval($offset);
			$sql .= "LIMIT ".$offset.",".$psize;
		}
		$rslist = $this->db->get_all($sql,"id");
		if(!$rslist){
			return false;
		}
		$idlist = array_keys($rslist);
		//读取会员积分信息
		$wlist = $this->model('wealth')->get_all(1,'id');
		if($wlist){
			$condition = "uid IN(".implode(",",$idlist).")";
			$tlist = $this->model('wealth')->vals($condition);
			if($tlist){
				$wealth = array();
				foreach($tlist as $key=>$value){
					$tmp = $wlist[$value['wid']];
					$rslist[$value['uid']]['wealth'][$tmp['identifier']] = $value['val'];
				}
			}
		}
        //获取会员扩展字段
       /*$flist = $this->fields_all();
        if(!$flist){
            return $rslist;
        }
        foreach($rslist AS $key=>$value){
            foreach($flist AS $k=>$v){
                $value[$v['identifier']] = $this->lib('form')->show($v,$value[$v['identifier']]);
            }
            $rslist[$key] = $value;
        }*/
        return $rslist;
    }


	//取得总数量
	public function get_count($condition="")
	{
		$sql = "SELECT count(u.id) FROM ".$this->db->prefix."user u ";
		$sql.= " LEFT JOIN ".$this->db->prefix."user_ext e ON(u.id=e.id) ";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		return $this->db->count($sql);
	}


	//检测账号是否冲突
	function chk_name($name,$id=0)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE user='".$name."' ";
		if($id)
		{
			$sql.= " AND id!='".$id."' ";
		}
		return $this->db->get_one($sql);
	}

	public function fields_save($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,"user_fields",array("id"=>$id));
		}else{
			return $this->db->insert_array($data,"user_fields");
		}
	}

    //取得扩展字段的所有扩展信息
    function fields_all($condition="",$pri_id="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user_fields ";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        $sql.= " ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri_id);
    }

	public function tbl_fields_list($tbl)
	{
		return $this->db->list_fields($tbl);
	}

	function field_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user_fields WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	//取得全部会员ID
	function get_all_from_uid($uid,$pri="")
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE id IN(".$uid.")";
		return $this->db->get_all($sql,$pri);
	}

	function fields()
	{
		return $this->db->list_fields($this->db->prefix."user");
	}

	function uid_from_email($email,$id="")
	{
		if(!$email) return false;
		$sql = "SELECT id FROM ".$this->db->prefix."user WHERE email='".$email."'";
		if($id)
		{
			$sql.= " AND id !='".$id."'";
		}
		$rs = $this->db->get_one($sql);
		if(!$rs) return false;
		return $rs['id'];
	}

	function uid_from_chkcode($code)
	{
		$sql = "SELECT id FROM ".$this->db->prefix."user WHERE code='".$code."'";
		$rs = $this->db->get_one($sql);
		if(!$rs) return false;
		return $rs['id'];
	}

	public function save($data,$id=0)
	{
		if($id){
			$status = $this->db->update_array($data,"user",array("id"=>$id));
			if($status){
				return $id;
			}
			return false;
		}else{
			return $this->db->insert_array($data,"user");
		}
	}

	public function save_ext($data)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		return $this->db->insert_array($data,"user_ext","replace");
	}

	public function update_ext($data,$id)
	{
		if(!$data || !is_array($data) || !$id){
			return false;
		}
		return $this->db->update_array($data,"user_ext",array("id"=>$id));
	}

	public function get_relation($uid)
	{
		$sql = "SELECT introducer FROM ".$this->db->prefix."user_relation WHERE uid='".$uid."'";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		return $rs['introducer'];
	}

	public function save_relation($uid,$introducer)
	{
		$sql = "REPLACE INTO ".$this->db->prefix."user_relation(uid,introducer,dateline) VALUES('".$uid."','".$introducer."','".$this->time."')";
		return $this->db->query($sql);
	}

	/*public function address($uid,$condition='',$offset=0,$psize=30)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user_address WHERE user_id='".$uid."' ";
		if($condition) $sql.= "and ".$condition." ";
		$sql.= "ORDER BY id DESC limit ".$offset.",".$psize;
		return $this->db->get_all($sql);
	}*/
    public function address($condition='',$offset=0,$psize=30)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user_address ";
        if($condition) $sql.= "where ".$condition." ";
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= "ORDER BY id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
	public function address_one($id,$field_id='id')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user_address WHERE ".$field_id."='".$id."'";
		return $this->db->get_one($sql);
	}
    public function get_address($condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user_address";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->get_one($sql);
    }
	public function address_save($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,'user_address',array('id'=>$id));
		}else{
			return $this->db->insert_array($data,'user_address');
		}
	}

	public function address_delete($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."user_address WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	public function sender_default($id=0)
	{
		if(!$id){
			return false;
		}
		$rs = $this->address_one($id);
		$sql = "UPDATE ".$this->db->prefix."sender SET is_default=0 WHERE user_id='".$rs['user_id']."'";
		$this->db->query($sql);
		$sql = "UPDATE ".$this->db->prefix."sender SET is_default=1 WHERE id='".$id."'";
		$this->db->query($sql);
		return true;
	}
    public function sender($uid,$condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."sender WHERE user_id='".$uid."' ";
        if($condition) $sql.= $condition;
        $sql.= " ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
    public function sender_one($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."sender WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }
    public function sender_save($data,$id=0)
    {
        if(!$data || !is_array($data)){
            return false;
        }
        if($id){
            return $this->db->update_array($data,'sender',array('id'=>$id));
        }else{
            return $this->db->insert_array($data,'sender');
        }
    }
    public function sender_delete($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."sender WHERE id='".$id."'";
        return $this->db->query($sql);
    }
	/*public function invoice($uid)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user_invoice WHERE user_id='".$uid."' ORDER BY id DESC";
		return $this->db->get_all($sql);
	}

	public function invoice_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."user_invoice WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	public function invoice_save($data,$id=0)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		if($id){
			return $this->db->update_array($data,'user_invoice',array('id'=>$id));
		}else{
			return $this->db->insert_array($data,'user_invoice');
		}
	}

	public function invoice_delete($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."user_invoice WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	public function invoice_default($id=0)
	{
		if(!$id){
			return false;
		}
		$rs = $this->invoice_one($id);
		$sql = "UPDATE ".$this->db->prefix."user_invoice SET is_default=0 WHERE user_id='".$rs['user_id']."'";
		$this->db->query($sql);
		$sql = "UPDATE ".$this->db->prefix."user_invoice SET is_default=1 WHERE id='".$id."'";
		$this->db->query($sql);
		return true;
	}*/

	public function token_check($uid,$chk)
	{
		if(!$uid || !$chk){
			return false;
		}
		$sql = "SELECT id,group_id,user,pass FROM ".$this->db->prefix."user WHERE id='".$uid."'";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		$code = md5($uid.'-'.$rs['user'].'-'.$rs['pass']);
		if(strtolower($code) == strtolower($chk)){
			$_SESSION['user_id'] = $uid;
			$_SESSION['user_name'] = $rs['user'];
			$_SESSION['user_gid'] = $rs['group_id'];
			return true;
		}else{
			return false;
		}
	}

	public function token_create($uid)
	{
		if(!$uid){
			return false;
		}
		$sql = "SELECT id,group_id,user,pass FROM ".$this->db->prefix."user WHERE id='".$uid."'";
		$rs = $this->db->get_one($sql);
		if(!$rs){
			return false;
		}
		$code = md5($uid.'-'.$rs['user'].'-'.$rs['pass']);
		$array = array('user_id'=>$uid,'user_chk'=>$code);
		return $this->lib('token')->encode($array);
	}
    //创建用户识别码
    public function create_ucode($num)
    {
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_str = '';
        while(strlen($rand_str)<$num){
            $rand_str .= $a[rand(0,strlen($a)-1)];
        }
        return $rand_str;
    }
	public function get_export($condition="")
    {
        $sql = " SELECT u.user,u.group_id,u.mobile,w.val FROM ".$this->db->prefix."user u ";
        $sql.= " LEFT JOIN ".$this->db->prefix."wealth_info w ON(u.id=w.uid) ";
        if($condition){
            $sql .= " WHERE ".$condition;
        }

        $sql.= " and wid=2 ORDER BY id DESC ";
        return $this->db->get_all($sql);
    }
	public function getAddress($condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."user_address";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->get_all($sql);
    }
	public function getSender($condition="")
    {
        $sql = "SELECT * FROM ".$this->db->prefix."sender";
        if($condition){
            $sql .= " where ".$condition;
        }
        return $this->db->get_one($sql);
    }
}
?>