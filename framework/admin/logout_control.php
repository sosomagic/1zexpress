<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class logout_control extends dsy_control
{
    public function __construct()
    {
        parent::control();
    }
    public function index_f()
    {
        $admin_name = $_SESSION["admin_account"];
        foreach($_SESSION as $key=>$value){
            if(substr($key,0,5) == 'admin'){
                unset($_SESSION[$key]);
            }
        }
        $tip = P_Lang('管理员{admin_name}成功退出',array('admin_name'=>$this->session->val("admin_account")));
        $this->success($tip,$this->url,1);
    }
}
?>