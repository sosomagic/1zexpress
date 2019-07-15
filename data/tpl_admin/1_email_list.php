<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'email'));?>">通知内容管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>列表 <em class="font-red">短信模板以标识：sms_开头，发送的内容不带样式</em></span>
        </li>
    </ul>
    <?php if($popedom['add']){ ?>
    <div class="text-right" style="padding:5px 10px 5px 0"><a class="btn btn-circle green-meadow btn-outline sbold uppercase btn-sm" href="<?php echo dsy_url(array('ctrl'=>'email','func'=>'set','type'=>'email'));?>"><?php echo P_Lang("添加邮件模板");?></a>&nbsp;&nbsp;<a class="btn btn-circle green-meadow btn-outline sbold uppercase btn-sm" href="<?php echo dsy_url(array('ctrl'=>'email','func'=>'set','type'=>'sms'));?>"><?php echo P_Lang("添加短信模板");?></a></div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold text-center">ID</th>
                            <th class="bold text-center">标题头</th>
                            <th class="bold text-center">标识</th>
                            <?php if($popedom['modify'] || $popedom['delete']){ ?><th class="bold text-center">操作</th><?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                        <tr>
                            <td class="text-center"><?php echo $value['id'];?></td>
                            <td><?php echo $value['title'];?></td>
                            <td class="text-center"><?php echo $value['identifier'];?></td>

                            <td class="text-center">
                                <?php if($popedom['modify']){ ?>
                                <input type="button" value="编辑" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'email','func'=>'set','id'=>$value['id']));?>')" class="btn btn-xs btn-info" />
                                <?php } ?>
                                <?php if($popedom['delete']){ ?>
                                <input type="button" value="删除" onclick="mail_delete('<?php echo $value['id'];?>','<?php echo $value['identifier'];?>')"  class="btn btn-xs btn-danger" />
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php if($pagelist){ ?>
                <div class="row text-right" style="margin-right:2px;"><?php $this->output("pagelist","file"); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function mail_delete(id,title)
    {
        $.dialog.confirm("确定要删除标识 <span class='red'>"+title+"</span> 的模板内容吗?<br />删除后不能正常发送通知",function(){
            var url = get_url("email","del")+"&id="+id;
            var rs = $.dsy.json(url);
            if(rs.status == "ok"){
                $.dsy.reload();
            }else{
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>