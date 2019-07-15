<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","收货地址"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>收件人管理</div>
                            <div class="text-right" style="margin-top:4px;">
                                <a class="btn green" href="javascript:address_setting(0);void(0)">
                                    <i class="fa fa-plus"></i>
                                    添加收件人
                                </a>
                                <a class="btn green" href="javascript:address_import(0);void(0)">
                                    <i class="fa fa-plus"></i>
                                       Excel导入
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="navbar-collapse bg-grey-steel" style="border-radius: 4px;">
                                <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'address'));?>" style="padding-left:0px;">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="fullname" value="<?php echo $fullname;?>" placeholder="收件人姓名">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="mobile" value="<?php echo $mobile;?>" placeholder="收件人手机">
                                    </div>
									<div class="form-group">
                                        <input class="form-control" type="text" name="idcard" value="<?php echo $idcard;?>" placeholder="身份证号">
                                    </div>
                                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                                </form>
                            </div>
                            <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                <tr>
                                    <th class="bold col-md-1">收件人</th>
                                    <th class="bold col-md-4">收件地址</th>
                                    <th class="bold col-md-2">手机</th>
                                    <th class="bold col-md-2">身份证</th>
                                    <th class="bold col-md-3">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($rslist){ ?>
                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                <tr>
                                    <td><?php echo $value['fullname'];?></td>
                                    <td><?php echo $value['province'];?><?php if($value['province'] != $value['city']){ ?><?php echo $value['city'];?><?php } ?><?php echo $value['county'];?><?php echo $value['address'];?></td>
                                    <td><?php echo $value['mobile'];?></td>
                                    <td class="text-center"><?php echo $value['idcard'];?>
                                        <br>
                                        <?php if($value['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?>
                                        <?php if($value['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?>
                                    </td>
                                    <td>
                                        <button onclick="address_setting('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                        <i class="fa fa-edit"></i> 编辑
                                        </button>
                                        <button onclick="address_delete('<?php echo $value['id'];?>')" class="btn btn-xs btn-danger" />
                                        <i class="fa fa-times"></i> 删除
                                        </button>
                                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'apply','id'=>$value['id']));?>" class="btn btn-xs btn-warning"> 下单 </a>
                                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','fullname'=>$value['fullname']));?>" class="btn btn-xs btn-success"> 运单列表 </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php } else { ?>
                                <tr><td colspan="5" class="text-center"><span class="fa fa-warning">没有记录!</span></td></tr>
                                <?php } ?>
                                </tbody>
                            </table>
                                </div>
                                <div class="text-right"><?php $this->output("block_pagelist","file"); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*function address_default(id)
    {
        var url = api_url('usercp','address_default','id='+id);
        $.dsy.json(url,function(){
            $.dsy.reload();
        });
    }*/
    function address_delete(id)
    {
        $.dialog.confirm('确定要删除这个地址吗？',function(){
            var url = api_url('usercp','address_delete','id='+id);
            $.dsy.json(url,function(){
                $.dsy.reload();
            })
        })
    }
    function address_setting(id)
    {
        var url = get_url('usercp','address_setting');
        if(id>0){
            url += "&id="+id;
            var title = '编辑地址(<span class="font-red">*</span>为必填项)';
        }else{
            var title = '添加地址(<span class="font-red">*</span>为必填项)';
        }
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'600px',
            'height':'500px',
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
    function address_import()
    {
        var url = get_url('usercp','address_import');
        var title = '批量导入收件地址';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'600px',
            'height':'310px',
            'cancel':function(){
                return true;
            }
        });
    }
    function address_idcard(id)
    {
        var url = api_url('usercp','address_idcard','id='+id);
        var title = '身份证照片';

        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'318px',
            'height':'438px',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot_member","file"); ?>