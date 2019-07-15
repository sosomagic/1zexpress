<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="news-tt-left"><?php echo $page_rs['title'];?></div>
<ul>
    <?php $list=dsy('_catelist',array('pid'=>$page_rs['id'],'cateid'=>$page_rs['cate']));?>
    <?php $list_id["num"] = 0;$list['sublist']=is_array($list['sublist']) ? $list['sublist'] : array();$list_id["total"] = count($list['sublist']);$list_id["index"] = -1;foreach($list['sublist'] AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
    <li<?php if($cate_rs['id'] == $value['id']){ ?> class="active"<?php } ?>><a href="<?php echo $value['url'];?>"><i class="nav01 nav0<?php echo $list_id['index']+5;?>"></i><?php echo $value['title'];?></a></li>
    <?php } ?>
</ul>