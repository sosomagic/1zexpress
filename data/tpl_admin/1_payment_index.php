<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'payment'));?>">充值方案</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>充值方案列表</span>
        </li>
    </ul>
</div>
<div id="payment_select_info" style="display: none">
    <select id="code">
        <?php $codelist_id["num"] = 0;$codelist=is_array($codelist) ? $codelist : array();$codelist_id["total"] = count($codelist);$codelist_id["index"] = -1;foreach($codelist AS $key=>$value){ $codelist_id["num"]++;$codelist_id["index"]++; ?>
        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
        <?php } ?>
    </select>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">名称</th>
                        <th class="bold">排序</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['title'];?><?php if(!$value['status']){ ?><span class="font-red">（停用）</span><?php } ?><?php if($value['is_wap']){ ?><span class="font-blue">（手机端）</span><?php } ?></td>
                        <td><?php echo $value['taxis'];?></td>
                        <td>
                            <?php if($popedom['groupedit']){ ?>
                            <input type="button" value="编辑" onclick="group_edit('<?php echo $value['id'];?>')" class="btn sbold uppercase btn-outline blue" />
                            <?php } ?>
                            <?php if($popedom['groupdelete']){ ?>
                            <input type="button" value="删除" onclick="group_delete('<?php echo $value['id'];?>','<?php echo $value['title'];?>')" class="btn sbold uppercase btn-outline red-thunderbird" />
                            <?php } ?>
                            <?php if($popedom['add']){ ?>
                            <input type="button" value="添加支付方案" onclick="payment_add('<?php echo $value['id'];?>')" class="btn sbold uppercase btn-outline green-jungle" />
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $value_paylist_id["num"] = 0;$value['paylist']=is_array($value['paylist']) ? $value['paylist'] : array();$value_paylist_id["total"] = count($value['paylist']);$value_paylist_id["index"] = -1;foreach($value['paylist'] AS $k=>$v){ $value_paylist_id["num"]++;$value_paylist_id["index"]++; ?>
                    <tr>
                        <td><div style="padding-left:24px;"><?php echo $v['title'];?><?php if(!$v['status']){ ?><span class="font-red">（停用）</span><?php } ?><?php if($v['wap']){ ?><span class="font-blue">（手机端）</span><?php } ?></div></td>
                        <td><?php echo $v['taxis'];?></td>
                        <td>
                            <?php if($popedom['edit']){ ?>
                            <input type="button" value="编辑" onclick="payment_edit('<?php echo $v['id'];?>')" class="btn btn-xs btn-info" />
                            <?php } ?>
                            <?php if($popedom['delete']){ ?>
                            <input type="button" value="删除" onclick="payment_delete('<?php echo $v['id'];?>','<?php echo $v['title'];?>')" class="btn btn-xs btn-danger" />
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo include_js('payment.js');?>"></script>
<?php $this->output("foot","file"); ?>