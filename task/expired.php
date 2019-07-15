<?php
/***********************************************************
Filename: task/expired.php
Note	: 自动删除过期
Version : 2.0
Web		: www.dsaiyin.com
Author  : dsaiyin <QQ:17189095>
Update  : 2017年03月04日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
$this->cache->expired();
return true;
?>