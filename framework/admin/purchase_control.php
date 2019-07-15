<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 17-8-17
 * Time: 下午1:50
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class purchase_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("purchase");
        $this->assign("popedom",$this->popedom);
    }

    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        //管理员所属仓库运单
        $condition = "stock in(".$_SESSION["admin_stock"].")";
        $rslist = $this->model('purchase')->get_list($condition);
        foreach($rslist as $key=>$value){
            $value['stock'] = $this->model('stock')->get_one($value['stock']);
            $value['user'] = $this->model('user')->get_one($value['user_id']);
            $rslist[$key] = $value;
        }
        $this->assign("rslist",$rslist);
        $this->view("purchase_list");
    }

    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('delivery'),'error',10);
        }
        if($id){
            $rs = $this->model('purchase')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $stock = $this->model('stock')->get_list();
        $this->assign('stock',$stock);
        $catelist = $this->model('cate')->get_all(1,1,70);
        $this->assign('catelist',$catelist);
        $ext_list = $this->model('fields')->get_one('141');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            if($rs[$ext_list["identifier"]]){
                $ext_list["content"] = $rs[$ext_list["identifier"]];
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view("purchase_set");
    }

    public function setok_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('delivery'),'error',10);
        }
        $addtime = $this->get('addtime');
        $addtime = $addtime ? strtotime($addtime) : $this->time;
        if(!$id)  error(P_Lang('未指定ID'),$this->url('purchase'),'error',10);
        $old = $this->model('purchase')->get_one($id);
        if(!$old){
            error(P_Lang('代购信息不存在'),$this->url('purchase'),'error',10);
        }
        if($old['status']==1 || $old['status']==2){
            error(P_Lang('代购信息已扣款或已订购'),$this->url('purchase'),'error',10);
        }
        $status = $this->get("status","int");
        if(!$status){
            error(P_Lang('请选择状态'),$this->url('purchase'),'error',10);
        }
        $array = array();
        $array['url'] = $this->get('url');
        $array['catename'] = $this->get('catename');
        $array['title'] = $this->get('title');
        $array['price'] = $this->get('price');
        $array['express_price'] = $this->get('express_price');
        $array['pay_price'] = $this->get('pay_price');
        $array['num'] = $this->get('num');
        $array['size'] = $this->get('size');
        $array['vpic'] = $this->get('vpic');
        $array['stock'] = $this->get('stock','int');
        $array['note'] = $this->get('note');
        $array["status"] = $status;
        $error_url = $this->url('purchase','set');
        if($id) $error_url = $this->url('purchase','set','id='.$id);
        //if($status==1){
            //计算会员余额
            $balance = $this->model('wealth')->get_val($old['user_id'],2);
            $price = $array['pay_price'];
            $data = array('wid'=>2,'uid'=>$old['user_id'],'lasttime'=>$this->time);
            $data['val'] = round(($balance-$price),2);
            if($data['val']>=0){
                $this->model('wealth')->save_info($data);
            }else{
                error(P_Lang('账户余额不足'),$this->url('purchase'),'error',10);
            }
            $sn = $this->_create_sn();
            $title = P_Lang('代购账户余额扣款：{title}【'.$_SESSION["admin_account"].'】',array('title'=>$old['title']));
            $arr = array('sn'=>$sn,'type'=>'order','payment_id'=>0,'title'=>$title,'content'=>$title,'status'=>'1');
            $arr['dateline'] = $this->time;
            $arr['user_id'] = $old['user_id'];
            $arr['price'] = $price;
            $arr['balance'] = $data['val'];
            $arr['currency_id'] = 2;
            $this->model('payment')->log_create($arr);//payment_log
        //}
        $this->model('purchase')->save($array,$id);
        error(P_Lang('代购信息设置操作成功'),$this->url("purchase"));
    }

    public function delete_f()
    {
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $id = $this->get("id",'int');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $this->model('purchase')->del($id);
        $this->json("ok",true);
    }
    private function _create_sn()
    {
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_str = '';
        for($i=0;$i<3;$i++){
            $rand_str .= $a[rand(0,25)];
        }
        $rand_str .= rand(1000,9999);
        $rand_str .= date("YmdHis",$this->time);
        return $rand_str;
    }
    public function show_pic_f()
    {
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('res')->get_one($id,true);
            if(!$rs)
            {
                $this->json(P_Lang("没有商品图片"));
            }
            $this->assign('rs',$rs);
            $this->view('claim_pic');
        }
    }
}
?>