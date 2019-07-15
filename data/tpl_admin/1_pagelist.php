<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php if($pagelist){ ?>
<ul class="pagination">
    <?php $idx["num"] = 0;$pagelist=is_array($pagelist) ? $pagelist : array();$idx["total"] = count($pagelist);$idx["index"] = -1;foreach($pagelist AS $key=>$value){ $idx["num"]++;$idx["index"]++; ?>
    <?php if($value['type'] != 'opt'){ ?>
    <li<?php if($value['status']){ ?> class="active"<?php } ?>><?php if($value['url']){ ?><a href="<?php echo $value['url'];?>"><?php echo $value['title'];?></a><?php } else { ?><a><?php echo $value['title'];?></a><?php } ?></li>
    <?php } ?>
    <?php if($value['type'] == 'opt'){ ?>
    <li>
        <select onchange="direct('<?php echo $value['url'];?>'+this.value)">
            <?php $value_title_id["num"] = 0;$value['title']=is_array($value['title']) ? $value['title'] : array();$value_title_id["total"] = count($value['title']);$value_title_id["index"] = -1;foreach($value['title'] AS $k=>$v){ $value_title_id["num"]++;$value_title_id["index"]++; ?>
            <option value="<?php echo $v['value'];?>"<?php if($v['status']){ ?> selected<?php } ?>><?php echo $v['title'];?></option>
            <?php } ?>
        </select>
    </li>
    <?php } ?>
    <?php } ?>

    <li>
        <a style="padding: 0px;border: 0px;">
        <div class="input-group" style="width:80px;">
            <input class="form-control" type="text" name="go_to_page" id="go_to_page" value="<?php echo G('pageid');?>" style="height:29px;padding:0px;">
            <span class="input-group-btn"><button style="height: 29px;" type="button" class="btn blue btn-sm" onclick="go_to_page_action()">Go!</button></span>
        </div>
        </a>
    </li>

</ul>
<?php } ?>