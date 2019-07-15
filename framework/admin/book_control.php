<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 17-4-13
 * Time: 下午2:34
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class book_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("book");
        $this->assign("popedom",$this->popedom);
    }

    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config['pageid'],'int');
        if(!$pageid){
            $pageid = 1;
        }
        $psize = $this->config['psize'] ? $this->config['psize'] : 30;
        $offset = ($pageid-1)*$psize;
        $pageurl = $this->url('book');
        $condition = "1=1";
        $status = $this->get("status");
        if($status<>null){
            $condition .= " AND status='".$status."'";
            $pageurl .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }
        $cate_id = $this->get("cate_id",'int');
        if($cate_id){
            $condition .= " AND cate_id=".$cate_id;
            $pageurl .= "&cate_id=".rawurlencode($cate_id);
            $this->assign('cate_id',$cate_id);
        }
        $sn = trim($this->get('sn'));
        if($sn){
            $condition .= " AND sn like '%".$sn."%'";
            $pageurl .= "&sn=".rawurlencode($sn);
            $this->assign('sn',$sn);
        }
        $content = trim($this->get('content'));
        if($content){
            $condition .= " AND content like '%".$content."%'";
            $pageurl .= "&content=".rawurlencode($content);
            $this->assign('content',$content);
        }
        $dateRange = $this->get('dateRange');
        if($dateRange){
            $dateRangeArr = explode(' - ',$dateRange);
            $condition .= " AND addtime>=".strtotime($dateRangeArr[0]);
            $condition .= " AND addtime<=".strtotime($dateRangeArr[1]);
            $pageurl .= "&dateRange=".rawurlencode($dateRange);
            $this->assign('dateRange',$dateRange);
        }
        $total = $this->model('book')->get_count($condition);
        if($total>0){
            $rslist = $this->model('book')->get_list($condition,$offset,$psize);
            if(!$rslist) $rslist = array();
            foreach($rslist AS $key=>$value){
                $value['user'] = $this->model('user')->get_one($value['user_id'],'id',false,false);
                $value['catename'] = $this->model('cate')->get_one($value['cate_id']);
                $rslist[$key] = $value;
            }
            $this->assign("rslist",$rslist);
            $this->assign('total',$total);
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=5';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($pageurl,$total,$pageid,$psize,$string);
            $this->assign('pagelist',$pagelist);
        }
        //工单分类
        $catelist = $this->model('cate')->get_all(1,1,604);//(网站id,1,根目录id)
        $this->assign("catelist",$catelist);
        if($pageid) $pageurl .= "&pageid=".$pageid;
        $this->assign('pageurl',$pageurl);
        $this->view("book_list");
    }

    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('book'),'error',10);
        }
        if($id){
            $rs = $this->model('book')->get_one($id);
            $rs['catename'] = $this->model('cate')->get_one($rs['cate_id']);
            //读图片
            $res = $this->model('res')->get_one($rs['vpic']);
            $rs['file'] = $res["filename"];
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $this->view("book_set");
    }

    public function setok_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('book'),'error',10);
        }
        $array = array();
        $array["adm_reply"] = $this->get("reply");
        $array["status"] = $this->get("status","int");
        $this->model('book')->save($array,$id);
        error(P_Lang('问题编辑成功'),$this->url("book"));
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
        $this->model('book')->del($id);
        $this->json("ok",true);
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
        $rs = $this->model('book')->get_one($id);
        $status = $rs['status'] ? '0' : '1';
        $this->model('book')->update_status($id,$status);
        $this->json($status,true);
    }
   /* public function show_pic_f()
    {
        $id = $this->get('id','int');
        if($id){
            $rs = $this->model('res')->get_one($id);
            if(!$rs)
            {
                $this->json(P_Lang("未指定ID"));
            }
            $this->assign('rs',$rs);
            $this->view('book_pic');
        }
    }*/
}
?>