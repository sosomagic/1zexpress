<?php
class admin_ordertopdf extends dsy_plugin
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

	public function html_order_index_foot()
	{
		$this->_show("html_order_index_foot.html");
	}

	public function orderpdf()
	{
		$id = $this->get('order_id');
		if(!$id){
			$this->error('未指定订单ID');
		}
		$rs = $this->model('order')->get_one($id);
		$this->assign('rs',$rs);
		if($rs['user_id']){
			$user = $this->model('user')->get_one($rs['user_id']);
			if($user && !$user['fullname']){
				$user['fullname'] = $user['user'];
			}
			$this->assign('user',$user);
		}
		$address = $this->model('order')->address($id);
		if($address){
			$this->assign('address',$address);
		}
		$fullname = $address ? $address['fullname'] : ($user ? $user['fullname'] : '未知');
		$this->assign('fullname',$fullname);
		$rslist = $this->model('order')->product_list($id);
		$this->assign('rslist',$rslist);
		$price_tpl_list = $this->model('site')->price_status_all();
		$order_price = $this->model('order')->order_price($id);
		if($price_tpl_list && $order_price){
			$pricelist = array();
			foreach($price_tpl_list as $key=>$value){
				$tmpval = floatval($order_price[$key]);
				if(!$value['status'] || !$tmpval){
					continue;
				}
				$tmp = array('val'=>$tmpval);
				$tmp['price'] = price_format($order_price[$key],$rs['currency_id']);
				$tmp['title'] = $value['title'];
				$pricelist[$key] = $tmp;
			}
			$this->assign('pricelist',$pricelist);
		}
		//生成条码
		$barcodefile = md5($rs['sn']).'.png';
		if(!file_exists($this->dir_cache.$barcodefile)){
			$this->lib('barcode')->font($this->dir_data.'font/arial.ttf');
			$this->lib('barcode')->create($rs['sn'],$this->dir_cache.$barcodefile);
		}
		$this->assign('barcode','data/cache/'.$barcodefile);
		$content = $this->_tpl('pdf.html');
		$view = $this->get('preview','int');
		if($view){
			echo $content;
			exit;
		}
		if(!$content){
			$this->error('PDF内容为空');
		}
		$this->lib('tcpdf')->create($content,$rs['sn'].'.pdf',true);
		//$this->to_pdf($content,$rs['sn']);
	}

	public function pdflist()
	{
		$list = $this->lib('file')->ls($this->dir_plugin.'ordertopdf/template/');
		if($list){
			$tplist = false;
			foreach($list as $key=>$value){
				$value = basename($value);
				if(substr($value,0,8) == 'pdf_tpl_'){
					if(!$tplist){
						$tplist = array();
					}
					$tplist[] = $value;
				}
			}
			$this->assign('tplist',$tplist);
		}
		$this->_view('order_pdflist.html');
	}
    public function shopLabel()
    {
        $cate_id = $this->get('cate_id','int');
        if(!$cate_id){
            $this->error('请选择批次');
        }
        $orderlist = $this->model('order')->get_cate_list($cate_id);
        $tpl = 'shoplabel.html';
        $list = array();
        foreach($orderlist as $key=>$value){
            $address = $this->model('order')->address($value['id']);
            $this->assign('address',$address);
            $product = $this->model('order')->product_list($value['id']);
            $sumPrice = 0;
            foreach($product as $v){
                $sumPrice += $v['total_price'];
            }
            $this->assign('sumPrice',$sumPrice);
            $this->assign('product',$product);
            $this->assign('rs',$value);
            $list[] = $this->_tpl($tpl);
        }
        $tmpfile = 'data/cache/'.$cate_id.'.pdf';
        $this->lib('tcpdf')->page_format('A4');
        $this->lib('tcpdf')->create($list,$this->dir_root.$tmpfile,false);
        $this->success('<a href='.$tmpfile.'>下载预览</a>');
    }
	public function download()
	{
		$file = $this->get('file');
		if(!$file){
			$this->error('参数不完整',$this->url('order'));
		}
		if(!file_exists($this->dir_root.$file)){
			$this->error('文件不存在',$this->url('order'));
		}
		$this->lib('file')->download($this->dir_root.$file,'订单.pdf');
	}
    public function to_pdflist()
    {
        $ids = $this->get('ids');
        if($ids){
            $orderlist = $this->model('order')->get_order($ids);
        }
        if(!$orderlist){
            $this->error('没有订单信息');
        }
        $bid = $this->get('bid');
        if($bid){
            $orderlist = $this->model('order')->get_cate_list($bid);
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
            /*if($address['province']=='北京市'){
                $letter = '';
            }elseif($address['province']=='天津市'||$address['province']=='河北省'||$address['province']=='青海省'||
                $address['province']=='新疆维吾尔自治区'||$address['province']=='西藏自治区'||$address['province']=='甘肃省'||
                $address['province']=='宁夏回族自治区'){
                $letter = 'B';
            }else{
                $letter = 'J';
            };
            $this->assign('letter',$letter);*/
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
        }*/
        $this->lib('tcpdf')->page_format($page_format);
        $this->lib('tcpdf')->create($list,$this->dir_root.$tmpfile,false);
        $this->success('<a href='.$tmpfile.'>打印预览</a>');
    }
}