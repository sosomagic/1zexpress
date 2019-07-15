<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white">
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="/">
                    <img src="<?php echo $config['adm_logo29'] ? $config['adm_logo29'] : 'tpl/www/images/logo.png';?>" alt="<?php echo $config['title'];?>" class="logo-default" width="86" height="14"/> </a>
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                <span></span>
            </a>
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" src="<?php echo $sys['url'];?>bootstrap/layouts/layout/img/nav.jpg" />
                            <span class="username username-hide-on-mobile">网站导航</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <?php $list = dsy('menu');?>
                            <?php $list_id["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$list_id["total"] = count($list['rslist']);$list_id["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
                            <li>
                                <a href="<?php echo $value['url'];?>" target="_blank"><i class="fa fa-navicon"></i> <?php echo $value['title'];?> </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" src="<?php echo $user['avatar'] ? $user['avatar'] : 'bootstrap/layouts/layout/img/avatar3_small.jpg';?>" />
                            <span class="username username-hide-on-mobile"> <?php echo $session['user_name'];?>【<?php echo $session['user_ucode'];?>】 </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php echo dsy_url(array('ctrl'=>'usercp'));?>">
                                    <i class="icon-user"></i> 会员中心 </a>
                            </li>
                            <li>
                                <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'info'));?>">
                                    <i class="fa fa-edit"></i> 修改资料 </a>
                            </li>
                            <li>
                                <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'passwd'));?>">
                                    <i class="icon-lock"></i> 修改密码 </a>
                            </li>
                            <li><a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'recharge'));?>" class="bar_link">
                                    <i class="fa fa-dollar"></i> <span class="font-red"><?php echo $user['wealth']['wallet']['val'];?> [充值]</span></a>
                            </li>
                            <li>
                                <a href="javascript:logout('<?php echo $session['user_name'];?>');void(0);">
                                    <i class="fa fa-sign-out"></i> 退出登陆 </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"> </div>