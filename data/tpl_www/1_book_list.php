<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","我的工单"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>我的工单</div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="bold">运单号</th>
                                                    <th class="bold">问题描述</th>
                                                    <th class="bold">分类</th>
                                                    <th class="bold">提交时间</th>
                                                    <th class="bold">管理员回复</th>
                                                    <th class="bold">状态</th>
                                                    <th class="bold text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if($rslist){ ?>
                                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                                <tr>
                                                    <td><?php echo $value['sn'];?></td>
                                                    <td><?php echo $value['content'];?></td>
                                                    <td><?php echo $value['catename']['title'];?></td>
                                                    <td><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                                                    <td><?php echo $value['adm_reply'];?></td>
                                                    <td><?php if($value['status']==0){ ?> 未处理 <?php } else { ?> 已处理 <?php } ?></td>
                                                    <td class="text-center">
                                                        <input type="button" value="查看" onclick="show('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                                        <?php if($value['status']==0){ ?>
                                                        <input type="button" value="修改" onclick="edit('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                                        <input type="button" value="删除" onclick="del('<?php echo $value['id'];?>')" class="btn btn-xs btn-danger" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <tr><td colspan="7" class="text-center"><span class="fa fa-warning">没有记录!</span></td></tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="text-right" style="margin-right: 16px;"><?php $this->output("block_pagelist","file"); ?></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function edit(id){
        direct("<?php echo dsy_url(array('ctrl'=>'book'));?>&id="+id);
    }
    function del(id){
        $.dialog.confirm('确定要删除提交的问题吗?<br />删除后您不能再恢复!',function(){
            var url = get_url('book','delete','id='+id);
            var rs = json_ajax(url);
            if(rs.status == 'ok')
            {
                $.dsy.reload();
            }
            else
            {
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
    function show(id)
    {
        var url = get_url('book','show','id='+id);
        $.dialog.open(url,{
            'title':'问题详情',
            'lock':true,
            width: "700px",
            height: "70%",
            resize: false
        });
    }
</script>
<?php $this->output("foot_member","file"); ?>