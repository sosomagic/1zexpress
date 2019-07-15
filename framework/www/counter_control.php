<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 18-1-24
 * Time: 上午10:45
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class counter_control extends dsy_control
{
    public function __construct()
    {
        parent::control();
    }
    public function index_f(){
        $this->view("counter");
    }
    public function channel_f(){
        $channel = $this->model('channel')->CounterChannel();
        $this->json($channel,true);
    }
}
