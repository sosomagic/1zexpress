<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-7-22
 * Date: 16-10-21
 * Time: 下午9:24
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class number_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("number");
        $this->assign("popedom",$this->popedom);
    }
    public function index_f()
    {
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'),'','error');
        }
        $pageid = $this->get($this->config["pageid"],"int");
        if(!$pageid) $pageid = 1;
        $psize = $this->config["psize"];
        if(!$psize) $psize = 30;
        $page_url = $this->url("number");
        $offset = ($pageid-1) * $psize;
        $condition = "1=1";
        $status = $this->get("status");
        if($status<>""){
            $condition .= " AND status=".$status;
            $page_url .= "&status=".rawurlencode($status);
            $this->assign('status',$status);
        }
        $cid = $this->get("cid","int");
        if($cid){
            $condition .= " AND channel_id=".$cid;
            $page_url .= "&cid=".rawurlencode($cid);
            $this->assign('cid',$cid);
        }
        $total = $this->model('number')->get_count($condition);
        if($total>0){
            $rslist = $this->model('number')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['channel'] = $this->model('channel')->get_one($value['channel_id']);
                $value['express'] = $this->model('express')->get_one($value['express_id']);
                $rslist[$key] = $value;
            }
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($page_url,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
        $channel = $this->model('channel')->get_channelList();
        foreach($channel as $key => $value){
            $count[$key] = $this->model('number')->get_count("channel_id=".$value['id']." and status=0");
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign("channel",$channel);
        $this->view("number_list");
    }
    public function set_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('number'),'error',10);
        }
        $channel = $this->model('channel')->get_list();
        $this->assign('channel',$channel);
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $expresslist = $this->model('express')->get_all();
        $this->assign('expresslist',$expresslist);
        $this->view("number_set");
    }
    public function edit_f()
    {
        $id = $this->get('id','int');
        if(!$this->popedom['modify']){
            error(P_Lang('您没有权限执行此操作'),$this->url('number'),'error',10);
        }
        $channel = $this->model('channel')->get_list();
        $this->assign('channel',$channel);
        $expresslist = $this->model('express')->get_all();
        $this->assign('expresslist',$expresslist);
        $rs = $this->model('number')->get_one($id);
        $this->assign('id',$id);
        $this->assign('rs',$rs);
        $this->view("number_edit");
    }
    public function editok_f()
    {
        $id = $this->get('id','int');
        if(!$this->popedom['modify']){
            error('您没有权限执行此操作',$this->url('batch'),'error',10);
        }
        $array = array();
        $array["channel_id"] = $this->get("channel_id",'int');
        $array["express_id"] = $this->get("express_id",'int');
        $array["title"] = $this->get("title");
        $array["status"] = $this->get("status",'int');
        if($id) $error_url = $this->url('number','edit','id='.$id);
        if(!$array["channel_id"]){
            error('请选择渠道',$error_url,'error');
        }
        if(!$array["express_id"]){
            error('请选择快递公司',$error_url,'error');
        }
        if(!$array["title"]){
            error('请填写快递单号',$error_url,'error');
        }
        $this->model('number')->save($array,$id);
        error('快递单号编辑成功',$this->url("number"));
    }
    public function setok_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('number'),'error',10);
        }
        $error_url = $this->url('number','set');
        $channel_id = $this->get('channel_id','int');
        if(!$channel_id){
            error(P_Lang('请选择发货渠道'),$error_url,'error');
        }
        $express_id = $this->get('express_id','int');
        if(!$express_id){
            error(P_Lang('请选择快递公司'),$error_url,'error');
        }
        $file = $this->get('dfile','int');
        if(!$file)
        {
            error(P_Lang('请上传文件'),$error_url,'error');
        }
        $res = $this->model('res')->get_one($file);
        if(!$res)
        {
            $this->json(P_Lang("文件不存在"));
        }
        if(!is_file($this->dir_root.$res["filename"]))
        {
            $this->json(P_Lang("文件不存在"));
        }
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
            $data = $row;
			$ex = trim($data['国内快递单号']);
            if(!$ex){
                error('国内快递单号不能为空',$error_url,'error');
            }
            $rs = $this->model('number')-> get_one($ex,'title');
			if($rs){
				error('国内快递单号：'.$ex.'系统已存在',$error_url,'error');
			}
        }
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            // $data []= $row;
            $data = $row;
            $arr = array('channel_id'=>$channel_id,'title'=>trim($data['国内快递单号']),'express_id'=>$express_id);
            $this->model('number')->save($arr);
        }
        error(P_Lang('快递单号导入成功'),$this->url("number"));
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
        $this->model('number')->del($id);
        $this->json("ok",true);
    }
    //批量删除操作
    public function deletes_f()
    {
        $id = $this->get('id');
        if(!$id) $this->json('未指定ID');
        if(!$this->popedom['delete']){
            $this->json('您没有权限执行此操作');
        }
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $this->model('number')->del($value);
        }
        $this->json('删除成功',true);
    }
}
?>
