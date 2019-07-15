<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">
        <div class="page-logo">
            <a href="/">
                <img src="<?php echo $config['adm_logo29'] ? $config['adm_logo29'] : 'images/logo.png';?>" alt="<?php echo $config['title'];?>" class="logo-default" width="86" height="14"/> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a href="<?php echo $sys['www_file'];?>" class="dropdown-toggle" style="width:40px;" title="访问网站首页" target="_blank">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:dsy_admin_clear();void(0);" class="dropdown-toggle" style="width: 40px" title="更新缓存">
                        <i class="icon-refresh"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="<?php echo dsy_url(array('ctrl'=>'user'));?>" class="dropdown-toggle" style="width:40px" title="会员中心">
                        <i class="icon-user"></i>
                    </a>
                </li>
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?php echo $sys['url'];?>bootstrap/layouts/layout/img/avatar3_small.jpg" />
                        <span class="username username-hide-on-mobile"> <?php echo $session['admin_account'];?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="javascript:dsy_admin_control();void(0);">
                                <i class="icon-user"></i> 基本资料 </a>
                        </li>
                        <li>
                            <a href="javascript:dsy_admin_logout();void(0);">
                                <i class="fa fa-sign-out"></i> 退出登陆 </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="clearfix"> </div>