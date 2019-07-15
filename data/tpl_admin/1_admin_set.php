<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('admin');?>" title="返回管理员列表">管理员列表</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>管理员</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('admin','save');?>" onsubmit="return check_save();">
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right">管理员账号：</td>
                        <td><input id="account" name="account" type="text" value="<?php echo $rs['account'];?>" class="form-control"/>
                            <span class="help-block">建议使用英文单词，中文或数字</span></td>
                    </tr>
                    <tr>
                        <td class="text-right">管理员密码：</td>
                        <td><input id="pass" name="pass" type="text" class="form-control"/>
                            <span class="help-block">密码长不能少于4位数，建议使用数字，字母及下划线等</span></td>
                    </tr>
                    <tr>
                        <td class="text-right">管理员邮箱：</td>
                        <td><input id="email" name="email" type="text" class="form-control" value="<?php echo $rs['email'];?>"/>
                            <span class="help-block">此邮箱可用于接收网站通知信息，请不要和SMTP配置的邮箱一致</span></td>
                    </tr>
                    <?php if($popedom['status']){ ?>
                    <tr>
                        <td class="text-right">状态：</td>
                        <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                    </tr>
                    <?php } else { ?>
                    <input type="hidden" name="status" value="<?php echo $rs['status'] ? 1 : 0;?>" />
                    <tr>
                        <td class="text-right">状态：</td>
                        <input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                    </tr>
                    <?php } ?>
                    <?php if($session['admin_rs']['if_system']){ ?>
                    <tr>
                        <td class="text-right">管理员级别：</td>
                        <td><div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" name="if_system" id="if_system_1" value="1"<?php if($rs['if_system']){ ?> checked<?php } ?> onclick="admin_system(1)" class="md-radiobtn">
                                <label for="if_system_1">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> 系统管理员 </label>
                            </div>
                            <div class="md-radio">
                                <input type="radio" name="if_system" id="if_system_0" value="0"<?php if(!$rs['if_system']){ ?> checked<?php } ?> onclick="admin_system(0)" class="md-radiobtn">
                                <label for="if_system_0">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> 普通管理员 </label>
                            </div>

                        </div>
                        </td>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td class="text-right">管理员级别：</td>
                        <td><div class="md-radio-inline">
                            <div class="md-radio">
                                <input type="radio" name="if_system" id="if_system_0" value="0"<?php if(!$rs['if_system']){ ?> checked<?php } ?> onclick="admin_system(0)" class="md-radiobtn">
                                <label for="if_system_0">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> 普通管理员 </label>
                            </div>
                        </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($session['admin_rs']['if_system']){ ?>
                    <tr>
                        <td class="text-right">仓库管理：</td>
                        <td>
                            <div class="md-checkbox-inline">
                                <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                <div class="md-checkbox">
                                    <input id="stock_id<?php echo $value['id'];?>" type="checkbox" value="<?php echo $value['id'];?>" name="stock_id[]"<?php if(in_array($value['id'],$c_id)){ ?> checked<?php } ?>>
                                    <label for="stock_id<?php echo $value['id'];?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo $value['title'];?> </label>
                                </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="sysmenu_html" >
    <?php $syslist_id["num"] = 0;$syslist=is_array($syslist) ? $syslist : array();$syslist_id["total"] = count($syslist);$syslist_id["index"] = -1;foreach($syslist AS $key=>$value){ $syslist_id["num"]++;$syslist_id["index"]++; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey" id="mlist_<?php echo $value['id'];?>">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i><?php echo P_Lang($value['title']);?></div>
                    <div class="tools">
                        <input class="btn btn-xs btn-danger" type="button" value="全选" onclick="$.input.checkbox_all('mlist_<?php echo $value['id'];?>')" />
                        <input class="btn btn-xs btn-success" type="button" value="全不选" onclick="$.input.checkbox_none('mlist_<?php echo $value['id'];?>')" />
                        <input class="btn btn-xs btn-warning" type="button" value="反选" onclick="$.input.checkbox_anti('mlist_<?php echo $value['id'];?>')" />
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <?php $value_sublist_id["num"] = 0;$value['sublist']=is_array($value['sublist']) ? $value['sublist'] : array();$value_sublist_id["total"] = count($value['sublist']);$value_sublist_id["index"] = -1;foreach($value['sublist'] AS $k=>$v){ $value_sublist_id["num"]++;$value_sublist_id["index"]++; ?>
                        <?php if($v['appfile'] != 'list' && $v['appfile'] != 'admin'){ ?>
                        <tr id="mlist_<?php echo $v['id'];?>">
                            <td class="col-md-2"><?php echo P_Lang($v['title']);?></td>
                            <td class="col-md-2"><input class="btn btn-xs btn-danger" type="button" value="全选" onclick="$.input.checkbox_all('mlist_<?php echo $v['id'];?>')" />
                                <input class="btn btn-xs btn-success" type="button" value="全不选" onclick="$.input.checkbox_none('mlist_<?php echo $v['id'];?>')" />
                                <input class="btn btn-xs btn-warning" type="button" value="反选" onclick="$.input.checkbox_anti('mlist_<?php echo $v['id'];?>')" /></td>
                            <td class="col-md-8">
                                <?php if($glist[$v['id']]){ ?>
                                <div class="md-checkbox-inline">
                                    <?php $glist__v_id__id["num"] = 0;$glist[$v['id']]=is_array($glist[$v['id']]) ? $glist[$v['id']] : array();$glist__v_id__id["total"] = count($glist[$v['id']]);$glist__v_id__id["index"] = -1;foreach($glist[$v['id']] AS $kk=>$vv){ $glist__v_id__id["num"]++;$glist__v_id__id["index"]++; ?>
                                    <div class="md-checkbox">
                                        <input id="pop<?php echo $vv['id'];?>" type="checkbox" name="popedom[]" value="<?php echo $vv['id'];?>"<?php if($plist && in_array($vv['id'],$plist)){ ?> checked<?php } ?>>
                                        <label for="pop<?php echo $vv['id'];?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> <?php echo P_Lang($vv['title']);?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
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
    <?php } ?>
    <?php $sitelist_id["num"] = 0;$sitelist=is_array($sitelist) ? $sitelist : array();$sitelist_id["total"] = count($sitelist);$sitelist_id["index"] = -1;foreach($sitelist AS $key=>$value){ $sitelist_id["num"]++;$sitelist_id["index"]++; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey" id="site_<?php echo $value['id'];?>">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i><?php echo $value['title'];?></div>
                    <div class="tools">
                        <input class="btn btn-xs btn-danger" type="button" value="全选" onclick="$.input.checkbox_all('site_<?php echo $value['id'];?>')" />
                        <input class="btn btn-xs btn-success" type="button" value="全不选" onclick="$.input.checkbox_none('site_<?php echo $value['id'];?>')" />
                        <input class="btn btn-xs btn-warning" type="button" value="反选" onclick="$.input.checkbox_anti('site_<?php echo $value['id'];?>')" />
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <?php $value_sonlist_id["num"] = 0;$value['sonlist']=is_array($value['sonlist']) ? $value['sonlist'] : array();$value_sonlist_id["total"] = count($value['sonlist']);$value_sonlist_id["index"] = -1;foreach($value['sonlist'] AS $k=>$v){ $value_sonlist_id["num"]++;$value_sonlist_id["index"]++; ?>
                        <tr id="site_p_<?php echo $v['id'];?>">
                            <td class="col-md-2"><?php echo $v['space'];?><?php echo $v['title'];?></td>
                            <td class="col-md-2"><input class="btn btn-xs btn-danger" type="button" value="全选" onclick="$.input.checkbox_all('site_p_<?php echo $v['id'];?>')" />
                                <input class="btn btn-xs btn-success" type="button" value="全不选" onclick="$.input.checkbox_none('site_p_<?php echo $v['id'];?>')" />
                                <input class="btn btn-xs btn-warning" type="button" value="反选" onclick="$.input.checkbox_anti('site_p_<?php echo $v['id'];?>')" /></td>
                            <td class="col-md-8">
                                <div class="md-checkbox-inline">
                                    <?php $v__popedom_id["num"] = 0;$v['_popedom']=is_array($v['_popedom']) ? $v['_popedom'] : array();$v__popedom_id["total"] = count($v['_popedom']);$v__popedom_id["index"] = -1;foreach($v['_popedom'] AS $kk=>$vv){ $v__popedom_id["num"]++;$v__popedom_id["index"]++; ?>
                                    <div class="md-checkbox">
                                        <input id="pop1<?php echo $vv['id'];?>" class="md-check" type="checkbox" name="popedom[]" value="<?php echo $vv['id'];?>"<?php if($plist && in_array($vv['id'],$plist)){ ?> checked<?php } ?>>
                                        <label for="pop1<?php echo $vv['id'];?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> <?php echo P_Lang($vv['title']);?> </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<div class="text-center">
    <button class="btn blue" type="submit">
        <i class="fa fa-edit"></i>
        提 交
    </button>
</div>
</form>
<script type="text/javascript">
    function admin_system(val)
    {
        if(val && val == 1)
        {
            $("#sysmenu_html").hide();
            //$("#store").hide();
        }
        else
        {
            $("#sysmenu_html").show();
            //$("#store").show();
        }
    }
    function to_use_all()
    {
        $("input[data=category_use]").attr("checked",true);
    }
    function check_save()
    {
        var account = $("#account").val();
        if(!account)
        {
            $.dialog.alert("<?php echo P_Lang("管理员账号不能为空");?>");
            return false;
        }
        var id = $("#id").val();
        //检测账号是否存在
        var url = get_url("admin","check_account") + "&account="+$.str.encode(account);
        if(id && id != "undefined")
        {
            url += '&id='+id;
        }
        var rs = json_ajax(url);
        if(rs.status != "ok")
        {
            $.dialog.alert(rs.content);
            return false;
        }
        if(!id || id == "0" || id == "undefined")
        {
            var pass = $("#pass").val();
            if(!pass || pass.length < 4)
            {
                $.dialog.alert("<?php echo P_Lang("密码不能为空或密码长度太短");?>");
                return false;
            }
        }
        //
        //判断是否是系统管理员
        var if_system = $("input[name=if_system]:checked").val();
        if(if_system != "1")
        {
            //检测是否有
            var popedom_id = $.input.checkbox_join("sysmenu_html");
            if(!popedom_id)
            {
                $.dialog.alert("<?php echo P_Lang("账号：");?><span class='red'>"+account+"</span> <?php echo P_Lang("不是系统管理员，请配置好权限！");?>");
                return false;
            }
            //检查系统中是否还存在系统管理员（如果不存在，必须至少有一位系统管理员）
            var url = get_url("admin","check_if_system");
            if(id)
            {
                url += "&id="+id;
            }
            var rs = json_ajax(url);
            if(rs.status != "ok")
            {
                $.dialog.alert(rs.content);
                return false;
            }
        }
    }
    /*$(document).ready(function(){
        top.$.desktop.title('<?php if($id){ ?><?php echo P_Lang("编辑管理员");?><?php } else { ?><?php echo P_Lang("添加管理员");?><?php } ?>');
    });*/
    $(document).ready(function(){
        admin_system("<?php echo $rs['if_system'];?>");
    });
</script>
<?php $this->output("foot","file"); ?>