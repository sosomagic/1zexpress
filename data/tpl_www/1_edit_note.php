<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" id="setsubmit" onsubmit="return false;">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">备注：</td>
                            <td>
                                <textarea name="note" maxlength="100" style="width:100%;" rows="2"><?php echo $rs['note'];?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function save()
    {
        var id = '<?php echo $id;?>';
        var url = api_url('order','edit_note');
        if(id){
            url += "&id="+id;
        }
        $("#setsubmit").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                if(rs.status == 'ok'){
                    var tip = '备注信息修改成功';
                    $.dialog.alert(tip,function(){
                        top.$.dsy.reload();
                        //$.dsy.go('<?php echo dsy_url(array('ctrl'=>'open','func'=>'address'));?>&id=0');
                    },'succeed');
                }else{
                    $.dialog.alert(rs.content);
                    return false;
                }
            }
        });
        return false;
    }
</script>
<?php $this->output("foot_open","file"); ?>