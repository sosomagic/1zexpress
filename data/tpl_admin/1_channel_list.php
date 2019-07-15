<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?><div class="page-bar">    <ul class="page-breadcrumb">        <li>            <a href="<?php echo dsy_url(array('ctrl'=>'channel'));?>">渠道管理</a>            <i class="fa fa-angle-right"></i>        </li>        <li>            <span>渠道列表</span>        </li>    </ul>    <?php if($popedom['add']){ ?>    <div class="page-toolbar" style="margin-top: 5px;margin-right: 10px;margin-bottom: 5px;">        <div class="btn-group pull-right">            <a class="btn green" href="<?php echo admin_url('channel','set');?>">                <i class="fa fa-plus"></i>                添加渠道            </a>        </div>    </div>    <?php } ?></div><div class="row">    <div class="col-md-12">        <div class="portlet light bordered">            <div class="portlet-body">                <div class="table-scrollable">                    <table class="table table-striped table-bordered table-advance table-hover">                        <thead>                        <tr>                            <th class="bold text-center">渠道名称</th>                            <th class="bold text-center">包裹类别</th>                            <th class="bold text-center">备注</th>                            <th class="bold text-center">一单到底</th>                            <th class="bold text-center">身份证</th>                            <th class="bold text-center">启用</th>                            <th class="bold text-center">排序</th>                            <th class="bold text-center" scope="col" style="width:150px !important">操作</th>                        </tr>                        </thead>                        <tbody>                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>                        <tr>                            <td class="text-center"><?php echo $value['title'];?></td>                            <td class="text-center"><?php echo $value['type'];?></td>                            <td class="text-center"><?php echo $value['note'];?></td>                            <td class="text-center"><?php echo $value['tracking_number'] ? '是' : '否';?></td>                            <td class="text-center"><?php echo $value['idcard'] ? '是' : '否';?></td>                            <td class="text-center"><?php echo $value['status'] ? '是' : '否';?></td>                            <td class="text-center"><?php echo $value['taxis'];?></td>                            <td class="text-center">                                <?php if($popedom['modify']){ ?>                                <a class="btn btn-xs btn-info" href="javascript:;" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'channel','func'=>'set','id'=>$value['id']));?>')"><i class="fa fa-edit"></i> 编辑</a>                                <?php } ?>                                <?php if($popedom['delete']){ ?>                                <a class="btn btn-xs btn-danger" href="javascript:;" onclick="channel_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')"><i class="fa fa-times"></i> 删除</a>                                <?php } ?>                            </td>                        </tr>                        <?php } ?>                        </tbody>                    </table>                </div>            </div>        </div>    </div></div><script type="text/javascript" src="<?php echo add_js('channel.js');?>"></script><?php $this->output("foot","file"); ?>