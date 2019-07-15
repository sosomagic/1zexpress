<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" action="<?php echo dsy_url(array('ctrl'=>'open','func'=>'sender'));?>">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td>发件人姓名:</td>
                            <td><input type="text" name="fullname" value="<?php echo $fullname;?>"></td>
                            <td>电话:</td>
                            <td><input type="text" name="tel" value="<?php echo $tel;?>"></td>
                            <td><input type="submit" value="查 询" class="btn btn-sm btn-info" /></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">ID</th>
                        <th class="bold">姓名</th>
                        <th class="bold">电话</th>
                        <th class="bold">地址</th>
                        <th class="bold">邮编</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['id'];?></td>
                        <td><?php echo $value['fullname'];?></td>
                        <td><?php echo $value['tel'];?></td>
                        <td><?php echo $value['address'];?></td>
                        <td><?php echo $value['zipcode'];?></td>
                        <td><input type="button" value="选择" class="dsy-btn btn btn-xs btn-danger" /></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var id = "<?php echo $id;?>";
    //var id=GetQueryString("id");
    var obj = $.dialog.opener;
    $(document).ready(function(){
        $('.dsy-btn').click(function(){
            var val = $(this).parents('tr').children('td');
            obj.$('#consignor_'+id).val(val.eq(1).text());
            obj.$('#consignor_tel_'+id).val(val.eq(2).text());
            obj.$('#consignor_address_'+id).val(val.eq(3).text());
            $.dialog.close();
        });
    })
</script>
<?php $this->output("foot_open","file"); ?>