<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="bottom-back">
    <div class="container">
        <ul class="bottom-list text-center row">
            <li><img src="tpl/www/images/bottom01.png"><span>极速高效</span></li>
            <li><img src="tpl/www/images/bottom02.png"><span>稳定物流</span></li>
            <li><img src="tpl/www/images/bottom03.png"><span>阳光口岸</span></li>
            <li><img src="tpl/www/images/bottom04.png"><span>安全快捷</span></li>
            <li><img src="tpl/www/images/bottom05.png"><span>优质服务</span></li>
        </ul>
    </div>
</div>
<div class="footer">
    <div class="container clearfix">
        <div class="pull-left footer-left">
            <div class="footer-tt">关于我们</div>
            <ul class="footer-list">
                <?php $list = dsy('footnav');?>
                <?php $list_id["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$list_id["total"] = count($list['rslist']);$list_id["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
                <li><a href="<?php echo $value['url'];?>"><span>·</span> <?php echo $value['title'];?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="pull-left footer-left">
            <div class="footer-tt">海淘工具</div>
            <ul class="footer-list">
                <li><a href=""><span>·</span> 快递查询</a></li>
                <li><a href=""><span>·</span> 在线翻译</a></li>
                <li><a href=""><span>·</span> 汇率查询</a></li>
                <li><a href=""><span>·</span> 单位换算</a></li>
            </ul>
        </div>
        <div class="pull-left footer-left footer-right">
            <div class="footer-qq"><img src="tpl/www/images/qq.png"></div>
            <div class="clearfix">
                <div class="pull-left">
                    <div class="footer-phone"><?php echo $config['contact']['tel'];?><br><span>7*24小时全天守候，用心服务！</span></div>
                    <div class="footer-phone footer-email"><?php echo $config['contact']['email'];?></div>
                </div>
                <?php $list = dsy('kefu');?>
                <?php if($list['project'] && $list['rslist']){ ?>
                <?php if($list['project']['barcode']){ ?>
                <div class="pull-left ewm">
                    <div class="ewm-txt">关注微信</div>
                    <div><img src="<?php echo $list['project']['barcode']['filename'];?>" width="106"></div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php $list = dsy('kefu');?>
<?php if($list['project'] && $list['rslist']){ ?>
<ul class="fixed-ul">
    <li>
        <span class="fixed-qq"></span>
        <div class="qq-c">
            <div class="qq-back">
                <div class="qq-tt text-center">在线咨询</div>
                <div class="clearfix qq-border">
                    <div class="pull-left qq-left">
                        周一至周六<br>08：30～12：00
                    </div>
                    <div class="pull-right"><a href="" class="qq-a">联系客服</a></div>
                </div>
                <div class="row text-center">
                    <?php $list_rslist_id["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$list_rslist_id["total"] = count($list['rslist']);$list_rslist_id["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $list_rslist_id["num"]++;$list_rslist_id["index"]++; ?>
                    <div class="col-xs-6">
                        <a href="tencent://Message/?Uin=<?php echo $value['qq'];?>&websiteName=dsaiyin.com=&Menu=yes" class="qq-tank"><img src="tpl/www/images/qq-a.png"><br>08：30～12：00</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </li>
    <li class="phone-li"><div class="phone-txt"><?php echo $config['contact']['tel'];?></div><span class="fixed-qq fixed-phone"></span></li>
    <?php if($list['project']['barcode']){ ?>
    <li class="wx-li">
        <div class="wx-img"><img src="<?php echo $list['project']['barcode']['filename'];?>"></div><span class="fixed-qq fixed-wx"></span>
    </li>
    <?php } ?>
    <li class="jsq-li"><a href="index.php?id=news&cate=fysx" class="fixed-qq fixed-jsq"></a></li>
    <li><a href="javascript:goTop();" class="fixed-qq fixed-top"></a></li>
</ul>
<script>
    $(".navbar-toggle").click(function(){
        if($(".nav-list").is(":hidden"))
        {
            $(".nav-list").slideDown("slow");
        }else{
            $(".nav-list").slideUp("slow");
            $(".nav-down").slideUp("slow");
        }
    });
    $(".nav-down-c").click(function(){
        if($(".nav-down").is(":hidden"))
        {
            $(".nav-down").slideDown("slow");
        }else{
            $(".nav-down").slideUp("slow");
        }
    });
	//回到顶部
function goTop(){
	$('html,body').animate({'scrollTop':0},600);
}
</script>
<?php } ?>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>