<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<script type="text/javascript" src="<?php echo include_js('list.js');?>"></script>
<script type="text/javascript">
    function set_ext_cateid(val)
    {
        var main_cateid = $("input[name=cate_id]:checked").val();
        if(!main_cateid || main_cateid == 'undefined'){
            $("input[name=cate_id][value="+val+"]").attr('checked',true);
            return true;
        }
        if(main_cateid == val){
            $("#ext_cate_id_"+val).attr('checked',true);
            $.dialog.alert('<?php echo P_Lang("当前分类已设置为主分类，扩展分类不支持取消");?>');
        }
    }
    function set_main_cateid(val)
    {
        $("input[name=cate_id][value="+val+"]").attr('checked',true);
        $("#ext_cate_id_"+val).attr('checked',true);
    }
    $(document).keypress(function(e){
        if(e.ctrlKey && e.which == 13 || e.which == 10) {
            $('#dsy_submit_<?php echo $pid;?>').click();
        }
    });
    $(document).ready(function(){
        $("#_listedit").submit(function(){
            var id = $("#id").val();
            var pcate = parseInt("<?php echo $p_rs['cate'];?>");
            var pcate_multiple = '<?php echo $p_rs['cate_multiple'];?>';
            $(this).ajaxSubmit({
                'url':'<?php echo dsy_url(array('ctrl'=>'list','func'=>'ok'));?>',
                'type':'post',
                'dataType':'json',
                'success':function(rs){
                    if(rs.status == 'ok'){
                        var url = "<?php echo dsy_url(array('ctrl'=>'list','func'=>'action','id'=>$pid));?>";
                        if(pcate>0){
                            if(pcate_multiple > 0){
                                var cateid = $("input[name=cate_id]:checked").val();
                            }else{
                                var cateid = $("#cate_id").val();
                            }
                            url += "&cateid="+cateid;
                        }
                        if(id){
                            $.dialog.alert('<?php echo P_Lang("内容信息修改成功");?>',function(){
                                $.dsy.go(url);
                            },'succeed');
                        }else{
                            $.dialog({
                                'icon':'succeed',
                                'content':'<?php echo P_Lang("内容添加操作成功，请选择继续添加或返回列表");?>',
                                'ok':function(){$.dsy.reload();},
                                'okVal':'<?php echo P_Lang("继续添加");?>',
                                'cancel':function(){$.dsy.go(url);},
                                'cancelVal':'<?php echo P_Lang("返回列表");?>',
                                'lock':true
                            });
                        }
                    }else{
                        $.dialog.alert(rs.content);
                    }
                }
            });
            return false;
        });
    });
    laydate.render({
        elem: '#dateline'
        ,type: 'datetime'
    });
</script>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <?php if($pid){ ?>
            <?php $plist_id["num"] = 0;$plist=is_array($plist) ? $plist : array();$plist_id["total"] = count($plist);$plist_id["index"] = -1;foreach($plist AS $key=>$value){ $plist_id["num"]++;$plist_id["index"]++; ?>
            <a href="<?php echo admin_url('list','action');?>&id=<?php echo $value['id'];?>" title="<?php echo $value['title'];?>"><?php echo $value['title'];?></a>
            <?php } ?>
            <?php } ?>
            <?php if($parent_id){ ?>
            父主题：<a href="<?php echo admin_url('list','edit');?>&id=<?php echo $parent_id;?>" title=""><span class="red"><?php echo $parent_rs['title'];?></span></a>
            <?php } ?>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑内容<?php } else { ?>添加内容<?php } ?></span>
        </li>
    </ul>
</div>
<form method="post" id="_listedit">
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $parent_id;?>" />
<div class="row">
<div class="col-md-12">
<div class="portlet box grey">
<div class="portlet-body">
<table class="table table-striped table-bordered table-hover">
<tbody>
<tr>
    <td class="text-right col-md-2">标题：</td>
    <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control" placeholder="请在此输入<?php echo $p_rs['alias_title'] ? $p_rs['alias_title'] : '主题';?><?php if($p_rs['alias_note']){ ?>，<?php echo $p_rs['alias_note'];?><?php } ?>" />
    </td>
</tr>
<?php if($p_rs['cate'] && $p_rs['cate_multiple']){ ?>
<tr>
    <td class="text-right col-md-2">分类：</td>
    <td>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th width="30px">主</th>
                <th>扩展分类</th>
            </tr>
            <?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
            <tr<?php if($tmpid['num']%2){ ?> class="list"<?php } ?>>
            <td align="center">
                <div class="md-radio">
                    <input type="radio" name="cate_id" id="cate_id" value="<?php echo $value['id'];?>"<?php if($rs['cate_id'] == $value['id']){ ?> checked<?php } ?> onclick="set_main_cateid('<?php echo $value['id'];?>')">
                    <label for="cate_id">
                        <span></span>
                        <span class="check"></span>
                        <span class="box"></span> </label>
                </div>
            </td>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <?php for($i=1;$i<$value['_layer'];$i++){ ?>
                        <td class="cate_line">&nbsp;</td>
                        <?php } ?>
                        <?php if($value['_layer']){ ?>
                        <td class="cate_middle">&nbsp;</td>
                        <?php } ?>
                        <td colspan="2">
                            <div class="md-checkbox">
                                <input id="ext_cate_id_<?php echo $value['id'];?>" class="md-check" type="checkbox" name="ext_cate_id[]" value="<?php echo $value['id'];?>" onclick="set_ext_cateid('<?php echo $value['id'];?>')"<?php if(in_array($value['id'],$extcate)){ ?> checked<?php } ?>>
                                <label for="ext_cate_id_<?php echo $value['id'];?>">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span><?php echo $value['title'];?>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            </tr>
            <?php } ?>
        </table>
    </td>
</tr>
<?php } ?>
<?php if($attrlist && $p_rs['is_attr']){ ?>
<tr>
    <td class="text-right col-md-2">属性：</td>
    <td>
        <div class="md-checkbox-inline">
            <?php $attr = $rs['attr'] ? explode(",",$rs['attr']) : array();?>
            <?php $attrlist_id["num"] = 0;$attrlist=is_array($attrlist) ? $attrlist : array();$attrlist_id["total"] = count($attrlist);$attrlist_id["index"] = -1;foreach($attrlist AS $key=>$value){ $attrlist_id["num"]++;$attrlist_id["index"]++; ?>
            <div class="md-checkbox">
                <input type="checkbox" name="attr[]" id="_attr_<?php echo $key;?>" value="<?php echo $key;?>"<?php if(in_array($key,$attr)){ ?> checked<?php } ?>>
                <label for="_attr_<?php echo $key;?>">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span> <?php echo $value;?> </label>
            </div>
            <?php } ?>
        </div>
    </td>
</tr>
<?php } ?>
<?php if($p_rs['cate'] && !$p_rs['cate_multiple']){ ?>
<tr>
    <td class="text-right col-md-2">分类：</td>
    <td>
        <select name="cate_id" id="cate_id">
            <option value="">请选择分类…</option>
            <?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
            <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $rs['cate_id']){ ?> selected<?php } ?>><?php echo $value['_space'];?><?php echo $value['title'];?></option>
            <?php } ?>
        </select>
    </td>
</tr>
<?php } ?>
<?php if($p_rs['biz_attr']){ ?>
<script type="text/javascript">
    function biz_attr_reload()
    {
        $("#biz_attr_options").html('<div style="background:#fff;padding:30px;text-align:center;"><img src="images/loading.gif" /></div>');
        biz_attr_loading();
    }
    function biz_attr_loading()
    {
        var tid = "<?php echo $id;?>";
        var url = get_url('list','options_html','pid=<?php echo $pid;?>&tid='+tid);
        $.dsy.json(url,function(rs){
            if(rs.status == 'ok'){
                if(!rs.content){
                    rs.content = '';
                }
                $("#biz_attr_options").html(rs.content);
            }else{
                $("#biz_attr_options").html(rs.content);
            }
        });
    }
    function attr_add(tid,pid)
    {
        var val = $("#biz_attr_id").val();
        if(!val){
            $.dialog.alert('请选择一个要添加的属性');
            return false;
        }
        var text = $("#biz_attr_id").find('option:selected').text();
        var url = get_url('list','options_add','aid='+val);
        if(tid && tid != 'undefined'){
            url += "&tid="+tid;
        }else{
            url += "&pid="+pid;
        }
        var rs = $.dsy.json(url+"&type=chk");
        if(rs.status != 'ok'){
            $.dialog.alert(rs.content);
            return false;
        }
        $.dialog.open(url,{
            'title':'添加属性，'+text,
            'width':'700px',
            'height':'400px',
            'lock':true,
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
            'okVal':'提交',
            'cancel':function(){
                return true;
            },
            'cancelVal':'取消'
        });
    }
    function biz_attr_delete(aid,tid)
    {
        var url = get_url('list','options_delete','aid='+aid+"&tid="+tid);
        var rs = $.dsy.json(url);
        if(rs.status == 'ok'){
            biz_attr_reload();
        }else{
            $.dialog.alert(rs.content);
            return false;
        }
    }
    function biz_attr_edit(aid,tid)
    {
        var url = get_url('list','options_edit','aid='+aid);
        if(tid && tid != 'undefined'){
            url += "&tid="+tid;
        }else{
            url += "&pid=<?php echo $pid;?>";
        }
        var rs = $.dsy.json(url+"&type=chk");
        if(rs.status != 'ok'){
            $.dialog.alert(rs.content);
            return false;
        }
        $.dialog.open(url,{
            'title':'编辑属性',
            'width':'700px',
            'height':'400px',
            'lock':true,
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
            'okVal':'提交',
            'cancel':function(){
                return true;
            },
            'cancelVal':'取消'
        });
    }
    $(document).ready(function(){
        biz_attr_reload();
    });
</script>
<?php if($biz_attrlist){ ?>
<tr>
    <td class="text-right col-md-2">属性：</td>
    <td>
        <select id="biz_attr_id">
            <option value="">请选择一个属性…</option>
            <?php $tmpid["num"] = 0;$biz_attrlist=is_array($biz_attrlist) ? $biz_attrlist : array();$tmpid["total"] = count($biz_attrlist);$tmpid["index"] = -1;foreach($biz_attrlist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
            <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
            <?php } ?>
        </select>
        <input type="button" value="添加属性" onclick="attr_add('<?php echo $id;?>','<?php echo $p_rs['id'];?>')" class="btn btn-success" />
    </td>
</tr>
<?php } ?>
<div id="biz_attr_options" class="options"></div>
<?php } ?>
<?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
<tr>
    <td class="text-right col-md-2"><?php echo $value['title'];?>：</td>
    <td>
        <?php echo $value['html'];?>
        <span class="help-block">
        <?php echo $value['note'];?>
        <?php if($popedom['ext'] && $value['is_edit']){ ?>
        <?php if($ext_module != 'add-list'){ ?>
        <a class="icon edit" onclick="ext_edit('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>')"></a>
        <?php } ?>
        <a class="icon delete" onclick="ext_delete('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>','<?php echo $value['title'];?>')"></a>
        <?php } ?>
        </span>
    </td>
</tr>
<?php } ?>
<?php if($p_rs['is_identifier']){ ?>
<tr>
    <td class="text-right col-md-2">自定义网址标识</td>
    <td><input type="text" id="identifier" name="identifier" value="<?php echo $rs['identifier'];?>" class="form-control"/>
        <span class="help-block">限字母、数字、下划线或中划线且必须是字母开头</span>
    </td>
</tr>
<?php } ?>
<tr>
    <td class="text-right col-md-2">发布时间</td>
    <td><input type="text" id="dateline" name="dateline" value="<?php if($rs['dateline']){ ?><?php echo date('Y-m-d H:i:s',$rs['dateline']);?><?php } ?>" class="form-control"/>
        <span class="help-block">自定义发布时间，留空使用系统默认时间</span>
    </td>
</tr>
<tr>
    <td class="text-right col-md-2">阅读次数</td>
    <td><input type="text" id="hits" name="hits" value="<?php echo $rs['hits'];?>" class="form-control"/>
    </td>
</tr>
<?php if($p_rs['is_tag']){ ?>
<tr>
    <td class="text-right col-md-2">Tag标签：</td>
    <td>
        <input type="text" id="tag" name="tag" value='<?php echo $rs['tag'];?>' class="form-control"/>
        <span class="help-block">多个Tag用英文空格分开，最多不能超过10个</span>
    </td>
</tr>
<?php } ?>
<tr>
    <td class="text-right col-md-2">状态：</td>
    <td>
        <div class="md-radio-inline">
            <div class="md-radio">
                <input type="radio" name="status" id="status_0" value="0"<?php if(!$rs['status']){ ?> checked<?php } ?> class="md-radiobtn">
                <label for="status_0">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span> 未审核 </label>
            </div>
            <div class="md-radio">
                <input type="radio" name="status" id="status_1" value="1"<?php if($rs['status']){ ?> checked<?php } ?> class="md-radiobtn">
                <label for="status_1">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span> 已审核 </label>
            </div>
        </div>
    </td>
</tr>
<tr>
    <td align="right"><?php echo P_Lang("排序：");?></td>
    <td><input type="text" id="sort" name="sort" class="form-control" value="<?php echo $rs['sort'];?>" /></td>
</tr>
</tbody>
</table>
<div class="text-center">
    <button class="btn blue" type="submit">
        <i class="fa fa-edit"></i>
        提  交
    </button>
</div>
</div>
</div>
</div>
</div>
</form>
<?php $this->output("foot","file"); ?>