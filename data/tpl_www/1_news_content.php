<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $title = $rs['title'].' - '.$cate_rs['title'].' - '.$page_rs['title'];?>
<?php $this->assign("title",$title); ?><?php $this->assign("menutitle",$cate_rs['title']); ?><?php $this->output("head","file"); ?>
<div class="banner-product news-banner">
    <div class="container">
        <div class="banner-tt">资讯中心</div>
        <div class="banner-englist">Information Center</div>
    </div>
</div>
<div class="about-padding">
    <div class="container">
        <div class="nav-b clearfix"><a href="<?php echo $sys['url'];?>">首页</a><span>/</span><a href="<?php echo $page_rs['url'];?>" title="<?php echo $page_rs['title'];?>"><?php echo $page_rs['title'];?>></a>
            <?php if($cate_parent_rs){ ?>
            <span>/</span><a href="<?php echo $cate_parent_rs['url'];?>" title="<?php echo $cate_parent_rs['title'];?>"><?php echo $cate_parent_rs['title'];?></a>
            <?php } ?>
            <?php if($cate_rs){ ?>
            <span>/</span><a href="<?php echo $cate_rs['url'];?>" title="<?php echo $cate_rs['title'];?>"><?php echo $cate_rs['title'];?></a>
            <?php } ?>
        </div>
        <div class="clearfix">
            <div class="pull-left about-left">
                <?php $this->output("block_catelist","file"); ?>
            </div>
            <div class="pull-right about-right">
                <div class="article-tt text-center"><?php echo $rs['title'];?></div>
                <div class="article-date text-center">发表时间：<?php echo date('Y-m-d',$rs['dateline']);?><span>浏览次数：<?php echo $rs['hits'];?></span></div>
                <div class="article-c">
                    <?php echo $rs['content'];?>
                </div>
                <div class="news-nev">下一篇文章：<?php $next =dsy_next($rs);?>
                    <?php if($next){ ?>
                    <a href="<?php echo $next['url'];?>" title="<?php echo $next['title'];?>"><?php echo $next['title'];?></a>
                    <?php } else { ?>
                    没有了
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot","file"); ?>