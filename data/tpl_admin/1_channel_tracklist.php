<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'track_list'));?>">运单状态管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运单状态</span>
        </li>
    </ul>
    <?php if($popedom['add']){ ?>
    <div class="page-toolbar" style="margin-top: 5px;margin-right: 10px;margin-bottom: 5px;">
        <div class="btn-group pull-right">
            <a class="btn green" href="<?php echo admin_url('channel','track');?>">
                <i class="fa fa-plus"></i>
                添加运单状态
            </a>
        </div>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold text-center">状态名</th>
                        <th class="bold text-center">标识</th>
                        <th class="bold text-center">隶属仓库</th>
                        <th class="bold text-center">邮件</th>
                        <th class="bold text-center">手机短信</th>
                        <th class="bold text-center">排序</th>
                        <th class="bold text-center" scope="col" style="width:150px !important">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['title'];?><?php if($value['space']){ ?><span class="font-blue">（自动更新+<?php echo $value['space'];?>）</span><?php } ?></td>
                        <td class="text-center"><?php echo $value['status'] ? '系统【'.$value['status'].'】' :'自定义';?></td>
                        <td class="text-center"><?php $list_id["num"] = 0;$value['stock']=is_array($value['stock']) ? $value['stock'] : array();$list_id["total"] = count($value['stock']);$list_id["index"] = -1;foreach($value['stock'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?><?php echo $v['title'];?><?php if($list_id['num'] != $list_id['total']){ ?> 、 <?php } ?><?php } ?></td>
                        <td class="text-center"><?php if($value['mail']){ ?><span class="font-red">通知</span><?php } else { ?>不通知<?php } ?></td>
                        <td class="text-center"><?php if($value['sms']){ ?><span class="font-red">通知</span><?php } else { ?>不通知<?php } ?></td>
                        <td class="text-center"><?php echo $value['taxis'];?></td>
                        <td class="text-center">
                            <?php if($popedom['track']){ ?>
                            <a class="btn btn-xs btn-info" href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'track','id'=>$value['id']));?>"><i class="fa fa-edit"></i>编辑</a>
                            <a class="btn btn-xs btn-danger" href="javascript:;" onclick="track_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')"><i class="fa fa-times"></i>删除</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('channel.js');?>"></script>
<?php $this->output("foot","file"); ?>