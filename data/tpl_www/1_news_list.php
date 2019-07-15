<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $title= $cate_rs ? $cate_rs['title'].' - '.$page_rs['title'] : $page_rs['title'];?>
<?php $this->assign("title",$title); ?><?php $this->assign("menutitle",$cate_rs['title']); ?><?php $this->output("head","file"); ?>
<div class="banner-product news-banner">
    <div class="container">
        <div class="banner-tt">资讯中心</div>
        <div class="banner-englist">Information Center</div>
    </div>
</div>
<!--运算器-->
<?php if($cate_rs['id'] ==590){ ?>
<div class="container calculator">
    <div class="clearfix calculator-c">
        <div class="first-con">
            <div class="pull-left calculator-l1">运费计算器:</div>
            <div class="pull-left ">
				<select id="channel" class="form-control select-way1" name="channel" >
					<option value="">---请选择发货渠道---</option>
					<?php $channel_id["num"] = 0;$channel=is_array($channel) ? $channel : array();$channel_id["total"] = count($channel);$channel_id["index"] = -1;foreach($channel AS $key=>$value){ $channel_id["num"]++;$channel_id["index"]++; ?>
					<option value="<?php echo $value['id'];?>">---<?php echo $value['title'];?>---</option>
					<?php } ?>
				</select>
            </div>
        </div>
        <div class="second-con">
            <div class="pull-left title-select"> 会员级别：</div>
            <div class="pull-left calculator-l2">
                <select class="form-control select-way2" name="group_id" id="group_id">
                    <?php $group_id["num"] = 0;$group=is_array($group) ? $group : array();$group_id["total"] = count($group);$group_id["index"] = -1;foreach($group AS $key=>$value){ $group_id["num"]++;$group_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>">
                       <?php echo $value['title'];?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="three-con">
            <div class="pull-left calculator-l3 none-folat">
                <form class="form-inline">
                    <div class="form-group">
                        <label class="label-l" for="weight">重量(LB): </label>
                        <input type="text" class="form-control form-text text-lc" id="weight" name="weight" value="" >
                    </div>
                    <button type="button " class="btn btn-default gusuan-btn" id="count" >估算</button>
                </form>
            </div>
        </div>
        <div class="pull-right calculator-r1 "></div>
    </div>
</div>
<?php } ?>
<div class="about-padding">
    <div class="container">
        <div class="nav-b clearfix"><a href="<?php echo $sys['url'];?>" title="<?php echo $config['title'];?>">首页</a><span>/</span><a href="<?php echo $page_rs['url'];?>" title="<?php echo $page_rs['title'];?>"><?php echo $page_rs['title'];?></a>
            <?php if($cate_rs){ ?>
            <span>/</span><a href="<?php echo $cate_rs['url'];?>" title="<?php echo $cate_rs['title'];?>"><?php echo $cate_rs['title'];?></a>
            <?php } ?>
        </div>
        <div class="clearfix">
            <div class="pull-left about-left">
                <?php $this->output("block_catelist","file"); ?>
            </div>
            <div class="pull-right about-right">
                <div class="news-tt-left news-tt-left02"><?php echo $cate_rs['title'];?></div>
                <ul class="news-list-c">
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <li><a href="<?php echo $value['url'];?>"><span clas="news-txt-l"><?php echo $value['title'];?></span><span class="news-date-r"><?php echo date('Y-m-d',$value['dateline']);?></span></a></li>
                    <?php } ?>
                </ul>
                <!--分页 -->
                <div class="showpage">
                    <?php $pagelist = dsy_page($pageurl,$total,$pageid,$psize,"prev=上一页&next=下一页&last=末页&home=首页&always=1");?>
                    <?php $pagelist_id["num"] = 0;$pagelist=is_array($pagelist) ? $pagelist : array();$pagelist_id["total"] = count($pagelist);$pagelist_id["index"] = -1;foreach($pagelist AS $key=>$value){ $pagelist_id["num"]++;$pagelist_id["index"]++; ?>
                    <a<?php if($value['status']){ ?> class="now"<?php } ?> href="<?php echo $value['url'];?>"><?php echo $value['title'];?></a>
                    <?php } ?>
                </div>
                <!--分页 -->
            </div>
        </div>
    </div>
</div>
<?php if($cate_rs['id'] ==590){ ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#count").click(function(){
            var channel=$("#channel").val();
            var weight=$("#weight").val();
            var group_id = $("#group_id").val();
            var url = get_url('index','count','channel='+channel+'&weight='+weight+'&group_id='+group_id);
            $.dialog.open(url,{
                'title':'计算运费',
                'width':'50%',
                'height':'50%',
                'lock':true
            });
            return false;
        });
    });
</script>
<?php } ?>
<?php $this->output("foot","file"); ?>