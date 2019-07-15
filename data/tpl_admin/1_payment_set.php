<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'payment'));?>" title="返回充值方案列表">充值管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>充值方式，当前使用的支付引挈是：<span class="font-red"><?php echo $extlist['title'];?></span></span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'save'));?>" onsubmit="return check_payment_set()">
    <?php if($id){ ?>
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <?php } ?>
    <input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">充值名称：</td>
                            <td><input id="title" name="title" value="<?php echo $rs['title'];?>" type="text" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">所属组：</td>
                            <td>
                                <select name="gid" id="gid" class="form-control">
                                    <?php $grouplist_id["num"] = 0;$grouplist=is_array($grouplist) ? $grouplist : array();$grouplist_id["total"] = count($grouplist);$grouplist_id["index"] = -1;foreach($grouplist AS $key=>$value){ $grouplist_id["num"]++;$grouplist_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"<?php if($gid == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?><?php if($value['is_wap']){ ?> - 手机端<?php } ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block">设置支付所属组，此项不能为空</span>
                            </td>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2">状态：</td>
                            <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>>
                                <span class="help-block">只有启用此项，前台支付才能生效</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2">手机端使用：</td>
                            <td><input id="wap" name="wap" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['wap']){ ?> checked<?php } ?>>
                                <span class="help-block">启用后将允许在手机端使用该支付接口</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-right">货币类型：</td>
                            <td>
                                <select name="currency" class="form-control">
                                    <option value="CNY">默认</option>
                                    <?php $currency_list_id["num"] = 0;$currency_list=is_array($currency_list) ? $currency_list : array();$currency_list_id["total"] = count($currency_list);$currency_list_id["index"] = -1;foreach($currency_list AS $key=>$value){ $currency_list_id["num"]++;$currency_list_id["index"]++; ?>
                                    <option value="<?php echo $value['code'];?>"<?php if($rs['currency'] == $value['code']){ ?> selected<?php } ?>><?php echo $value['title'];?>（汇率：<?php echo $value['val'];?>）</option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-right">支付图片一：</td>
                            <td>
                                <?php echo form_edit('logo1',$rs['logo1'],'text','form_btn=image&width=500');?>
                                <span class="help-block">设置该支付接口的小图，建议使用正方形小图</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">支付图片二：</td>
                            <td><?php echo form_edit('logo2',$rs['logo2'],'text','form_btn=image&width=500');?>
                                <span class="help-block">设置该支付图片，建议使用长方形，如160x50之类的</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">支付图片三：</td>
                            <td><?php echo form_edit('logo3',$rs['logo3'],'text','form_btn=image&width=500');?>
                                <span class="help-block">这个图片一般是大图，适用于一些需要大图的场合</span>
                            </td>
                        </tr>
                        <?php $extlist_code_id["num"] = 0;$extlist['code']=is_array($extlist['code']) ? $extlist['code'] : array();$extlist_code_id["total"] = count($extlist['code']);$extlist_code_id["index"] = -1;foreach($extlist['code'] AS $key=>$value){ $extlist_code_id["num"]++;$extlist_code_id["index"]++; ?>
                        <?php $valinfo = $rs['param'][$key] ? $rs['param'][$key] : $value['default'];?>
                        <tr>
                            <td class="text-right"><?php echo $value['title'];?>：</td>
                            <td>
                                <?php if($value['type'] == 'radio'){ ?>
                                <div class="md-radio-inline col-md-12">
                                    <?php $value_option_id["num"] = 0;$value['option']=is_array($value['option']) ? $value['option'] : array();$value_option_id["total"] = count($value['option']);$value_option_id["index"] = -1;foreach($value['option'] AS $k=>$v){ $value_option_id["num"]++;$value_option_id["index"]++; ?>
                                    <div class="md-radio">
                                        <input type="radio" name="<?php echo $code;?>_<?php echo $key;?>" id="<?php echo $code;?>_<?php echo $k;?>" value="<?php echo $k;?>"<?php if($valinfo == $k){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="<?php echo $code;?>_<?php echo $k;?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> <?php echo $v;?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php }elseif($value['type'] == 'select'){ ?>
                                <select class="form-control" name="<?php echo $code;?>_<?php echo $key;?>" id="<?php echo $code;?>_<?php echo $key;?>">
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
                                        <input id="<?php echo $code;?>_<?php echo $k;?>" type="checkbox" value="<?php echo $k;?>" name="<?php echo $code;?>_<?php echo $k;?>"<?php if(in_array($k,$valinfo)){ ?> checked<?php } ?>>
                                        <label for="<?php echo $code;?>_<?php echo $k;?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> <?php echo $v;?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } else { ?>
                                <?php $input_name = $code.'_'.$key;?>
                                <?php if($value['typebtn'] == 'file'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=file&width=500');?>
                                <?php }elseif($value['typebtn'] == 'image'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=image&width=500');?>
                                <?php }elseif($value['typebtn'] == 'video'){ ?>
                                <?php echo form_edit($input_name,$valinfo,'text','form_btn=video&width=500');?>
                                <?php } else { ?>
                                <input type="text" id="<?php echo $code;?>_<?php echo $key;?>" name="<?php echo $code;?>_<?php echo $key;?>" class="form-control" value="<?php echo $valinfo;?>" />
                                <?php } ?>
                                <?php } ?>
                                <span class="help-block"><?php echo $value['note'];?></span>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right">排序：</td>
                            <td><input type="text" id="taxis" name="taxis" value="<?php echo $id ? $rs['taxis'] : 255;?>" class="form-control"/>
                                <span class="help-block">值范围在0-255，越小越往前靠，默认为255</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">摘要：</td>
                            <td><?php echo form_edit('note',$rs['note'],'editor','width=700&height=300&etype=simple&btn_image=1');?>
                                <span class="help-block">说明性信息，如此支付方案的限额，注意事项</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit">
                            <i class="fa fa-edit"></i>
                            提 交
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
<script type="text/javascript" src="<?php echo include_js('payment.js');?>"></script>
<?php $this->output("foot","file"); ?>