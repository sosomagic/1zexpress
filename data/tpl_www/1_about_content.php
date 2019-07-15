<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $title = $rs['title'].' - '.$page_rs['title'];?>
<?php $this->assign("title",$title); ?><?php $this->assign("menutitle",$page_rs['title']); ?><?php $this->output("head","file"); ?>
<div class="banner-product banner-about">
    <div class="container">
        <div class="banner-tt">关于我们</div>
        <div class="banner-englist">About Us</div>
    </div>
</div>
<div class="about-padding">
    <div class="container">
        <div class="nav-b clearfix"><a href="<?php echo $sys['url'];?>" title="<?php echo $config['title'];?>">首页</a><span>/</span><a href="index.php?id=aboutus"><?php echo $page_rs['title'];?></a><span>/</span><a href="<?php echo $page_rs['url'];?>" title="<?php echo $page_rs['title'];?>"><?php echo $rs['title'];?></a></div>
        <div class="clearfix">
            <div class="pull-left about-left">
                <ul>
                    <?php $list=dsy('_arclist',array('pid'=>$page_rs['id'],'psize'=>"100",'fields'=>"id"));?>
                    <?php $list_id["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$list_id["total"] = count($list['rslist']);$list_id["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
                    <li<?php if($rs['id'] == $value['id']){ ?> class="active"<?php } ?>><a href="<?php echo $value['url'];?>"><i class="nav01<?php if($list_id['index']>0){ ?> nav0<?php echo $list_id['index'];?><?php } ?>"></i><?php echo $value['title'];?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="pull-right about-right">
                <div class="about-tt"><?php echo $rs['title'];?></div>
                <div class="article">
                    <?php echo $rs['content'];?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot","file"); ?>