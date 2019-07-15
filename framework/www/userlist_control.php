<?php
/*****************************************************************************************
	文件： www/userlist_control.php
	备注： 会员组列表
	版本： 4.x
	网站： www.dsaiyin.com
	作者： dsaiyin <QQ:850272422>
	时间： 2015年06月29日 15时08分
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class userlist_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}

	public function index_f()
	{
		$gid = $this->get('gid');
		$array = array();
		$pageurl = $this->url('userlist');
		if($gid){
			$array['group_id'] = $gid;
			$pageurl .= '&gid='.$gid;
		}
		$is_avatar = $this->get('is_avatar','int');
		if($is_avatar){
			$array['is_avatar'] = 1;
			$pageurl .= '&is_avatar=1';
		}
		$pageid = $this->get($this->config['pageid'],'int');
		$array['pageid'] = $pageid;
		$array['psize'] = $this->config['psize'];
		$data = $this->call->dsy('_userlist',$array);
		if($data['total']){
			$this->assign('rslist',$data['rslist']);
			$this->assign('pageurl',$pageurl);
			$this->assign('total',$data['total']);
			$this->assign('pageid',$pageid);
			$this->assign('psize',$array['psize']);
		}
		$this->view('userlist');
	}
}

?>