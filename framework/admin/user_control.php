<?php
/***********************************************************
	Filename: app/admin/user.php
	Note	: 会员中心
	Version : 3.0
	Author  : dsaiyin
	Update  : 2009-12-23
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class user_control extends dsy_control
{
	var $popedom;
	function __construct()
	{
		parent::control();
		$this->model("user");
		$this->model("usergroup");
		$this->popedom = appfile_popedom("user");
		$this->assign("popedom",$this->popedom);
	}
	//会员列表
	public function index_f()
	{
        if(!$this->popedom["list"]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$pageid = $this->get($this->config["pageid"],"int");
		if(!$pageid) $pageid = 1;
		$psize = $this->config["psize"];
		if(!$psize) $psize = 30;
		$keywords = trim($this->get("keywords"));
		$page_url = $this->url("user");
        //隶属仓库会员
        if($_SESSION['admin_rs']['if_system']=='1'){
            $condition = "1=1";
        }else{
            $adm_stock = $this->session->val('admin_stock');
            $condition = "stock_id in(".$adm_stock.")";
        }
        $group_id = $this->get('group_id','int');
        if($group_id){
            $this->assign("group_id",$group_id);
            $condition .= " AND u.group_id = '".$group_id."'";
            $page_url.="&group_id=".rawurlencode($group_id);
        }
        $store = $this->get('store','int');
        if($store){
            $this->assign("store",$store);
            $condition .= " AND u.stock_id = ".$store;
            $page_url.="&stock_id=".rawurlencode($store);
        }
		if($keywords){
			$this->assign("keywords",$keywords);
			$condition .= " AND u.user LIKE '%".$keywords."%'";
			$page_url.="&keywords=".rawurlencode($keywords);
		}
        $ucode = trim($this->get("ucode"));
        if($ucode){
            $this->assign("ucode",$ucode);
            $condition .= " AND u.ucode LIKE '%".$ucode."%'";
            $page_url.="&ucode=".rawurlencode($ucode);
        }
        $mobile = trim($this->get("mobile"));
        if($mobile){
            $this->assign("mobile",$mobile);
            $condition .= " AND u.mobile LIKE '%".$mobile."%'";
            $page_url.="&mobile=".rawurlencode($mobile);
        }
		$offset = ($pageid-1) * $psize;
		$rslist = $this->model('user')->get_list($condition,$offset,$psize);
        foreach($rslist as $key=>$value){
            $value['stock'] = $this->model('stock')->get_one($value['stock_id']);
            $rslist[$key] = $value;
        }
		$count = $this->model('user')->get_count($condition);
		$string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
		$string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
		$pagelist = dsy_page($page_url,$count,$pageid,$psize,$string);
		$this->assign("total",$count);
		$this->assign("rslist",$rslist);
		$this->assign("pagelist",$pagelist);
		$list = $this->lib('xml')->read($this->dir_root.'data/xml/admin_user.xml');
		$this->assign("arealist",$list);
		$grouplist = $this->model('usergroup')->get_all("","id");
		$this->assign("grouplist",$grouplist);
		$wlist = $this->model('wealth')->get_all(1,'identifier');
		$this->assign('wlist',$wlist);
		//返回当前页
        $backurl = str_replace($this->url("user"),'',$page_url);
        if($pageid){
            $backurl .= "&pageid=".$pageid;
        }
        $this->assign('backurl',rawurlencode($backurl));
		$this->view("user_list");
	}
    public function address_list_f()
    {
        //读取会员地址库
        if(!$this->popedom["address_list"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config["pageid"],"int");
        if(!$pageid) $pageid = 1;
        $psize = $this->config["psize"];
        if(!$psize) $psize = 30;
        $page_url = $this->url("user","address_list");
        $condition = " 1=1";
        $fullname = trim($this->get("fullname"));
        if($fullname){
            $condition .= " AND fullname like '%".$fullname."%'";
            $this->assign('fullname',$fullname);
            $page_url.="&fullname=".rawurlencode($fullname);
        }
        $mobile = trim($this->get("mobile"));
        if($mobile){
            $condition .= " AND mobile ='".$mobile."'";
            $this->assign('mobile',$mobile);
            $page_url.="&mobile=".rawurlencode($mobile);
        }
        $idcard = trim($this->get("idcard"));
        if($idcard){
            $condition .= " AND idcard like '%".$idcard."%'";
            $this->assign('idcard',$idcard);
            $page_url.="&idcard=".rawurlencode($idcard);
        }
        $ucode = trim($this->get("ucode"));
        if($ucode){
            $condition .= " AND user_id IN(SELECT id FROM ".$this->db->prefix."user WHERE ucode = '".$ucode."')";
            $this->assign('ucode',$ucode);
            $page_url.="&ucode=".rawurlencode($ucode);
        }
        $offset = ($pageid-1) * $psize;
        $total = $this->model('address')->get_count($condition);
        if($total>0){
            $rslist = $this->model('user')->address_list($condition,$offset,$psize);
			foreach($rslist as $key=>$value){
				$value["user"] = $this->model('user')->get_one($value['user_id']);
				$rslist[$key] = $value;
				
			}
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($page_url,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
		if($pageid) $page_url .= "&pageid=".$pageid;
        $this->assign('pageurl',$page_url);
        $this->view('user_address');
    }
	public function address_set_f()
	{
        $id = $this->get('id','int');
        $rs = array();
        if($id){
            $rs = $this->model('user')->address_one($id);
            if(!$rs){
                error(P_Lang('数据获取失败，请检查'));
            }
            //$uid = $rs['user_id'];
            $this->assign('rs',$rs);
            $this->assign('id',$id);
        }
        /*if(!$uid){
            error(P_Lang('未指定会员账号'));
        }
        $this->assign('uid',$uid);*/
        $info = form_edit('pca',array('p'=>$rs['province'],'c'=>$rs['city'],'a'=>$rs['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            //编辑的时候有图片显示
            $idlist[] = strtolower($value["identifier"]);
            if($rs[$value["identifier"]]){
                $value["content"] = $rs[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
		$pageurl = $this->get('pageurl');
		$this->assign("pageurl",$pageurl);
		$this->view('user_address_set');
	}
	public function address_delete_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$this->model('user')->address_delete($id);
		$this->json(true);
	}
	public function add_f()
	{
		$this->set_f();
	}
	public function set_f()
	{
        $adm_stock = $this->session->val('admin_stock');
        $id = $this->get("id","int");
        if($id){
            if(!$this->popedom["modify"]){
                error(P_Lang('您没有权限执行此操作'),'','error');
            }
            $rs = $this->model('user')->get_one($id);
        }else{
            if(!$this->popedom["add"]){
                error(P_Lang('您没有权限执行此操作'),'','error');
            }
        }
        //会员组
        $grouplist = $this->model('usergroup')->get_all();
        $this->assign("grouplist",$grouplist);
        $this->assign("rs",$rs);
        $this->assign("id",$id);
        $rslist = $this->model('channel')->get_channelList();
        $user_id = $rs['id'] ? $rs['id'] : 0;
        foreach($rslist as $key => $value){
            $value['user_price'] = $this->model('user')->get_oneprice('user_id='.$user_id.' and channel_id='.$value['id'].' and stock_id='.$adm_stock);
            $rslist[$key] = $value;
        }
        $this->assign("rslist",$rslist);
		$backurl = $this->get('backurl');
        $this->assign("backurl",$backurl);
        $this->view("user_add");
	}
	public function chk_f()
	{
		$id = $this->get("id","int");
		$user = $this->get("user");
		if(!$user){
			$this->json('会员账号不允许为空');
		}
		$rs_name = $this->model('user')->chk_name($user,$id);
		if($rs_name){
			$this->json('会员账号已经存在');
		}
		$mobile = $this->get('mobile');
		if($mobile){
			/*if(!$this->lib('common')->tel_check($mobile)){
				$this->json(P_Lang('手机号填写不正确'));
			}*/
			$chk = $this->model('user')->get_one($mobile,'mobile');
			if($id){
				if($chk && $chk['id'] != $id){
					$this->json('手机号已被占用');
				}
			}else{
				if($chk){
					$this->json('手机号已被占用');
				}
			}
		}
		$email = $this->get('email');
		if($email){
			if(!$this->lib('common')->email_check($email)){
				$this->json('邮箱填写不正确');
			}
			$chk = $this->model('user')->get_one($email,'email');
			if($id){
				if($chk && $chk['id'] != $id){
					$this->json('邮箱已被占用');
				}
			}else{
				if($chk){
					$this->json('邮箱已被占用');
				}
			}
		}
		$this->json('验证通过',true);
	}
	//存储信息
	public function setok_f()
	{
		$backurl = $this->get('backurl');
		$id = $this->get("id","int");
		$array = array();
        if(!$id){
            $array["user"] = $this->get("user");
            $array["regtime"] = $this->time;
        }
        $array['ucode'] = $this->get('ucode') ? $this->get('ucode') : $this ->model('user')->create_ucode(5); //用户标识码
        $pass = $this->get("pass");
        if(!$pass && !$id){
            error(P_Lang('密码不能为空'),'',"error");
        }
        if($pass){
            $array["pass"] = password_create($pass);
        }
		$array['email'] = $this->get('email');
        if(!$array['email']){
            error(P_Lang('邮箱不能为空'),'','error');
        }
		$array['mobile'] = $this->get('mobile');
        if(!$array['mobile']){
            error(P_Lang('手机不能为空'),'','error');
        }
		$popedom_id = $id ? 'modify' : 'add';
		if(!$this->popedom[$popedom_id]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		$array["group_id"] = $this->get("group_id","int");
		$array["status"] = $this->get("status","int");
        $insert_id = $this->model('user')->save($array,$id);
        $first_name = $this->get('first_name');
        $last_name = $this->get('last_name');
        $address = $this->get('address');
        $zipcode = $this->get('zipcode');
        $tel = $this->get('tel');
        $weixin = $this->get('weixin');
        $qq = $this->get('qq');
        if(!$id){
			$tmplist = array();
            $tmplist["id"] = $insert_id;
            $tmplist['first_name'] = $first_name;
            $tmplist['last_name'] = $last_name;
            $tmplist['address'] = $address;
            $tmplist['zipcode'] = $zipcode;
            $tmplist['tel'] = $tel;
            $tmplist['weixin'] = $weixin;
            $tmplist['qq'] = $qq;
            $this->model('user')->save_ext($tmplist);
            $note = P_Lang('新会员添加成功');
        }else{
            $tmplist['first_name'] = $first_name;
            $tmplist['last_name'] = $last_name;
            $tmplist['address'] = $address;
            $tmplist['zipcode'] = $zipcode;
            $tmplist['tel'] = $tel;
            $tmplist['weixin'] = $weixin;
            $tmplist['qq'] = $qq;
            $this->model('user')->update_ext($tmplist,$id);
            $note = P_Lang('会员编辑成功');
        }
        $this->success($note,$this->url("user",'',$backurl));
	}
	public function ajax_status_f()
	{
		if(!$this->popedom["status"]) exit(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('没有指定ID'));
		}
		$rs = $this->model('user')->get_one($id);
		$status = $rs["status"] ? 0 : 1;
		$this->model('user')->set_status($id,$status);
		exit("ok");
	}
	public function ajax_del_f()
	{
		if(!$this->popedom["delete"]) exit(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id){
			exit(P_Lang('未指定ID'));
		}
		$this->model('user')->del($id);
		exit("ok");
	}
	//会员字段管理器中涉及到的字段
	function fields_auto()
	{
		$this->form_list = $this->model('form')->form_all();
		$this->field_list = $this->model('form')->field_all();
		$this->format_list = $this->model('form')->format_all();
		$this->assign('form_list',$this->form_list);
		$this->assign("field_list",$this->field_list);
		$this->assign("format_list",$this->format_list);
		$this->popedom = appfile_popedom("user:fields");
		$this->assign("popedom",$this->popedom);
	}
	function fields_f()
	{
		$this->fields_auto();
		if(!$this->popedom["list"]){
			error(P_Lang('您没有权限执行此操作'),'','error');
		}
		// 取得现有全部字段
		$condition = "area LIKE '%user%'";
		$used_list = $this->model('user')->fields_all("","identifier");
		if($used_list)
		{
			foreach($used_list AS $key=>$value)
			{
				$value["field_type_name"] = $this->field_list[$value["field_type"]];
				$value["form_type_name"] = $this->form_list[$value["form_type"]];
				$used_list[$key] = $value;
			}
		}
		$this->assign("used_list",$used_list);
		if($this->popedom["set"])
		{
			$fields_list = $this->model('fields')->get_all($condition,"identifier");
			if($fields_list)
			{
				foreach($fields_list AS $key=>$value)
				{
					$value["field_type_name"] = $this->field_list[$value["field_type"]];
					$value["form_type_name"] = $this->form_list[$value["form_type"]];
					$fields_list[$key] = $value;
				}
			}
			if($fields_list && $used_list)
			{
				$main_key = $this->model('user')->fields();
				$newlist = array();
				foreach($fields_list AS $key=>$value)
				{
					if(!$used_list[$key] && !in_array($key,$main_key))
					{
						$newlist[$key] = $value;
					}
				}
				$this->assign("fields_list",$newlist);
			}
			else
			{
				$this->assign("fields_list",$fields_list);
			}
		}
		$this->view("user_fields");
	}
	//自定义字段
	function fields_save_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error(P_Lang('您没有权限执行此操作'));
		$id_list = isset($_POST["add_field"]) ? $_POST["add_field"] : "";
		if($id_list && is_array($id_list))
		{
			$condition = "area LIKE '%user%'";
			$flist = $this->model('fields')->get_all($condition,"id");
			foreach($id_list AS $key=>$value)
			{
				if(!$flist[$value]) continue;
				$f_rs = $flist[$value];
				$title = $this->get("field_title_".$value);
				if(!$title) $title = $f_rs["title"];
				$note = $this->get("field_note_".$value);
				if(!$note) $note = $f_rs["note"];
				$tmp_array = array("title"=>$title,"note"=>$note);
				$tmp_array["identifier"] = $f_rs["identifier"];
				$tmp_array["field_type"] = $f_rs["field_type"];
				$tmp_array["form_type"] = $f_rs["form_type"];
				$tmp_array["form_style"] = $f_rs["form_style"];
				$tmp_array["format"] = $f_rs["format"];
				$tmp_array["content"] = $f_rs["content"];
				$tmp_array["taxis"] = $f_rs["taxis"];
				$tmp_array["ext"] = $f_rs["ext"] ? serialize(unserialize($f_rs["ext"])) : "";
				$this->model('user')->fields_save($tmp_array);
			}
		}
		$list = $this->model('user')->fields_all();
		if($list)
		{
			foreach($list AS $key=>$value)
			{
				$this->model('user')->create_fields($value);
			}
		}
		error(P_Lang('会员自定义字段配置成功'),$this->url("user","fields"));
	}
	
	function field_edit_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error_open(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id)
		{
			error_open(P_Lang('未指定ID'));
		}
		$rs = $this->model('user')->field_one($id);
		$this->assign("rs",$rs);
		$this->assign("id",$id);
		$this->view("user_field_set");
	}
	function field_edit_save_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error_open(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id)
		{
			error_open(P_Lang('未指定ID'));
		}
		$title = $this->get("title");
		$note = $this->get("note");
		$form_type = $this->get("form_type");
		$form_style = $this->get("form_style","html");
		$content = $this->get("content");
		$format = $this->get("format");
		$taxis = $this->get("taxis","int");
		$ext_form_id = $this->get("ext_form_id");
		$ext = array();
		if($ext_form_id)
		{
			$list = explode(",",$ext_form_id);
			foreach($list AS $key=>$value)
			{
				$val = explode(':',$value);
				if($val[1] && $val[1] == "checkbox")
				{
					$value = $val[0];
					$ext[$value] = $this->get($value,"checkbox");
				}
				else
				{
					$value = $val[0];
					$ext[$value] = $this->get($value);
				}
			}
		}
		$array = array();
		$array["title"] = $title;
		$array["note"] = $note;
		$array["form_type"] = $form_type;
		$array["form_style"] = $form_style;
		$array["format"] = $format;
		$array["content"] = $content;
		$array["taxis"] = $taxis;
		$array["ext"] = ($ext && count($ext)>0) ? serialize($ext) : "";
		$array["is_edit"] = $this->get("is_edit","int");
		$this->model('user')->fields_save($array,$id);
		$html = '<input type="button" value=" '.P_Lang('确定').' " class="submit" onclick="$.dialog.close();" />';
		error_open(P_Lang('自定义字段信息配置成功'),"ok",$html);
	}
	//删除字段
	function field_delete_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) $this->json(P_Lang('您没有权限执行此操作'));
		$id = $this->get("id","int");
		if(!$id)
		{
			$this->json(P_Lang('未指定要删除的字段'));
		}
		$this->model('user')->field_delete($id);
		$this->json(P_Lang('删除成功'),true);
	}
    public function info_f()
    {
        $uid = $this->get('uid');
        if(!$uid){
            $this->json(P_Lang('未指定会员ID'));
        }
        $this->assign("uid",$uid);
        //$rslist = $this->model('user')->address("user_id='".$uid."'");
        //$this->assign("rslist",$rslist);
        //
        //if(!$this->popedom["list"]){
            //error(P_Lang('您没有权限执行此操作'),'','error');
        //}
        $pageid = $this->get($this->config["pageid"],"int");
        if(!$pageid) $pageid = 1;
        $psize = $this->config["psize"];
        if(!$psize) $psize = 30;
        $page_url = $this->url("user","info","uid=".$uid);
        $condition = " user_id='".$uid."'";
        $fullname = trim($this->get("fullname"));
        if($fullname){
            $condition .= " AND fullname='".$fullname."'";
            $this->assign('fullname',$fullname);
        }
        $mobile = trim($this->get("mobile"));
        if($mobile){
            $condition .= " AND mobile ='".$mobile."'";
            $this->assign('mobile',$mobile);
        }
        $offset = ($pageid-1) * $psize;
        $total = $this->model('address')->get_count($condition);
        if($total>0){
            $rslist = $this->model('user')->address_list($condition,$offset,$psize);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($page_url,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
        //
        $this->view("user_address_list");
    }
    //获取身份证图片
    public function address_idcard_f()
    {
        $id = $this->get('id','int');
        if(!$id) $this->json(P_Lang("未指定ID"));
		$rs = $this->model('res')->get_one($id);
		if(!$rs)
		{
			$this->json(P_Lang("没有身份证照片！"));
		}
		$this->assign('rs',$rs);
		$this->view('user_address_idcard');
    }
    //装袋功能
    public function move_cate_f(){
        $ids = $this->get("ids");
        $group_id = $this->get("group_id");
        $type = $this->get('type');
        if(!$group_id || !$ids || !$type){
            $this->json(P_Lang('参数传递不完整'));
        }
        $list = explode(",",$ids);
        $delete_ok = true;
        foreach($list AS $key=>$value){
            $value = intval($value);
            if(!$value){
                continue;
            }
            if($type == 'move'){
                $this->model('user')->save(array("group_id"=>$group_id),$value);
            }
            if($type == 'cancel'){
                $this->model('user')->save(array("group_id"=>2),$value);
            }
        }
        $this->json(P_Lang('更新成功'),true);
    }
    public function address_setting_f()
    {
        $id = $this->get('id','int');
        $this->assign("id",$id);
        $rs = array();
        $info = form_edit('pca',array('p'=>$rs['province'],'c'=>$rs['city'],'a'=>$rs['county']),'pca');
        $this->assign('pca_rs',$info);
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            $idlist[] = strtolower($value["identifier"]);
            if($rs[$value["identifier"]]){
                $value["content"] = $rs[$value["identifier"]];
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $country = $this->model('address')->country();
        $this->assign('country',$country);
        $this->view('usercp_address_setting');
    }
    public function address_setok_f()
    {
        $id = $this->get('id','int');
        $uid = $this->get('user_id','int');
        $array = array();
        $array['user_id'] = $uid;
        $array['fullname'] = $this->get('fullname');
        if(!$array['fullname']){
            $this->json('收件人不能为空');
        }
        $array['mobile'] = $this->get('mobile');
        if(!$array['mobile']){
            $this->json('手机(Mobile)不能为空');
        }
        $array['country'] = $this->get('country');
        if($array['country']=='中国'){
            if(!$this->lib('common')->tel_check($array['mobile'],'mobile')){
                $this->json(P_Lang('手机号格式不对，请填写11位数字'));
            }
            $array['province'] = $this->get('pca_p');
            $array['city'] = $this->get('pca_c');
            if(!$array['province'] || !$array['city'] ){
                $this->json('所在省市(Province)不能为空');
            }
            $array['county'] = $this->get('pca_a');
            $array['idcard'] = $this->get('idcard');
            if($array['idcard'] && !$this->lib('common')->idcard_check($array['idcard'])){
                $this->json(P_Lang('身份证号不合法'));
            }
            $array['idcard_front'] = $this->get('idcard_front');
            $array['idcard_back'] = $this->get('idcard_back');
        }else{
            $array['province'] = $this->get('prov');
            if(!$array['province']){
                $this->json('州/省(State)不能为空');
            }
            $array['city'] = $this->get('city');
            if(!$array['city']){
                $this->json('城市(City)不能为空');
            }
        }
        $array['address'] = $this->get('address');
        if(!$array['address']){
            $this->json('详细地址(Address)不能为空');
        }
        $array['zipcode'] = $this->get('zipcode');
        $array['tel'] = $this->get('tel');
        $array['email'] = $this->get('email');
        if($array['email']){
            if(!$this->lib('common')->email_check($array['email'])){
                $this->json(P_Lang('邮箱格式不对'));
            }
        }
		$this->model('user')->address_save($array,$id);
        $this->json(true);
    }
    public function address_f(){
        $user_id = $this->get('user_id','int');
        $fullname = $this->get('fullname');
        $rs = $this->model('user')->address("user_id=".$user_id." and fullname like '%".$fullname."%'");
        $this->json($rs,true);
    }
    public function address_one_f(){
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('user')->address_one($id);
            $info = form_edit('pca',array('p'=>$rs['province'],'c'=>$rs['city'],'a'=>$rs['county']),'pca');
            $this->json($info,true);
        }
    }
    public function show_f()
    {
        if(!$this->popedom["modify"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $id = $this->get("id","int");
        if($id){
            $rs = $this->model('user')->get_one($id);
        }
        //会员组
        $grouplist = $this->model('usergroup')->get_one($rs['group_id']);
        $this->assign("grouplist",$grouplist);
        $this->assign("rs",$rs);
        $this->assign("id",$id);
        $this->view("user_show");
    }
    public function sender_list_f()
    {
        //读取会员地址库
        if(!$this->popedom["list"]){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config["pageid"],"int");
        if(!$pageid) $pageid = 1;
        $psize = $this->config["psize"];
        if(!$psize) $psize = 30;
        $page_url = $this->url("user","sender_list");
        $condition = " 1=1";
        $fullname = trim($this->get("fullname"));
        if($fullname){
            $condition .= " AND fullname='".$fullname."'";
            $this->assign('fullname',$fullname);
        }
        $tel = trim($this->get("tel"));
        if($tel){
            $condition .= " AND tel ='".$tel."'";
            $this->assign('tel',$tel);
        }
        $offset = ($pageid-1) * $psize;
        $total = $this->model('sender')->get_count($condition);
        if($total>0){
            $rslist = $this->model('sender')->sender_list($condition,$offset,$psize);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($page_url,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
        $this->view('sender_list');
    }
    public function sender_set_f()
    {
        $id = $this->get('id','int');
        $rs = array();
        if($id){
            $rs = $this->model('sender')->get_one($id);
            if(!$rs){
                error(P_Lang('数据获取失败，请检查'));
            }
            $this->assign('rs',$rs);
            $this->assign('id',$id);
        }
        $this->view('sender_set');
    }
    public function sender_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $this->model('sender')->delete($id);
        $this->json(true);
    }
    public function sender_setok_f()
    {
        $id = $this->get('id','int');
        $uid = $this->get('user_id','int');
        $array = array();
        $array['user_id'] = $uid;
        $array['fullname'] = $this->get('fullname');
        if(!$array['fullname']){
            $this->json('收件人不能为空');
        }
        $array['tel'] = $this->get('tel');
        if(!$array['tel']){
            $this->json('电话不能为空');
        }
        $array['address'] = $this->get('address');
        if(!$array['address']){
            $this->json('详细地址不能为空');
        }
        $array['zipcode'] = $this->get('zipcode');
        $this->model('sender')->save($array,$id);
        $this->json(true);
    }
    public function get_user_f()
    {
        $ucode = trim($this->get('ucode'));
        $rs = $this->model('user')->uid_from_ucode($ucode);
        if(!$rs){
            $this->json('会员编号不正确');
        }else{
            //获取资费
            $channel=$this->model('channel')->channel_list($rs['group_id']);
            $this->json(array('rs'=>$rs,'channel'=>$channel),true);
        }
    }
    public function move_stock_f(){
        $ids = $this->get("ids");
        $stock_id = $this->get("stock_id");
        if(!$stock_id || !$ids){
            $this->json(P_Lang('参数传递不完整'));
        }
        $list = explode(",",$ids);
        foreach($list AS $key=>$value){
            $value = intval($value);
            if(!$value){
                continue;
            }
            $this->model('user')->save(array("stock_id"=>$stock_id),$value);
        }
        $this->json(P_Lang('更新成功'),true);
    }
}
?>