<?php
/*****************************************************************************************
	文件： plugins/loginext/www.php
	备注： 前台操作
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class www_loginext extends dsy_plugin
{
	public $me;
	//QQ账号信息
	private $qqurl = 'https://graph.qq.com/user/get_user_info';
	private $weibo;
	private $state;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->plugin_info();
		$this->tpl->assign('plugin',$this->me);
		if($this->me['param']['wb_appkey'] && $this->me['param']['wb_secret']){
			include_once($this->me['path'].'saetv2.ex.class.php');
			$this->weibo = new SaeTOAuthV2($this->me['param']['wb_appkey'],$this->me['param']['wb_secret']);
		}
	}

	//登录页增加插件
	public function ap_login_index_after()
	{
		if($this->tpl->tpl_value['_back']){
			$_SESSION['_back'] = $this->tpl->tpl_value['_back'];
		}
		$this->links();
	}

	public function html_login_index_dsybody()
	{
		$this->_show('login.html');
	}

	public function html_usercp_index_dsybody()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."plugin_loginext WHERE uid='".$this->session->val('user_id')."'";
		$tmplist = $this->db->get_all($sql);
		$bindlist = array('qq'=>false,'weixin'=>false,'weibo'=>false);
		if($tmplist){
			foreach($tmplist as $key=>$value){
				$bindlist[$value['type']] = true;
			}
		}
		$this->assign('binder',$bindlist);
		$this->links();
		$this->_show('_bindaccount.html');
	}

	public function getlinks()
	{
		$info = $this->links();
		$this->json($info,true);
	}

	private function links()
	{
		if($this->me['param']['qq_appid'] && $this->me['param']['qq_appkey']){
			$qqlink = $this->create_url('qq');
			$this->tpl->assign('qqlink',$qqlink);
		}
		if($this->me['param']['wx_appid'] && $this->me['param']['wx_secret']){
			$wxlink = $this->create_url('weixin');
			$this->tpl->assign('wxlink',$wxlink);
		}
		if($this->me['param']['wb_appkey'] && $this->me['param']['wb_secret']){
			$wblink = $this->weibo->getAuthorizeURL($this->url('plugin','','id=loginext&exec=weibo'));
			$this->tpl->assign('wblink',$wblink);
		}
		return array('qqlink'=>$qqlink,'wxlink'=>$wxlink,'wblink'=>$wblink);
	}

	public function weixin()
	{
		$state = $this->get('state');
		if(!$state || ($state && $state != $this->session->val('weixin_state'))){
			$this->error('登录有异常，请检查');
		}
		$code = $this->get('code');
		if(!$code){
			$this->error('授权登录异常，请检查');
		}
		$token_url  = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->me['param']['wx_appid'];
		$token_url .= "&secret=".$this->me['param']['wx_secret'];
		$token_url .= "&code=".$code."&grant_type=authorization_code";
		$response = $this->lib('html')->get_content($token_url);
		if(strpos($response,"callback") !== false){
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = $this->lib('json')->decode($response);
			if(isset($msg['errcode'])){
				$this->error('登录异常，错误ID：'.$msg['errcode'].'，错误描述：'.$msg['errmsg']);
			}
		}
		$params = $this->lib('json')->decode($response);
		if(isset($params['errcode'])){
			$this->error('登录异常，错误ID：'.$params['errcode'].'，错误描述：'.$params['errmsg']);
		}
		$token = $params['access_token'];
		$expires_in = $params['expires_in'] - 1800;
		$refresh_token = $params['refresh_token'];
		$openid = $params['openid'];
		$last_time = $this->time;
		$sql = "SELECT * FROM ".$this->db->prefix."plugin_loginext WHERE openid='".$openid."' AND type='weixin'";
		$rs = $this->db->get_one($sql);
		if($rs){
			if($this->session->val('user_id') && $rs['uid'] && $rs['uid'] != $this->session->val('user_id')){
				$this->joinuser($this->session->val('user_id'),$rs['uid']);
			}
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET token='".$token."',expire_time='".$expires_in."',last_time='".$last_time."'";
			$sql.= ",refresh_token='".$refresh_token."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
		}else{
			$array = array('openid'=>$openid,'type'=>'weixin');
			$array['token'] = $token;
			$array['expire_time'] = $expires_in;
			$array['last_time'] = $last_time;
			$array['refresh_token'] = $refresh_token;
			$insert_id = $this->db->insert_array($array,"plugin_loginext");
			$rs = $array;
			$rs['id'] = $insert_id;
		}
		if($this->session->val('user_id')){
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$this->session->val('user_id')."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$this->_location($this->url('plugin','index','id=loginext&exec=bindaccount'));
		}
		if(!$rs['uid']){
			$open_id_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".urlencode($rs['token']).'&openid='.$rs['openid'];
			$info = $this->lib('html')->get_content($open_id_url);
			if(strpos($info,"callback") !== false){
				$lpos = strpos($info, "(");
				$rpos = strrpos($info, ")");
				$info = substr($info, $lpos + 1, $rpos - $lpos -1);
			}
			$user = $this->lib('json')->decode($info);
			if(isset($user['errcode'])){
				error('登录异常，错误ID：'.$user['errcode'].'，错误描述：'.$user['errmsg']);
			}
			$avatar = $user['headimgurl'];
			$nickname = $user['nickname'];
			$nickname = trim($nickname);
			if(!$nickname){
				$nickname = "AWX-".$this->time.'-'.rand(100,999);
			}
			$chkname = $this->model('user')->chk_name($nickname);
			if($chkname){
				$nickname = "AWX-".$this->time.'-'.rand(100,999);
			}
			//更新会员头像
			$imginfo = $this->lib('html')->get_content($avatar);
			$avatarfile = "res/user/avatar/".$rs['id'].'.jpg';
			$this->lib('file')->save_pic($imginfo,$avatarfile);
			$array = array();
			$array["user"] = $nickname;
			$array["pass"] = password_create($this->lib('common')->str_rand());
			$array['email'] = '';
			$array['mobile'] = '';
			$group_rs = $this->model('usergroup')->get_default();
			$group_id = $group_rs ? $group_rs['id'] : 0;
			$array['group_id'] = $group_id;
			//$array["status"] = 1;
            //开启审核
            $array["status"] = $group_rs["register_status"] == '1' ? 1 : 0;
			$array["regtime"] = $this->time;
			$array['avatar'] = $avatarfile;
            $array['ucode'] = $this ->model('user')->create_ucode(5);
			$uid = $this->model('user')->save($array);
			if(!$uid){
				$this->error('系统注册会员失败，请联系网站管理员');
			}
            $this->model('user')->save_ext(array('id'=>$uid));
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$uid."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$rs['uid'] = $uid;
            //后台开启审核
            if(!$group_rs["tbl_id"] && !$group_rs['register_status']){
                $this->error('等待管理员审核');
            }
		}
		$info = $this->model('user')->get_one($rs['uid']);
		$this->session->assign('user_id',$info['id']);
		$this->session->assign('user_gid',$info['group_id']);
		$this->session->assign('user_name',$info['user']);
        $this->session->assign('user_ucode',$info['ucode']);
		$backurl = $this->session->val("_back");
		if(!$backurl){
			$backurl = $this->config['url'];
		}
		$this->_location($backurl);	
	}
	
	public function weibo()
	{
		$code = $this->get('code');
		if($code){
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->url('plugin','','id=loginext&exec=weibo');
			$tk = $this->weibo->getAccessToken( 'code', $keys ) ;
		}
		if(!$tk){
			error('登录有异常，请检查',$this->url('login'),'error');
		}
		$token = $tk['access_token'];
		$expires_in = $tk['expires_in'];
		$last_time = $this->time - 1800;
		$refresh_token = $tk['refresh_token'];
		$c = new SaeTClientV2($this->me['param']['wb_appkey'] , $this->me['param']['wb_secret'],$token);
		$uid_get = $c->get_uid();
		$openid = $uid_get['uid'];
		$sql = "SELECT * FROM ".$this->db->prefix."plugin_loginext WHERE openid='".$openid."' AND type='weibo'";
		$rs = $this->db->get_one($sql);
		if($rs){
			if($this->session->val('user_id') && $rs['uid'] && $rs['uid'] != $this->session->val('user_id')){
				$this->joinuser($this->session->val('user_id'),$rs['uid']);
			}
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET token='".$token."',expire_time='".$expires_in."',last_time='".$last_time."'";
			$sql.= ",refresh_token='".$refresh_token."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
		}else{
			$array = array('openid'=>$openid,'type'=>'weibo');
			$array['token'] = $token;
			$array['expire_time'] = $expires_in;
			$array['last_time'] = $last_time;
			$array['refresh_token'] = $refresh_token;
			$insert_id = $this->db->insert_array($array,"plugin_loginext");
			$rs = $array;
			$rs['id'] = $insert_id;
		}
		if($this->session->val('user_id')){
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$this->session->val('user_id')."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$this->_location($this->url('plugin','index','id=loginext&exec=bindaccount'));
		}
		if(!$rs['uid']){
			$c = new SaeTClientV2($this->me['param']['wb_appkey'] , $this->me['param']['wb_secret'],$rs['token']);
			$user = $c->show_user_by_id($rs['openid']);
			if($user['error_code']){
				$this->error('登录异常，错误ID：'.$user['error_code'].'，错误描述：'.$user['error']);
			}
			$nickname = $user['screen_name'];
			$avatar = $user['profile_image_url'];
			$nickname = trim($nickname);
			if(!$nickname){
				$nickname = "AWB-".$this->time.'-'.rand(100,999);
			}
			$chkname = $this->model('user')->chk_name($nickname);
			if($chkname){
				$nickname = "AWB-".$this->time.'-'.rand(100,999);
			}
			//更新会员头像
			$imginfo = $this->lib('html')->get_content($avatar);
			$avatarfile = "res/user/avatar/".$rs['id'].'.jpg';
			$this->lib('file')->save_pic($imginfo,$avatarfile);
			$array = array();
			$array["user"] = $nickname;
			$array["pass"] = password_create($this->lib('common')->str_rand());
			$array['email'] = '';
			$array['mobile'] = '';
			$group_rs = $this->model('usergroup')->get_default();
			$group_id = $group_rs ? $group_rs['id'] : 0;
			$array['group_id'] = $group_id;
			//$array["status"] = 1;
            $array["status"] = $group_rs["register_status"] == '1' ? 1 : 0;
			$array["regtime"] = $this->time;
			$array['avatar'] = $avatarfile;
            $array['ucode'] = $this ->model('user')->create_ucode(5);
			$uid = $this->model('user')->save($array);
			if(!$uid){
				$this->error('系统注册会员失败，请联系网站管理员');
			}
            $this->model('user')->save_ext(array('id'=>$uid));
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$uid."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$rs['uid'] = $uid;
            //后台开启审核
            if(!$group_rs["tbl_id"] && !$group_rs['register_status']){
                $this->error('等待管理员审核');
            }
		}
		$info = $this->model('user')->get_one($rs['uid']);
        $this->session->assign('user_id',$info['id']);
        $this->session->assign('user_gid',$info['group_id']);
        $this->session->assign('user_name',$info['user']);
        $this->session->assign('user_ucode',$info['ucode']);
		$backurl = $this->session->val("_back");
		if(!$backurl){
			$backurl = $this->config['url'];
		}
		$this->_location($backurl);
	}

	public function qq()
	{
		$state = $this->get('state');
		$qqstate = $this->session->val('qq_state');
		if(!$qqstate){
			$this->error('SESSION丢失');
		}
		if(!$state){
			$this->error('参数丢失');
		}
		if($state != $qqstate){
			$this->error("参数异常，请检查");
		}
		$token_url  = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code";
		$token_url .= "&client_id=".$this->me['param']["qq_appid"];
		$token_url .= "&redirect_uri=".urlencode($this->url('plugin','','id=loginext&exec=qq'));
		$token_url .= "&client_secret=".$this->me['param']["qq_appkey"]."&code=".$this->get('code');
		$response = $this->lib('html')->get_content($token_url);
		if(strpos($response,"callback") !== false){
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = $this->lib('json')->decode($response);
			if(isset($msg['error'])){
				$this->error('登录异常，错误ID：'.$msg['error'].'，错误描述：'.$msg['error_description']);
			}
		}
		$params = array();
		parse_str($response, $params);
		$token = $params['access_token'];
		$expires_in = $params['expires_in'] - 1800;
		$refresh_token = $params['refresh_token'];
		$last_time = $this->time;
		$open_id_url = "https://graph.qq.com/oauth2.0/me?access_token=".urlencode($token);
		$info = $this->lib('html')->get_content($open_id_url);
		if(strpos($info,"callback") !== false){
			$lpos = strpos($info, "(");
			$rpos = strrpos($info, ")");
			$info = substr($info, $lpos + 1, $rpos - $lpos -1);
		}
		$user = $this->lib('json')->decode($info);
		if(isset($user['error'])){
			$this->error('登录异常，错误ID：'.$user['error'].'，错误描述：'.$user['error_description']);
		}
		$sql = "SELECT * FROM ".$this->db->prefix."plugin_loginext WHERE openid='".$user['openid']."' AND type='qq'";
		$rs = $this->db->get_one($sql);
		$login_status = false;
		if($rs){
			if($this->session->val('user_id') && $rs['uid'] && $rs['uid'] != $this->session->val('user_id')){
				$this->joinuser($this->session->val('user_id'),$rs['uid']);
			}
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET token='".$token."',expire_time='".$expires_in."',last_time='".$last_time."'";
			$sql.= ",refresh_token='".$refresh_token."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
		}else{
			$array = array('openid'=>$user['openid'],'type'=>'qq');
			$array['token'] = $token;
			$array['expire_time'] = $expires_in;
			$array['last_time'] = $last_time;
			$array['refresh_token'] = $refresh_token;
			$insert_id = $this->db->insert_array($array,"plugin_loginext");
			$rs = $array;
			$rs['id'] = $insert_id;
		}
		if($this->session->val('user_id')){
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$this->session->val('user_id')."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$this->_location($this->url('plugin','index','id=loginext&exec=bindaccount'));
		}
		if(!$rs['uid']){
			$url = 'https://graph.qq.com/user/get_user_info'.'?oauth_consumer_key='.$this->me['param']['qq_appid'];
			$url.= "&access_token=".$rs['token'].'&openid='.$rs['openid'].'&format=json';
			$info = $this->lib('html')->get_content($url);
			$info = $this->lib('json')->decode($info);
			$avatar = $info['figureurl_2'] ? $info['figureurl_2'] : $info['figureurl_1'];
			$nickname = $info['nickname'];
			$nickname = trim($nickname);
			if(!$nickname){
				$nickname = "AQQ-".$this->time.'-'.rand(100,999);
			}
			$chkname = $this->model('user')->chk_name($nickname);
			if($chkname){
				$nickname = "AQQ-".$this->time.'-'.rand(100,999);
			}
			//更新会员头像
			$imginfo = $this->lib('html')->get_content($avatar);
			$avatarfile = "res/user/avatar/".$rs['id'].'.jpg';
			$this->lib('file')->save_pic($imginfo,$avatarfile);
			$array = array();
			$array["user"] = $nickname;
			$array["pass"] = password_create($this->lib('common')->str_rand());
			$array['email'] = '';
			$array['mobile'] = '';
			$group_rs = $this->model('usergroup')->get_default();
			$group_id = $group_rs ? $group_rs['id'] : 0;
			$array['group_id'] = $group_id;
			//$array["status"] = 1;
            $array["status"] = $group_rs["register_status"] == '1' ? 1 : 0;
			$array["regtime"] = $this->time;
			$array['avatar'] = $avatarfile;
            $array['ucode'] = $this ->model('user')->create_ucode(5);
			$uid = $this->model('user')->save($array);
			if(!$uid){
				$this->error('系统注册会员失败，请联系网站管理员');
			}
            $this->model('user')->save_ext(array('id'=>$uid));
			$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$uid."' WHERE id='".$rs['id']."'";
			$this->db->query($sql);
			$rs['uid'] = $uid;
            //后台开启审核
            if(!$group_rs["tbl_id"] && !$group_rs['register_status']){
                $this->error('等待管理员审核');
            }
		}
		$info = $this->model('user')->get_one($rs['uid']);
        $this->session->assign('user_id',$info['id']);
        $this->session->assign('user_gid',$info['group_id']);
        $this->session->assign('user_name',$info['user']);
        $this->session->assign('user_ucode',$info['ucode']);
		$backurl = $this->session->val("_back");
		if(!$backurl){
			$backurl = $this->config['url'];
		}
		$this->_location($backurl);
	}

	private function create_url($type='qq')
	{
		if($type == 'weixin'){
			$state = $this->get_state('weixin');
			$url = 'https://open.weixin.qq.com/connect/qrconnect?appid='.$this->me['param']['wx_appid'];
			$url.= "&redirect_uri=".urlencode($this->url('plugin','','id=loginext&exec=weixin'));
			$url.= "&response_type=code&scope=snsapi_login&state=".$state."#wechat_redirect";
			return $url;
		}
		if($type == 'qq'){
			$query = array('response_type'=>'code','client_id'=>$this->me['param']['qq_appid']);
			$query['redirect_uri'] = $this->url('plugin','','id=loginext&exec=qq','www',true);
			$query['state'] = $this->get_state('qq');
			$query['scope'] = 'get_user_info';
			return 'https://graph.qq.com/oauth2.0/authorize?'.http_build_query($query);
		}
		return false;
	}

	private function get_state($type='qq')
	{
		$id = $type.'_state';
		if($this->session->val($id)){
			return $this->session->val($id);
		}
		$info = md5(uniqid(rand(), true));
		$this->session->assign($id,$info);
		return $info;
	}

	public function bindaccount()
	{
		if(!$this->session->val('user_id')){
			$this->error('非会员不能执行此操作',$this->url('login'));
		}
		$sql = "SELECT * FROM ".$this->db->prefix."plugin_loginext WHERE uid='".$this->session->val('user_id')."'";
		$tmplist = $this->db->get_all($sql);
		$bindlist = array('qq'=>false,'weixin'=>false,'weibo'=>false);
		if($tmplist){
			foreach($tmplist as $key=>$value){
				$bindlist[$value['type']] = true;
			}
		}
		$this->assign('binder',$bindlist);
		$this->links();
        //设置默认仓库，渠道
        $stock = $this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $channel_list=$this->model('channel')->get_channelList();
        $this->assign('channel_list',$channel_list);
        $rs = $this->model('user')->get_one($this->session->val('user_id'));
        $this->assign('rs',$rs);
		$this->_view("bindaccount.html");
	}

	private function joinuser($main_uid=0,$ext_uid=0)
	{
		if(!$main_uid || !$ext_uid || $main_uid == $ext_uid){
			return false;
		}
		$sql = "UPDATE ".$this->db->prefix."plugin_loginext SET uid='".$main_uid."' WHERE uid='".$ext_uid."'";
		$this->db->query($sql);
		//合并主题数
		$sql = "UPDATE ".$this->db->prefix."list SET user_id='".$main_uid."' WHERE user_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并收藏夹
		$sql = "UPDATE ".$this->db->prefix."fav SET user_id='".$main_uid."' WHERE user_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并订单
		$sql = "UPDATE ".$this->db->prefix."order SET user_id='".$main_uid."' WHERE user_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并支付记录
		$sql = "UPDATE ".$this->db->prefix."payment_log SET user_id='".$main_uid."' WHERE user_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并回复
		$sql = "UPDATE ".$this->db->prefix."reply SET uid='".$main_uid."' WHERE uid='".$ext_uid."'";
		$this->db->query($sql);
		//合并附件
		$sql = "UPDATE ".$this->db->prefix."res SET user_id='".$main_uid."' WHERE user_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并介绍人
		$sql = "UPDATE ".$this->db->prefix."user_relation SET introducer='".$main_uid."' WHERE introducer='".$ext_uid."'";
		$this->db->query($sql);
		//合并财富日志
		$sql = "UPDATE ".$this->db->prefix."wealth_log SET goal_id='".$main_uid."' WHERE goal_id='".$ext_uid."'";
		$this->db->query($sql);
		//合并财富
		$sql = "SELECT * FROM ".$this->db->prefix."wealth_info WHERE uid='".$ext_uid."'";
		$tmplist = $this->db->get_all($sql);
		if($tmplist){
			foreach($tmplist as $key=>$value){
				$sql = "SELECT * FROM ".$this->db->prefix."wealth_info WHERE uid='".$main_uid."' AND wid='".$value['wid']."'";
				$tmprs = $this->db->get_one($sql);
				if(!$tmprs){
					$array = $value;
					$array['uid'] = $main_uid;
					$this->db->insert_array($array,'wealth_info','replace');
				}else{
					$tmp = $tmprs['val'] + $value['val'];
					if($tmp<0){
						$tmp = 0;
					}
					$sql = "UPDATE ".$this->db->prefix."wealth_info SET val='".$tmp."',lasttime='".$this->time."' WHERE wid='".$value['wid']."' AND uid='".$main_uid."'";
					$this->db->query($sql);
				}
			}
			$sql = "DELETE FROM ".$this->db->prefix."wealth_info WHERE uid='".$ext_uid."'";
			$this->db->query($sql);
		}
		//删除会员数据
		$this->model('user')->del($ext_uid);
		return true;
	}
}