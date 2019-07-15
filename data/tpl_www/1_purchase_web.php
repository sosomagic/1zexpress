<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <tbody>
                    <tr>
                        <?php $web_id["num"] = 0;$web=is_array($web) ? $web : array();$web_id["total"] = count($web);$web_id["index"] = -1;foreach($web AS $key=>$value){ $web_id["num"]++;$web_id["index"]++; ?>
                        <td class="text-center"><img src="<?php echo $value['filename'];?>" title="<?php echo $value['title'];?>" width="260" height="100">
                            <br>
                            <?php echo $value['title'];?>
                        </td>
                        <?php if(($key+1)%4==0){ ?>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot_open","file"); ?>