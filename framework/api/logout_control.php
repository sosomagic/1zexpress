<?php
/***********************************************************
	Filename: api/logout_control.php
	Note	: 会员退出接口
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class logout_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}
	public function index_f()
    {
        $this->session->unassign('user_id');
        $this->session->unassign('user_gid');
        $this->session->unassign('user_name');
        $this->session->unassign('user_ucode');
        $this->json(true);
    }
}
?>