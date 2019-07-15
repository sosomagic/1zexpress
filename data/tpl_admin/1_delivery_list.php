<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'delivery'));?>">取件管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>上门取件列表</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold"></th>
                            <th class="bold">ID</th>
                            <th class="bold">联系人</th>
                            <th class="bold">手机</th>
                            <th class="bold">取件地址</th>
                            <th class="bold">取件时间</th>
                            <th class="bold">物品重量</th>
                            <th class="bold">仓库</th>
                            <th class="bold">备注</th>
                            <th class="bold">提交时间</th>
                            <th class="bold">状态</th>
                            <th class="bold text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td class="text-center">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                    <span></span>
                                </label>

                            </td>
                            <td><?php echo $value['id'];?></td>
                            <td><?php echo $value['name'];?></td>
                            <td><?php echo $value['mobile'];?></td>
                            <td><?php echo $value['address'];?></td>
                            <td><?php echo date('Y-m-d H:i:s',$value['visitTime']);?></td>
                            <td><?php echo $value['weight'];?></td>
                            <td><?php echo $value['stock']['title'];?></td>
                            <td><?php echo $value['note'];?></td>
                            <td><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                            <td><?php if($value['status']){ ?>已取件<?php } else { ?><span class="font-red">未取件</span><?php } ?></td>
                            <td class="text-center">
                                <?php if($popedom['modify']){ ?><a class="btn btn-xs blue" href="<?php echo dsy_url(array('ctrl'=>'delivery','func'=>'set','id'=>$value['id']));?>">编辑</a> <?php } ?>
                                <?php if($popedom['delete']){ ?><input type="button" value="删除" onclick="delivery_delete('<?php echo $value['id'];?>')" class="btn btn-xs btn-danger" /><?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                            <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                            <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                        </div>
                        <a class="btn btn-xs btn-info" href="javascript:void(0);" onclick="print()">
                            <i class="fa fa-print"></i>
                            取件打印
                        </a>
                        <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="dels()">
                            <i class="fa fa-close"></i>
                            批量删除
                        </a>
                    </div>
                    <div class="col-md-6 text-right" style="font-size: 12px;"><?php $this->output("pagelist","file"); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delivery_delete(id)
    {
        $.dialog.confirm('确定要删除订单吗?<br />删除后您不能再恢复，请慎用',function(){
            var url = get_url('delivery','delete','id='+id);
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
    function print()
    {
        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要打印的上门取件单');
            return false;
        }
        var url = get_url('delivery','print')+"&ids="+$.str.encode(ids);
        var title = '打印上门取件单';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'100%',
            'height':'100%',
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.window.print();
                $.dsy.reload();
                //return false;
            },'okVal':'打印',
            'cancel':function(){
                return true;
            }
        });
    }
    function dels()
    {

        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要删除的上门取件单');
            return false;
        }
        var url = get_url("delivery","dels",'ids='+$.str.encode(ids));
        $.dsy.go(url);
    }
</script>
<?php $this->output("foot","file"); ?>