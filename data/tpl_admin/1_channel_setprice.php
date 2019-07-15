<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'price'));?>">运费管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运费设置</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('channel','price_save');?>">
    <input type="hidden" name="group_id" value="<?php echo $group['id'];?>">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i><?php echo $group['title'];?></div>
                    <div class="tools">
                        <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-advance table-hover" id="prolist">
                        <thead>
                        <tr>
                            <th class="bold text-center">线路名称</th>
                            <th class="bold text-center">抹零设置</th>
                            <th class="bold text-center">计重模式</th>
                            <th class="bold text-center">起步重量</th>
                            <th class="bold text-center">首磅费用</th>
                            <th class="bold text-center">续磅费用</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td>
                                <?php echo $value['title'];?>
                                <input type="hidden" name="cid[]" value="<?php echo $value['id'];?>" />
                                <input type="hidden" name="pid[]" value="<?php echo $rs[$key]['id'];?>" />
                                <input type="hidden" name="taxis[]" value="<?php echo $value['taxis'];?>" />
                                <input type="hidden" name="status[]" value="<?php echo $value['status'];?>" />
                            </td>
                            <td><input type="text" name="num[]" value="<?php echo $rs[$key]['num'];?>" class="form-control" placeholder="0.01"/></td>
                            <td>
                                <select class="form-control" name="rule[]">
                                    <option value="intval"<?php if($rs[$key]['rule']=="intval"){ ?> selected<?php } ?>>实际计重</option>
                                    <option value="ceil"<?php if($rs[$key]['rule']=="ceil"){ ?> selected<?php } ?>>进位取整</option>
                                    <option value="round"<?php if($rs[$key]['rule']=="round"){ ?> selected<?php } ?>>0.5进制</option>
                                </select>
                            </td>
                            <td><input type="text" name="start_heavy[]" value="<?php echo $rs[$key]['start_heavy'];?>" class="form-control" onkeyup="value=value.replace(/[^\d.]/g,'')" required=""/></td>
                            <td><input type="text" name="first_price[]" class="form-control" value="<?php echo $rs[$key]['first_price'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" required=""/></td>
                            <td class="center"><input type="text" name="second_price[]" class="form-control" value="<?php echo $rs[$key]['second_price'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" required=""/></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit"> 提 交 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $this->output("foot","file"); ?>