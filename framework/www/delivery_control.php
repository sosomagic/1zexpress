<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 18-7-25
 * Time: 下午1:39
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class delivery_control extends dsy_control
{
    function __construct()
    {
        parent::control();
    }
    public function delivery_f()
    {
        $backurl = $this->url('delivery');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $rs = $this->model('delivery')->get_one($id);
        }else{
            $rs = $this->user;
        }
        $this->assign('rs',$rs);
        $stock=$this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $this->view("delivery_set");
    }
    function delivery_list_f()
    {
        $backurl = $this->url('delivery');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 20;
        $pageid = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
        $pageid = $this->get($pageid,'int');
        if(!$pageid){
            $pageid = 1;
        }
        $offset = ($pageid-1) * $psize;
        $condition = "user_id='".$this->session->val('user_id')."'";
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('package');
        $this->assign('pageurl',$pageurl);
        $total = $this->model('delivery')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('delivery')->get_list($condition,$offset,$psize);
            $this->assign('rslist',$rslist);
        }
        $this->view('delivery_set');
    }
    public function delivery_delete_f()
    {
        $backurl = $this->url('delivery');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //删除订单
        if(!$id) error(P_Lang('未指定上门取件ID号'),$this->url('order','','_back='.rawurlencode($backurl)));
        $this->model('delivery')->del($id);
        $this->json(true);
    }
}
?>