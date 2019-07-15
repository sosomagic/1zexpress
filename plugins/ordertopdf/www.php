<?php
class www_ordertopdf extends dsy_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}
	//全局运行插件，在执行当前方法运行前，调整参数
	public function dsy_before()
	{
		//编写代码;
	}
	//全局运行插件，在执行当前方法运行后，数据未输出前
	public function dsy_after()
	{
		//编写代码;
	}
    //批量PDf打印
    public function to_pdflist()
    {
        $ids = $this->get('ids');
        $orderlist = $this->model('order')->get_order($ids);
        if(!$orderlist){
            $this->error('没有订单信息');
        }
        $page_format = $this->get('page_format');
        $tp = $this->get('tp');
        if($tp=='a'){
            $tpl = 'a.html';
        }elseif($tp=='b'){
            $tpl = 'b.html';
        }elseif($tp=='c'){
            $tpl = 'c.html';
        }else{
            $tpl = 'default.html';
        }
        $this->lib('barcode')->font($this->dir_root.'data/font/Arial.ttf',12);
        $list = array();
        foreach($orderlist as $key=>$value){
			if($this->session->val('user_id')!=$value['user_id']){
				$this->error(P_Lang('非法操作'));
			}
            $barcodefile = 'data/cache/'.$value['customer_sn'].'.png';
            if(!file_exists($this->dir_root.$barcodefile)){
                $this->lib('barcode')->create($value['customer_sn'],$this->dir_root.$barcodefile);
            }
            $this->assign('barcode',$barcodefile);
            $sn_barcodefile = 'data/cache/'.$value['sn'].'.png';
            if(!file_exists($this->dir_root.$sn_barcodefile)){
                $this->lib('barcode')->create($value['sn'],$this->dir_root.$sn_barcodefile);
            }
            $this->assign('sn_barcode',$sn_barcodefile);
            $exbarcode = 'data/cache/'.$value['express_sn'].'.png';
            if(!file_exists($this->dir_root.$exbarcode)){
                $this->lib('barcode')->create($value['express_sn'],$this->dir_root.$exbarcode);
            }
            $this->assign('exbarcode',$exbarcode);
            $jibaodi = 'data/cache/'.$value['jibaodi'].'.png';
            if(!file_exists($this->dir_root.$jibaodi)){
                $this->lib('barcode')->create($value['jibaodi'],$this->dir_root.$jibaodi);
            }
            $this->assign('jibaodi',$jibaodi);
            $address = $this->model('order')->address($value['id']);
            $this->assign('address',$address);
            if($address['province']=='北京市'){
                $letter = '';
            }elseif($address['province']=='天津市'||$address['province']=='河北省'||$address['province']=='青海省'||
                $address['province']=='新疆维吾尔自治区'||$address['province']=='西藏自治区'||$address['province']=='甘肃省'||
                $address['province']=='宁夏回族自治区'){
                $letter = 'B';
            }else{
                $letter = 'J';
            };
            $this->assign('letter',$letter);
            $user = $this->model('user')->get_one($value['user_id']);
            $this->assign('ucode',$user['ucode']);
            $product = $this->model('order')->product_list($value['id']);
            $this->assign('product',$product);
			$this->assign('rs',$value);
            $channel = $this->model('channel')->get_one($value['channel']);
            $this->assign('channel',$channel);
            $list[] = $this->_tpl($tpl);
        }
        $tmpfile = 'data/cache/'.$this->time.'.pdf';
        /*if($page_format=='A4'){
            $this->lib('tcpdf')->psize('4');
        }
        $this->lib('tcpdf')->page_format($page_format);*/
        $this->lib('tcpdf')->create($list,$this->dir_root.$tmpfile,false);
        $this->success('<a href='.$tmpfile.'>打印预览</a>');
    }
}