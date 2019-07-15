<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-5-20
 * Time: 上午11:39
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class stock_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("stock");
        $this->assign("popedom",$this->popedom);
    }
    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $rslist = $this->model('stock')->get_list();
        $this->assign("rslist",$rslist);
        $this->view("stock_list");
    }
    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('stock'),'error',10);
        }
        if($id){
            $rs = $this->model('stock')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $this->view("stock_set");
    }

    public function setok_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('stock'),'error',10);
        }
        $array = array();
        $array["title"] = $this->get("title");
        $array["province"] = $this->get('province');
        $array["city"] = $this->get('city');
        $array["address"] = $this->get('address');
        $array["zipcode"] = $this->get("zipcode");
        $array["consignor"] = $this->get("consignor");
        $array["tel"] = $this->get("tel");
        $array["note"] = $this->get("note");
        $array["taxis"] = $this->get("taxis","int");
        $array["status"] = $this->get("status","int");
        $error_url = $this->url('stock','set');
        if($id) $error_url = $this->url('stock','set','id='.$id);
        if(!$array["title"]){
            error(P_Lang('仓库名称不能为空'),$error_url,'error');
        }
        $this->model('stock')->save($array,$id);
        error(P_Lang('仓库设置成功'),$this->url("stock"));
    }

    public function delete_f()
    {
        $id = $this->get("id",'int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $this->model('stock')->del($id);
        $this->json("ok",true);
    }

    public function sort_f()
    {
        $sort = $this->get('sort');
        if(!$sort || !is_array($sort)){
            $this->json(P_Lang('更新排序失败'));
        }
        foreach($sort AS $key=>$value){
            $key = intval($key);
            $value = intval($value);
            $this->model('stock')->update_sort($key,$value);
        }
        json_exit(P_Lang('更新排序成功'),true);
    }

    public function status_f()
    {
        if(!$this->popedom['status']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定要操作的ID'));
        }
        $rs = $this->model('stock')->get_one($id);
        $status = $rs['status'] ? '0' : '1';
        $this->model('stock')->update_status($id,$status);
        $this->json($status,true);
    }
}
?>
