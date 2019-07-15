<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('log');?>" title="返回银行卡转账列表">日志管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>日志列表</span>
        </li>
    </ul>
    <div class="text-right" style="margin-top:6px;margin-bottom:6px;margin-right:6px;">
        <?php $date30=date('Y-m-d',strtotime("-30 day"));$date7=date('Y-m-d',strtotime("-7 day"));;?>
        <a href="javascript:void(0);" onclick="$.admin_log.search('start_time','<?php echo $date30;?>')" class="btn btn-circle green-haze btn-outline btn-sm"><?php echo P_Lang("最近30天内日志");?></a>
        <a href="javascript:void(0);" onclick="$.admin_log.search('start_time','<?php echo $date7;?>')" class="btn btn-circle green-haze btn-outline btn-sm"><?php echo P_Lang("最近7天内日志");?></a>
        <?php if($session['admin_rs']['if_system']){ ?>
        <a href="javascript:void(0);" onclick="$.admin_log.delete30()" class="btn btn-circle red btn-outline btn-sm"><?php echo P_Lang("删除30天之前的日志");?></a>
        <?php } ?>
    </div>
</div>
<h1 class="page-title"> </h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="navbar-collapse bg-grey-steel" style="border-radius: 4px;">
                    <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'log'));?>" style="padding-left:0px;">
                        <div class="form-group">
                            <select name="position" class="form-control">
                                <option value="admin"<?php if($position == 'admin'){ ?> selected<?php } ?>>后台</option>
                                <option value="www"<?php if($position == 'www'){ ?> selected<?php } ?>>前台</option>
                                <option value="api"<?php if($position == 'api'){ ?> selected<?php } ?>>接口</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control date-item" type="text" name="start_time" value="<?php echo $start_time;?>" placeholder="开始时间"/>
                        </div>
                        <div class="form-group">
                            <input class="form-control date-item" type="text" name="stop_time" value="<?php echo $stop_time;?>" placeholder="结束时间" />
                        </div>
                        <div class="form-group">
                            <input type="text" id="keywords" name="keywords" value="<?php echo $keywords;?>" placeholder="<?php echo P_Lang("请输入要搜索的关键字");?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="adminer" value="<?php echo $adminer;?>" placeholder="<?php echo P_Lang("管理员账号");?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="user" value="<?php echo $user;?>" placeholder="<?php echo P_Lang("会员账号");?>" class="form-control">
                        </div>
                        <button class="btn btn-info" type="submit" style="margin-top:-5px;"><i class="fa fa-search"></i> 查 询 </button>
                    </form>
                </div>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold">&nbsp;</th>
                            <th class="bold text-center">备注/网址</th>
                            <th class="bold text-center">IP</th>
                            <th class="bold text-center">操作人</th>
                            <th class="bold text-center">文件</th>
                            <th class="bold text-center">时间</th>
                            <?php if($session['admin_rs']['if_system']){ ?>
                            <th class="bold text-center">操作</th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                        <tr>
                            <td class="text-center">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-center"><?php if($value['note']){ ?><?php echo $value['note'];?><?php } ?></td>
                            <td class="text-center"><?php echo $value['ip'];?></td>
                            <td class="text-center">
                                <?php if($value['account']){ ?><span class="font-red"><?php echo $value['account'];?></span><?php } ?>
                                <?php if($value['account'] && $value['user']){ ?> / <?php } ?>
                                <?php if($value['user']){ ?><span class="font-blue"><?php echo $value['user'];?></span><?php } ?>
                                <?php if(!$value['admin_id'] && !$value['user_id']){ ?><?php echo P_Lang("访客");?><?php } ?>
                            </td>
                            <td class="text-center"><?php echo $value['ctrl'];?>_control.php &raquo; <?php echo $value['func'];?>_f</td>
                            <td class="text-center"><?php echo date('Y-m-d H:i:s',$value['dateline']);?></td>
                            <?php if($session['admin_rs']['if_system']){ ?>
                            <td class="text-center">
                                <a href="javascript:;" onclick="$.admin_log.del('<?php echo $value['id'];?>')" class="btn btn-sm btn-danger">删除</a>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                            <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                            <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                            <input type="button" value="<?php echo P_Lang("删除选中日志");?>" onclick="$.admin_log.delete_selected()" class="btn btn-xs btn-danger" />
                        </div>
                    </div>
                    <div class="col-md-6 text-right"><?php $this->output("pagelist","file"); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo include_js('admin.log.js');?>"></script>
<script>
    lay('.date-item').each(function(){
        laydate.render({
            elem: this
            ,type: 'date'
            ,trigger: 'click'
        });
    });
</script>
<?php $this->output("foot","file"); ?>