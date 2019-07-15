<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('user','address_list');?>" title="返回收件人列表">收件人管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>收件人列表</span>
        </li>
    </ul>
</div>
<div class="page-bar">
    <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'user','func'=>'address_list'));?>">
        <div class="form-group">
            <input class="form-control" type="text" name="fullname" value="<?php echo $fullname;?>" placeholder="收件人姓名">
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="mobile" value="<?php echo $mobile;?>" placeholder="收件人手机">
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="idcard" value="<?php echo $idcard;?>" placeholder="身份证号">
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="ucode" value="<?php echo $ucode;?>" placeholder="会员编号">
        </div>
        <div class="form-group">
            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold col-md-2">会员编号</th>
                        <th class="bold col-md-2">收件人</th>
                        <th class="bold col-md-4">收件地址</th>
                        <th class="bold col-md-2">手机</th>
                        <th class="bold col-md-2">身份证</th>
                        <th class="bold col-md-2 text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['user']['ucode'];?></td>
                        <td><?php echo $value['fullname'];?></td>
                        <td><?php echo $value['province'];?><?php if($value['province'] != $value['city']){ ?><?php echo $value['city'];?><?php } ?><?php echo $value['county'];?><?php echo $value['address'];?></td>
                        <td><?php echo $value['mobile'];?></td>
                        <td class="text-center"><?php echo $value['idcard'];?>
                            <br>
                            <?php if($value['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?>
                            <?php if($value['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?>
                        </td>
                        <td class="text-center">
                            <a href="javascript:;" class="btn btn-xs blue" onclick="edit('<?php echo $value['id'];?>','<?php echo $pageurl;?>')">编辑</a>
                            <a href="javascript:del('<?php echo $value['id'];?>');void(0);" class="btn btn-xs btn-danger">删除</a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php if($pagelist){ ?>
                <div class="row text-right" style="margin-right:2px;"><?php $this->output("pagelist","file"); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	function edit(id,pageurl)
	{
		var url;
		if(pageurl){
			pageurl = pageurl.replace(/\&/g, "%26");//"&"
			url = get_url('user','address_set','id='+id+'&pageurl='+pageurl);
		}else{
			url = get_url('user','address_set','id='+id);
		}
		$.dsy.go(url);
	}
    function del(id)
    {
        $.dialog.confirm('确定要删除这个地址吗？',function(){
            var url = get_url('user','address_delete','id='+id);
            var rs = $.dsy.json(url);
            if(rs.status == 'ok'){
                $.dialog.alert('删除成功',function(){
                    $.dsy.reload();
                },'succeed');
            }else{
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
    function address_idcard(id)
    {
        var url = get_url('user','address_idcard','id='+id);
        var title = '<?php echo P_Lang("身份证照片");?>';

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
<?php $this->output("foot","file"); ?>