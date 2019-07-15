<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-7-17
 * Time: 下午1:10
 */
set_time_limit(0); //执行时间无限
ini_set('memory_limit','-1'); //内存无限
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class batch_control extends dsy_control
{
    private $popedom;
    public function __construct()
    {
        parent::control();
        $this->popedom = appfile_popedom("batch");
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
        $page_url = $this->url("batch");
        $offset = ($pageid-1) * $psize;
        $condition = "1=1";
        $stock_id = $this->get("stock_id",'int');
        if($stock_id){
            $condition .= " AND stock_id=".$stock_id;
            $page_url .= "&stock_id=".rawurlencode($stock_id);
            $this->assign('stock_id',$stock_id);
        }else{
            $condition .= " and stock_id in(".$_SESSION["admin_stock"].")";
        }
        $total = $this->model('batch')->get_count($condition);
        if($total>0){
            $rslist = $this->model('batch')->get_list($condition,$offset,$psize);
            foreach($rslist as $key=>$value){
                //判断该批次是否有运单
                $list = $this->model('order')->get_one_cate_list($value['id']);
                if(!$list){
					$value['sign'] = 0;
				}else{
					$value['sign'] = 1;
				}
				$value['log'] =  $this->model('order')->log_one_from_order($list['id']);
				//$value['zt'] = $list['status'];
                $value['stock'] =  $this->model('stock')->get_one($value['stock_id']);
                $rslist[$key] = $value;
            }
            $string = 'home='.P_Lang('首页').'&prev='.P_Lang('上一页').'&next='.P_Lang('下一页').'&last='.P_Lang('尾页').'&half=3';
            $string.= '&add='.P_Lang('数量：').'(total)/(psize)'.P_Lang('，').P_Lang('页码：').'(num)/(total_page)&always=1';
            $pagelist = dsy_page($page_url,$total,$pageid,$psize,$string);
            $this->assign("total",$total);
            $this->assign("rslist",$rslist);
            $this->assign("pagelist",$pagelist);
        }
		$stocks = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stocks',$stocks);
        $this->view("batch_list");
    }
    public function set_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error',10);
        }
        if($id){
            $rs = $this->model('batch')->get_one($id);
            $this->assign("rs",$rs);
            $this->assign("id",$id);
        }
        $stock = $this->model('stock')->stock_from_admin($_SESSION["admin_stock"]);
        $this->assign('stock',$stock);
        $this->view("batch_set");
    }

    public function setok_f()
    {
        $id = $this->get('id','int');
        $popedom_id = $id ? 'modify' : 'add';
        if(!$this->popedom[$popedom_id]){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error',10);
        }
        $error_url = $this->url('batch');
		$title = $this->get("title");
        if(!$title){
            error(P_Lang('批次名称不允许为空'),$error_url,'error');
        }
		$stock_id = $this->get("stock","int");
        if(!$stock_id){
            error(P_Lang('请选择所属仓库'),$error_url,'error');
        }
        $array = array();
        $array["title"] = $title;
        $array["stock_id"] = $stock_id;
        $array["note"] = $this->get("note");
        $array["addtime"] = $this->time;
        $error_url = $this->url('batch','set');
        if($id) $error_url = $this->url('batch','set','id='.$id);
        if(!$array["title"]){
            error(P_Lang('批次名称不允许为空'),$error_url,'error');
        }
        $this->model('batch')->save($array,$id);
        error(P_Lang('批次设置操作成功'),$this->url("batch"));
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
        $rs = $this->model('order')->get_cate_list($id);
        if($rs){
            $this->json(P_Lang('该批次下有运单，不允许删除'));
            //$this->model('batch')->cate($id);
        }
        $this->model('batch')->del($id);
        $this->json("ok",true);
    }
    public function print_f()
    {
        if(!$this->popedom['print']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error');
        }
        $id = $this->get('id','int');
        if(!$id){
            $this->json(P_Lang('未指定批次ID'));
        }
        $rs = $this->model('order')->get_cate_list($id);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        foreach($rs as $key=>$value){
            $value['product'] = $this ->model('order')->product_list($value['id']);
           // $value['userinfo'] = $this->model('user')->get_one($value['user_id']);
            $value['address'] = $this->model('order')->address($value['id']);
            $rs[$key] = $value;
        }
        $this->json($rs,true);
    }
   //身份证导出
    public function export_f()
    {
        if(!$this->popedom['export']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error');
        }
        $error_url = $this->url("batch");
        $cate_id = $this->get("id","int");
        if(!$cate_id)
        {
            error(P_Lang('请选择要导出身份证的运单批次'),$error_url,"error");
        }
		$filename = 'data/cache/'.$cate_id.'.zip';
		if(!file_exists($this->dir_root.$filename)){
			$condition = "cate_id='".$cate_id."'";
			$rslist = $this->model('order')->get_zip_export($condition);
			$fileNameArr = '';
			foreach($rslist as $key=>$value){
				if(!$value['idcard'] || !$value['idcard_front'] || !$value['idcard_back']){
                    continue;
                }
				//获取身份证正反面图片
				$res_front = $this->model('res')->get_one($value['idcard_front'],true);
				$res_back = $this->model('res')->get_one($value['idcard_back'],true);
				$front = $this->dir_root.$res_front['gd']['thumb']['filename'];
				$back = $this->dir_root.$res_back['gd']['thumb']['filename'];
				$fileNameArr .= $front.'|||'.$value['fullname'].'(正面)'.$value['sn'].','.$back.'|||'.$value['fullname'].'(反面)'.$value['sn'].',';
			}
			//去掉右边逗号
			$fileNameArr = rtrim($fileNameArr,',');
			 //注意：不能用绝对路径
			 //----------------生成ZIP压缩包，打包下载-------------------------
			if($fileNameArr){
                // 生成文件
                $zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需开启php_zip.dll
                if ($zip->open ( $filename, ZIPARCHIVE::CREATE ) !== TRUE) {
                    exit ( '无法打开文件，或者文件创建失败' );
                }
                $fileNameArr=explode(",",$fileNameArr);
                foreach ( $fileNameArr as $val )
                {
                    //只能打包/res/目录下的文件,防止入侵
                    if (stristr($val,'/res/'))
                    {
                        $name=explode("|||",$val);//获取姓名
                        $sfr=basename ($name[0]);//文件地址
                        $sfr=pathinfo($sfr, PATHINFO_EXTENSION);
                        //$sfr2= mb_convert_encoding($name[1], 'GBK','GBK,GB2312,UTF-8');//文件名:由于页面不能指定用编码，只能用浏览器默认编码，因此必须转为GB2312输出
                        $sfr2= mb_convert_encoding($name[1],'GBK','UTF-8');
                        $sfr=$sfr2.".".$sfr;
                        $zip->addFile ($name[0],$sfr); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    }
                }
                $zip->close (); // 关闭
            }
		}	
		//下面是输出下载;
		header ( "Cache-Control: max-age=0" );
		header ( "Content-Description: File Transfer" );
		header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
		header ( "Content-Type: application/zip" ); // zip格式的
		header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
		header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
		@readfile ( $filename );//输出文件;
    }
    public function out_f()
    {
        if(!$this->popedom['out']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error');
        }
        $cid = $this->get('cid','int');
        if(!$cid)
        {
            error(P_Lang('请选择批次号'),$this->url('batch','out'),"error",3);
        }
        $rs = $this->model('batch')->get_one($cid);
        $this->assign("rs",$rs);
        $this->assign("cid",$cid);
        $this->view("batch_out");
    }
    /*public function out_save_f(){
        $sn = trim($this->get('sn'));
        $cid = $this->get('cid','int');
        if(!$cid){
            $this->json('请选择批次');
        }
        $rs = $this->model('order')->get_from_sn($sn);
        if(!$rs){
            //$this->error('运单信息不存在');
            $this->json(false);
        }
        //未扣款
        if($rs['status']!='paid' && $rs['status']!='shipped') $this->json(false);
        //同一个批次已出库
        if($rs['status']=='shipped' && $rs['cate_id']==$cid) $this->json(false);
        $this->model('order')->save(array('cate_id'=>$cid,'status'=>'shipped'),$rs['id']);
        $ship = $this->model('channel')->get_track('shipped','status');
        $this->model('order')->log_save(array('order_id'=>$rs['id'],'status'=>'shipped','note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }*/
    //新扫描出库
    public function CheckOrder_f(){
        $sn = trim($this->get('sn'));
        if(!$sn){
            $this->json(P_Lang('请扫描运单号'));
        }
        $cid = $this->get('cid','int');
        if(!$cid){
            $this->json('请选择批次');
        }
        $rs = $this->model('order')->GetOrder($sn);
        if(!$rs){
            $this->json(P_Lang('运单信息不存在'));
        }
        //未扣款
        if($rs['status']!='paid' && $rs['status']!='shipped'){
            $this->json(P_Lang('运单未扣款'));
        }
        //同一个批次已出库
        if($rs['status']=='shipped' && $rs['cate_id']==$cid){
            $this->json(P_Lang('运单已出库，请不要重复扫描'));
        }
        $this->json($rs,true);
    }
    public function OutSave_f(){
        $cid = $this->get('cid','int');
        $sn = trim($this->get('sn'));
        $rs = $this->model('order')->get_from_sn($sn);
		if(!$rs){
            $this->json('订单不存在');
        }
        $this->model('order')->save(array('cate_id'=>$cid,'status'=>'shipped'),$rs['id']);
        $ship = $this->model('channel')->get_track('shipped','status');
        $this->model('order')->log_save(array('order_id'=>$rs['id'],'status'=>'shipped','note'=>$ship['title'],'addtime'=>$this->time));
        $this->json(true);
    }
    //end
    public function delivery_f()
    {
        if(!$this->popedom['delivery']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error');
        }
        $cid = $this->get('cid','int');
        if(!$cid){
            $this->error(P_Lang('未指定发货批次'));
        }
        $this->assign('cid',$cid);
        $rslist = $this->model('order')->get_cate_list($cid);
        if(!$rslist){
            $this->error(P_Lang('该批次下没有运单，不能进行发货操作'));
        }
        foreach($rslist as $key=>$value){
            $idlist[$key] = $value['id'];
            $stock[$key] = $value['stock_id'];
            $status[$key] = $value['status'];
        }
        $ids = implode(',',$idlist);
        $this->assign('ids',$ids);
        if(count(array_unique($stock))!=1){
            $this->error('请选择同一个仓库订单');
        }
        if(count(array_unique($status))!=1){
            $this->error('运单状态不同步，请检查');
        }
        //运单状态国内派送received，不执行
        if($status[0]=='received'){
            $this->error('运单状态已国内派送');
        }
        $track_list = $this->model('channel')->track_list("FIND_IN_SET(".$stock[0].",stock_id)");
        //隶属仓库状态
        foreach($track_list as $key=>$value){
            //if (strpos($value['stock_id'],$stock[0]) !== false && $value['status']!='unpaid') {
                $delivery_list[] = $value;
            //}
        }
        $this->assign('delivery_list',$delivery_list);
        $list = $this->model('order')->pl_fahuo($idlist[0]);
        $this->assign('list',$list);
        //判断前台状态是否已更新
        //$this->assign('rlist',array_reverse($list));
        $this->view('batch_delivery');
    }
    public function delivery_save_f(){
        $id = $this->get('id');
        $cid = $this->get('cid');
        $note = explode('|',$this->get('note'));
        $title =$note[0];
        if(!$title){
            $this->json(P_Lang('请选择运单状态'));
        }
        //批次状态改变,列表页下拉框就不读出该批次
        $this->model('batch')->save(array('status'=>'1'),$cid);
        $addtime = $this->get('addtime');
        $addtime = $addtime ? strtotime($addtime) : $this->time;
        $idlist=explode(",",$id);
        foreach($idlist AS $key=>$value)
        {
            $value = intval($value);
            //判断运单里是否已经更新过该状态
            $rs=$this->model('order')->log_list_one($value);
            if($rs['note']==$title) continue;
			$data = array('order_id'=>$value,'status'=>$note[1],'note'=>$title,'addtime'=>$addtime);
			$this->model('order')->log_save($data);
			if($note[1]){
				$this->model('order')->update_order_status($value,$note[1]);
			}
        }
		if($note[1]){
			$this->json($note[1],true);
		}else{
			$this->json(true);
		}
    }
    public function batch_delete_f()
    {
        if(!$this->popedom['delivery']){
            error(P_Lang('您没有权限执行此操作'),$this->url('batch'),'error');
        }
        $id = $this->get('id');
        $note = $this->get('note');
        if(!$id){
            $this->json(P_Lang('未指定ID'));
        }
        $this->model('order')->log_dels($id,$note);
        $this->json(true);
    }
    public function import_f(){
        if(!$this->popedom['list']){
            error(P_Lang('您没有权限执行此操作'));
        }
        $ext_list = $this->model('fields')->get_one('127');
        if($ext_list){
            $ext = unserialize($ext_list["ext"]);
            foreach($ext AS $k=>$v){
                $ext_list[$k] = $v;
            }
            $extlist = $this->lib('form')->format($ext_list);
        }
        $this->assign("extlist",$extlist);
        $this->view('batch_import');
    }
    public function importData_f()
    {
        $cid = $this->get('id','int');
        if(!$cid){
            $this->json(P_Lang("未指定pic"));
        }
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
        $data = $tips = array();
        for($i=2;$i<=$allRow;$i++){
            $row = array();
            for($j=0; $j<$ColumnNum;$j++){
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                $cellVal = $currentSheet->getCell($cellName)->getValue();
                $row[$filed[$j]] = $cellVal;
            }
            // $data []= $row;
            $data = $row;
            if(!trim($data['订单号'])){
                //$this->json('称重单号不能为空！');
                $tips[] = '第'.$i.'行，订单号不能为空';
            }
            $rs = $this->model('order')->get_from_sn(trim($data['订单号']));
            if(!$rs){
                //$this->json('你好，单号['.$data['称重单号'].']不存在，请查证后再导入！');
                $tips[] = '第'.$i.'行，单号不存在，请查证后再导入';
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
            // $data []= $row;
            $data = $row;
            $rs = $this->model('order')->get_from_sn(trim($data['订单号']));
            $this->model('order')->save(array('cate_id'=>$cid),$rs['id']);
        }
        $this->json(true);
    }
}
?>
