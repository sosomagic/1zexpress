<?php
/***********************************************************
	Filename: www/logout_control.php
	Note	: 会员退出操作
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013年07月01日 05时33分
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class logout_control extends dsy_control
{
	function __construct()
	{
		parent::control();
	}
	public function index_f()
    {
        $nickname = $this->session->val('user_name');
        $this->session->unassign('user_id');
        $this->session->unassign('user_gid');
        $this->session->unassign('user_name');
        $this->session->unassign('user_ucode');
        $tip = P_Lang('会员{user}成功退出',array('user'=>'<span class="red"> '.$nickname.' </span>'));
        $this->success($tip,$this->url,1);
    }
}
?>