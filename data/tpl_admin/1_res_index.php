<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<style type="text/css">
    .thumb{float:left;width:130px;margin:3px 5px;padding:2px;border:1px solid #ccc;border-radius:3px;position:relative;z-index:1;}
    .thumb div.check_box{position:absolute;left:2px;top:2px;z-index:2;}
</style>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'res'));?>">资源附件管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>附件列表</span>
        </li>
    </ul>
    <div class="pull-right" style="padding: 5px 10px 5px 0">
        <a href="javascript:update_pl_pictures();void(0);" class="btn btn-circle green-haze btn-outline sbold uppercase btn-sm">全部更新</a>
    </div>
</div>
<div class="page-bar">
    <form class="navbar-form navbar-left" method="post" action="<?php echo admin_url('res');?>">
        <div class="form-group">
            <input class="form-control" type="text" id="keywords" name="keywords" value="<?php echo $keywords;?>" placeholder="关键字">
        </div>
        <div class="form-group">
            <select id="cate_id" name="cate_id" class="form-control">
                <option value="0">不限</option>
                <?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>
                <option value="<?php echo $value['id'];?>"<?php if($cate_id == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
        </div>
        <div class="form-group">
            <select class="form-control" id="filetype" name="filetype">
                <option value=""><?php echo P_Lang("请选择附件类型");?></option>
                <?php $tmpid["num"] = 0;$filetypes=is_array($filetypes) ? $filetypes : array();$tmpid["total"] = count($filetypes);$tmpid["index"] = -1;foreach($filetypes AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                <option value="<?php echo $value;?>" <?php if($filetype== $value){ ?> selected<?php } ?>><?php echo strtoupper($value);?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <div id="move_cate_html" style="display: none">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                        <tr>
                            <td>
                                <div class="md-radio">
                                    <input type="radio" name="newcate" id="newcate_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>"<?php if($tmpid['num'] == 1){ ?> checked<?php } ?> class="md-radiobtn">
                                    <label for="newcate_<?php echo $value['id'];?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo $value['title'];?> </label>
                                </div>
                                <span class="help-block"><?php if($value['typeinfos']){ ?> 支持类型：<?php echo $value['typeinfos'];?><?php } ?></span></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <ul class="attrlist clearfix" id="attr2list">
                    <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                    <div class="thumb">
                        <div class="check_box"><input type="checkbox" name="attrid[]" id="attrid_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" /></div>
                        <label for="attrid_<?php echo $value['id'];?>"><div style="text-align:center;"><img src="<?php echo $value['ico'];?>" width="100" height="100" /></div></label>
                        <div class="file-action" style="text-align:center;"><div class="button-group">
                            <input type="button" value="修改" class="btn btn-xs" onclick="modify('<?php echo $value['id'];?>')" />
                            <input type="button" value="预览" class="btn btn-xs" onclick="preview_attr('<?php echo $value['id'];?>')" />
                            <input type="button" value="删除" class="btn btn-xs" onclick="file_delete('<?php echo $value['id'];?>')" /></div>
                        </div></div>
                    <?php } ?>
                    <div class="clear"></div>
                </ul>
                <div class="row">
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                            <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                            <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                        </div>
                        <div class="form-group">
                            <input type="button" value="移动分类" onclick="move_cate()" class="btn btn-info btn-xs" />
                            <input type="button" value="更新图片" onclick="pl_update()" class="btn btn-success btn-xs" />
                            <input type="button" value="删除" onclick="pl_delete()" class="btn btn-danger btn-xs" />
                        </div>
                    </div>
                    <div class="col-md-6 text-right" style="font-size: 12px;"><?php $this->output("pagelist","file"); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script type="text/javascript">
    function pl_update()
    {
        var id = $.input.checkbox_join(".check_box input[type=checkbox]");
        if(!id || id == "undefined"){
            $.dialog.alert("未指定要操作的附件");
            return true;
        }
        var url = get_url("res","update_pl") + "&id="+$.str.encode(id);
        $.dialog.open(url,{
            'title':'附件批量更新中，请不要关掉这个页面',
            'lock':true,
            'width':'50%',
            'height':'50%',
            'cancel':function(){
                return true;
            }
        });
    }
    function pl_delete()
    {
        var id = $.input.checkbox_join(".check_box input[type=checkbox]");
        if(!id || id == "undefined"){
            $.dialog.alert("未指定要操作的附件");
            return false;
        }
        $.dialog.confirm("确定要删除选中的附件吗？删除后是不可恢复的",function(){
            var url = get_url("res","delete_pl") + "&id="+$.str.encode(id);
            $.dsy.json(url,function(rs){
                if(rs.status == 'ok'){
                    $.dialog.alert('批量删除附件操作成功',function(){
                        $.dsy.reload();
                    },'succeed');
                }else{
                    $.dialog.alert(rs.content);
                }
            })
        });
    }

    function file_delete(id)
    {
        $.dialog.confirm('<?php echo P_Lang("确定要删除此附件吗？删除后不能恢复");?><br />附件ID：<span class="red">'+id+'</span>',function(){
            url = "<?php echo dsy_url(array('ctrl'=>'upload','func'=>'delete'));?>&id="+id;
            $.dsy.json(url,function(rs){
                if(rs.status == 'ok'){
                    $.dsy.reload();
                }else{
                    $.dialog.alert(rs.content);
                    return false;
                }
            })
        });
    }
    function preview_attr(id)
    {
        $.dialog.open(get_url('upload','preview','id='+id),{
            'title':'<?php echo P_Lang("预览附件信息");?>',
            'width':'700px',
            'height':'400px',
            'lock':true,
            'okVal':'关闭',
            'ok':function(){}
        });
    }
    function modify(id)
    {
        $.dialog.open(get_url('res','set','id='+id),{
            'title':'<?php echo P_Lang("编辑附件信息");?>',
            'width':'700px',
            'height':'400px',
            'lock':true,
            'okVal':'提交',
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('<?php echo P_Lang("iframe还没加载完毕呢");?>');
                    return false;
                };
                iframe.save();
                return false;
            },
            'cancelVal':'取消修改',
            'cancel':function(){}
        });
    }
    function add_file()
    {
        $.dialog.open(get_url('res','add'),{
            'title':'添加附件信息',
            'width':'700px',
            'height':'400px',
            'lock':true,
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('<?php echo P_Lang("iframe还没加载完毕呢");?>');
                    return false;
                };
                iframe.save();
                return false;
            },
            'okVal':'<?php echo P_Lang("执行附件上传");?>',
            'cancelVal':'<?php echo P_Lang("取消上传并关闭");?>',
            'cancel':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('<?php echo P_Lang("iframe还没加载完毕呢");?>');
                    return false;
                };
                iframe.cancel();
                return true;
            }
        });
    }
    function update_pl_pictures()
    {
        $.dialog.confirm('<?php echo P_Lang("确定要全部更新图片吗？执行此操作占用时间很长，程序会新开桌面，请不要关闭这个页面");?>',function(){
            var url = get_url("res","update_pl") + "&id=all";
            $.dialog.open(url,{
                'title':'附件批量更新中，请不要关掉这个页面',
                'lock':true,
                'width':'50%',
                'height':'50%',
                'cancel':function(){
                    return true;
                }
            });
        });
    }
    function action_search()
    {
        if($("#adv_search").is(":hidden")){
            $("#adv_search").show();
        }else{
            $("#adv_search").hide();
        }
    }
    function move_cate()
    {
        var id = $.input.checkbox_join(".check_box input[type=checkbox]");
        if(!id || id == "undefined"){
            $.dialog.alert("未指定要操作的附件");
            return false;
        }
        $.dialog({
            'title':'移动分类，请选择目标移动分类',
            'content':document.getElementById('move_cate_html'),
            'lock':true,
            'width':'500px',
            'height':'100px',
            'cancel':function(){},
            'cancelVal':'取消移动',
            'okVal':'执行',
            'ok':function(){
                var newcate = $("input[name=newcate]:checked").val();
                var url = "<?php echo dsy_url(array('ctrl'=>'res','func'=>'movecate'));?>&id="+$.str.encode(id)+"&newcate="+newcate;
                $.dsy.json(url,function(){
                    $.input.checkbox_none('.checkbox input[type=checkbox]');
                    return $.dialog.alert('分类移动成功');
                })
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>
