<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <span>
                <?php $plist_id["num"] = 0;$plist=is_array($plist) ? $plist : array();$plist_id["total"] = count($plist);$plist_id["index"] = -1;foreach($plist AS $key=>$value){ $plist_id["num"]++;$plist_id["index"]++; ?>
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>$value['id']));?>" title="<?php echo $value['title'];?>"><?php echo $value['nick_title'] ? $value['nick_title'] : $value['title'];?></a>
               <?php } ?>
               <?php if($show_parent_catelist){ ?>
                    <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>$pid,'cateid'=>$show_parent_catelist));?>"><?php echo $parent_cate_rs['title'];?></a>
               <?php } ?>
            </span>
        </li>
    </ul>
    <div class="text-right" style="margin:5px 10px 5px 0;">
        <?php if($popedom['add']){ ?>
            <a class="btn green" href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'edit','pid'=>$pid));?>">添加内容</a>
        <?php } ?>
        &nbsp;
        <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'set','id'=>$pid));?>" class="btn sbold uppercase btn-outline green-jungle">编辑项目</a>
    </div>
</div>
<div class="row">
<div class="col-md-12">
<div class="portlet light bordered">
<div class="portlet-body">
<?php if($rslist){ ?>
<div class="table-scrollable">
<table class="table table-striped table-bordered table-advance table-hover">
<thead>
<tr>
    <th class="bold">&nbsp;</th>
    <th class="bold"><?php echo $rs['alias_title'] ? $rs['alias_title'] : '主题';?></th>
    <?php if($rs['cate']){ ?>
    <th class="bold">分类</th>
    <?php } ?>
    <?php $layout_id["num"] = 0;$layout=is_array($layout) ? $layout : array();$layout_id["total"] = count($layout);$layout_id["index"] = -1;foreach($layout AS $key=>$value){ $layout_id["num"]++;$layout_id["index"]++; ?>
    <?php if($key == "dateline"){ ?>
    <th class="bold"><?php echo $value;?></th>
    <?php }elseif($key == "hits"){ ?>
    <th class="bold">浏览量</th>
    <?php } else { ?>
    <th class="bold"><?php echo $value;?></th>
    <?php } ?>
    <?php } ?>
    <th class="bold text-center">审核</th>
    <th class="bold">排序</th>
    <th class="bold text-center">操作</th>
</tr>
</thead>
<tbody>
<?php $tmp_m = 0;?>
<?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
<?php $tmp_m++;?>
<tr id="list_<?php echo $value['id'];?>" title="<?php echo $rs['alias_title'] ? $rs['alias_title'] : P_Lang('主题');?>: <?php echo $value['title'];?>&#10;<?php echo P_Lang("发布日期");?>: <?php echo date('Y-m-d H:i:s',$value['dateline']);?>">
    <td class="text-center">
        <label class="mt-checkbox mt-checkbox-outline">

            <input id="id_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" name="ids[]" type="checkbox">
            <span></span>
        </label>
    </td>
    <td>
        <label for="id_<?php echo $value['id'];?>">
            <?php echo $value['title'];?>
            <?php if($value['attr']){ ?>
            <?php $attr = explode(",",$value['attr']);?>
            <?php $attr_id["num"] = 0;$attr=is_array($attr) ? $attr : array();$attr_id["total"] = count($attr);$attr_id["index"] = -1;foreach($attr AS $attr_k=>$attr_v){ $attr_id["num"]++;$attr_id["index"]++; ?>
            <a href="<?php echo admin_url('list','action');?>&id=<?php echo $pid;?>&attr=<?php echo $attr_v;?>" class="gray">[<?php echo $attrlist[$attr_v];?>]</a>
            <?php } ?>
            <?php } ?>
            <?php if($value['identifier']){ ?>
            <small>（<?php echo $value['identifier'];?>）</small>
            <?php } ?>
            <?php if($rs['is_biz']){ ?>
            <?php echo price_format($value['price'],$value['currency_id']);?>
            <?php } ?>
            <?php if($value['hidden']){ ?>
            <span class="font-red">(隐藏)</span>
            <?php } ?>
            <?php if($clist && $clist[$value['id']]){ ?>
            <div class="extcate">
                分类：
                <?php $clist__value_id__id["num"] = 0;$clist[$value['id']]=is_array($clist[$value['id']]) ? $clist[$value['id']] : array();$clist__value_id__id["total"] = count($clist[$value['id']]);$clist__value_id__id["index"] = -1;foreach($clist[$value['id']] AS $ck=>$cv){ $clist__value_id__id["num"]++;$clist__value_id__id["index"]++; ?>
                <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>$pid,'cateid'=>$cv));?>" class="i"><?php echo $cateall[$cv];?></a>
                <?php } ?>
            </div>
            <?php } ?>
        </label>
    </td>
    <?php if($rs['cate']){ ?>
    <td>
        <?php if($value['cate_id'] && is_array($value['cate_id'])){ ?>
        <a href="<?php echo admin_url('list','action');?>&id=<?php echo $pid;?>&cateid=<?php echo $value['cate_id']['id'];?>"><?php echo $value['cate_id']['title'];?></a>
        <?php } else { ?>
        未设分类
        <?php } ?>
    </td>
    <?php } ?>
    <?php $layout_id["num"] = 0;$layout=is_array($layout) ? $layout : array();$layout_id["total"] = count($layout);$layout_id["index"] = -1;foreach($layout AS $k=>$v){ $layout_id["num"]++;$layout_id["index"]++; ?>
    <?php if($k == "dateline"){ ?>
    <td><?php echo date('Y-m-d',$value['dateline']);?></td>
    <?php }elseif($k == "hits"){ ?>
    <td><?php echo $value['hits'];?></td>
    <?php }elseif($k == "user_id"){ ?>
    <td><?php echo $value['_user'] ? $value['_user'] : '-';?></td>
    <?php } else { ?>
    <?php if(is_array($value[$k])){ ?>
    <?php $c_list = $value[$k]['_admin'];?>
    <?php if($c_list['type'] == 'pic'){ ?>
    <td><img src="<?php echo $c_list['info'];?>" width="28px" height="28px" border="0" class="hand" onclick="preview_attr('<?php echo $c_list['id'];?>')" style="border:1px solid #dedede;padding:1px;" /></td>
    <?php } else { ?>
    <?php if(is_array($c_list['info'])){ ?>
    <td><?php echo implode(' / ',$c_list['info']);?></td>
    <?php } else { ?>
    <td><?php echo $c_list['info'] ? $c_list['info'] : '-';?></td>
    <?php } ?>
    <?php } ?>
    <?php } else { ?>
    <td><?php echo $value[$k] ? $value[$k] : '-';?></td>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    <td class="text-center"><?php if($value['status']){ ?>已审核<?php } else { ?><span class="font-blue">未审核</span><?php } ?></td>
    <td><?php echo $value['sort'];?></td>
    <td class="text-center">
        <?php if($popedom['modify']){ ?>
        <button class="btn btn-xs btn-info" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'list','func'=>'edit','id'=>$value['id']));?>')">
            <i class="fa fa-edit"></i> 编辑
        </button>
        <?php } ?>
        <?php if($popedom['delete']){ ?>
        <button class="btn btn-xs btn-danger" onclick="content_del('<?php echo $value['id'];?>')">
            <i class="fa fa-times"></i> 删除
        </button>
        <?php } ?>
        <?php if($session['admin_rs']['if_system'] && $rs['is_appoint']){ ?>
        <button class="btn btn-sm btn-warning" onclick="set_admin_id('<?php echo $value['id'];?>')">
            <i class="fa fa-angle-double-left"></i> 指派
        </button>
        <?php } ?>
        <?php if($rs['subtopics'] && !$value['parent_id'] && $popedom['add']){ ?>
        <button type="button" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'list','func'=>'edit','parent_id'=>$value['id'],'pid'=>$value['project_id']));?>')" class="btn btn-sm btn-warning" />
        <i class="fa fa-angle-double-right"></i> 加子导航
        </button>
        <?php } ?>
    </td>
</tr>
<?php $value_sonlist_id["num"] = 0;$value['sonlist']=is_array($value['sonlist']) ? $value['sonlist'] : array();$value_sonlist_id["total"] = count($value['sonlist']);$value_sonlist_id["index"] = -1;foreach($value['sonlist'] AS $kk=>$vv){ $value_sonlist_id["num"]++;$value_sonlist_id["index"]++; ?>
<?php $tmp_m++;?>
<tr id="list_<?php echo $vv['id'];?>" title="<?php echo $rs['alias_title'] ? $rs['alias_title'] : '主题';?>：<?php echo $vv['title'];?>&#10;发布日期：<?php echo date('Y-m-d H:i:s',$vv['dateline']);?>">
    <td class="center">
        <label class="mt-checkbox mt-checkbox-outline">

            <input type="checkbox" name="ids[]" id="id_<?php echo $vv['id'];?>" value="<?php echo $vv['id'];?>">
            <span></span>
        </label>
    </td>
    <td>
        <label for="id_<?php echo $vv['id'];?>">
            &nbsp; &nbsp; ├─ <?php echo $vv['title'];?>
            <?php if($vv['attr']){ ?>
            <?php $attr = explode(",",$vv['attr']);?>
            <?php $attr_id["num"] = 0;$attr=is_array($attr) ? $attr : array();$attr_id["total"] = count($attr);$attr_id["index"] = -1;foreach($attr AS $attr_k=>$attr_v){ $attr_id["num"]++;$attr_id["index"]++; ?>
            [<?php echo $attrlist[$attr_v];?>]
            <?php } ?>
            <?php } ?>
            <?php if($vv['identifier']){ ?>
            <span class="gray i">（<?php echo $vv['identifier'];?>）</span>
            <?php } ?>
            <?php if($rs['is_biz']){ ?>
            <span class="red i"> <?php echo price_format($vv['price'],$vv['currency_id']);?></span>
            <?php } ?>
            <?php if($vv['hidden']){ ?>
            <span class="red i">(隐藏)</span>
            <?php } ?>
            <?php if($clist && $clist[$vv['id']]){ ?>
            <div class="extcate">
                分类：
                <?php $clist__vv_id__id["num"] = 0;$clist[$vv['id']]=is_array($clist[$vv['id']]) ? $clist[$vv['id']] : array();$clist__vv_id__id["total"] = count($clist[$vv['id']]);$clist__vv_id__id["index"] = -1;foreach($clist[$vv['id']] AS $ck=>$cv){ $clist__vv_id__id["num"]++;$clist__vv_id__id["index"]++; ?>
                <a href="<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>$pid,'cateid'=>$cv));?>" class="i"><?php echo $cateall[$cv];?></a>
                <?php } ?>
            </div>
            <?php } ?>
        </label>
    </td>
    <?php if($rs['cate']){ ?>
    <td class="gray center">
        <?php if($vv['cate_id'] && is_array($vv['cate_id'])){ ?>
        <a href="<?php echo admin_url('list','action');?>&id=<?php echo $pid;?>&cateid=<?php echo $vv['cate_id']['id'];?>"><?php echo $vv['cate_id']['title'];?></a>
        <?php } else { ?>
        未设分类
        <?php } ?>
        <?php } ?>
        <?php $layout_id["num"] = 0;$layout=is_array($layout) ? $layout : array();$layout_id["total"] = count($layout);$layout_id["index"] = -1;foreach($layout AS $k=>$v){ $layout_id["num"]++;$layout_id["index"]++; ?>
        <?php if($k == "dateline"){ ?>
    <td class="center"><?php echo date("Y-m-d",$vv['dateline']);?></td>
    <?php }elseif($k == "hits"){ ?>
    <td class="center"><?php echo $vv['hits'];?></td>
    <?php }elseif($k == 'user_id'){ ?>
    <td><?php echo $vv['_user'] ? $vv['_user'] : '-';?></td>
    <?php } else { ?>
    <?php if(is_array($vv[$k])){ ?>
    <?php $c_list = $vv[$k]['_admin'];?>
    <?php if($c_list['type'] == 'pic'){ ?>
    <td><img src="<?php echo $c_list['info'];?>" width="28px" height="28px" border="0" class="hand" onclick="preview_attr('<?php echo $c_list['id'];?>')" style="border:1px solid #dedede;padding:1px;" /></td>
    <?php } else { ?>
    <?php if(is_array($c_list['info'])){ ?>
    <td><?php echo implode(' / ',$c_list['info']);?></td>
    <?php } else { ?>
    <td><?php echo $c_list['info'] ? $c_list['info'] : '-';?></td>
    <?php } ?>
    <?php } ?>
    <?php } else { ?>
    <td><?php echo $vv[$k] ? $vv[$k] : '-';?></td>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    <td class="text-center"><?php if($vv['status']){ ?>已审核<?php } else { ?><span class="font-blue">未审核</span><?php } ?></td>
    <td class="center"><?php echo $vv['sort'];?></td>
    <td class="text-center">
        <?php if($popedom['modify']){ ?>
        <button type="button"  onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'list','func'=>'edit','id'=>$vv['id']));?>')" class="btn btn-sm btn-info" />
        <i class="fa fa-edit"></i>
        編輯
        </button>
        <?php } ?>
        <?php if($popedom['delete']){ ?>
        <button type="button"  onclick="content_del('<?php echo $vv['id'];?>')" class="btn btn-sm btn-danger" />
        <i class="fa fa-times"></i>
        刪除
        </button>
        <?php } ?>
    </td>
</tr>
<?php } ?>
<?php } ?>
</table>
</div>
</tbody>
</table>
</div>
<?php } ?>
<div class="row">
    <?php if($rslist){ ?>
    <div class="col-md-6 form-inline">
        <div class="form-group">
            <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
            <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
            <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
        </div>
        <div class="form-group">
            <select id="list_action_val" style="margin-top:5px;" onchange="update_select()">
                <option value="">选择要执行的动作…</option>
                <?php if($opt_catelist){ ?>
                <optgroup label="分类操作">
                    <?php $opt_catelist_id["num"] = 0;$opt_catelist=is_array($opt_catelist) ? $opt_catelist : array();$opt_catelist_id["total"] = count($opt_catelist);$opt_catelist_id["index"] = -1;foreach($opt_catelist AS $key=>$value){ $opt_catelist_id["num"]++;$opt_catelist_id["index"]++; ?>
                    <option value="cate:<?php echo $value['id'];?>"><?php echo $value['_space'];?><?php echo $value['title'];?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
                <?php if($rs['is_attr']){ ?>
                <optgroup label="<?php echo $rs['alias_title'] ? $rs['alias_title'] : P_Lang('主题');?><?php echo P_Lang("属性");?>">
                    <?php $attrlist_id["num"] = 0;$attrlist=is_array($attrlist) ? $attrlist : array();$attrlist_id["total"] = count($attrlist);$attrlist_id["index"] = -1;foreach($attrlist AS $key=>$value){ $attrlist_id["num"]++;$attrlist_id["index"]++; ?>
                    <option value="attr:<?php echo $key;?>"><?php echo $value;?> <?php echo $key;?></option>
                    <?php } ?>
                </optgroup>
                <?php } ?>
                <optgroup label="其他">
                    <?php if($popedom['status']){ ?>
                    <option value="status">审核</option>
                    <option value="unstatus">取消审核</option>
                    <?php } ?>
                    <option value="hidden">隐藏</option>
                    <option value="show">显示</option>
                    <?php if($popedom['delete']){ ?>
                    <option value="delete">删除</option>
                    <?php } ?>
                    <?php if($session['admin_rs']['if_system'] && $rs['is_appoint']){ ?>
                    <option value="appoint">指派管理员维护</option>
                    <?php } ?>
                </optgroup>
            </select>
        </div>
        <div class="form-group" id="attr_set_li" style="display: none;">
            <select name="attr_set_val" style="margin-top:5px;" id="attr_set_val">
                <option value="add">添加</option>
                <option value="delete">移除</option>
            </select>
        </div>
        <?php if($opt_catelist){ ?>
        <div class="form-group" id="cate_set_li" style="display: none;">
            <select name="cate_set_val" style="margin-top:5px;" id="cate_set_val">
                <?php if($rs['cate_multiple']){ ?>
                <option value="add">添加扩展分类</option>
                <option value="delete">移除分类绑定</option>
                <?php } ?>
                <option value="move">移动主分类</option>
            </select>
        </div>
        <?php } ?>
        <div class="form-group" id="plugin_button"><input type="button" value="执行操作" onclick="list_action_exec()" class="btn btn-xs blue" /></div>
    </div>
    <?php } ?>
    <div class="text-right"><?php $this->output("pagelist","file"); ?></div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="<?php echo include_js('list.js');?>"></script>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<?php $this->output("foot","file"); ?>