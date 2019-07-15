<?php
/***********************************************************
	Filename: api/order_control.php
	Note	: 创建运单操作
	Version : 2.0
	Update  : 10:59 2015-12-1
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_control extends dsy_control
{
	private $user_id;
	private $group_id;
	public function __construct()
	{
		parent::control();
		if(!$this->session->val('user_id')){
            $this->error(P_Lang('您还没有登录，请先登录或注册'));
        }
        $this->user_id = $this->session->val('user_id');
        $this->group_id = $this->session->val('user_gid');
	}
    public function create_f()
    {
        $pid = $this->get('ids');//包裹数
        $channel = $this->get('channel');
        if(!$channel){
            $this->json('请选择发货渠道');
        }else{
            $channel=explode('|',$channel);
        }
        //$currency = $this->get('currency');
        $ptype=$this->get('ptype','int');
        if(!$ptype){
            $this->json(P_Lang('请选择合箱/分箱'));
        }
        $user_id = $this->user_id;
        $group_id = $this->group_id;
        $stock_id = $this->get('stock','int');
        //读取运单状态
        $ship = $this->model('channel')->get_track('create','status');
        $group = $this->model('user')->get_one($user_id);
        $wt = $this->get('wt');
        $val = $this->get('val');
        $tax = $this->get('tax');
        $safe = $this->get('safe');
        $safe_price = $this->get('safe_price');
        if($ptype==3){
            $bs_boxnum = $this->get('bs_boxnum','int');
            //$wt = $wt + $bs_boxnum -1;
        }
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $k => $v){
                $custom_tmp= $this->model('custom')->get_one($v);
                $custom_price += $custom_tmp['price'];
            }
        }
        //添加发件人信息
        $consignor = $this->get('consignor');
        $consignor_tel = $this->get('consignor_tel');
        $consignor_address = $this->get('consignor_address');
        //都为空获取发货仓库信息
        if(!$consignor){
			$consign = $this->model('stock')->get_one($stock_id);
            $consignor = $consign['consignor'];
            $consignor_tel = $consign['tel'];
            $consignor_address = $consign['address'];
        }
        //计算运单预估费用
        if($channel[2]<>'0.00'){
            $fn=round($wt-floor($wt),2);
            if($fn <= $channel[2]){
                $wt= floor($wt);
            }
        }
        if($channel[1]=='ceil'){
            $pay_weight = ceil($wt);
        }
        if($channel[1]=='round'){
            $fn=round($wt-floor($wt),2);
            if($fn >0 && $fn < 0.5){
                $pay_weight = floor($wt)+0.5;
            }elseif($fn > 0.5){
                $pay_weight = floor($wt)+1;
            }
        }
        switch($pay_weight){
            case $pay_weight<=$channel[3]:
                $channel_price = $channel[4];
                break;
            case $pay_weight>$channel[3] && $pay_weight<=$channel[7]:
                $channel_price = $channel[4]+($pay_weight-$channel[3])/$channel[5]*$channel[6];
                break;
            case $pay_weight>$channel[7] && $pay_weight<=$channel[17]:
                $channel_price = $channel[4]+($channel[7]-$channel[3])/$channel[5]*$channel[6]+($pay_weight-$channel[7])/$channel[8]*$channel[9];
                break;
            default:
                $channel_price = $channel[4]+($channel[7]-$channel[3])/$channel[5]*$channel[6]+($channel[17]-$channel[7])/$channel[8]*$channel[9]+($pay_weight-$channel[17])/$channel[18]*$channel[19];
        }
        $pay_price = round(($channel_price + $tax + $safe_price+ $custom_price),2); //预估费用
        $balance = $this->model('wealth')->get_val($user_id,2);
        if($pay_price > $balance){
            $this->json(P_Lang('余额不够支付该订单费用，请先充值￥'.$pay_price.'元，再提交运单'));
        }else{
            //分箱判断
            if($ptype==3){
                for($i=0;$i<$bs_boxnum;$i++){ //分箱循环
                    $ydid=$i+1;
                    $aid=$this->get('aid_'.$i,'int');
                    if(!$aid){
                        $this->json(P_Lang('运单'.$ydid.'，请选择收件人信息'));
                    }
                    //判断渠道是否需要身份证
                    $address = $this->model('user')->address_one($aid);
                    if($channel[16]){
                        if(!$address || !$address['idcard'] || !$address['idcard_front'] || !$address['idcard_back']){
                            $this->json(P_Lang('收件人身份证号或身份证图片不齐全'));
                        }
                    }
                }
                for($i=0;$i<$bs_boxnum;$i++){ //分箱循环
                    $aid=$this->get('aid_'.$i,'int');
                    //运单信息添加
                    $sn = $this->model('order')->create_sn();
                    $arr = array('sn'=>$sn);
                    //$arr['number'] = $order_number;//运单号
                    $arr['package_id'] = $pid;
                    $arr['stock_id'] = $stock_id;
                    $arr['user_id'] = $user_id;
                    $arr['addtime'] = $this->time;
                    $arr['status'] = 'check';
                    //$arr['ext'] = '运单创建成功';
                    //$arr['ext'] = $ship['title'];
                    //$arr['passwd'] = md5(str_rand(10));
                    $arr['channel'] = intval($channel[0]);
                    $arr['currency_id'] = intval($channel[10]);
                    $arr['weight_id'] = $channel[12];
                    $arr['val'] = $val;
                    $arr['tax'] = $tax;
                    $arr['safe'] = $safe;
                    $arr['safe_price'] = $safe_price;
                    $arr['type'] = $ptype;//业务要求
                    $arr['weight'] = $wt;
                    $arr['jingzhong'] = $wt;
                    $arr['pay_weight'] = $pay_weight;
                    $arr['channel_price'] = $channel_price;
                    $arr['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
                    $arr['custom_price'] = $custom_price;
                    $arr['price'] = $pay_price;
                    $arr['note'] = $this->get('note');
                    $arr['consignor'] = $consignor;
                    $arr['consignor_tel'] = $consignor_tel;
                    $arr['consignor_address'] = $consignor_address;
                    //导入清关编码
                    if($channel[14]){
                        $code = $this->model('code')->get_code('channel_id='.$channel[0].' and status=0');
                        if($code){
                            $arr['customer_sn'] = $code['title'];
                            $this->model('code')->save(array('status'=>1),$code['id']);
                        }else{
                            $this->json('请导入报关条码');
                        }
                    }
                    //导入国内快递单号
                    if($channel[15]){
                        $number = $this->model('number')->get_number('channel_id='.$channel[0].' and status=0');
                        if($number){
                            $express = $this->model('express')->get_one($number['express_id']);
                            $arr['express_sn'] = $number['title'];
                            $arr['express'] = $express['title'];
                            $this->model('number')->save(array('status'=>1),$number['id']);
                        }else{
                            $this->json('请导入国内快递单号');
                        }
                    }
                    $oid = $this->model('order')->save($arr);
                    if(!$oid){
                        $this->json('运单'.$ydid.'，提交失败');
                    }
                    //增加快递order_erpress,一单到底
                    if($number && $express){
                        $ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
                        $ex_array['title'] = $express['title'];
                        $ex_array['homepage'] = $express['homepage'];
                        $ex_array['company'] = $express['company'];
                        $this->model('order')->express_save($ex_array);
                    }
                    //增加运单日志
                    $this->model('order')->log_save(array('order_id'=>$oid,'status'=>'create','note'=>$ship['title'],'addtime'=>$arr['addtime']));
                    if($ship['sms'] && $this->user['sms']){
                        $param = 'id='.$oid."&status=".$arr['status'];
                        $this->model('task')->add_once('order',$param);
                    }
                    //添加收件人信息
                    $address = $this->model('user')->address_one($aid);
                    if($address){
                        $tmp = array('order_id'=>$oid);
                        $tmp['country'] = $address['country'];
                        $tmp['province'] = $address['province'];
                        $tmp['city'] = $address['city'];
                        $tmp['county'] = $address['county'];
                        $tmp['address'] = $address['address'];
                        $tmp['mobile'] = $address['mobile'];
                        $tmp['tel'] = $address['tel'];
                        $tmp['email'] = $address['email'];
                        $tmp['zipcode'] = $address['zipcode'];
                        $tmp['fullname'] = $address['fullname'];
                        $tmp['idcard'] = $address['idcard'];
                        $tmp['idcard_front'] = $address['idcard_front'];
                        $tmp['idcard_back'] = $address['idcard_back'];
                        $this->model('order')->save_address($tmp);
                    }
                    //运单物品修改
                    //$pro_id = is_array($pro_id) ? explode(',',$pro_id) : intval($pro_id);
                    $pro_id=$this->get('pro_id_'.$i);
                    $catename = $this->get('catename_'.$i);
                    $brand = $this->get('brand_'.$i);
                    $title = $this->get('title_'.$i);
                    $size = $this->get('size_'.$i);
                    //$unit = $this->get('unit_'.$i);
                    //$weight = $this->get('weight_'.$i);
                    $qty = $this->get('qty_'.$i);
                    $price = $this->get('price_'.$i);
                    $total_price = $this->get('total_price_'.$i);
                    foreach($pro_id as $k=>$v){
                        $main['order_id'] = $oid;
                        $main['catename'] = $catename[$k];
                        $main['title'] = $title[$k];
                        $main['brand'] = $brand[$k];
                        $main['size'] = $size[$k];
                        //$main['unit'] = $unit[$k];
                        //$main['weight'] = $weight[$k];
                        $main['qty'] = $qty[$k];
                        $main['price'] = $price[$k];
                        $main['total_price'] = $total_price[$k];
                        //$main['currency'] = $currency;
                        $this->model('order')->save_product($main);
                    }
                }
            }else{
                $aid=$this->get('aid_0','int');
                if(!$aid){
                    $this->json(P_Lang('请选择收件人'));
                }
                //$safe_price = $this->get('order_safe_price_0');
                //$tax = $this->get('tax_0');
                //运单信息添加
                //$sn = $this->create_sn();
                $sn = $this->model('order')->create_sn();
                $arr = array('sn'=>$sn);
                //$arr['number'] = $order_number;
                $arr['package_id'] = $pid;
                $arr['stock_id'] = $stock_id;
                $arr['user_id'] = $user_id;
                $arr['addtime'] = $this->time;
                $arr['status'] = 'check';
                $arr['channel'] = intval($channel[0]);
                $arr['currency_id'] = intval($channel[10]);
                $arr['weight_id'] = $channel[12];
                $arr['val'] = $val;
                $arr['tax'] = $tax;
                $arr['safe'] = $safe;
                $arr['safe_price'] = $safe_price;
                $arr['type'] = $ptype;
                $arr['weight'] = $wt;
				$arr['jingzhong'] = $wt;
                $arr['pay_weight']= $pay_weight;
                $arr['custom'] = is_array($custom) ? implode(',',$custom) : intval($custom);
                $arr['custom_price'] = $custom_price;
                $arr['channel_price'] = $channel_price;
                $arr['price']= $pay_price;
                $arr['note'] = $this->get('note');
                $arr['consignor'] = $consignor;
                $arr['consignor_tel'] = $consignor_tel;
                $arr['consignor_address'] = $consignor_address;
                //导入清关编码
                if($channel[14]){
                    $code = $this->model('code')->get_code('channel_id='.$channel[0].' and status=0');
                    if($code){
                        $arr['customer_sn'] = $code['title'];
                        $this->model('code')->save(array('status'=>1),$code['id']);
                    }else{
                        $this->json('请导入报关条码');
                    }
                }
                //导入国内快递单号
                if($channel[15]){
                    $number = $this->model('number')->get_number('channel_id='.$channel[0].' and status=0');
                    if($number){
                        $express = $this->model('express')->get_one($number['express_id']);
                        $arr['express_sn'] = $number['title'];
                        $arr['express'] = $express['title'];
                        $this->model('number')->save(array('status'=>1),$number['id']);
                    }else{
                        $this->json('请导入国内快递单号');
                    }
                }
                $oid = $this->model('order')->save($arr);
                if(!$oid){
                    $this->json('运单提交失败');
                }
                //增加快递order_erpress,一单到底
                if($number && $express){
                    $ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
                    $ex_array['title'] = $express['title'];
                    $ex_array['homepage'] = $express['homepage'];
                    $ex_array['company'] = $express['company'];
                    $this->model('order')->express_save($ex_array);
                }
                //增加运单日志
                $this->model('order')->log_save(array('order_id'=>$oid,'status'=>$arr['status'],'note'=>$ship['title'],'addtime'=>$arr['addtime']));
                if($ship['sms'] && $this->user['sms']){
                    $param = 'id='.$oid."&status=".$arr['status'];
                    $this->model('task')->add_once('order',$param);
                }
                //添加收件人信息
                $address = $this->model('user')->address_one($aid);
                if($address){
                    $tmp = array('order_id'=>$oid);
                    $tmp['country'] = $address['country'];
                    $tmp['province'] = $address['province'];
                    $tmp['city'] = $address['city'];
                    $tmp['county'] = $address['county'];
                    $tmp['address'] = $address['address'];
                    $tmp['mobile'] = $address['mobile'];
                    $tmp['tel'] = $address['tel'];
                    $tmp['email'] = $address['email'];
                    $tmp['zipcode'] = $address['zipcode'];
                    $tmp['fullname'] = $address['fullname'];
                    $tmp['idcard'] = $address['idcard'];
                    $tmp['idcard_front'] = $address['idcard_front'];
                    $tmp['idcard_back'] = $address['idcard_back'];
                    $this->model('order')->save_address($tmp);
                }
                //运单物品添加
                $pro_id=$this->get('pro_id_0');
                $catename = $this->get('catename_0');
                $brand = $this->get('brand_0');
                $title = $this->get('title_0');
                $size = $this->get('size_0');
                //$unit = $this->get('unit_0');
                //$weight = $this->get('weight_0');
                $qty = $this->get('qty_0');
                $price = $this->get('price_0');
                $total_price = $this->get('total_price_0');
                foreach($pro_id as $k=>$v){
                    $main['order_id'] = $oid;
                    $main['catename'] = $catename[$k];
                    $main['brand'] = $brand[$k];
                    $main['title'] = $title[$k];
                    $main['size'] = $size[$k];
                    //$main['unit'] = $unit[$k];
                    //$main['weight'] = $weight[$k];
                    $main['qty'] = $qty[$k];
                    $main['price'] = $price[$k];
                    $main['total_price'] = $total_price[$k];
                    //$main['currency'] = $currency;
                    $this->model('order')->save_product($main);
                }
            }
            //修改包裹状态
            $pid=explode(',',$pid);
            foreach($pid as $val){
                $this->model('package')->save(array('status'=>'generated'),$val);//多个值转换数组，循环修改
            }
            $this->json(true);
        }
    }
    public function apply_f()
    {
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(p_Lang('请选择发货渠道'));
        }else{
            $channel=explode('|',$channel);
        }
        $user_id = $this->user_id;
        $group_id = $this->group_id;
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }else{
            $consign = $this->model('stock')->get_one($stock);
        }
        //收件人
        $fullname = trim($this->get('fullname'));
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = trim($this->get('mobile'));
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = trim($this->get('idcard'));
        /*if(!$idcard){
            $this->json(P_Lang('请填写身份证号'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        //添加收件人
        $u_r = $this->model('user')->get_address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$u_r){
            $this->model('user')->address_save(array('user_id'=>$user_id,'province'=>$pca_p,'city'=>$pca_c,'address'=>$address,'zipcode'=>$zipcode,'fullname'=>$fullname,'mobile'=>$mobile,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back));
        }
		//没有身份证，再添加身份证，更新收件人信息及以前订单
		if($u_r && !$u_r['idcard_front'] && $idcard_front ){
			//更新收件人
			$this->model('user')->address_save(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$u_r['id']);
			//更新订单收件人信息
			$order_address = $this->model('order')->get_address("fullname='".$fullname."' and mobile='".$mobile."'");
			foreach($order_address as $key => $value){
				if(!$value['idcard_front']){
					$this->model('order')->save_address(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$value['id']);
				}
			}
		}
        //判断渠道是否需要身份证
        if($channel[12]){
            if(!$idcard || !$idcard_front || !$idcard_back){
                $this->json(P_Lang('收件人身份证号或身份证图片不齐全'));
            }
        }
        $safe = $this->get('safe');
        $safe_price = $this->get('safe_price');
        if($safe_price){
            $temp_safe_price = $safe_price;
        }else{
            $temp_safe_price = 0;
        }
        $tax = $this->get('tax');
        if($tax){
            $tax_price = $tax;
        }else{
            $tax_price = 0;
        }
        $wt = $this->get('wt');
        if($channel[1]<>'0.00'){
            $fn=round($wt-floor($wt),2);
            if($fn <= $channel[1]){
                $wt = floor($wt);
            }
        }
        if($channel[2]=='ceil'){
            $wt = ceil($wt);
        }
        if($channel[2]=='round'){
            $fn=round($wt-floor($wt),2);
            if($fn >0 && $fn < 0.5){
                $wt = floor($wt)+0.5;
            }elseif($fn > 0.5){
                $wt = floor($wt)+1;
            }
        }
        if($wt>$channel[3]){
            $channel_price = $channel[4] + $channel[5] * ($wt - 1);
        }else{
            $channel_price = $channel[4] + $channel[5] * ($channel[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $pay_price = round(($channel_price + $temp_safe_price + $tax_price + $custom_price),2);
        $consignor = $this->get('consignor');
        $consignor_tel = $this->get('consignor_tel');
        $consignor_address = $this->get('consignor_address');
        //都为空获取发货仓库信息
        if(!$consignor){
            $consignor = $consign['consignor'];
            $consignor_tel = $consign['tel'];
            $consignor_address = $consign['address'];
        }
        $sender_tmp['user_id'] = $user_id;
        $sender_tmp['fullname'] = $consignor;
        $sender_tmp['tel'] = $consignor_tel;
        $sender_tmp['address'] = $consignor_address;
        $sender_rs = $this->model('user')->sender($user_id,"and fullname ='".$consignor."' and tel='".$consignor_tel."'");
        if(!$sender_rs){
            $this->model('user')->sender_save($sender_tmp);
        }
        //运单信息添加
        $sn = $this->model('order')->create_sn();
        $arr = array('sn'=>$sn);
        $arr['stock_id'] = $stock;
        $arr['user_id'] = $user_id;
        $arr['addtime'] = $this->time;
        $arr['status'] = 'create';
        $arr['type'] = 4;
        //$arr['ext'] = '运单创建成功';
        //读取运单状态
        $ship = $this->model('channel')->get_track($arr['status'],'status');
        $arr['channel'] = intval($channel[0]);
        $arr['currency_id'] = intval($channel[6]);
        $arr['weight_id'] = $channel[7];
        $arr['custom'] = is_array($custom) ? implode(',',$custom) : 0;
        $arr['price'] =$pay_price;
        $arr['channel_price'] =$channel_price;
        $arr['custom_price'] =$custom_price;
        $arr['pay_weight']= $wt;
        $arr['val'] = $this->get('val');
        $arr['tax'] = $tax;
        $arr['safe'] = $safe;
        $arr['safe_price'] = $safe_price;
        $arr['weight'] = $this->get('wt');
        $arr['note'] = $this->get('note');
        $arr['consignor'] = $consignor;
        $arr['consignor_tel'] = $consignor_tel;
        $arr['consignor_address'] = $consignor_address;
        //导入清关编码
        if($channel[10]){
            $code = $this->model('code')->get_code('channel_id='.$channel[0].' and status=0');
            if($code){
                $arr['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }else{
                $this->json('请导入报关条码！');
            }
        }
        //导入国内快递单号
        if($channel[11]){
            $number = $this->model('number')->get_number('channel_id='.$channel[0].' and status=0');
            if($number){
                $express = $this->model('express')->get_one($number['express_id']);
                $arr['express_sn'] = $number['title'];
                $arr['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }else{
                $this->json('请导入国内快递单号！');
            }
        }
        $oid = $this->model('order')->save($arr);
        if(!$oid){
            $this->json(P_Lang('运单创建失败'));
        }
		//增加快递order_erpress,一单到底
		if($number && $express){
			$ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
			$ex_array['title'] = $express['title'];
			$ex_array['homepage'] = $express['homepage'];
			$ex_array['company'] = $express['company'];
			$this->model('order')->express_save($ex_array);
		}
        //增加运单日志
        $this->model('order')->log_save(array('order_id'=>$oid,'status'=>$arr['status'],'note'=>$ship['title'],'addtime'=>$arr['addtime']));
		if($ship['sms'] && $this->user['sms']){
            $param = 'id='.$oid."&status=".$arr['status'];
            $this->model('task')->add_once('order',$param);
        }
        //运单收件地址
        $tmp_address = array('order_id'=>$oid);
        //$tmp['country'] = $address['country'];
        $tmp_address['province'] = $pca_p;
        $tmp_address['city'] = $pca_c;
        //$tmp['county'] = $address['county'];
        $tmp_address['address'] = $address;
        $tmp_address['mobile'] = $mobile;
        //$tmp['tel'] = $address['tel'];
        //$tmp['email'] = $address['email'];
        $tmp_address['zipcode'] = $zipcode;
        $tmp_address['fullname'] = $fullname;
        $tmp_address['idcard'] = $idcard;
        $tmp_address['idcard_front'] = $idcard_front;
        $tmp_address['idcard_back'] = $idcard_back;
        $this->model('order')->save_address($tmp_address);
        //运单物品添加
        $catename = $this->get('catename') ? $this->get('catename') : array();//考虑后台删除类别
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$title || !is_array($title)){
            $this->json(P_Lang('物品信息不能为空'));
        }
        foreach($title AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            //$tmp_weight = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$oid);
            $this->model('order')->save_product($tmp);
        }
        $this->json(true);
    }
    //批量创建运单
    public function import_f()
    {
        $file = $this->get('dfile','int');
        if(!$file)
        {
            $this->json(P_Lang("未导入批量运单文件"));
        }
        $res = $this->model('res')->get_one($file);
        if(!$res)
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        if(!is_file($this->dir_root.$res["filename"]))
        {
            $this->json(P_Lang("批量运单文件不存在"));
        }
        $user_id = $this->user_id;
        $group_id = $this->group_id;
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(p_Lang('请选择到货仓库'));
        }else{
            $consign = $this->model('stock')->get_one($stock);
        }
        //读取运单状态
        $ship = $this->model('channel')->get_track('create','status');
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
        $tips = array();
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
            if(!$data['包裹类别']){
                $tips[] = "第".$i."行包裹类别不能为空";
            }
            if(!$data['发件人姓名']){
                $tips[] = "第".$i."行发件人姓名不能为空";
            }
            if(!$data['发件人电话']){
                $tips[] = "第".$i."行发件人电话不能为空";
            }
            if(!$data['收件人姓名']){
                $tips[] = "第".$i."行收件人姓名不能为空";
            }
            if(!$data['收件人手机']){
                $tips[] = "第".$i."行收件人手机不能为空";
            }
            if(!$data['省']){
                $tips[] = "第".$i."行省不能为空";
            }
            if(!$data['市']){
                $tips[] = "第".$i."行市不能为空";
            }
            if(!$data['具体地址']){
                $tips[] = "第".$i."行具体地址不能为空";
            }
            if(!$data['收件人身份证号']){
                $tips[] = "第".$i."行收件人身份证号不能为空";
            }
            if(!$data['内件1品牌']){
                $tips[] = "第".$i."行内件1品牌不能为空";
            }
            if(!$data['内件1品名']){
                $tips[] = "第".$i."行内件1品名不能为空";
            }
            if(!$data['内件1数量']){
                $tips[] = "第".$i."行内件1数量不能为空";
            }
            $type = trim($data['包裹类别']);
            $channel = $this->model('channel')->get_one($type,'type');
            if(!$channel){
                $this->json(P_Lang("第".$i."行，请在后台渠道管理设置对应的包裹类别"));
            }
            if(!$channel['status']){
                $this->json(P_Lang("第".$i."行，请开启包裹类别对应的渠道"));
            }
            //导入清关编码
            if($channel['from_sn']){
                $code_count = $this->model('code')->get_count('status=0');
                if($code_count<$allRow){
                    $this->json('请导入报关条码！');
                }
            }
            //导入国内快递单号
            if($channel['tracking_number']){
                $number_count = $this->model('number')->get_count('status=0');
                if($number_count<$allRow){
                    $this->json('请导入国内快递单号！');
                }
            }
        }
		if($tips){
			$info = implode("<br>",$tips);
			$this->json($info);
		}
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
                /*if($data['发件人姓名'] && $data['发件人电话'] && $data['发件人地址']){
                    $consignor = $data['发件人姓名'];
                    $consignor_tel = $data['发件人电话'];
                    $consignor_address = $data['发件人地址'];
                }else{
                    $consignor = $consign['consignor'];
                    $consignor_tel = $consign['tel'];
                    $consignor_address = $consign['address'];

                }*/
            $consignor = $data['发件人姓名'];
            $consignor_tel = $data['发件人电话'];
            $consignor_address = $data['发件人地址'];
            if(!$data['发件人地址']){
                $consignor_address = $consign['address'];
            }
            $type = trim($data['包裹类别']);
            $tmp_channel = $this->model('channel')->get_one($type,'type');
            $channel = $this->model('channel')->get_onechannelprice('channel_id='.$tmp_channel['id'].' and group_id='.$group_id);
            $wt = $data['包裹总重量'] ? $data['包裹总重量'] : 0;
            if($wt){
                if($channel['remove']){
                    $fn=round($wt-floor($wt),2);
                    if($fn <= $channel['num']){
                        $val_weight = floor($wt);
                    }else{
                        $val_weight = $wt;
                    }
                }else{
                    $val_weight = $wt;
                }
                if($channel['rule']=='ceil'){
                    $pay_weight = ceil($val_weight);
                }
                if($channel['rule']=='round'){
                    $fn=round($val_weight-floor($val_weight),2);
                    if($fn >0 && $fn < 0.5){
                        $pay_weight = floor($val_weight)+0.5;
                    }elseif($fn > 0.5){
                        $pay_weight = floor($val_weight)+1;
                    }
                }
                if($channel['rule']=='intval'){
                    $pay_weight = $val_weight;
                }
                if($pay_weight > $channel['start_heavy']){
                    $channel_price = $channel['first_price'] + $channel['second_price'] * ($pay_weight - 1);
                }else{
                    $channel_price = $channel['first_price'] + $channel['second_price'] * ($channel['start_heavy'] - 1);
                }
                $channel_price = round($channel_price,2);
            }else{
                $channel_price = 0;
            }
            $sn = $this->model('order')->create_sn();
            $arr = array('user_id'=>$user_id,'channel'=>$tmp_channel['id'],'currency_id'=>$tmp_channel['currency_id'],'weight_id'=>$tmp_channel['weight_id'],'sn'=>$sn,'stock_id'=>$stock,'price'=>$channel_price,'pay_weight'=> $pay_weight,'consignor'=>$consignor,'consignor_tel'=>$consignor_tel,'consignor_address'=>$consignor_address,'jingzhong'=>$wt,'type'=>5,'status'=>'create','val'=>$data['保险金额($)'],'addtime'=>$this->time);
            //导入清关编码
            if($tmp_channel['from_sn']){
                $code = $this->model('code')->get_code('channel_id='.$tmp_channel['id'].' and status=0');
                $arr['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }
            //导入国内快递单号
            if($tmp_channel['tracking_number']){
                $number = $this->model('number')->get_number('channel_id='.$tmp_channel['id'].' and status=0');
                $express = $this->model('express')->get_one($number['express_id']);
                $arr['express_sn'] = $number['title'];
                $arr['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }
            $oid = $this->model('order')->save($arr);
            if(!$oid){
                $this->json(P_Lang('运单创建失败'));
            }
            //增加快递order_erpress,一单到底
            if($number && $express){
                $ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
                $ex_array['title'] = $express['title'];
                $ex_array['homepage'] = $express['homepage'];
                $ex_array['company'] = $express['company'];
                $this->model('order')->express_save($ex_array);
            }
            //增加运单日志
            $this->model('order')->log_save(array('order_id'=>$oid,'note'=>$ship['title'],'addtime'=>$this->time));
            if($ship['sms'] && $this->user['sms']){
                $param = 'id='.$oid."&status=create";
                $this->model('task')->add_once('order',$param);
            }
            //添加收件人信息
            $fullname = trim($data['收件人姓名']);
            $mobile = trim($data['收件人手机']);
            $user_address = array('fullname'=>$fullname,'user_id'=>$user_id,'province'=>$data['省'],'city'=>$data['市'],'address'=>$data['具体地址'],'mobile'=>$mobile,'idcard'=>$data['收件人身份证号']);
            $address = $this->model('user')->get_address("fullname='".$fullname."' and mobile = '".$mobile."' and user_id=".$user_id);
            if(!$address['id']){
                $this->model('user')->address_save($user_address);
            }
            $order_address = array('order_id'=>$oid,'fullname'=>$fullname,'province'=>$data['省'],'city'=>$data['市'],'address'=>$data['具体地址'],'mobile'=>$mobile,'idcard'=>$data['收件人身份证号']);
            $this->model('order')->save_address($order_address);
            //运单物品添加
            //$catename = trim($data['商品类别']);
            //
            $title = array($data['内件1品名'],$data['内件2品名'],$data['内件3品名'],$data['内件4品名'],$data['内件5品名'],$data['内件6品名']);
            $brand = array($data['内件1品牌'],$data['内件2品牌'],$data['内件3品牌'],$data['内件4品牌'],$data['内件5品牌'],$data['内件6品牌']);
            $size = array($data['内件1规格'],$data['内件2规格'],$data['内件3规格'],$data['内件4规格'],$data['内件5规格'],$data['内件6规格']);
            $qty = array($data['内件1数量'],$data['内件2数量'],$data['内件3数量'],$data['内件4数量'],$data['内件5数量'],$data['内件6数量']);
            $price = array($data['内件1商品单价($)'],$data['内件2商品单价($)'],$data['内件3商品单价($)'],$data['内件4商品单价($)'],$data['内件5商品单价($)'],$data['内件6商品单价($)']);
            foreach($title AS $key=>$value){
                $tmp_title = $title[$key];
                if(!$tmp_title || !trim($tmp_title)){
                    continue;
                }
                $tmp_brand = htmlspecialchars($brand[$key],ENT_QUOTES);//解决单引号插入
                $tmp_size = $size[$key];
                $tmp_qty = intval($qty[$key]);
                $tmp_price = floatval($price[$key]);
                $tmp_total_price = round($tmp_price*$tmp_qty,2);
                $tmp = array('brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'order_id'=>$oid);
                $this->model('order')->save_product($tmp);
            }
        }
        $this->json(true);
    }
    public function set_f(){
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定运单ID'));
        }
        $old = $this->model('order')->get_one($id);
        if(!$old){
            $this->json(P_Lang('运单信息不存在'));
        }
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(p_Lang('请选择发货渠道'));
        }else{
            $channel=explode('|',$channel);
        }

        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }
        $user_id = $this->user_id;
        $group_id = $this->group_id;
        //收件人
        $fullname = trim($this->get('fullname'));
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = trim($this->get('mobile'));
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = trim($this->get('idcard'));
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        //添加收件人
        $u_r = $this->model('user')->get_address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$u_r){
            $this->model('user')->address_save(array('user_id'=>$user_id,'province'=>$pca_p,'city'=>$pca_c,'address'=>$address,'zipcode'=>$zipcode,'fullname'=>$fullname,'mobile'=>$mobile,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back));
        }
		//没有身份证，再添加身份证，更新收件人信息及以前订单
		if($u_r && !$u_r['idcard_front'] && $idcard_front ){
			//更新收件人
			$this->model('user')->address_save(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$u_r['id']);
			//更新订单收件人信息
			$order_address = $this->model('order')->get_address("fullname='".$fullname."' and mobile='".$mobile."'");
			foreach($order_address as $key => $value){
				if(!$value['idcard_front']){
					$this->model('order')->save_address(array('idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back),$value['id']);
				}
			}
		}
        //判断渠道是否需要身份证
        if($channel[12]){
            if(!$idcard || !$idcard_front || !$idcard_back){
                $this->json(P_Lang('收件人身份证号或身份证图片不齐全'));
            }
        }
        $val = $this->get('val');
        $tax = $this->get('tax');
        $safe = $this->get('safe');
        $safe_price = $this->get('safe_price');
        $wt = $this->get('wt');
        if($channel[1]<>'0.00'){
            $fn=round($wt-floor($wt),2);
            if($fn <= $channel[1]){
                $wt = floor($wt);
            }
        }
        if($channel[2]=='ceil'){
            $wt = ceil($wt);
        }
        if($channel[2]=='round'){
            $fn=round($wt-floor($wt),2);
            if($fn >0 && $fn < 0.5){
                $wt = floor($wt)+0.5;
            }elseif($fn > 0.5){
                $wt = floor($wt)+1;
            }
        }
        if($wt > $channel[3]){
            $channel_price = $channel[4] + $channel[5] * ($wt - 1);
        }else{
            $channel_price = $channel[4] + $channel[5] * ($channel[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $pay_price = round(($channel_price + $safe_price + $tax + $custom_price),2);
        //运单信息添加
        $arr['custom'] = is_array($custom) ? implode(',',$custom) : 0;
        $arr['stock_id'] = intval($stock);
        //$arr['user_id'] = $user_id;
        $arr['channel'] = intval($channel[0]);
        $arr['currency_id'] = intval($channel[10]);
        $arr['weight_id'] = $channel[12];
        $arr['val'] = $val;
        $arr['tax'] = $tax;
        $arr['safe'] = $safe;
        $arr['safe_price'] = $safe_price;
        $arr['weight'] = $this->get('wt');
        $arr['price']= $pay_price;
        $arr['channel_price']= $channel_price;
        $arr['custom_price']= $custom_price;
        $arr['pay_weight']= $wt;
        $arr['note'] = $this->get('note');
        $arr['consignor'] =$this->get('consignor');
        $arr['consignor_tel'] = $this->get('consignor_tel');
        $arr['consignor_address'] = $this->get('consignor_address');
        //导入清关编码
        if($channel[10] && ($old["channel"]!=$channel[0])){
            $code = $this->model('code')->get_code('channel_id='.$channel[0].' and status=0');
            if($code){
                $arr['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }else{
                $this->json('请导入报关条码！');
            }
        }
        //导入国内快递单号
        if($channel[11] && ($old["channel"]!=$channel[0])){
            $number = $this->model('number')->get_number('channel_id='.$channel[0].' and status=0');
            if($number){
                $express = $this->model('express')->get_one($number['express_id']);
                $arr['express_sn'] = $number['title'];
                $arr['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }else{
                $this->json('请导入国内快递单号！');
            }
        }
		$oid = $this->model('order')->save($arr,$id);
		if(!$oid){
			$this->json(P_Lang('运单编辑失败'));
		}
		$address_id = $this->model('order')->address($id);
		$tmp_address['province'] = $pca_p;
		$tmp_address['city'] = $pca_c;
		//$tmp['county'] = $address['county'];
		$tmp_address['address'] = $address;
		$tmp_address['mobile'] = $mobile;
		//$tmp['tel'] = $address['tel'];
		//$tmp['email'] = $address['email'];
		$tmp_address['zipcode'] = $zipcode;
		$tmp_address['fullname'] = $fullname;
		$tmp_address['idcard'] = $idcard;
		$tmp_address['idcard_front'] = $idcard_front;
		$tmp_address['idcard_back'] = $idcard_back;
		$this->model('order')->save_address($tmp_address,$address_id['id']);
		//运单物品添加
		$prolist = $this->get('pro_id');
		$catename = $this->get('catename')? $this->get('catename'):array();
		$brand = $this->get('brand');
		$title = $this->get('title');
		$size = $this->get('size');
		$qty = $this->get('qty');
		//$unit = $this->get('unit');
		//$weight = $this->get('weight');
		$price = $this->get('price');
		$total_price = $this->get('total_price');
		$currency = $this->get('currency');
		if(!$prolist || !is_array($prolist)){
			$this->json(P_Lang('物品信息不能为空'));
		}
		foreach($prolist AS $key=>$value){
			$tmp_title = $title[$key];
			if(!$tmp_title || !trim($tmp_title)){
				continue;
			}
			$tmp_catename = $catename[$key];
			$tmp_brand = $brand[$key];
			$tmp_size = $size[$key];
			//$tmp_unit = $unit[$key];
			//$tmp_weight = floatval($weight[$key]);
			//$tmp_currency = $currency;
			$tmp_qty = intval($qty[$key]);
			$tmp_price = floatval($price[$key]);
			$tmp_total_price = floatval($total_price[$key]);
			$tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$id);
			if($value && $value != 'add'){
				$this->model('order')->save_product($tmp,$value);
			}else{
				$this->model('order')->save_product($tmp);
			}
		}
		$this->json(true);
    }
    public function copy_f(){
        $channel = $this->get('channel');
        if(!$channel){
            $this->json(p_Lang('请选择发货渠道'));
        }else{
            $channel=explode('|',$channel);
        }
        $user_id = $this->user_id;
        $group_id = $this->group_id;
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(P_Lang('请选择发货仓库'));
        }else{
            $consign = $this->model('stock')->get_one($stock);
        }
        //收件人
        $fullname = $this->get('fullname');
        if(!$fullname){
            $this->json(p_Lang('收件人不能为空'));
        }
        $mobile = $this->get('mobile');
        if(!$mobile){
            $this->json(p_Lang('收件人手机不能为空'));
        }
        if(!$this->lib('common')->tel_check($mobile,'mobile')){
            $this->json(P_Lang('手机号格式不对，请填写11位数字'));
        }
        $pca_p = $this->get('pca_p');
        $pca_c = $this->get('pca_c');
        if(!$pca_p || !$pca_c){
            $this->json(p_Lang('收件人所在省市不能为空'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(p_Lang('收件人地址不能为空'));
        }
        $zipcode = $this->get('zipcode');
        $idcard = $this->get('idcard');
        /*if(!$idcard){
            $this->json(P_Lang('请填写身份证号'));
        }*/
        if($idcard && !$this->lib('common')->idcard_check($idcard)){
            $this->json(P_Lang('身份证号码不合法'));
        }
        $idcard_front = $this->get('idcard_front');
        $idcard_back = $this->get('idcard_back');
        //添加收件人
        $u_r = $this->model('user')->address("user_id = '".$user_id."'and fullname='".$fullname."' and mobile='".$mobile."'");
        if(!$u_r){
            $this->model('user')->address_save(array('user_id'=>$user_id,'province'=>$pca_p,'city'=>$pca_c,'address'=>$address,'zipcode'=>$zipcode,'fullname'=>$fullname,'mobile'=>$mobile,'idcard'=>$idcard,'idcard_front'=>$idcard_front,'idcard_back'=>$idcard_back));
        }
        //判断渠道是否需要身份证
        if($channel[12]){
            if(!$idcard || !$idcard_front || !$idcard_back){
                $this->json(P_Lang('收件人身份证号或身份证图片不齐全'));
            }
        }
        $safe = $this->get('safe');
        $safe_price = $this->get('safe_price');
        if($safe_price){
            $temp_safe_price = $safe_price;
        }else{
            $temp_safe_price = 0;
        }
        $tax = $this->get('tax');
        if($tax){
            //$tax_price = price_format_val($tax,$channel[10],$this->site['currency_id']);
            $tax_price = $tax;
        }else{
            $tax_price = 0;
        }
        $wt = $this->get('wt');
        if($channel[1]<>'0.00'){
            $fn=round($wt-floor($wt),2);
            if($fn <= $channel[1]){
                $wt = floor($wt);
            }
        }
        if($channel[2]=='ceil'){
            $wt = ceil($wt);
        }
        if($channel[2]=='round'){
            $fn=round($wt-floor($wt),2);
            if($fn >0 && $fn < 0.5){
                $wt = floor($wt)+0.5;
            }elseif($fn > 0.5){
                $wt = floor($wt)+1;
            }
        }
        if($wt > $channel[3]){
            $channel_price = $channel[4] + $channel[5] * ($wt - 1);
        }else{
            $channel_price = $channel[4] + $channel[5] * ($channel[3] - 1);
        }
        $channel_price = round($channel_price,2);
        $custom = $this->get('custom');
        $custom_price = 0;
        if($custom || is_array($custom)){
            foreach($custom as $key => $value){
                $custom_tmp= $this->model('custom')->get_one($value);
                $custom_price += $custom_tmp['price'];
            }
        }
        $pay_price = round(($channel_price + $temp_safe_price + $tax_price + $custom_price),2);
        $consignor = $this->get('consignor');
        $consignor_tel = $this->get('consignor_tel');
        $consignor_address = $this->get('consignor_address');
        //都为空获取发货仓库信息
        if(!$consignor){
            $consignor = $consign['consignor'];
            $consignor_tel = $consign['tel'];
            $consignor_address = $consign['address'];
        }
        $sender_tmp['user_id'] = $user_id;
        $sender_tmp['fullname'] = $consignor;
        $sender_tmp['tel'] = $consignor_tel;
        $sender_tmp['address'] = $consignor_address;
        $sender_rs = $this->model('user')->sender($user_id,"and fullname ='".$consignor."' and tel='".$consignor_tel."'");
        if(!$sender_rs){
            $this->model('user')->sender_save($sender_tmp);
        }
        //运单信息添加
        $sn = $this->model('order')->create_sn();
        $arr = array('sn'=>$sn);
        $arr['stock_id'] = $stock;
        $arr['user_id'] = $user_id;
        $arr['addtime'] = $this->time;
        $arr['status'] = 'create';
        $arr['type'] = 4;
        //读取运单状态
        $ship = $this->model('channel')->get_track($arr['status'],'status');
        $arr['channel'] = intval($channel[0]);
        $arr['currency_id'] = intval($channel[6]);
        $arr['weight_id'] = $channel[7];
        $arr['custom'] = is_array($custom) ? implode(',',$custom) : 0;
        $arr['price'] =$pay_price;
        $arr['channel_price'] =$channel_price;
        $arr['custom_price'] =$custom_price;
        $arr['pay_weight']= $wt;
        $arr['val'] = $this->get('val');
        $arr['tax'] = $tax;
        $arr['safe'] = $safe;
        $arr['safe_price'] = $safe_price;
        $arr['weight'] = $this->get('wt');
        $arr['note'] = $this->get('note');
        $arr['consignor'] = $consignor;
        $arr['consignor_tel'] = $consignor_tel;
        $arr['consignor_address'] = $consignor_address;
        //导入清关编码
        if($channel[10]){
            $code = $this->model('code')->get_code('channel_id='.$channel[0].' and status=0');
            if($code){
                $arr['customer_sn'] = $code['title'];
                $this->model('code')->save(array('status'=>1),$code['id']);
            }else{
                $this->json('请导入报关条码！');
            }
        }
        //导入国内快递单号
        if($channel[11]){
            $number = $this->model('number')->get_number('channel_id='.$channel[0].' and status=0');
            if($number){
                $express = $this->model('express')->get_one($number['express_id']);
                $arr['express_sn'] = $number['title'];
                $arr['express'] = $express['title'];
                $this->model('number')->save(array('status'=>1),$number['id']);
            }else{
                $this->json('请导入国内快递单号！');
            }
        }
        $oid = $this->model('order')->save($arr);
        if(!$oid){
            $this->json(P_Lang('运单创建失败'));
        }
		//增加快递order_erpress,一单到底
		if($number && $express){
			$ex_array = array('order_id'=>$oid,'express_id'=>$number['express_id'],'code'=>$number['title'],'addtime'=>$arr['addtime']);
			$ex_array['title'] = $express['title'];
			$ex_array['homepage'] = $express['homepage'];
			$ex_array['company'] = $express['company'];
			$this->model('order')->express_save($ex_array);
		}
        //增加运单日志
        $this->model('order')->log_save(array('order_id'=>$oid,'status'=>$arr['status'],'note'=>$ship['title'],'addtime'=>$arr['addtime']));
        //运单收件地址
        $tmp_address = array('order_id'=>$oid);
        //$tmp['country'] = $address['country'];
        $tmp_address['province'] = $pca_p;
        $tmp_address['city'] = $pca_c;
        //$tmp['county'] = $address['county'];
        $tmp_address['address'] = $address;
        $tmp_address['mobile'] = $mobile;
        //$tmp['tel'] = $address['tel'];
        //$tmp['email'] = $address['email'];
        $tmp_address['zipcode'] = $zipcode;
        $tmp_address['fullname'] = $fullname;
        $tmp_address['idcard'] = $idcard;
        $tmp_address['idcard_front'] = $idcard_front;
        $tmp_address['idcard_back'] = $idcard_back;
        $this->model('order')->save_address($tmp_address);
        //运单物品添加
        $catename = $this->get('catename') ? $this->get('catename') : array();//考虑后台删除类别
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        $currency = $this->get('currency');
        if(!$title || !is_array($title)){
            $this->json(P_Lang('物品信息不能为空'));
        }
        foreach($title AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            //$tmp_weight = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'currency'=>$currency,'order_id'=>$oid);
            $this->model('order')->save_product($tmp);
        }
        $this->json(true);
    }
     public function idcard_f()
    {
        $idcard = $this->get('idcard');
        if(!$idcard){
            $this->json(p_Lang('身份证号码不能为空！'));
        }
        if($idcard){
            $chk = $this->lib('common')->idcard_check($idcard);
            if(!$chk){
                $this->json(P_Lang('身份证号码格式不正确！'));
            }
        }
        $idcard_front = $this->get('idcard_front');
        if(!$idcard_front){
            $this->json(P_Lang('请上传身份证正面照片！'));
        }
        $idcard_back = $this->get('idcard_back');
        if(!$idcard_back){
            $this->json(P_Lang('请上传身份证反面照片！'));
        }
        $data['idcard_front'] = $idcard_front;
        $data['idcard_back'] = $idcard_back;
        $user_address = $this->model('user')->getAddress("idcard='".$idcard."'");
        $order_address = $this->model('order')->get_address("idcard='".$idcard."'");
        foreach($user_address as $key => $value){
            if(!$value['idcard_front']){
                $this->model('user')->address_save($data,$value['id']);
            }
        }
        foreach($order_address as $key => $value){
            if(!$value['idcard_front'] || !$value['idcard_back']){
                $this->model('order')->save_address($data,$value['id']);
            }
        }
        $this->json(true);
    }
    public function claim_f()
    {
        $id = $this->get('id','int');
		$user_id = $this->user_id;
        $type = $this->get('type');
        if(!$type){
            $this->json(p_Lang('请选择理赔类型！'));
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(p_Lang('理赔运单号不能为空！'));
        }
		$rs = $this->model('order')->get_one_order("sn='".$sn."' and user_id=".$user_id);
		if(!$rs){
			$this->json(p_Lang('非法操作'));
		}
        $detail = $this->get('detail');
        if(!$detail){
            $this->json(p_Lang('理赔说明不能为空！'));
        }
        $note = $this->get('note');
        if(!$note){
            $this->json(p_Lang('理赔要求不能为空！'));
        }
        $vpic = $this->get('vpic');
        if(!$vpic){
            $this->json(P_Lang('请上传理赔凭证！'));
        }
        $addtime = $this->time;
        $data = array();
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['sn'] = $sn;
        $data['detail'] = $detail;
        $data['note'] = $note;
        $data['pic'] = $vpic;
        $data['addtime'] = $addtime;
        $rs = $this->model('claim')->save($data,$id);
        if(!$rs){
            $this->json(P_Lang('理赔申请失败'));
        }
        $this->json(true);
    }
    public function edit_note_f(){
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定运单ID'));
        }
        $old = $this->model('order')->get_one($id);
        if(!$old){
            $this->json(P_Lang('运单信息不存在'));
        }
        $note = $this->get('note');
        $this->model('order')->save(array('note'=>$note),$id);
        $this->json(true);
    }
}

?>