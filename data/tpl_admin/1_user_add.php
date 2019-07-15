<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('user');?>" title="返回管会员列表">会员管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>会员</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('user','setok');?>" onsubmit="return check_add()">
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <input type="hidden" name="backurl" value="<?php echo $backurl;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">会员名：</td>
                            <td><input class="form-control" id="user" name="user" type="text" value="<?php echo $rs['user'];?>"<?php if($id){ ?>disabled<?php } ?>/>
                        </tr>
                        <tr>
                            <td class="text-right">会员组：</td>
                            <td>
                                <select class="form-control" id="group_id" name="group_id">
                                    <!--<option value="0">请选择会员组</option>-->
                                    <?php $grouplist_id["num"] = 0;$grouplist=is_array($grouplist) ? $grouplist : array();$grouplist_id["total"] = count($grouplist);$grouplist_id["index"] = -1;foreach($grouplist AS $key=>$value){ $grouplist_id["num"]++;$grouplist_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $rs['group_id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">会员密码：</td>
                            <td><input class="form-control" id="pass" name="pass" type="text" />
                        </tr>
                        <tr>
                            <td class="text-right">邮箱：</td>
                            <td><input class="form-control" id="email" name="email" type="text" value="<?php echo $rs['email'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">手机：</td>
                            <td><input class="form-control" id="mobile" name="mobile" type="text" value="<?php echo $rs['mobile'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">First Name：</td>
                            <td><input class="form-control" type="text" name="first_name" placeholder="您的英文名或中文名全拼" value="<?php echo $rs['first_name'];?>" onkeyup="value=value.replace(/[^\w\.\s*/]/ig,'')"/>
                            </td>
                        </tr>
                        <?php if($id){ ?>
                        <tr>
                            <td class="text-right">会员编号：</td>
                            <td><input class="form-control"  name="lase_name" type="text" value="<?php echo $rs['ucode'];?>" readonly/>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-right">地址：</td>
                            <td><input class="form-control" type="text" name="address" value="<?php echo $rs['address'];?>" placeholder="国外退货地址" onkeyup="value=value.replace(/[^\w\.\s*/]/ig,'')"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">邮编：</td>
                            <td><input class="form-control" type="text" name="zipcode" value="<?php echo $rs['zipcode'];?>" placeholder="国外邮编"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">备用电话：</td>
                            <td><input class="form-control" type="text" name="tel" value="<?php echo $rs['tel'];?>" placeholder="国外电话"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">微信：</td>
                            <td><input class="form-control" type="text" name="weixin" value="<?php echo $rs['weixin'];?>"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">QQ：</td>
                            <td><input class="form-control" type="text" name="qq" value="<?php echo $rs['qq'];?>"/></td>
                        </tr>

                        <tr>
                            <td class="text-right">状态：</td>
                            <td><div class="md-radio-inline">
                                <div class="md-radio">
                                    <input type="radio" name="status" id="status_0" value="0"<?php if(!$rs['status'] && $rs['status']<>''){ ?> checked<?php } ?> class="md-radiobtn">
                                    <label for="status_0">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 未审核 </label>
                                </div>
                                <div class="md-radio">
                                    <input type="radio" name="status" id="status_1" value="1"<?php if($rs['status'] == 1 || $rs['status']==''){ ?> checked<?php } ?> class="md-radiobtn">
                                    <label for="status_1">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 正常 </label>
                                </div>
                            </div>
                            </td>
                        </tr>
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
<script type="text/javascript" src="<?php echo include_js('user.js');?>"></script>
<?php $this->output("foot","file"); ?>