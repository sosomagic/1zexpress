<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'purchase'));?>">代购管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>代购列表</span>
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
                            <th class="bold text-center">会员名</th>
                            <th class="bold text-center">商品缩略图</th>
                            <th class="bold text-center">商品名称</th>
                            <th class="bold text-center">单价</th>
                            <th class="bold text-center">数量</th>
                            <th class="bold text-center">备注</th>
                            <th class="bold text-center">到货仓库</th>
                            <th class="bold text-center">提交时间</th>
                            <th class="bold text-center">状态</th>
                            <th class="bold text-center">运费（$）</th>
                            <th class="bold text-center">总费用（$）</th>
                            <th class="bold text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td class="text-center"><?php echo $value['user']['user'];?></td>
                            <td class="text-center"><?php if($value['vpic']<>''){ ?><a href="javascript:show_pic('<?php echo $value['vpic'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13"></a><?php } ?></td>
                            <td><a href="<?php echo $value['url'];?>" target="_blank"><?php echo $value['title'];?></a></td>
                            <td><?php echo $value['price'];?></td>
                            <td><?php echo $value['num'];?></td>
                            <td><?php echo $value['note'];?></td>
                            <td><?php echo $value['stock']['title'];?></td>
                            <td><?php echo date('Y-m-d',$value['addtime']);?></td>
                            <td class="text-center"><?php if($value['status']==2){ ?>已订购<?php }elseif($value['status']==1){ ?><span class="font-red">已扣款</span><?php } else { ?><span class="font-blue">未审核</span><?php } ?></td>
                            <td class="text-center"><?php echo $value['express_price'];?></td>
                            <td class="text-center"><?php echo $value['pay_price'];?></td>
                            <td class="text-center">
                                <a class="btn btn-xs blue" href="<?php echo dsy_url(array('ctrl'=>'purchase','func'=>'set','id'=>$value['id']));?>">编辑</a>
                                <input type="button" value="删除" onclick="del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')" class="btn btn-xs btn-danger" />
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if($pagelist){ ?><div class="text-right"><?php $this->output("pagelist","file"); ?></div><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTAINER -->
<script>
    function del(id,title)
    {
        $.dialog.confirm('确定要删除代购信息：<span class="red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
            var url = get_url('purchase','delete','id='+id);
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
    function show_pic(id)
    {
        var url = get_url('purchase','show_pic','id='+id);
        var title = '商品图片';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'350px',
            'height':'350px',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>