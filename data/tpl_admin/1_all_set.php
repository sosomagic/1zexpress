<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('all');?>" title="返回全局维护">全局设置</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php echo $rs['title'];?></span>
            <?php if($popedom['gset']){ ?>
            <a href="<?php echo admin_url('all','gset');?>&id=<?php echo $id;?>" class="font-green-meadow">【维护设置】</a>
            <?php } ?>
        </li>
    </ul>
</div>
<form method="post" id="<?php echo $ext_module;?>" action="<?php echo admin_url('all','ext_save');?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
                        <tr>
                            <td class="text-right"><?php echo $value['title'];?><span class="font-blue">[<?php echo $value['identifier'];?>]</span>：<?php if($popedom['ext']){ ?>
                                <a class="fa fa-edit" onclick="ext_edit('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>')"></a>&nbsp;&nbsp;<a class="fa fa-times" onclick="ext_delete('<?php echo $value['identifier'];?>','<?php echo $ext_module;?>','<?php echo $value['title'];?>')"></a>
                                <?php } ?></td>
                            <td><?php echo $value['html'];?>
                                <span class="help-block"><?php echo $value['note'];?></span></td>
                        </tr>
                        <?php } ?>
                        <?php if($popedom['ext']){ ?>
                        <tr>
                            <td class="text-right"></td>
                            <td>
                                <span id="_quick_insert"></span>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $.ajax({
                                            'url':"<?php echo dsy_url(array('ctrl'=>'ext','func'=>'select','type'=>'all','module'=>$ext_module));?>",
                                            'dataType':'html',
                                            'cache':false,
                                            'async':true,
                                            'beforeSend': function (XMLHttpRequest){
                                                XMLHttpRequest.setRequestHeader("request_type","html");
                                            },
                                            'success':function(rs){
                                                $("#_quick_insert").html(rs);
                                            }
                                        });
                                    });
                                </script><input type="button" value="标准创建扩展字段" onclick="ext_add('<?php echo $ext_module;?>')" class="btn sbold uppercase btn-outline green-jungle" /></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit"> 提 交 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $this->output("foot","file"); ?>