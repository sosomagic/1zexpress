<?php
/***********************************************************
Filename: task/clear.php
Note	: 订单管理，编辑和删除等相关操作
Version : 2.0
Web		: www.dsaiyin.com
Author  : dsaiyin <QQ:17189095>
Update  : 2017年03月04日
 ***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
$this->cache->clear();
return true;
?>