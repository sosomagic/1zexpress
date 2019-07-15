<?php
/***********************************************************
	Filename: www/user_control.php
	Note	: 会员
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年9月30日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class user_control extends dsy_control
{
	function __construct()
	{
		parent::control();

	}

	function index_f()
	{
		$uid = $this->get("uid");
		if(!$uid){
			error(P_Lang('未指定会员信息'));
		}
		$user_rs = $this->model('user')->get_one($uid);
		$this->assign("user_rs",$user_rs);
		$this->view("user_info");
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
            $info = form_edit('pca',array('p'=>$rs['province'],'c'=>$rs['city']),'pca');
            $this->json($info,true);
        }
    }
}
?>