<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" id="setsubmit">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>身份证号：</td>
                            <td><input type="text" id="idcard" name="idcard" value=""  class="form-control"/>
                                <span class="help-block">身份证号要与身份证图片一致</span>
                            </td>
                        </tr>
                        <?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span><?php echo $value['title'];?>：</td>
                            <td><?php echo $value['html'];?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn blue" type="submit">
                                <i class="fa fa-edit"></i>
                                提 交
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#setsubmit").submit(function(){
            $(this).ajaxSubmit({
                type:'post',
                url: api_url('order','idcard'),
                dataType:'json',
                'success':function(rs){
                    if(rs.status == 'ok'){
                        var tip = '身份证上传成功';
                        $.dialog.alert(tip,function(){
                            top.$.dsy.reload();
                        },'succeed');
                    }else{
                        $.dialog.alert(rs.content);
                        return false;
                    }
                }
            });
            return false;
        });
    });
</script>
<!--<script type="text/javascript">
    function save()
    {
        var url = api_url('order','idcard');
        $("#setsubmit").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                if(rs.status == 'ok'){
                    var tip = '身份证上传成功';
                    $.dialog.alert(tip,function(){
                        top.$.dsy.reload();
                    },'succeed');
                }else{
                    $.dialog.alert(rs.content);
                    return false;
                }
            }
        });
        return false;
    }
</script>-->
<?php $this->output("foot_open","file"); ?>