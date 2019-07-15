<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>
        <li class="nav-item start active open">
            <a href="<?php echo dsy_url(array('ctrl'=>'index','func'=>'main'));?>" target="main" class="nav-link">
                <i class="icon-home"></i>
                <span class="title">后台主页</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-cubes"></i>
                <span class="title">转运管理</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'package'));?>" target="main" class="nav-link ">
                        <span class="title">全部包裹</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'storage'));?>" target="main" class="nav-link ">
                        <span class="title">扫描入库</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'add'));?>" target="main" class="nav-link ">
                        <span class="title">添加包裹</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'chkorder'));?>" target="main" class="nav-link ">
                        <span class="title">待审核运单</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-bank"></i>
                <span class="title">快递管理</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order'));?>" target="main" class="nav-link">
                        <span class="title">全部运单</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'set'));?>" target="main" class="nav-link ">
                        <span class="title">直接下单</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'import'));?>" target="main" class="nav-link ">
                        <span class="title">批量下单</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'pickup'));?>" target="main" class="nav-link ">
                        <span class="title">扫描揽收</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'export'));?>" target="main" class="nav-link ">
                        <span class="title">运单导出</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'search'));?>" target="main" class="nav-link">
                        <span class="title">批量单号导出</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'modify'));?>" target="main" class="nav-link ">
                        <span class="title">派送信息更新</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'batch'));?>" target="main" class="nav-link ">
                        <span class="title">批次管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'recycle'));?>" target="main" class="nav-link ">
                        <span class="title">运单回收站</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-layers"></i>
                <span class="title">其他服务</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'delivery'));?>" target="main" class="nav-link ">
                        <span class="title">取件管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'purchase'));?>" target="main" class="nav-link ">
                        <span class="title">代购管理</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-social-dribbble"></i>
                <span class="title">内容管理</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'87'));?>" target="main" class="nav-link ">
                        <span class="title">关于我们</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'43'));?>" target="main" class="nav-link ">
                        <span class="title">资讯中心</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo admin_url('book');?>" target="main" class="nav-link ">
                        <span class="title">工单管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'cate'));?>" target="main" class="nav-link ">
                        <span class="title">分类管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'142'));?>" target="main" class="nav-link ">
                        <span class="title">友情链接</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'148'));?>" target="main" class="nav-link ">
                        <span class="title">在线客服</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'42'));?>" target="main" class="nav-link ">
                        <span class="title">导航菜单</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'147'));?>" target="main" class="nav-link ">
                        <span class="title">页脚导航</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'41'));?>" target="main" class="nav-link ">
                        <span class="title">幻灯片</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>'150'));?>" target="main" class="nav-link ">
                        <span class="title">注册协议</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-user"></i>
                <span class="title">会员管理</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'user'));?>" target="main" class="nav-link ">
                        <span class="title">会员列表</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'usergroup'));?>" target="main" class="nav-link ">
                        <span class="title">会员等级</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'user','func'=>'address_list'));?>" target="main" class="nav-link ">
                        <span class="title">收件人管理</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-money"></i>
                <span class="title">财务管理</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'show'));?>" target="main" class="nav-link ">
                        <span class="title">资金明细</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'currency'));?>" target="main" class="nav-link ">
                        <span class="title">货币/汇率</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'payment'));?>" target="main" class="nav-link ">
                        <span class="title">充值方案</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank_list'));?>" target="main" class="nav-link ">
                        <span class="title">转账管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'claim'));?>" target="main" class="nav-link ">
                        <span class="title">理赔管理</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-truck"></i>
                <span class="title">物流设置</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'track_list'));?>" target="main" class="nav-link ">
                        <span class="title">运单状态</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'stock'));?>" target="main" class="nav-link ">
                        <span class="title">仓库管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'channel'));?>" target="main" class="nav-link ">
                        <span class="title">线路渠道</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'price'));?>" target="main" class="nav-link ">
                        <span class="title">运费设置</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'express'));?>" target="main" class="nav-link ">
                        <span class="title">快递设置</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'code'));?>" target="main" class="nav-link ">
                        <span class="title">报关条码</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'number'));?>" target="main" class="nav-link ">
                        <span class="title">国内快递单号</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'custom'));?>" target="main" class="nav-link ">
                        <span class="title">增值服务</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="icon-settings"></i>
                <span class="title">系统设置</span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'all','func'=>'setting'));?>" target="main" class="nav-link ">
                        <span class="title">基本设置</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'all'));?>" target="main" class="nav-link ">
                        <span class="title">全局设置</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'gateway'));?>" target="main" class="nav-link ">
                        <span class="title">邮箱/短信</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'email'));?>" target="main" class="nav-link ">
                        <span class="title">通知模板</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'plugin','func'=>'config','id'=>'loginext'));?>" target="main" class="nav-link ">
                        <span class="title">快捷登录</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'res'));?>" target="main" class="nav-link ">
                        <span class="title">资源管理</span>
                    </a>
                </li>
                <?php if($session['admin_rs']['if_system']){ ?>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'log'));?>" target="main" class="nav-link ">
                        <span class="title">日志管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo dsy_url(array('ctrl'=>'admin'));?>" target="main" class="nav-link ">
                        <span class="title">管理员设置</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </li>
        </ul>
    </div>
</div>
<script>
    $('.page-sidebar-menu li').on('click', '.nav-item', function(e){
        $('.nav-item').removeClass('start active open');
        $(this).addClass('start active open');
        $(this).parent().parent().addClass('start active open');
    });
</script>
