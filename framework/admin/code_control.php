<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-7-22
 * Time: 下午8:15
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class code_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("code");
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
        $page_url = $this->url("code");
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
        $total = $this->model('code')->get_count($condition);
        if($total>0){
            $rslist = $this->model('code')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                $value['channel'] = $this->model('channel')->get_one($value['channel_id']);
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
            $count[$key] = $this->model('code')->get_count("channel_id=".$value['id']." and status=0");
        }
        $this->assign('count',$count);
        $this->assign('sum',array_sum($count));
        $this->assign("channel",$channel);
        $this->view("code_list");
    }
    public function set_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('code'),'error',10);
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
        $this->view("code_set");
    }
    public function edit_f()
    {
        $id = $this->get('id','int');
        if(!$this->popedom['modify']){
            error(P_Lang('您没有权限执行此操作'),$this->url('code'),'error',10);
        }
        $channel = $this->model('channel')->get_list();
        $this->assign('channel',$channel);
        $rs = $this->model('code')->get_one($id);
        $this->assign('id',$id);
        $this->assign('rs',$rs);
        $this->view("code_edit");
    }
    public function editok_f()
    {
        $id = $this->get('id','int');
        if(!$this->popedom['modify']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error',10);
        }
        $array = array();
        $array["channel_id"] = $this->get("channel_id");
        $array["title"] = $this->get("title");
        $array["status"] = $this->get("status");
        if($id) $error_url = $this->url('code','edit','id='.$id);
        if(!$array["channel_id"]){
            error(P_Lang('请选择发货渠道'),$error_url,'error');
        }
        if(!$array["title"]){
            error(P_Lang('请填写报关条码'),$error_url,'error');
        }
        $this->model('code')->save($array,$id);
        error(P_Lang('报关条码编辑成功'),$this->url("code"));
    }
    public function setok_f()
    {
        if(!$this->popedom['add']){
            error(P_Lang('您没有权限执行此操作'),$this->url('code'),'error',10);
        }
        $error_url = $this->url('code','set');
        $channel_id = $this->get('channel_id','int');
        if(!$channel_id){
            error(P_Lang('请选择发货渠道'),$error_url,'error');
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
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
			$ex = trim($data['报关条码']);
			if(!$ex){
                error('报关条码不能为空',$error_url,'error');
            }
            $rs = $this->model('code')-> get_one($ex,'title');
			if($rs){
				error('报关条码：'.$ex.'系统已存在',$error_url,'error');
			}
        }
		for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            $data = $row;
            $arr = array('channel_id'=>$channel_id,'title'=>trim($data['报关条码']));
            $this->model('code')->save($arr);
        }
        error('报关条码设置成功',$this->url("code"));
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
        $this->model('code')->del($id);
        $this->json("ok",true);
    }
    //批量删除操作
    public function deletes_f()
    {
        $id = $this->get('id');
        if(!$id) $this->json(P_Lang('未指定ID'));
        if(!$this->popedom['delete']){
            $this->json(P_Lang('您没有权限执行此操作'));
        }
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $this->model('code')->del($value);
        }
        $this->json('删除成功',true);
    }
}
?>
