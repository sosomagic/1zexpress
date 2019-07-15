<?php
/***********************************************************
	Filename: api/usercp_control.php
	Note	: 会员中心数据存储
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013年11月5日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class usercp_control extends dsy_control
{
	private $user_id; //会员ID
	public function __construct()
	{
		parent::control();
		if(!$this->session->val('user_id')){
			$this->error(P_Lang('您还没有登录，请先登录或注册'));
		}
		$this->user_id = $this->session->val('user_id');
	}

	//存储个人数据
	public function info_f()
	{
       // $group_id = $this->get('group_id','int');
        $email = $this->get('email');
        if(!$email){
            $this->json(P_Lang('邮箱不能为空！'));
        }
        $user['email'] = $email;
        $mobile = $this->get('mobile');
        if(!$mobile){
            $this->json(P_Lang('手机不能为空！'));
        }
        $user['mobile'] = $mobile;
        $user_id = $this->user_id;
        $this->model('user')->update_user($user,$user_id);
        $first_name = $this->get('first_name');
        if(!$first_name){
            $this->json(P_Lang('First Name不能为空'));
        }
        $ext['first_name'] = $first_name;
        $ext['last_name'] = $this->get('last_name');
        $address = $this->get('address');
        /*if(!$address){
            $this->json(P_Lang('地址不能为空'));
        }*/
        $ext['address'] = $address;
        $ext['zipcode'] = $this->get('zipcode');
        $ext['tel'] = $this->get('tel');
        $ext['weixin'] = $this->get('weixin');
        $ext['qq'] = $this->get('qq');
        if($ext){
            $this->model('user')->update_ext($ext,$user_id);
        }
		$this->json(true);
	}
	//更新会员密码功能
	public function passwd_f()
	{
		$oldpass = $this->get("oldpass");
		if(!$oldpass){
			$this->json(P_Lang('旧密码不能为空'));
		}
		$newpass = $this->get("newpass");
		$chkpass = $this->get("chkpass");
		if(!$newpass || !$chkpass){
			$this->json(P_Lang('新密码不能为空'));
		}
		if($newpass != $chkpass){
			$this->json(P_Lang('两次输入密码不一致'));
		}
		if($oldpass == $newpass){
            $this->json(P_Lang('新旧密码不能一样'));
        }
		if(!preg_match("/^(?!\s)((?=.*[a-zA-Z])(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).\S{7,15})$/",$newpass)){
            $this->json('请输入8-16位字符，至少包含数字、大小写字母、特殊字符');
        }
        $user = $this->model('user')->get_one($this->user_id);
        if(!password_check($oldpass,$user["pass"])){
            $this->json(P_Lang('旧密码输入错误'));
        }
		$password = password_create($newpass);
		$this->model('user')->update_password($password,$this->user_id);
		$this->json(true);
	}

	//更新地址信息
	public function address_f()
	{
		$id = $this->get('id','int');
		if($id){
			$rs = $this->model('address')->get_one($id);
			if(!$rs){
				$this->json(P_Lang('地址信息不存在'));
			}
			if($rs['user_id'] != $this->user_id){
				$this->json(P_Lang('地址信息与账号不匹配'));
			}
		}
		$data = array();
		$data['type_id'] = $this->get('type') == 'billing' ? 'billing' : 'shipping';
		$data['fullname'] = $this->get('fullname');
		if(!$data['fullname']){
			$this->json(P_Lang('姓名不能为空'));
		}
		$data['gender'] = $this->get('gender','int');
		$data['country'] = $this->get('country');
		if(!$data['country']){
			$data['country'] = '中国';
		}
		$data['province'] = $this->get('province');
		if(!$data['province']){
			$this->json(P_Lang('请选择收件人省份信息'));
		}
		$data['city'] = $this->get('city');
		$data['county'] = $this->get('county');
		$data['address'] = $this->get('address');
		if(!$data['address']){
			$this->json(P_Lang('请填写地址信息，要求尽可能详细'));
		}
		$data['zipcode'] = $this->get('zipcode');
		if(!$data['zipcode']){
			$this->json(P_Lang('邮编不能为空'));
		}
		$data['tel'] = $this->get('tel');
		$data['mobile'] = $this->get('mobile');
		if(!$data['tel'] && !$data['mobile']){
			$this->json(P_Lang('请填写联系电话或手机号'));
		}
		if($data['tel']){
			$type = substr($data['tel'],0,3) == '400' ? '400' : 'tel';
			if(!$this->lib('common')->tel_check($data['tel'],$type)){
				$this->json(P_Lang('电话填写不正确，请规范填写，如：0571-123456789 或 400123456'));
			}
		}
		if($data['mobile']){
			$type = substr($data['mobile'],0,3) == '400' ? '400' : 'mobile';
			if(!$this->lib('common')->tel_check($data['mobile'],$type)){
				$this->json(P_Lang('手机填写不正确，请规范填写，要求以13，15，17，18开头的11位数字 或 400 电话'));
			}
		}
		if($id){
			if($rs['type_id'] != $data['type_id']){
				$this->json(P_Lang('地址类型不匹配'));
			}
			$this->model('address')->save($data,$id);
			$this->json(P_Lang('地址信息更新成功'),true);
		}
		$insert_id = $this->model('address')->save($data);
		if(!$insert_id){
			$this->json(P_Lang('地址信息创建存储失败'));
		}
		$this->json(P_Lang('地址信息创建成功'),true);
	}

	public function sender_default_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$rs = $this->model('user')->sender_one($id);
		if($rs['user_id'] != $this->user_id){
			$this->json(P_Lang('您没有权限操作此地址信息'));
		}
		$this->model('user')->sender_default($id);
		$this->json(true);
	}

	public function address_setting_f()
	{
		$id = $this->get('id','int');
		$array = array();
		if($id){
			$chk = $this->model('user')->address_one($id);
			if(!$chk || $chk['user_id'] != $this->user_id){
				$this->json(P_Lang('您没有权限执行此操作'));
			}
		}else{
			$array['user_id'] = $this->user_id;
		}
		
		$array['fullname'] = $this->get('fullname');
		if(!$array['fullname']){
			$this->json('收件人(Recipients)不能为空');
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
        //判断收件人是否存在
        if($id){
            $this->model('user')->address_save($array,$id);
        }else{
            $rs = $this->model('user')->address("user_id = '".$array['user_id']."'and fullname='".$array['fullname']."' and mobile='".$array['mobile']."'");
            if(!$rs){
                $this->model('user')->address_save($array);
            }else{
                $this->json('收件人已经存在');
            }
        }

		$this->json(true);
	}
    public function sender_setting_f()
    {
        $id = $this->get('id','int');
        $array = array();
        if($id){
            $chk = $this->model('user')->sender_one($id);
            if(!$chk || $chk['user_id'] != $this->user_id){
                $this->json(P_Lang('您没有权限执行此操作'));
            }
        }else{
            $array['user_id'] = $this->user_id;
        }
        $array['fullname'] = $this->get('fullname');
        if(!$array['fullname']){
            $this->json(P_Lang('发件人不能为空'));
        }
        $array['tel'] = $this->get('tel');
        if(!$array['tel']){
            $this->json(P_Lang('电话不能为空'));
        }
        $array['address'] = $this->get('address');
        if(!$array['address']){
            $this->json(P_Lang('地址不能为空'));
        }
        $array['zipcode'] = $this->get('zipcode');
        $this->model('user')->sender_save($array,$id);
        $this->json(true);
    }
    public function sender_delete_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $rs = $this->model('user')->sender_one($id);
        if($rs['user_id'] != $this->user_id){
            $this->json(P_Lang('您没有权限操作此地址信息'));
        }
        $this->model('user')->sender_delete($id);
        $this->json(true);
    }
	public function address_delete_f()
	{
		$id = $this->get('id','int');
		if(!$id){
			$this->json(P_Lang('未指定ID'));
		}
		$rs = $this->model('user')->address_one($id);
		if($rs['user_id'] != $this->user_id){
			$this->json(P_Lang('您没有权限操作此地址信息'));
		}
		$this->model('user')->address_delete($id);
		$this->json(true);
	}

    public function import_f()
    {
        $file = $this->get('dfile','int');
        if(!$file)
        {
            $this->json(P_Lang("未导入Excel文件"));
        }
        $res = $this->model('res')->get_one($file);
        if(!$res)
        {
            $this->json(P_Lang("Excel文件不存在"));
        }
        if(!is_file($this->dir_root.$res["filename"]))
        {
            $this->json(P_Lang("Excel文件不存在"));
        }
        $user_id = $this->user['id'];
        $country = $this->get['country'];
        //通过excel
        include_once $this->dir_root.'extension/phpexcel/PHPExcel.php';
        $filetype = $res["ext"] == "xlsx" ? "Excel2007" : "Excel5";
        $objReader = PHPExcel_IOFactory::createReader($filetype);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->dir_root.$res["filename"]);
        $currentSheet = $objPHPExcel->getSheet(0);
        $allColumn = $currentSheet->getHighestColumn();
        $allRow = $currentSheet->getHighestRow();
        $ColumnNum = PHPExcel_Cell::columnIndexFromString($allColumn);
        //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $filed = array();
        for($i=0; $i<$ColumnNum;$i++){
            $cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            $cellVal = $currentSheet->getCell($cellName)->getValue();//取得列内容
            $filed []= $cellVal;
        }
        //开始取出数据并存入数组
        $data = array();
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            // $data []= $row;
            $data = $row;
            if(!trim($data['姓名'])){
                $this->json('姓名不能为空！');
            }
            if(!trim($data['省'])){
                $this->json('省份不能为空！');
            }
            if(!trim($data['市'])){
                $this->json('城市不能为空！');
            }
            /*if(!trim($data['区/镇'])){
                $this->json('区/镇不能为空！');
            }*/
            if(!trim($data['具体地址'])){
                $this->json('具体地址不能为空！');
            }
            if(!trim($data['手机'])){
                $this->json('收件人手机不能为空！');
            }
            //判断是否存在
            $rs = $this->model('user')->address("user_id = '".$user_id."'and fullname='".trim($data['姓名'])."' and mobile='".trim($data['手机'])."'");
            if($rs) continue;
            $arr = array('user_id'=>$user_id,'country'=>$country,'province'=>$data['省'],'city'=>$data['市'],'address'=>$data['具体地址'],'mobile'=>$data['手机'],'tel'=>$data['电话'],'email'=>$data['邮箱'],'zipcode'=>$data['邮编'],'fullname'=>$data['姓名'],'idcard'=>$data['身份证']);
            $oid = $this->model('user')->address_save($arr);
            if(!$oid){
                $this->json(P_Lang('地址导入失败'));
            }
            /*
             * 判断是否重复
             * $idcard = trim($data['收件人身份证号']);
            $address = $this->model('user')->address_one($idcard,$field_id='idcard');
            if(!$address['id']){
                $this->model('user')->address_save($user_address);
            }*/
        }
        $this->json(true);
    }

    //获取身份证图片
    public function address_idcard_f()
    {
        $id = $this->get('id','int');
        if(!$id) $this->json(P_Lang("未指定ID"));
		$rs = $this->model('res')->get_one($id,true);
		if(!$rs)
		{
			$this->json(P_Lang("没有身份证照片！"));
		}
		$this->assign('rs',$rs);
		$this->view('usercp_address_idcard');
    }
    public function set_f()
    {
        $stock = $this->get('stock');
        /*if(!$stock){
            $this->json(P_Lang('请选择默认仓库'));
        }*/
        $channel = $this->get('channel');
        /*if(!$channel){
            $this->json(P_Lang('请选择默认渠道'));
        }*/
        $user['stock_id'] = $stock;
        $user['channel_id'] = $channel;
        $user['sms'] = $this->get('sms');
        $user['mail'] = $this->get('mail');
        $this->model('user')->update_user($user,$this->user_id);
        $this->json(true);
    }
}
?>