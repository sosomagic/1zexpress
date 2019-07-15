<?php
/***********************************************************
	Filename: /www/login_control.php
	Note	: 会员登录操作
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2016年05月16日 09时34分
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class login_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}

	public function index_f()
	{
		$_back = $this->get("_back");
		if(!$_back){
			$_back = $this->url;
		}
		if($_SESSION["user_id"]){
			error(P_Lang('您已是本站会员，不需要再次登录'),$_back);
		}
		if(!$this->site['login_status']){
			$tips = $this->site["login_close"] ? $this->site["login_close"] : P_Lang('网站关闭');
			error($tips,$_back,'error',10);
		}
		$this->assign("_back",$_back);
		$this->view("login");
	}

	//登录，基于HTML模式
	public function ok_f()
	{
        $_back = $this->get("_back");
        if($_back==$this->url || !$_back){
            $_back = $this->url('usercp');
            $error_url = $this->url('login');
        }else{
            $error_url = $this->url('login','','_back='.rawurlencode($_back));
        }
        if($this->session->val('user_id')){
            $this->success(P_Lang('您已是本站会员，不需要再次登录'),$_back);
        }
		if($this->config['is_vcode'] && function_exists('imagecreate')){
			$code = $this->get('_chkcode');
			if(!$code){
				$this->error(P_Lang('验证码不能为空'),$error_url);
			}
			$code = md5(strtolower($code));
			if($code != $_SESSION['vcode']){
                $this->error(P_Lang('验证码填写不正确'),$error_url);
			}
            $this->session->unassign('vocode');
		}
		//获取登录信息
		$user = $this->get("user");
		if(!$user){
            $this->error(P_Lang('账号不能为空'),$error_url);
		}
		$pass = $this->get("pass");
		if(!$pass){
            $this->error(P_Lang('会员密码不能为空'),$error_url);
		}
		//多种登录方式
		$user_rs = $this->model('user')->get_one($user,'user');
		if(!$user_rs){
			$user_rs = $this->model('user')->get_one($user,'email');
			if(!$user_rs){
				$user_rs = $this->model('user')->get_one($user,'mobile');
				if(!$user_rs){
                    $this->error(P_Lang('会员信息不存在'),$error_url);
				}
			}
		}
		if(!$user_rs['status']){
            $this->error(P_Lang('会员审核中，暂时不能登录'),$error_url);
		}
		if($user_rs['status'] == '2'){
            $this->error(P_Lang('会员被管理员锁定，请联系管理员解锁'),$error_url);
		}
		if(!password_check($pass,$user_rs["pass"])){
            $this->error(P_Lang('登录密码不正确'),$error_url);
		}
        $this->session->assign('user_id',$user_rs['id']);
        $this->session->assign('user_gid',$user_rs['group_id']);
        $this->session->assign('user_name',$user_rs['user']);
        $this->session->assign('user_ucode',$user_rs['ucode']);
        //接入财富
        //$this->model('wealth')->login($user_rs['id'],P_Lang('会员登录'));
        $this->success(P_Lang('会员登录成功'),$_back,2);
	}

	//弹出窗口
	public function open_f()
	{
		if($_SESSION["user_id"]){
			error(P_Lang('您已是本站会员，不需要再次登录'));
		}
		if(!$this->site['login_status']){
			$tips = $this->site["login_close"] ? $this->site["login_close"] : P_Lang('网站关闭');
			error($tips,'','error');
		}
		$this->view("login_open");
	}

	/**
	 * 取回密码
	 * @参数 _back 返回之前页面
	**/
	public function getpass_f()
	{
		$_back = $this->get("_back");
		if(!$_back){
			$_back = $this->config['url'];
			$error_url = $this->url('login');
		}else{
			$error_url = $this->url('login','','_back='.rawurlencode($_back));
		}
		if($this->session->val('user_id')){
			$this->error(P_Lang('您已是本站会员，不能执行这个操作'),$_back);
		}
		$server = $this->model('gateway')->get_default('email');
		$sms_server = $this->model('gateway')->get_default('sms');
		if(!$server && !$sms_server){
			$this->error(P_Lang('未配置好邮件/短信通知功能，请联系管理员'),$error_url,10);
		}
		$getpasstype = array('sms'=>false,'email'=>false);
		if($sms_server){
			$getpasstype['sms'] = true;
		}
		if($server){
			$getpasstype['email'] = true;
		}
		$this->assign('getpasstype',$getpasstype);
		if(!$server){
			$this->view("login_smspass");
		}
		$this->view("login_getpass");
	}

	/**
	 * 重置密码操作
	 * @参数 _back 返回之前跳转的页面
	 * @参数 _code 险证码
	 * @参数 
	 * @返回 
	 * @更新时间 
	**/
	public function repass_f()
	{
		$_back = $this->get("_back");
		if(!$_back){
			$_back = $this->config['url'];
			$error_url = $this->url('login');
		}else{
			$error_url = $this->url('login','','_back='.rawurlencode($_back));
		}
		if($this->session->val('user_id')){
			$this->error(P_Lang('您已是本站会员，不能执行这个操作'),$_back);
		}
		$code = $this->get('_code');
		if($code){
			$time = intval(substr($code,-10));
			if(($this->time - $time) > (24*60*60)){
				$this->error(P_Lang('验证码超时过期，请重新获取'),$this->url('login','getpass'),10);
			}
			$uid = $this->model('user')->uid_from_chkcode($code);
			if(!$uid){
				$this->error(P_Lang('验证码不存在'),$this->url('login','getpass'),10);
			}
			$this->assign('code',$code);
			$user = $this->model('user')->get_one($uid);
			$this->assign("user",$user);
		}
		$this->view('login_repass');
	}

}
?>