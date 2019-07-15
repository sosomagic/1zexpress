<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 17-8-16
 * Time: 下午5:06
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class purchase_control extends dsy_control
{
    function __construct()
    {
        parent::control();
    }
    public function add_f()
    {
        $backurl = $this->url('purchase');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $this->assign('id',$id);
            $rs = $this->model('purchase')->get_one($id);
            $this->assign('rs',$rs);
        }
        $web = $this->model('list')->notice_list('cate_id=608',$offset=0,$psize=100);
        $this->assign('web',$web);
        $catelist = $this->model('cate')->get_cate(1,1,70);
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
        $stock = $this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $this->assign('user',$this->user);
        $this->view("purchase");
    }
    function index_f()
    {
        $backurl = $this->url('purchase');
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
        $total = $this->model('purchase')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('purchase')->get_list($condition,$offset,$psize);
            foreach($rslist as $key => $value){
                $value['stock'] = $this->model('stock')->get_one($value['stock']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
        }
        $this->view('purchase');
    }
    public function delete_f()
    {
        $backurl = $this->url('purchase');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        if(!$id) error(P_Lang('未指定ID'),$this->url('purchase','','_back='.rawurlencode($backurl)));
        $this->model('purchase')->del($id);
        $this->json(true);
    }
    public function web_f()
    {
        $backurl = $this->url('purchase');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $web = $this->model('list')->web_list();
        $this->assign('web',$web);
        $this->view("purchase_web");
    }
    function save_f()
    {
        $backurl = $this->url('purchase');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->add_save();
        }
        $this->modify_save($id);
    }
    private function add_save()
    {
        $main = array();
        $main['user_id'] = $this->session->val('user_id');
        $main['url'] = $this->get('url');
        $main['catename'] = $this->get('catename');
        $main['title'] = $this->get('title');
        $main['price'] = $this->get('price');
        $main['express_price'] = $this->get('express_price');
        $main['pay_price'] = $this->get('pay_price');
        $main['num'] = $this->get('num');
        $main['size'] = $this->get('size');
        $main['vpic'] = $this->get('vpic');
        $main['stock'] = $this->get('stock','int');
        $main['note'] = $this->get('note');
        $main['addtime'] = $this->time;
        $rs = $this->model('purchase')->save($main);
        if(!$rs){
            $this->json(P_Lang('代购提交失败'));
        }
        $this->json(true);
    }
    private function modify_save($id)
    {
        $old = $this->model('purchase')->get_one($id);
        if(!$old){
            $this->json(P_Lang('代购信息不存在'));
        }
        $main = array();
        $main['url'] = $this->get('url');
        $main['catename'] = $this->get('catename');
        $main['title'] = $this->get('title');
        $main['price'] = $this->get('price');
        $main['express_price'] = $this->get('express_price');
        $main['pay_price'] = $this->get('pay_price');
        $main['num'] = $this->get('num');
        $main['size'] = $this->get('size');
        $main['vpic'] = $this->get('vpic');
        $main['stock'] = $this->get('stock','int');
        $main['note'] = $this->get('note');
        $rs = $this->model('purchase')->save($main,$id);
        if(!$rs){
            $this->json(P_Lang('代购修改失败'));
        }
        $this->json(true);
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
            $this->view('purchase_pic');
        }
    }
}
?>