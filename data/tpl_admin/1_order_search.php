<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('order');?>" title="返回运单列表">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>批量查询</span>
        </li>
    </ul>
</div>
<form method="post" id="ordersave">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">运单号：</td>
                            <td><textarea class="form-control" rows="50" name="searchOrder" id="searchOrder" placeholder="一次最多查询1000个单号，敲回车换行"></textarea></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button class="btn blue" type="submit">
                <i class="fa fa-edit"></i>
                提 交
            </button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#ordersave").submit(function(){
            var sn = $("#searchOrder").val();
            if(!sn){
                $.dialog.alert("查询运单号不能为空！");
                return false;
            }
            var arr = sn.split('\n');
            if(arr.length>1000){
                $.dialog.alert("一次最多只能查询1000个运单号");
                return false;
            }
            var url = get_url('order','export_data','order_sn='+arr);
            $.dsy.go(url);
            return false;
        });
    });
</script>
<?php $this->output("foot","file"); ?>