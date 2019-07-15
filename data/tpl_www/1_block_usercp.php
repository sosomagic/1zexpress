<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="page-sidebar-wrapper">
<div class="page-sidebar navbar-collapse collapse">
<ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
<li class="sidebar-toggler-wrapper hide">
    <div class="sidebar-toggler">
        <span></span>
    </div>
</li>
<li class="nav-item<?php if($sys['ctrl'] == 'package'||$sys['func'] == 'create'||$sys['ctrl'] == 'purchase'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-cubes"></i>
        <span class="title">转运管理</span>
        <?php if($sys['ctrl'] == 'package'||$sys['func'] == 'create'||$sys['ctrl'] == 'purchase'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if($sys['func'] == 'forecast'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'forecast'));?>" class="nav-link ">
                <span class="title">预报包裹</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'package' && $sys['func'] == 'import'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'import'));?>" class="nav-link ">
                <span class="title">批量预报</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'package' && $sys['func'] == 'index' && $status == '' || $sys['func'] == 'info'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'index'));?>" class="nav-link ">
                <span class="title">我的包裹</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'chkorder'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'chkorder'));?>" class="nav-link ">
                <span class="title">待审核运单</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'purchase'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'purchase','func'=>'add'));?>" class="nav-link">
                <span class="title">代购商品</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item<?php if(($sys['ctrl'] == 'order' && $sys['func'] != 'create') || $sys['ctrl'] == 'delivery'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-layers"></i>
        <span class="title">快递管理</span>
        <?php if(($sys['ctrl'] == 'order' && $sys['func'] != 'create') || $sys['ctrl'] == 'delivery'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if($sys['func'] == 'apply'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'apply'));?>" class="nav-link ">
                <span class="title">直接下单</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'import'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'import'));?>" class="nav-link ">
                <span class="title">批量下单</span>
            </a>
        </li>
		 <li class="nav-item<?php if($sys['ctrl'] == 'order' && $sys['func'] == 'index' && $status== '' || $sys['func'] == 'order_info'|| $sys['func'] == 'set'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index'));?>" class="nav-link ">
                <span class="title">我的运单</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'delivery'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'delivery','func'=>'delivery'));?>" class="nav-link">
                <span class="title">上门取件</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item<?php if($sys['ctrl'] == 'payment'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-money"></i>
        <span class="title">财务管理</span>
        <?php if($sys['ctrl'] == 'payment'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if($sys['func'] == 'recharge'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'recharge'));?>" class="nav-link ">
                <span class="title">在线充值</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'bank' && $type=='sm'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank','type'=>'sm'));?>" class="nav-link ">
                <span class="title">扫码充值</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'bank' && $type=='yh'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank','type'=>'yh'));?>" class="nav-link ">
                <span class="title">银行转账</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'banklist'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'banklist'));?>" class="nav-link ">
                <span class="title">转账记录</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'claim'|| $sys['func'] == 'claim_list'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'claim'));?>" class="nav-link ">
                <span class="title">申请理赔</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'details'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'details'));?>" class="nav-link ">
                <span class="title">资金明细</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item<?php if($sys['ctrl'] == 'book'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-question-circle"></i>
        <span class="title">工单管理</span>
        <?php if($sys['ctrl'] == 'book'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if($sys['ctrl'] == 'book' && $sys['func'] == 'index'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'book'));?>" class="nav-link ">
                <span class="title">提交工单</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'book' && $sys['func'] == 'list'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'book','func'=>'list'));?>" class="nav-link ">
                <span class="title">我的工单</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item<?php if($sys['func'] == 'address'||$sys['func'] == 'sender'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-location-arrow"></i>
        <span class="title">我的地址</span>
        <?php if($sys['func'] == 'address'||$sys['func'] == 'sender'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if($sys['func'] == 'address'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'address'));?>" class="nav-link ">
                <span class="title">我的收件人</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'sender'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'sender'));?>" class="nav-link ">
                <span class="title">我的发件人</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item<?php if($sys['ctrl'] == 'usercp' || $sys['ctrl'] =='plugin'){ ?> start active open<?php } ?>">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-settings"></i>
        <span class="title">个人中心</span>
        <?php if($sys['ctrl'] == 'usercp' || $sys['ctrl'] =='plugin'){ ?>
        <span class="arrow open"></span>
        <?php } else { ?>
        <span class="arrow"></span>
        <?php } ?>
    </a>
    <ul class="sub-menu">
        <li class="nav-item<?php if(($sys['ctrl'] == 'usercp' && $sys['func'] == 'index')|| $sys['func'] == 'wealth'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp'));?>" class="nav-link ">
                <span class="title">我的账户</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'plugin'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'plugin','exec'=>'bindaccount','id'=>'loginext'));?>" class="nav-link ">
                <span class="title">个人设置</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['ctrl'] == 'usercp' && $sys['func'] == 'info'){ ?> start active open<?php } ?> ">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'info'));?>" class="nav-link ">
                <span class="title">完善资料</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'avatar'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'avatar'));?>" class="nav-link ">
                <span class="title">修改头像</span>
            </a>
        </li>
        <li class="nav-item<?php if($sys['func'] == 'passwd'){ ?> start active open<?php } ?>">
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'passwd'));?>" class="nav-link ">
                <span class="title">修改密码</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:logout('<?php echo $session['user_name'];?>');void(0);" class="nav-link ">
                <span class="title">会员退出</span>
            </a>
        </li>
    </ul>
</li>
</ul>
</div>
</div>