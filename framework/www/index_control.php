<?php
/***********************************************************
	Filename: www/index_control.php
	Note	: 网站首页及APP的封面页
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2015年06月06日 09时09分
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class index_control extends dsy_control
{
	public function __construct()
	{
		parent::control();
	}
	public function index_f()
	{
        //$channel = $this->model('channel')->channel_list(2);
        //$this->assign('channel',$channel);
        //执行任务
        if($this->config['async']['status']){
            $taskurl = api_url('task','index',$this->session->sid()."=".$this->session->sessid(),true);
            if($this->config['async']['type']){
                $this->lib('async')->loadtype($this->config['async']['type']);
            }
            $this->lib('async')->start($taskurl);
        }
        $notice = $this->model('list')->get_arclist(8);
        $this->assign('notice',$notice);
        $help = $this->model('list')->get_arclist(586);
        $this->assign('help',$help);
        $news = $this->model('list')->get_arclist(68);
        $this->assign('news',$news);
		$this->view('index');
	}
	public function error404_f()
	{
		if(file_exists(ROOT.'phpinc/404.php')){
			require(ROOT.'phpinc/404.php');
		}
		header("HTTP/1.0 404 Not Found");
		header('Status: 404 Not Found');
		exit;
	}
    public function count_f()
    {
        $channel = $this->get('channel');
        if(!$channel){
            $this->error(P_Lang('请选择发货渠道！'));
        }
        $weight = $this->get('weight');
        if(!$weight){
            $this->error(P_Lang('请填写包裹重量'));
        }
        $group_id = $this->get('group_id');
        $channel_arr = $this->model('channel')->get_onechannelprice('channel_id='.$channel.' and group_id='.$group_id);
        $rs = $this->model('channel')->get_one($channel);
        if($channel_arr['num']<>'0.00'){
            $fn=round($weight-floor($weight),2);
            if($fn <= $channel_arr['num']){
                $weight = floor($weight);
            }
        }
        if($channel_arr['rule']=='ceil'){
            $pay_weight = ceil($weight);
        }
        if($channel_arr['rule']=='round'){
            $fn=round($weight-floor($weight),2);
            if($fn >0 && $fn < 0.5){
                $pay_weight = floor($weight)+0.5;
            }elseif($fn > 0.5){
                $pay_weight = floor($weight)+1;
            }else{
				$pay_weight = $weight;
			}
        }
        if($channel_arr['rule']=='intval'){
            $pay_weight = $weight;
        }
        if($pay_weight>$channel_arr['start_heavy']){
            $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($pay_weight - 1);
        }else{
            $channel_price = $channel_arr['first_price'] + $channel_arr['second_price'] * ($channel_arr['start_heavy'] - 1);
        }
        $channel_price = round($channel_price,2);
        $this->assign('weight',$weight);
        $this->assign('rs',$rs);
        $this->assign('channel_price',$channel_price);
        $currency = $this->model('currency')->get_one($rs['currency_id']);
        $this->assign('currency',$currency);
        $this->view('index_count');
    }
    public function delivery_f(){
        $stock = $this->model('stock')->get_stockList();
        $this->assign('stock',$stock);
        $this->view('delivery');
    }
	public function idcard_f(){
        $ext_list = $this->model('fields')->get_all("identifier in ('idcard_front','idcard_back')");
        $extlist = array();
        foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
        {
            if($value["ext"]){
                $ext = unserialize($value["ext"]);
                foreach($ext AS $k=>$v){
                    $value[$k] = $v;
                }
            }
            $extlist[] = $this->lib('form')->format($value);
        }
        $this->assign("extlist",$extlist);
        $this->view("idcard");
    }
    //首页运单查询
    public function search_f(){
        $sn = $this->get('sn');
        $sn = trim($sn);
        if(!$sn){
            $this->error(P_Lang('请填写运单号查询'));
        }
        $order = $this->model('order')->get_one_from_sn($sn);
        if(!$order){
            $this->error(P_Lang('运单信息不存在'));
        }
        if($order['express'] && $order['status']=='received'){
            //读express信息
            $rs = $this->model('order')->express_oid($order['id']);
            $express = $this->model('express')->get_one($rs['express_id']);
            $rate = $express['rate'] ? $express['rate'] : 6;
            $time = strtotime(date("Y-m-d H:i",$rs['last_query_time']));
            $time += $rate * 3600;
            //如果未超出系统限制，不查询直接返回查询结果
            if($time < $this->time && !$rs['is_end']){
                $file = $this->dir_root.'gateway/express/'.$express['code'].'/index.php';
                $info = include $file;
                //更新操作时间
                $this->model('order')->update_last_query_time($rs['id']);
                //删除旧的获取查询结果的数据
                $this->model('order')->log_del($rs['order_id'],$rs['id'],$express['title']);
                //保存新的
                if($info['content']){
                    foreach($info['content'] as $key=>$value){
                        $data = array('order_id'=>$rs['order_id'],'order_express_id'=>$rs['id']);
                        $data['addtime'] = strtotime($value['time']);
                        $data['who'] = $express['title'];
                        $data['note'] = $value['content'];
                        $this->model('order')->log_save($data);
                    }
                }
                if($info['is_end']){
                    $this->model('order')->update_end($rs['id']);
					$this->model('order')->update_order_status($order['id'],'finished');
                }
            }
        }
        $rslist = $this->model('order')->log_list($order['id']);
        $this->assign('rslist',$rslist);
		$this->assign('rs',$rs);
        $this->view('order_search');
    }
}
?>