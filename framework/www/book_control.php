<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 17-4-12
 * Time: 下午5:37
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class book_control extends dsy_control
{
    public function __construct()
    {
        parent::control();
    }
    public function index_f()
    {
        $backurl = $this->url('book','list');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('book')->get_one($id);
            $this->assign('id',$id);
            $this->assign('rs',$rs);
        }
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
        $type = $this->model('book')->type();
        $this->assign('type',$type);
        $catelist = $this->model('cate')->get_cate(1,1,604);
        $this->assign('catelist',$catelist);
        $this->view("book");
    }
    function list_f()
    {
        $backurl = $this->url('book','list');
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
        $condition = "user_id='".$_SESSION['user_id']."'";
        $this->assign('pageid',$pageid);
        $pageurl = $this->url('package');
        $this->assign('pageurl',$pageurl);
        $total = $this->model('book')->get_count($condition);
        $this->assign('total',$total);
        $this->assign('psize',$psize);
        if($total){
            $rslist = $this->model('book')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['catename'] = $this->model('cate')->get_one($value['cate_id']);
                $rslist[$key] = $value;
            }
            $this->assign('rslist',$rslist);
        }
        $this->view('book_list');
    }
    public function show_f()
    {
        $backurl = $this->url('book','list');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('book')->get_one($id);
            //读图片
            $res = $this->model('res')->get_one($rs['vpic']);
            $rs['file'] = $res["filename"];
            $this->assign('rs',$rs);
        }
        $this->view("book_show");
    }
    public function delete_f()
    {
        $backurl = $this->url('book','list');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id');
        //删除订单
        if(!$id) error(P_Lang('未指定ID号'),$this->url('book','','_back='.rawurlencode($backurl)));
        $this->model('book')->del($id);
        $this->json(true);
    }

    public function save_f()
    {
        $backurl = $this->url('book','list');
        if(!$this->session->val('user_id')){
            error(P_Lang('您还不是会员，请先登录'),$this->url('login','','_back='.rawurlencode($backurl)));
        }
        $id = $this->get('id','int');
        $type = $this->get('type');
        if(!$type){
            $this->json(p_Lang('请选择问题类型！'));
        }
        $sn = $this->get('sn');
        if(!$sn){
            $this->json(p_Lang('运单号不能为空！'));
        }
        $detail = $this->get('detail');
        if(!$detail){
            $this->json(p_Lang('问题描述不能为空！'));
        }
        $data = array();
        $data['user_id'] = $_SESSION['user_id'];
        $data['cate_id'] = $type;
        $data['sn'] = $sn;
        $data['content'] = $detail;
        $data['note'] = $this->get('note');
        $data['vpic'] = $this->get('vpic');
        $data['level'] = $this->get('level');
        $data['addtime'] = $this->time;
        $rs = $this->model('book')->save($data,$id);
        if(!$rs){
            $this->json(P_Lang('问题提交失败'));
        }
        $this->json(true);
    }
}
?>