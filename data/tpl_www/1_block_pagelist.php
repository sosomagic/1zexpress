<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $pagelist = dsy_page($pageurl,$total,$pageid,$psize,"prev=上一页&next=下一页&last=末页&home=首页&always=1");?>
<ul class="pagination">
    <?php if($total>0){ ?>
    <li>总共：<?php echo $total;?>条</li>
    <?php } ?>
	<?php $pagelist_id["num"] = 0;$pagelist=is_array($pagelist) ? $pagelist : array();$pagelist_id["total"] = count($pagelist);$pagelist_id["index"] = -1;foreach($pagelist AS $key=>$value){ $pagelist_id["num"]++;$pagelist_id["index"]++; ?>
	<li<?php if($value['status']){ ?> class="active"<?php } ?>>
		<?php if($value['nolink']){ ?>
		<a><?php echo $value['title'];?></a>
		<?php } else { ?>
		<a href="<?php echo $value['url'];?>" title="<?php echo $value['title'];?>"><?php echo $value['title'];?></a>
		<?php } ?>
	</li>
	<?php } ?>
</ul>