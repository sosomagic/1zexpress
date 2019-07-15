<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<script type="text/javascript" src="<?php echo include_js('all.js');?>"></script>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active bold">
                            <a aria-expanded="false" href="#tab1" data-toggle="tab">基本设置</a>
                        </li>
                        <li class="bold">
                            <a aria-expanded="true" href="#tab2" data-toggle="tab">扩展信息</a>
                        </li>
                        <li class="bold">
                            <a aria-expanded="false" href="#tab3" data-toggle="tab">SEO优化</a>
                        </li>
                    </ul>
                    <form method="post" id="cate_post" action="<?php echo admin_url('all','save');?>" onsubmit="return all_setting_check();">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">网站标题：</td>
                                        <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">网站地址：</td>
                                        <td><input id="domain" name="domain" type="text" value="<?php echo $rs['domain'];?>" class="form-control"/>
                                            <span class="help-block">网站域名，不用填写http://，也不能填写 / 结束符</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">安装目录：</td>
                                        <td><input id="dir" name="dir" type="text" value="<?php echo $rs['dir'];?>" class="form-control"/>
                                            <span class="help-block">根目录请用 /，其他目录请写目录名且要求以 / 结束，如：/dsyexpress/</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">网站LOGO：</td>
                                        <td><input id="logo" name="logo" type="text" style="width:80%;" value="<?php echo $rs['logo'];?>"/>&nbsp;<input type="button" value="<?php echo P_Lang("选择");?>" onclick="dsy_pic('logo')" class="btn  btn-xs btn-info" />&nbsp;<input type="button" value="<?php echo P_Lang("预览");?>" onclick="dsy_pic_view('logo')" class="btn  btn-xs green-meadow" />&nbsp;<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#logo').val('');" class="btn btn-xs btn-danger" />
                                            <span class="help-block">大小尺寸：201px × 34px</span></td>
                                    </tr>

                                    <tr>
                                        <td class="text-right">运单号生成规则：</td>
                                        <td><input id="biz_sn" name="biz_sn" value="<?php echo $rs['biz_sn'];?>" type="text" style="width: 80%"/>&nbsp;<input type="button" value="清空" onclick="$('#biz_sn').val('')" class="btn btn-xs btn-danger" />
                                            <div class="col-md-12" style="margin-top: 5px;">
                                                <input type="button" value="<?php echo P_Lang("前缀");?>" onclick="insert_input('prefix[P]')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("年");?>" onclick="insert_input('year')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("月");?>" onclick="insert_input('month')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("日");?>" onclick="insert_input('date')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("时");?>" onclick="insert_input('hour')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("分");?>" onclick="insert_input('minute')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("秒");?>" onclick="insert_input('second')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("随机");?>" onclick="insert_input('rand')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("时间戳");?>" onclick="insert_input('time')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("序号");?>" onclick="insert_input('number')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("订单ID");?>" onclick="insert_input('id')" class="btn green-meadow btn-xs" />
                                                <input type="button" value="<?php echo P_Lang("会员ID");?>" onclick="insert_input('user')" class="btn green-meadow btn-xs" /></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">默认货币：</td>
                                        <td><select id="currency_id" name="currency_id" class="form-control">
                                            <option value=""><?php echo P_Lang("不使用");?></option>
                                            <?php $currency_list_id["num"] = 0;$currency_list=is_array($currency_list) ? $currency_list : array();$currency_list_id["total"] = count($currency_list);$currency_list_id["index"] = -1;foreach($currency_list AS $key=>$value){ $currency_list_id["num"]++;$currency_list_id["index"]++; ?>
                                            <option value="<?php echo $value['id'];?>"<?php if($rs['currency_id'] == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?>(<?php echo P_Lang("标识：");?><?php echo $value['code'];?>, <?php echo P_Lang("汇率：");?><?php echo $value['val'];?>)</option>
                                            <?php } ?>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">默认支付方式：</td>
                                        <td><select class="form-control" name="biz_payment">
                                            <option value="0"><?php echo P_Lang("不指定");?></option>
                                            <?php $payment_id["num"] = 0;$payment=is_array($payment) ? $payment : array();$payment_id["total"] = count($payment);$payment_id["index"] = -1;foreach($payment AS $key=>$value){ $payment_id["num"]++;$payment_id["index"]++; ?>
                                            <option value="<?php echo $value['id'];?>"<?php if($rs['biz_payment'] == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                            <?php } ?>
                                        </select></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right col-md-2">网站状态：</td>
                                        <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">网站关闭说明：</td>
                                        <td><textarea id="content" name="content" maxlength="100" class="form-control" rows="2"><?php echo $rs['content'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">注册：</td>
                                        <td><input id="register_status" name="register_status" value="1" <?php if($rs['register_status']){ ?> checked<?php } ?> type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">注册关闭说明：</td>
                                        <td><textarea id="register_close" name="register_close" maxlength="100" class="form-control" rows="2"><?php echo $rs['register_close'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">登录：</td>
                                        <td><input id="login_status" name="login_status" value="1" <?php if($rs['login_status']){ ?> checked<?php } ?> type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">登录关闭说明：</td>
                                        <td><textarea iid="login_close" name="login_close" maxlength="100" class="form-control" rows="2"><?php echo $rs['login_close'];?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">会员中心LOGO：</td>
                                        <td><input id="adm_logo29" name="adm_logo29" type="text" style="width:80%;" value="<?php echo $rs['adm_logo29'];?>"/>&nbsp;<input type="button" value="<?php echo P_Lang("选择");?>" onclick="dsy_pic('adm_logo29')" class="btn  btn-xs blue" />&nbsp;<input type="button" value="<?php echo P_Lang("预览");?>" onclick="dsy_pic_view('adm_logo29')" class="btn  btn-xs green-meadow" />&nbsp;<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#adm_logo29').val('');" class="btn btn-xs btn-danger" />
                                            <span class="help-block">大小尺寸：86px × 14px</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">后台登陆界面LOGO：</td>
                                        <td><input id="adm_logo180" name="adm_logo180" type="text" style="width:80%;" value="<?php echo $rs['adm_logo180'];?>"/>&nbsp;<input type="button" value="<?php echo P_Lang("选择");?>" onclick="dsy_pic('adm_logo180')" class="btn  btn-xs blue" />&nbsp;<input type="button" value="<?php echo P_Lang("预览");?>" onclick="dsy_pic_view('adm_logo180')" class="btn  btn-xs green-meadow" />&nbsp;<input type="button" value="<?php echo P_Lang("清空");?>" onclick="$('#adm_logo180').val('');" class="btn btn-xs btn-danger" /></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">游客上传权限：</td>
                                        <td><input id="upload_guest" name="upload_guest" value="1" <?php if($rs['upload_guest']){ ?> checked<?php } ?> type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small"><span class="help-block">启用上传权限后，游客仅可以上传JPG，GIF，PNG，JPEG，ZIP，RAR这几种类型的附件</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">会员上传权限：</td>
                                        <td><input id="upload_user" name="upload_user" value="1" <?php if($rs['upload_user']){ ?> checked<?php } ?> type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small"><span class="help-block">支持类型有：JPG，GIF，PNG，JPEG，ZIP，RAR，PPT，PPTX，TXT，RTF，CSV，XLS，XLSX，DOC，DOCX，WPS</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">网址优化：</td>
                                        <td>
                                            <div class="md-radio">
                                                <input type="radio" id="url_type1" name="url_type" class="md-radiobtn" value="default"<?php if($rs['url_type'] == "default" || !$rs['url_type']){ ?> checked<?php } ?> >
                                                <label for="url_type1">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> 动态网址 </label>
                                            </div>
                                            <div class="md-radio">
                                                <input type="radio" id="url_type2" name="url_type" class="md-radiobtn" value="rewrite"<?php if($rs['url_type'] == "rewrite"){ ?> checked<?php } ?> >
                                                <label for="url_type2">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> 伪静态页 </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">SEO标题：</td>
                                        <td><input id="seo_title" name="seo_title" type="text" value="<?php echo $rs['seo_title'];?>" class="form-control"/>
                                            <span class="help-block">针对HTML里的Title属性进行优化，建议使用英文竖线分割开来，不超过80字</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">SEO关键字：</td>
                                        <td><input id="seo_keywords" name="seo_keywords" type="text" class="form-control" value="<?php echo $rs['seo_keywords'];?>"/>
                                            <span class="help-block">多个词用英文逗号隔开</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">SEO摘要：</td>
                                        <td><textarea id="seo_desc" name="seo_desc" maxlength="100" class="form-control" rows="2"><?php echo $rs['seo_desc'];?></textarea>
                                            <span class="help-block">建议不超过100字</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn blue" type="submit">
                                        <i class="fa fa-edit"></i>
                                        提 交
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn default" type="reset">
                                        <i class="fa fa-times"></i>
                                        重 置
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function insert_input(val)
    {
        var info = $("#biz_sn").val();
        if(info){
            val = info + '-' +val;
        }
        $("#biz_sn").val(val);
    }
</script>
<?php $this->output("foot","file"); ?>