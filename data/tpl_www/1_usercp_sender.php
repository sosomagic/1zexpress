<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","发件人管理"); ?><?php $this->output("head_member","file"); ?>
<?php $this->output("nav","file"); ?>
<div class="page-container">
    <?php $this->output("block_usercp","file"); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i>发件人管理</div>
                            <div class="text-right" style="margin-top:4px;">
                                <a class="btn green" href="javascript:address_setting(0);void(0)">
                                    <i class="fa fa-plus"></i>
                                    添加发件人
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                <tr>
                                    <th class="col-md-2 bold">发件人</th>
                                    <th class="col-md-2 bold">电话</th>
                                    <th class="col-md-4 bold">地址</th>
                                    <th class="col-md-2 bold">邮编</th>
                                    <th class="col-md-2 bold text-center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($rslist){ ?>
                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                <tr>
                                    <td><?php echo $value['fullname'];?></td>
                                    <td><?php echo $value['tel'];?></td>
                                    <td><?php echo $value['address'];?></td>
                                    <td><?php echo $value['zipcode'];?></td>
                                    <td class="text-center">
                                        <button type="button" onclick="address_setting('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                        <i class="fa fa-edit"></i> 编辑
                                        </button>
                                        <button type="button" onclick="address_delete('<?php echo $value['id'];?>')" class="btn btn-xs btn-danger" />
                                        <i class="fa fa-times"></i> 删除
                                        </button>
										<?php if(!$value['is_default']){ ?>
                                        <a href="javascript:sender_default('<?php echo $value['id'];?>');void(0);" class="btn btn-xs btn-warning">设为默认</a>
                                        <?php } else { ?>
                                        <span class="font-red">默认地址</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr><td colspan="5"><span class="fa fa-warning">没有记录!</span></td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function address_delete(id)
    {
        $.dialog.confirm('确定要删除这个地址吗？',function(){
            var url = api_url('usercp','sender_delete','id='+id);
            $.dsy.json(url,function(){
                $.dsy.reload();
            })
        })
    }
    function address_setting(id)
    {
        var url = get_url('usercp','sender_setting');
        if(id>0){
            url += "&id="+id;
            var title = '编辑发件人（*为必填项）';
        }else{
            var title = '添加发件人（*为必填项）';
        }
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'560px',
            'height':'260px',
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
            'cancel':function(){
                return true;
            }
        });
    }
	function sender_default(id)
    {
        var url = api_url('usercp','sender_default','id='+id);
        $.dsy.json(url,function(){
            $.dsy.reload();
        });
    }
</script>
<?php $this->output("foot_member","file"); ?>