<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">货币/汇率</span>
                </div>
                <div class="pull-right" >
                    <a class="btn green" href="<?php echo admin_url('currency','set');?>">
                        <i class="fa fa-plus"></i>
                        添加货币/汇率
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">货币代号</th>
                        <th class="bold">数字代码</th>
                        <th class="bold">名称</th>
                        <th class="bold">汇率</th>
                        <th class="bold">显示效果</th>
                        <th class="bold">排序</th>
                        <th class="bold">状态</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['code'];?></td>
                        <td><?php echo $value['code_num'];?></td>
                        <td><?php echo $value['title'];?></td>
                        <td><?php echo $value['val'];?></td>
                        <td><?php echo $value['symbol_left'];?><?php echo rand('10','99');?>.<?php echo rand('10','99');?><?php echo $value['symbol_right'];?></td>
                        <td><?php echo $value['taxis'];?></td>
                        <td><?php if($value['status']){ ?>启用<?php } else { ?>禁用<?php } ?></td>
                        <td class="text-center">
                            <?php if($popedom['modify']){ ?>
                            <a href="<?php echo dsy_url(array('ctrl'=>'currency','func'=>'set','id'=>$value['id']));?>" class="btn btn-xs blue">编辑</a>
                            <?php } ?>
                            <?php if($popedom['delete']){ ?><input type="button" value="删除" onclick="currency_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')" class="btn btn-xs btn-danger" /><?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('currency.js');?>"></script>
<?php $this->output("foot","file"); ?>