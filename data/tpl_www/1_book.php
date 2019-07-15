<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","提交问题-有问必答"); ?><?php $this->output("head_member","file"); ?>
<?php $this->output("nav","file"); ?>
<div class="page-container">
    <?php $this->output("block_usercp","file"); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i>提交问题</div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <form method="post" id="do_submit">
                                            <?php if($id){ ?><input type="hidden" name="id" value="<?php echo $id;?>" /><?php } ?>
                                            <table class="table table-striped table-bordered table-hover">
                                                <tbody>
                                                <tr>
                                                    <td class="text-right">优先级：</td>
                                                    <td><div class="md-radio-inline col-md-6">
                                                        <?php $type_id["num"] = 0;$type=is_array($type) ? $type : array();$type_id["total"] = count($type);$type_id["index"] = -1;foreach($type AS $key=>$value){ $type_id["num"]++;$type_id["index"]++; ?>
                                                        <div class="md-radio">
                                                            <input type="radio" name="level" id="level_<?php echo $key;?>" value="<?php echo $value;?>"<?php if(!$rs['level'] ? $value=="一般" : $rs['level']==$value){ ?> checked<?php } ?> class="md-radiobtn">
                                                            <label for="level_<?php echo $key;?>">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> <?php echo $value;?> </label>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">运单号：</td>
                                                    <td><input type="text" id="sn" name="sn" value="<?php echo $rs['sn'];?>" style="width:100%;" />
                                                        <span class="help-block">请正确填写运单号，错误运单号官方不予受理</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">问题分类：</td>
                                                    <td>
                                                        <select id="type" class="form-control" name="type">
                                                            <option value="">请选择问题类型</option>
                                                            <?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>
                                                            <option value="<?php echo $value['id'];?>"<?php if($rs['cate_id']==$value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-right">问题描述：</td>
                                                    <td>
                                                        <textarea id="detail" name="detail" maxlength="100" style="width:100%;" rows="2"><?php echo $rs['content'];?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">问题备注：</td>
                                                    <td>
                                                        <textarea id="note" name="note" maxlength="100" style="width:100%;" rows="2"><?php echo $rs['note'];?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">上传附件：</td>
                                                    <td><?php echo $extlist['html'];?></td>
                                                </tr>

                                                </tbody>
                                            </table>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var is_id = '<?php echo $id;?>';
    $(document).ready(function(){
        $("#do_submit").submit(function(){
            var type = $("#type").val();
            if(!type){
                $.dialog.alert("请选择问题分类");
                return false;
            }
            var sn = $("#sn").val();
            if(!sn){
                $.dialog.alert("运单号不能为空");
                return false;
            }
            var detail = $("#detail").val();
            if(!detail){
                $.dialog.alert("问题描述不能为空");
                return false;
            }
           /* var vpic = $("#vpic").val();
            if(!vpic){
                $.dialog.alert("请上传理赔凭证！");
                return false;
            }*/
            $(this).ajaxSubmit({
                type:'post',
                url: get_url('book','save'),
                dataType:'json',
                success: function(rs){
                    if(rs.status != 'ok'){
                        if(!rs.content){
                            rs.content = '提交失败';
                        }
                        $.dialog.alert(rs.content);
                        return false;
                    }
                    //订单操作成功的提示
                    if(is_id && is_id != '0'){
                        $.dialog.alert('问题编辑成功',function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'book','func'=>'list'));?>');
                        },'succeed');
                    }else{
                        $.dialog.alert('问题提交成功',function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'book','func'=>'list'));?>');
                        },'succeed');
                    }
                }
            });
            return false;
        });
    });
</script>
<?php $this->output("foot_member","file"); ?>