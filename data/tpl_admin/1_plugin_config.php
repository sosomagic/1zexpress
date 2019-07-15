<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'admin'));?>">后台主页</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id=='loginext'){ ?>快捷登录<?php } else { ?>微信<?php } ?>设置</span>
        </li>
    </ul>
    <?php if($id=='weixin'){ ?>
    <div class="pull-right" style="margin-top: 5px;margin-right: 10px;margin-bottom: 5px;">
        <a class="btn green" href="<?php echo admin_url('wechat','index');?>">微信菜单</a>
    </div>
    <?php } ?>
</div>
<form method="post" action="<?php echo admin_url('plugin','save');?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">插件名称：</td>
                            <td><input id="title" name="title" value="<?php echo $rs['title'];?>" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">备注：</td>
                            <td><input id="note" name="note" value="<?php echo $rs['note'];?>" class="form-control"/></td>
                        </tr>
                        <?php echo $plugin_html;?>
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
<script type="text/javascript" src="<?php echo include_js('plugin.js');?>"></script>
<?php $this->output("foot","file"); ?>