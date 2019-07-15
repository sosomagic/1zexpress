<?php
/***********************************************************
	Filename: package_control.php
	Note	: 创建包裹操作
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2016年5月17日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class package_control extends dsy_control
{
	private $user_id;
	public function __construct()
	{
		parent::control();
		if(!$this->session->val('user_id')){
            $this->error(P_Lang('您还没有登录，请先登录或注册'));
        }
        $this->user_id = $this->session->val('user_id');
	}
    //预报订单
    public function forecast_f()
    {
        $id = $this->get('id','int');
        if(!$id){
            $this->add_package();
        }
        else{
            $this->modify_package($id);
        }
    }

     //储存包裹信息
    private function add_package()
    {
        $stock=$this->get('stock');
        if(!$stock){
            $this->json(p_Lang('请选择到货仓库'));
        }
        $express=$this->get('express');
        $sn = trim($this->get('sn'));
        if(!$sn){
            $this->json(P_Lang('快递单号不能为空'));
        }
        $rs_sn = $this->model('package')->get_one_from_sn($sn);
        if($rs_sn){
            $this->json(P_Lang('包裹单号已被使用，请换个单号'));
        }
        /*$val = $this->get('val','float');
        if(!$val){
            $this->json(P_Lang('请填写包裹预估价值'));
        }
        if(!preg_match('/^\d+(\.\d{2})?$/',$val)){
            $this->json(P_Lang('只能输入数字，支持小数后两位'));
        }*/
        $wt = $this->get('wt');
       /* if(!$wt){
            $this->json(P_Lang('包裹重量不能为空！'));
        }
        if(!preg_match('/^[+-]?\d+(\.\d+)?$/',$wt)){
            $this->json(P_Lang('包裹只能输入数字格式！'));
        }*/
        $note = $this->get('note');
        $data = array();
        $data['user_id'] = $this->user_id;
        $data['stock'] = $stock;
        $data['express'] = $express;
        $data['sn'] = $sn;
       // $data['val'] = $val;
        $data['wt'] = $wt;
        /*if($arr[1]!='0.00'){
            $data['price'] = $wt * $arr[1];
        }*/
        //$pay_price = $wt * $arr[1];
        //$data['currency_id'] = $arr[2];
        //$data['weight_id'] = $arr[3];
        //$data['val'] = $this->get('val');
        $data['note'] = $note;
        $data['status'] = 'unstored';
        $data['addtime'] = $this->time;
        $rs=$this->model('package')->save($data);
        if(!$rs){
            $this->json(P_Lang('包裹预报失败'));
        }
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        //$currency = $this->get('currency');
		if(!$title || !is_array($title)){
            $this->json(P_Lang('物品信息不能为空'));
        }
		foreach($title AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            //$tmp_weight = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size);
            $tmp['package_id'] = $rs;
            $this->model('package')->save_product($tmp);
        }
        /*//计算会员余额
        if($pay_price && $pay_price != '0.00'){
            $balance = $this->model('wealth')->get_val($data['user_id'],2);
            if($balance < $pay_price){
                $this->json(P_Lang('您的余额已不足,请及时充值'));
            }
        }*/
        $this->json(true);
    }
    //编辑包裹信息
    private function modify_package($id)
    {
        $old = $this->model('package')->get_one($id);
        if(!$old){
            $this->json(P_Lang('包裹信息不存在'));
        }
        $stock=$this->get('stock');
        if(!$stock){
            $this->json(p_Lang('请选择仓库'));
        }
        $express=$this->get('express');
        $sn = trim($this->get('sn'));
        if(!$sn){
            $this->json(P_Lang('包裹单号不能为空'));
        }
        if($sn != $old['sn']){
            $rs_sn = $this->model('package')->get_one_from_sn($sn);
            if($rs_sn){
                $this->json(P_Lang('包裹单号已被使用，请检查'));
            }
        }
        /*$rs_sn = $this->model('package')->get_one_from_sn($sn);
        if($rs_sn['status']=='unstored' && $rs_sn['user_id']!=$old['user_id']){
            $this->json(P_Lang('包裹单号已被使用，请检查'));
        }
        if($rs_sn['status']=='stored'){
            $this->json(P_Lang('包裹已入库，请检查'));
        }*/
        $wt = $this->get('wt');
       /* if(!$wt){
            $this->json(P_Lang('包裹重量不能为空！'));
        }
        if(!preg_match('/^\d+(\.\d{2})?$/',$wt)){
            $this->json(P_Lang('只能输入数字，支持小数后两位'));
        }*/
        $note = $this->get('note');
        $data = array();
        //$data['user_id'] = $this->user['id'];
        $data['stock'] = $stock;
        $data['express'] = $express;
        $data['sn'] = $sn;
       // $data['val'] = $val;
        $data['wt'] = $wt;
        /*if($arr[1]!='0.00'){
            $data['price'] = $wt * $arr[1];
        }
        $data['currency_id'] = $arr[2];
        $data['weight_id'] = $arr[3];*/
        //$data['val'] = $this->get('val');
        $data['note'] = $note;
        $rs = $this->model('package')->save($data,$id);
        if(!$rs){
            $this->json(P_Lang('包裹修改失败，请检查'));
        }
        $prolist = $this->get('pro_id');
        $catename = $this->get('catename');
        $brand = $this->get('brand');
        $title = $this->get('title');
        $size = $this->get('size');
        $qty = $this->get('qty');
        //$unit = $this->get('unit');
        //$weight = $this->get('weight');
        $price = $this->get('price');
        $total_price = $this->get('total_price');
        //$currency = $this->get('currency');
        if(!$prolist || !is_array($prolist)){
            $this->json(P_Lang('物品信息不能为空'));
        }
        foreach($prolist AS $key=>$value){
            $tmp_title = $title[$key];
            if(!$tmp_title || !trim($tmp_title)){
                continue;
            }
            $tmp_catename = $catename[$key];
            $tmp_brand = $brand[$key];
            $tmp_size = $size[$key];
            //$tmp_unit = $unit[$key];
            //$tmp_weight = floatval($weight[$key]);
            //$tmp_currency = $currency;
            $tmp_qty = intval($qty[$key]);
            $tmp_price = floatval($price[$key]);
            $tmp_total_price = floatval($total_price[$key]);
            $tmp = array('catename'=>$tmp_catename,'brand'=>$tmp_brand,'title'=>$tmp_title,'price'=>$tmp_price,'total_price'=>$tmp_total_price,'qty'=>$tmp_qty,'size'=>$tmp_size,'package_id'=>$id);
            if($value && $value != 'add'){
                $this->model('package')->save_product($tmp,$value);
            }else{
                $this->model('package')->save_product($tmp);
            }
        }
        /*//计算会员余额
        if($pay_price && $pay_price != '0.00'){
            $balance = $this->model('wealth')->get_val($data['user_id'],2);
            if($balance < $pay_price){
                $this->json(P_Lang('您的余额已不足,请及时充值'));
            }
        }*/
        $this->json(true);
    }
    //批量导入包裹数据
    public function import_f()
    {
        $file = $this->get('dfile','int');
        if(!$file)
        {
            $this->json(P_Lang("未导入批量文件"));
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
        $user_id = $this->user_id;
        $stock = $this->get('stock','int');
        if(!$stock){
            $this->json(p_Lang('请选择到货仓库'));
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
            if(!trim($data['包裹单号']) || trim($data['包裹单号'])){
                //增加判断代表黄色部分不重复添加
                if(trim($data['包裹单号'])){
                    $arr = array('user_id'=>$user_id,'sn'=>$data['包裹单号'],'stock'=>$stock,'express'=>$data['快递公司'],'jingzhong'=>$data['包裹称重'],'status'=>'0','note'=>$data['备注信息'],'addtime'=>$this->time);
                    $rs = $this->model('order')->get_one_from_sn(trim($data['包裹单号']));
                    if($rs){
                        $this->json('你好，包裹单号['.$data['包裹单号'].']已存在，请查证后再导入！');
                    }
                }
            }else{
                $this->json('必填项目请填写完整');
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
            if(!trim($data['包裹单号']) || trim($data['包裹单号'])){
                //增加判断代表黄色部分不重复添加
                if(trim($data['包裹单号'])){
                    $arr = array('user_id'=>$user_id,'sn'=>$data['包裹单号'],'stock'=>$stock,'express'=>$data['快递公司'],'jingzhong'=>$data['包裹称重'],'status'=>'unstored','note'=>$data['备注信息'],'addtime'=>$this->time);
                    $oid = $this->model('package')->save($arr);
                    if(!$oid){
                        $this->json(P_Lang('包裹导入失败'));
                    }
                }
                //运单物品添加
                $catename = array(trim($data['商品类别']));
                $brand = array(trim($data['商品品牌']));
                $title = array(trim($data['商品名称']));
                $qty = array(trim($data['商品数量']));
                $size = array(trim($data['商品规格']));
                //$unit = array(trim($data['商品计量单位']));
                $price = array(trim($data['商品单价']));
                //$weight = array(trim($data['商品称重']));
                foreach($title as $key=>$value){
                    $main['package_id'] = $oid;
                    $main['title'] = htmlspecialchars($value,ENT_QUOTES);
                    $main['catename'] = $catename[$key];
                    $main['brand'] = htmlspecialchars($brand[$key],ENT_QUOTES);//解决单引号插入
                    $main['qty'] = intval($qty[$key]);
                    $main['size'] = $size[$key];
                    //$main['unit'] = $unit[$key];
                    $main['price'] = floatval($price[$key]);
                    $main['total_price'] = $qty[$key]*$price[$key];
                    //$main['weight'] = floatval($weight[$key])*$main['qty'];
                    $this->model('package')->save_product($main);
                }
            }
        }
        $this->json(true);
    }
}
?>