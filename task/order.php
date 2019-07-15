<?php
/*****************************************************************************************
	文件： task/order.php
	备注： 订单通知
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
if(!$param['id'] || !$param['status']){
	return false;
}
$order = $this->model('order')->get_one($param['id']);
if(!$order){
	return false;
}
$this->assign('order',$order);
$ship = $this->model('channel')->get_track($param['status'],'status');
if(!$ship){
    return false;
}
$this->assign('status',$ship['status']);

//通知会员
if($ship['mail']){
	$tpl = $this->model('email')->tpl($ship['mail']);
	if($tpl){
		$address = $this->model('order')->address($order['id']);
		$user = $this->model('user')->get_one($order['user_id']);
		$this->assign('address',$address);
		$this->assign('user',$user);
		$this->gateway('type','email');
		$this->gateway('param','default');
		//$email = $order['email'] ? $order['email'] : ($address['email'] ? $address['email'] : $user['email']);
        $email = $user['email'];
		if(!$email){
			return false;
		}
		//$fullname = $address['fullname'] ? $address['fullname'] : ($user['fullname'] ? $user['fullname'] : $user['user']);
        $fullname = $user['user'];
		$this->assign('fullname',$fullname);
		$title = $this->fetch($tpl['title'],'msg');
		$content = $this->fetch($tpl['content'],'msg');
		if($title && $email && $content){
			$array = array('email'=>$email,'fullname'=>$fullname,'title'=>$title,'content'=>$content);
			$this->gateway('exec',$array);
		}
	}
}

if($ship['sms']){
	$tpl = $this->model('email')->tpl($ship['sms']);
	if($tpl && $tpl['content']){
		$address = $this->model('order')->address($order['id']);
		//$user = $this->model('user')->get_one($order['user_id']);
		$this->assign('address',$address);
		//$this->assign('user',$user);
		$this->gateway('type','sms');
		$this->gateway('param','default');
        //
        $mobile = $address['mobile'];
        if($mobile){
            $content = $tpl['content'] ? $this->fetch($tpl['content'],'msg') : '';
            if($content){
                $content = strip_tags($content);
            }
            $this->gateway('exec',array('mobile'=>$mobile,'content'=>$content));
        }
	}
}
return true;
?>