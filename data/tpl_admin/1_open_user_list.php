<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<?php if($multi){ ?>
<div class="list">
    <ul class="layout">
        <li>已选择：</li>
        <div id="selected_list"></div>
        <div class="clear"></div>
    </ul>
</div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post"  action="<?php echo dsy_url(array('ctrl'=>'open','func'=>'user','id'=>$id));?>" onsubmit="return submit_search()">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td>会员名:</td>
                            <td><input type="text" name="keywords" value="<?php echo $keywords;?>" class="input-small"></td>
                            <td>姓名:</td>
                            <td><input type="text" name="first_name" value="<?php echo $first_name;?>" class="input-small"></td>
                            <td>标识码:</td>
                            <td><input type="text" name="ucode" value="<?php echo $ucode;?>" class="input-xsmall"></td>
                            <td><input type="submit" value="查 询" class="btn btn-sm btn-info" /></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">ID</th>
                        <th class="bold">会员名</th>
                        <th class="bold">姓名</th>
                        <th class="bold">标识码</th>
                       <!-- <th class="bold col-md-2">邮箱</th>-->
                        <th class="bold">手机</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['id'];?></td>
                        <td><?php echo $value['user'];?></td>
                        <td><?php echo $value['first_name'];?></td>
                        <td><?php echo $value['ucode'];?></td>
                        <!--<td id="email_<?php echo $value['id'];?>"><?php echo $value['email'];?></td>-->
                        <td id="mobile_<?php echo $value['id'];?>"><?php echo $value['mobile'];?></td>
                        <td><input type="button" value="选择" onclick="add_input('<?php echo $value['id'];?>')" class="dsy-btn btn btn-xs btn-danger" /></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="text-right"><?php $this->output("pagelist","file"); ?></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var input_id = "#<?php echo $id;?>";
    var multi = <?php echo $multi ? "1" : "0";?>;
    var obj = $.dialog.opener;
    function show_list()
    {
        cid = $.dialog.data('dsy_user_<?php echo $id;?>');
        if(cid == "undefined" || cid == "0" || cid == "")
        {
            return false;
        }
        $("li[name=list]").show();
        var url = get_url("inp")+"&type=user&content="+$.str.encode(cid);
        var rs = $.dsy.json(url);
        if(rs.status == "ok")
        {
            var lst = rs.content;
            var c = "";
            for(var i in lst)
            {
                c += '<li id="user_<?php echo $id;?>_'+lst[i]['id']+'">';
                c += '<div>'+lst[i]['user']+' <a href="javascript:delete_input(\''+lst[i]['id']+'\');void(0);" title="删除"><img src="images/page_delete.png" border="0" alt="" /></a></div>';
                c += '</li>';
                $("#user_"+lst[i]['id']).hide();
            }
            $("#selected_list").html(c).show();
            $.dialog.data("dsy_user_<?php echo $id;?>",cid);
        }
        else
        {
            $("#selected_list").hide();
            $.dialog.removeData("dsy_user_<?php echo $id;?>");
        }
    }
    function add_input(val)
    {
        if(multi)
        {
            var old_c = $.dialog.data('dsy_user_<?php echo $id;?>');
            var c = (old_c && old_c != 'undefined') ? old_c+","+val : val;
            var lst = c.split(",");
            lst = $.unique(lst);
            $.dialog.data('dsy_user_<?php echo $id;?>',lst.join(","));
            show_list();
        }
        else
        {
            obj.$("#<?php echo $id;?>").val(val);
            obj.action_<?php echo $id;?>_show();
            $.dialog.removeData("dsy_user_<?php echo $id;?>");
            $.dialog.close();
        }
    }
    function delete_input(val)
    {
        if(multi)
        {
            var old_c = $.dialog.data('dsy_user_<?php echo $id;?>');
            if(!old_c)
            {
                return true;
            }
            var lst = old_c.split(",");
            var n_list = new Array();
            var m=0;
            for(var i=0;i<lst.length;i++)
            {
                if(lst[i] != val)
                {
                    n_list[m] = lst[i];
                    m++;
                }
            }
            if(n_list.length<1)
            {
                $.dialog.removeData("dsy_user_<?php echo $id;?>");
            }
            else
            {
                var str = n_list.join(",");
                $.dialog.data("dsy_user_<?php echo $id;?>",str);
            }
        }
        else
        {
            $.dialog.removeData("dsy_user_<?php echo $id;?>");
        }
        show_list();
    }
    $(document).ready(function(){
        if(multi == 1){
            var new_c = $.dialog.data("dsy_user_<?php echo $id;?>");
            if(!new_c || new_c == "undefined"){
                new_c = obj.$(input_id).val();
            }
            show_list();
        }
    });
</script>
<?php $this->output("foot_open","file"); ?>