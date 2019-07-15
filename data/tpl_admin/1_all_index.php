<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'all'));?>">全局内容</a>
            <i class="fa fa-angle-right"></i>
        </li>
    </ul>
    <?php if($popedom['gset']){ ?>
    <div class="pull-right" style="padding: 5px 10px 5px 0">
        <a class="btn green-meadow" href="<?php echo admin_url('all','gset');?>">
            <i class="fa fa-plus"></i>
            添加
        </a>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">

                    <tbody>
                    <?php if($popedom['gset'] || $popedom['set']){ ?>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td class="col-md-10"><?php echo $value['title'];?></td>

                        <td class="col-md-2 text-center">
                            <a class="btn btn-xs btn-info" href="<?php echo admin_url('all','set');?>&id=<?php echo $value['id'];?>">
                                <i class="fa fa-edit"></i>
                                编辑
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function admin_delete(id,title)
    {
        $.dialog.confirm("<?php echo P_Lang("确定要删除管理员");?> <span class='red'>"+title+"</span> <?php echo P_Lang("吗?");?>",function(){
            var url = get_url("admin","delete") +"&id="+id;
            var rs = json_ajax(url);
            if(rs.status != "ok")
            {
                $.dialog.alert(rs.content);
                return false;
            }
            else
            {
                $("#admin_tr_"+id).remove();
            }
        });
    }
    /*function set_status(id)
     {
     var url = get_url("admin","status") + '&id='+id;
     var rs = json_ajax(url);
     if(rs.status == "ok")
     {
     if(!rs.content) rs.content = '0';
     var oldvalue = $("#status_"+id).attr("value");
     var old_cls = "status"+oldvalue;
     $("#status_"+id).removeClass(old_cls).addClass("status"+rs.content).attr("value",rs.content);
     }
     else
     {
     $.dialog.alert(rs.content);
     return false;
     }
     }*/
    function admin_edit(id)
    {
        var url = get_url("admin","set") + '&id='+id;
        direct(url);
    }
    /*$(document).ready(function(){
     top.$.desktop.title('<?php echo P_Lang("管理员维护");?>');
     });*/
</script>
<?php $this->output("foot","file"); ?>