<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div id="express_select_info" style="display: none">
    <select id="code">
        <?php $codelist_id["num"] = 0;$codelist=is_array($codelist) ? $codelist : array();$codelist_id["total"] = count($codelist);$codelist_id["index"] = -1;foreach($codelist AS $key=>$value){ $codelist_id["num"]++;$codelist_id["index"]++; ?>
        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
        <?php } ?>
    </select>
</div>
<script type="text/javascript">
    function add_it()
    {
        var url = get_url('express','set');
        $.dialog({
            'title':"请选择快递接口引挈",
            'content':document.getElementById("express_select_info"),
            'ok':function(){
                var code = $("#code").val();
                if(!code){
                    alert('请选择要创建的快递引挈');
                    return false;
                }
                url += "&code="+code;
                $.dsy.go(url);
                return true;
            },
            'cancel':function(){},'cancelVal':'取消'
        });
    }
    function edit_it(id)
    {
        $.dsy.go(get_url('express','set','id='+id));
    }
    function delete_it(id)
    {
        var text = $("#title_"+id).text();
        $.dialog.confirm('确定要删除物流快递：<span class="font-red">'+text+'</span> 吗？',function(){
            var url = get_url('express','delete','id='+id);
            var rs = $.dsy.json(url);
            if(rs.status == 'ok'){
                $.dsy.reload();
            }else{
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
</script>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('express');?>" title="返回快递列表">快递管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>快递列表</span>
        </li>
    </ul>
    <?php if($popedom['add']){ ?>
    <div class="pull-right" style="margin-bottom: 5px;margin-right: 10px;margin-top: 5px;" >
        <a class="btn green" href="javascript:add_it();void(0);">
            <i class="fa fa-plus"></i>
            添加快递
        </a>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="col-md-3 bold">快递名</th>
                        <th class="col-md-3 bold">公司名</th>
                        <th class="col-md-2 bold">官方网站</th>
                        <th class="col-md-2 bold">接口</th>
                        <th class="col-md-2 bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td id="title_<?php echo $value['id'];?>"><?php echo $value['title'];?></td>
                        <td><?php echo $value['company'];?></td>
                        <td><a href="<?php echo $value['url'];?>" target="_blank"><?php echo $value['homepage'];?></a></td>
                        <td><?php echo $codelist[$value['code']]['title'];?></td>
                        <td class="text-center">
                            <button class="btn btn-xs btn-info" onclick="edit_it('<?php echo $value['id'];?>')">
                                <i class="fa fa-edit"></i> 编辑
                            </button>
                            <button class="btn btn-xs btn-danger" onclick="delete_it('<?php echo $value['id'];?>')">
                                <i class="fa fa-times"></i> 删除
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot","file"); ?>