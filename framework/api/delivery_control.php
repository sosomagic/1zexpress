<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 18-7-25
 * Time: 下午1:48
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class delivery_control extends dsy_control
{
    public function __construct()
    {
        parent::control();
    }
    public function delivery_f()
    {
        $id = $this->get('id','int');
        $name = $this->get('name');
        if(!$name){
            $this->json(p_Lang('联系人姓名不能为空！'));
        }
        $mobile = $this->get('mobile');
        if(!$mobile){
            $this->json(p_Lang('手机号码不能为空！'));
        }
        $address = $this->get('address');
        if(!$address){
            $this->json(P_Lang('地址不能为空'));
        }
        $visitTime = $this->get('visitTime');
        $visitTime = $visitTime ? strtotime($visitTime) : $this->time;
        $data = array();
        $data['user_id'] = $this->session->val('user_id');
        $data['stock_id'] = $this->get('stock_id');
        $data['name'] = $name;
        $data['mobile'] = $mobile;
        $data['address'] = $address;
        $data['visitTime'] = $visitTime;
        $data['addtime'] = $this->time;
        $data['weight'] = $this->get('weight');
        $data['note'] = $this->get('note');
        $rs = $this->model('delivery')->save($data,$id);
        if(!$rs){
            $this->json(P_Lang('上门取件预约失败'));
        }
        $this->json(true);
    }
}
?>