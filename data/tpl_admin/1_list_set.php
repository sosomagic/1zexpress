<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<script type="text/javascript" src="<?php echo include_js('list.js');?>"></script>
<?php if($popedom['set'] || $session['admin_rs']['if_system']){ ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('list');?>">内容管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <?php if($pid){ ?>
            <?php $plist_id["num"] = 0;$plist=is_array($plist) ? $plist : array();$plist_id["total"] = count($plist);$plist_id["index"] = -1;foreach($plist AS $key=>$value){ $plist_id["num"]++;$plist_id["index"]++; ?>
            <span><a href="<?php echo admin_url('list','action');?>&id=<?php echo $value['id'];?>" title="<?php echo $value['title'];?>"><?php echo $value['title'];?></a></span>
            <?php } ?>
            <?php } ?>
            &raquo; 编辑页面属性
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php if($rs['admin_note']){ ?><?php echo $rs['admin_note'];?><?php } ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                        <li class="active bold">
                            <a aria-expanded="false" href="#tab1" data-toggle="tab">基本设置</a>
                        </li>
                        <li class="bold">
                            <a aria-expanded="false" href="#tab2" data-toggle="tab">SEO优化</a>
                        </li>
                    </ul>
                    <form method="post" id="<?php echo $ext_module;?>" action="<?php echo admin_url('list','save');?>" onsubmit="return project_check();">
                        <input type="hidden" id="id" name="id" value="<?php echo $id;?>" />
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">名称：</td>
                                        <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/></td>
                                    </tr>
                                    <?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
                                    <tr>
                                        <td class="text-right"><?php echo $value['title'];?><span class="font-blue">[<?php echo $value['identifier'];?>]</span>：</td>
                                        <td><?php echo $value['html'];?>
                                            <span class="help-block"><?php echo $value['note'];?></span></td>
                                    </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <table class="table table-striped table-bordered table-hover"  style="margin-bottom:20px;">
                                    <tbody>
                                    <tr>
                                        <td class="text-right col-md-2">SEO标题：</td>
                                        <td><input id="seo_title" name="seo_title" type="text" value="<?php echo $rs['seo_title'];?>" class="form-control"/>
                                            <span class="help-block">设置此标题后，网站Title将会替代默认定义的，不能超过85个汉字</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">SEO关键字：</td>
                                        <td><input id="seo_keywords" name="seo_keywords" type="text" value="<?php echo $rs['seo_keywords'];?>" class="form-control"/>
                                            <span class="help-block">多个关键字用英文逗号隔开</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">SEO描述：</td>
                                        <td><textarea id="seo_desc" name="seo_desc" maxlength="100" class="form-control" rows="2"><?php echo $rs['seo_desc'];?></textarea>
                                            <span class="help-block">简单描述该主题信息，用于搜索引挈，不支持HTML，不能超过85个汉字</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">Tag标签：</td>
                                        <td><input id="tag" name="tag" type="text" value="<?php echo $rs['tag'];?>" class="form-control"/>
                                            <span class="help-block">多个标签用英文空格隔开，最多不能超过10个词</span>
                                        </td>
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
                                    <button class="btn red" type="reset">
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
<?php } ?>
<?php $this->output("foot","file"); ?>