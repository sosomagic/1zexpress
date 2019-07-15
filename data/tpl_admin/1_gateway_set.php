<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<?php $this->output("nav","file"); ?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN SIDEBAR -->
<?php $this->output("left","file"); ?>
<!-- END SIDEBAR -->
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'gateway'));?>" title="返回短信/邮箱设置">短信/邮箱</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php echo $group['title'];?>
                &raquo; <?php if($id){ ?>编辑<?php } else { ?>添加新<?php } ?>网关信息，当前使用的引挈是：<span class="red"><?php echo $extlist['title'];?></span></span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo dsy_url(array('ctrl'=>'gateway','func'=>'save'));?>" onsubmit="return check_gateway_set()">
    <?php if($id){ ?>
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <?php } else { ?>
    <input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
    <input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
    <?php } ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i>网关设置</div>
                    <div class="tools">
                        <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">名称：</td>
                            <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" style="width: 100%"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">启用：</td>
                            <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                        </tr>
                        <tr>
                            <td class="text-right">默认：</td>
                            <td><input id="is_default" name="is_default" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['is_default']){ ?> checked<?php } ?>>
                                <span class="help-block">设置此网关是否默认使用，一种类型的网关仅支持一个默认</span></td>
                        </tr>
                        <?php $extlist_code_id["num"] = 0;$extlist['code']=is_array($extlist['code']) ? $extlist['code'] : array();$extlist_code_id["total"] = count($extlist['code']);$extlist_code_id["index"] = -1;foreach($extlist['code'] AS $key=>$value){ $extlist_code_id["num"]++;$extlist_code_id["index"]++; ?>
                        <?php $valinfo = $rs['ext'][$key] ? $rs['ext'][$key] : $value['default'];?>
                        <tr>
                            <td class="text-right"><?php echo $value['title'];?>：</td>
                            <td>
                                <?php if($value['type'] == 'radio'){ ?>
                                <div class="md-radio-inline">
                                    <?php $value_option_id["num"] = 0;$value['option']=is_array($value['option']) ? $value['option'] : array();$value_option_id["total"] = count($value['option']);$value_option_id["index"] = -1;foreach($value['option'] AS $k=>$v){ $value_option_id["num"]++;$value_option_id["index"]++; ?>
                                    <div class="md-radio">
                                        <input type="radio" id="<?php echo $code;?>_<?php echo $key;?>_<?php echo $k;?>" name="<?php echo $code;?>_<?php echo $key;?>" value="<?php echo $k;?>"<?php if($valinfo == $k){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="<?php echo $code;?>_<?php echo $key;?>_<?php echo $k;?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> <?php echo $v;?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php }elseif($value['type'] == 'select'){ ?>
                                <select name="<?php echo $code;?>_<?php echo $key;?>" id="<?php echo $code;?>_<?php echo $key;?>">
                                    <option value="">请选择…</option>
                                    <?php $value_option_id["num"] = 0;$value['option']=is_array($value['option']) ? $value['option'] : array();$value_option_id["total"] = count($value['option']);$value_option_id["index"] = -1;foreach($value['option'] AS $k=>$v){ $value_option_id["num"]++;$value_option_id["index"]++; ?>
                                    <option value="<?php echo $k;?>"<?php if($valinfo == $k){ ?> selected<?php } ?>><?php echo $v;?></option>
                                    <?php } ?>
                                </select>
                                <?php }elseif($value['type'] == 'checkbox'){ ?>
                                <?php $valinfo = $valinfo ? explode(',',$valinfo) : array();?>
                                <div class="md-checkbox-inline">
                                    <?php $value_option_id["num"] = 0;$value['option']=is_array($value['option']) ? $value['option'] : array();$value_option_id["total"] = count($value['option']);$value_option_id["index"] = -1;foreach($value['option'] AS $k=>$v){ $value_option_id["num"]++;$value_option_id["index"]++; ?>
                                    <div class="md-checkbox">
                                        <input id="<?php echo $code;?>_<?php echo $k;?>" type="checkbox" name="<?php echo $code;?>_<?php echo $k;?>" value="<?php echo $k;?>"<?php if(in_array($k,$valinfo)){ ?> checked<?php } ?>>
                                        <label for="<?php echo $code;?>_<?php echo $k;?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> <?php echo $v;?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php }elseif($value['type'] == 'password'){ ?>
                                <input type="password" id="<?php echo $code;?>_<?php echo $key;?>" name="<?php echo $code;?>_<?php echo $key;?>"  value="<?php echo $valinfo;?>" style="width: 100%" />
                                <?php } else { ?>
                                <?php $input_name = $code.'_'.$key;?>
                                <?php if($value['typebtn'] == 'file'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=file&width=500');?>
                                <?php }elseif($value['typebtn'] == 'image'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=image&width=500');?>
                                <?php }elseif($value['typebtn'] == 'video'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=video&width=500');?>
                                <?php } else { ?>
                                <input type="text" id="<?php echo $code;?>_<?php echo $key;?>" name="<?php echo $code;?>_<?php echo $key;?>" value="<?php echo $valinfo;?>" style="width: 100%" />
                                <?php } ?>
                                <?php } ?>
                                <span class="help-block"><?php echo $value['note'];?><?php if($value['required'] == 'true'){ ?> <span class="font-red">(此项必填)</span><?php } ?></span></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right">排序：</td>
                            <td><input id="taxis" name="taxis" value="<?php echo $rs['taxis'];?>" style="width:100%;"/>
                                <span class="help-block">值范围在0-255，越小越往前靠，默认为255</span></td>
                        </tr>
                        <tr>
                            <td class="text-right">摘要：</td>
                            <td><?php echo form_edit('note',$rs['note'],'editor','width=700&height=300&etype=simple&btn_image=1');?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button class="btn blue" type="submit">
                <i class="fa fa-edit"></i>
                提 交
            </button>
        </div>
    </div>
</form>
</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<script type="text/javascript">
    function check_gateway_set()
    {
        var title = $("#title").val();
        if(!title){
            $.dialog.alert('名称不能为空');
            return false;
        }
        return true;
    }
</script>
<?php $this->output("foot","file"); ?>