<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("menutitle","网站首页"); ?><?php $this->output("head","file"); ?>
<div id="carousel-example-generic" class="carousel slide banner" data-ride="carousel">
    <?php $list = dsy('picplayer');?>
    <?php if($list['total']){ ?>
    <ol class="carousel-indicators">
        <?php $listid["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$listid["total"] = count($list['rslist']);$listid["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $listid["num"]++;$listid["index"]++; ?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $listid['index'];?>"<?php if($listid['index']==0){ ?> class="active"<?php } ?>></li>
        <?php } ?>
    </ol>
    <div class="carousel-inner" role="listbox">
        <?php $listid["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$listid["total"] = count($list['rslist']);$listid["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $listid["num"]++;$listid["index"]++; ?>
        <div class="item<?php if($listid['index']==0){ ?> active<?php } ?>"><a href="<?php echo $value['link'];?>" target="<?php echo $value['target'];?>" title="<?php echo $value['title'];?>"><img src="<?php echo $value['pic']['filename'];?>" alt="<?php echo $value['title'];?>"></a></div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if(!$session['user_name']){ ?>
    <div class="login-c">
        <div class="login-mask"></div>
        <div class="login-container">
            <div class="login-tt text-center">会员登录</div>
            <form role="form" id="loginForm" method="post" onsubmit="return check_input()" action="<?php echo dsy_url(array('ctrl'=>'login','func'=>'ok'));?>">
            <div class="login-relative">
                <i class="login01"></i>
                <input type="text" name="user" class="form-control login-input" placeholder="用户名" />
            </div>
            <div class="login-relative">
                <i class="login01 login02"></i>
                <input type="password" name="pass" class="form-control login-input" placeholder="登录密码" />
            </div>
            <?php if($sys['is_vcode'] && function_exists("imagecreate")){ ?>
            <div class="login-relative">
                <i class="login01 login03"></i>
                <input type="text" name="_chkcode" id="_chkcode" class="form-control login-input login-input-xs" placeholder="验证码" />
                <img id="vcode" title="点击切换验证码" class="btn btn-yzm btn-yzm-xs" style="cursor:pointer;">
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#vcode").dsy_vcode();
                    $("#vcode").click(function(){
                        $(this).dsy_vcode();
                    });
                });
            </script>
            <?php } ?>
            <button type="submit" class="btn btn-login" />登录</button>
            </form>
            <div class="clearfix login-link">
                <div class="pull-left"><a href="<?php echo dsy_url(array('ctrl'=>'register'));?>">注册会员</a></div>
                <div class="pull-right"><a href="<?php echo dsy_url(array('ctrl'=>'login','func'=>'getpass'));?>">忘记密码</a></div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="link-back">
    <div class="container text-center">
        <div class="row">
            <div class="col-xs-3"><a href="javascript:idcard_submit(0);void(0)"><img src="tpl/www/images/link01.png"><span>上传身份证</span></a></div>
            <div class="col-xs-3"><a href="<?php echo dsy_url(array('ctrl'=>'usercp'));?>"><img src="tpl/www/images/link02.png"><span>在线缴税</span></a></div>
            <div class="col-xs-3"><a href="<?php echo dsy_url(array('ctrl'=>'usercp'));?>"><img src="tpl/www/images/link03.png"><span>提供购物截图</span></a></div>
            <div class="col-xs-3 after-no"><a href="javascript:qujian_submit(0);void(0)"><img src="tpl/www/images/link04.png"><span>上门取件</span></a></div>
        </div>
    </div>
</div>
<div class="container">
    <div class="search-c">
        <input type="text" name="sn" id="sn" class="form-control" placeholder="请输入您要查找的运单号" />
        <input id="start_search" type="button" class="btn btn-search" value="运单查询" />
    </div>
</div>
<div class="news-back">
    <div class="container">
        <div class="row news-row">
            <div class="col-xs-4">
                <div class="news-tt">网站公告<a href="index.php?id=news&cate=notice">更多</a><i class="glyphicon glyphicon-triangle-bottom"></i></div>
                <ul class="news-list">
                    <?php $listid["num"] = 0;$notice=is_array($notice) ? $notice : array();$listid["total"] = count($notice);$listid["index"] = -1;foreach($notice AS $key=>$value){ $listid["num"]++;$listid["index"]++; ?>
                    <li class="news-li">
                        <a href="index.php?id=<?php echo $value['id'];?>" title="<?php echo $value['title'];?>">
                            <div class="news-date"><span><?php echo date('d',$value['dateline']);?></span><br><?php echo date('Y-m',$value['dateline']);?></div>
                            <div class="news-a-tt"><?php echo dsy_cut($value['title'],'20','…');?><?php if($listid['index']==0){ ?><span>NEW</span><?php } ?></div>
                            <div class="news-txt"><?php echo dsy_cut($value['content'],'30','…');?></div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-4">
                <div class="news-tt news-tt02">帮助中心<a href="index.php?id=news&cate=help">更多</a><i class="glyphicon glyphicon-triangle-bottom"></i></div>
                <ul class="news-list">
                    <?php $listid["num"] = 0;$help=is_array($help) ? $help : array();$listid["total"] = count($help);$listid["index"] = -1;foreach($help AS $key=>$value){ $listid["num"]++;$listid["index"]++; ?>
                    <li class="news-li02">
                        <a href="index.php?id=<?php echo $value['id'];?>">
                            <div class="news-date"><span><?php echo date('d',$value['dateline']);?></span><br><?php echo date('Y-m',$value['dateline']);?></div>
                            <div class="news-a-tt"><?php echo dsy_cut($value['title'],'20','…');?><?php if($listid['index']==0){ ?><span>NEW</span><?php } ?></div>
                            <div class="news-txt"><?php echo dsy_cut($value['content'],'30','…');?></div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-4">
                <div class="news-tt news-tt03">行业新闻<a href="index.php?id=news&cate=industry">更多</a><i class="glyphicon glyphicon-triangle-bottom"></i></div>
                <ul class="news-list">
                    <?php $listid["num"] = 0;$news=is_array($news) ? $news : array();$listid["total"] = count($news);$listid["index"] = -1;foreach($news AS $key=>$value){ $listid["num"]++;$listid["index"]++; ?>
                    <li class="news-li02">
                        <a href="index.php?id=<?php echo $value['id'];?>">
                            <div class="news-date"><span><?php echo date('d',$value['dateline']);?></span><br><?php echo date('Y-m',$value['dateline']);?></div>
                            <div class="news-a-tt"><?php echo dsy_cut($value['title'],'20','…');?><?php if($listid['index']==0){ ?><span>NEW</span><?php } ?></div>
                            <div class="news-txt"><?php echo dsy_cut($value['content'],'30','…');?></div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="zy-tt"><span class="tt-txt">转运流程</span><span class="tt-lind"></span></div>
    <ul class="lc-row text-center row">
        <li>
            <div class="lc-img"><img src="tpl/www/images/lc01.png"></div>
            <div class="lc-tt">注册会员</div>
            <div class="lc-txt">获取国外收货地址</div>
        </li>
        <li>
            <div class="lc-img"><img src="tpl/www/images/lc02.png"></div>
            <div class="lc-tt">轻松购物</div>
            <div class="lc-txt">填写国外收货地址</div>
        </li>
        <li>
            <div class="lc-img"><img src="tpl/www/images/lc03.png"></div>
            <div class="lc-tt">包裹入库</div>
            <div class="lc-txt">提交发货申请</div>
        </li>
        <li>
            <div class="lc-img"><img src="tpl/www/images/lc04.png"></div>
            <div class="lc-tt">仓库发货</div>
            <div class="lc-txt">随时跟踪状态</div>
        </li>
        <li>
            <div class="lc-img"><img src="tpl/www/images/lc05.png"></div>
            <div class="lc-tt">坐等收货</div>
            <div class="lc-txt">祝您生活愉快</div>
        </li>
    </ul>
    <div class="zy-tt"><span class="tt-txt">合作伙伴</span><span class="tt-lind"></span></div>
    <?php $list = dsy('link');?>
    <ul class="row hb-list">
        <?php $list_rslist_id["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$list_rslist_id["total"] = count($list['rslist']);$list_rslist_id["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $list_rslist_id["num"]++;$list_rslist_id["index"]++; ?>
        <li><a href="<?php echo $value['link'];?>" target="<?php echo $value['target'];?>"><img src="<?php echo $value['thumb']['gd']['auto'];?>"></a></li>
        <?php } ?>
    </ul>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#start_search").click(function(){
        var sn=$("#sn").val();
        if(!sn){
            $.dialog.alert("请填写运单号查询");
            return false;
        }
        var arr = sn.split('\n');
        if(arr.length>1){
            $.dialog.alert("一次最多只能查询1个运单号");
            return false;
        }
        var url = get_url('index','search','sn='+sn);
        $.dialog.open(url,{
            'title':'运单号：'+sn+'物流查询信息',
            'width':'70%',
            'height':'70%',
            'lock':true
        });
        return false;
    });
});
function idcard_submit(){
    var url = get_url("index","idcard");
    $.dialog.open(url,{
        title: "上传身份证",
        lock : true,
        width: "600px",
        height: "300px"
    });
    return false;
}
function qujian_submit(){
    var url = get_url("index","delivery");
    $.dialog.open(url,{
        title: "免费预约上门取件",
        lock : true,
        width: "450px",
        height: "100%"
    });
    return false;
}
</script>
<?php $this->output("foot","file"); ?>