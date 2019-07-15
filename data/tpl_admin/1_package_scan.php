<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('package');?>" title="返回包裹列表">包裹管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>扫描入库</span>
        </li>

    </ul>
</div>
    <div class="row">
        <form method="post" id="ordersave">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">到货仓库：</td>
                            <td>
                                <select class="form-control" id="stock" name="stock">
                                    <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">仓位：</td>
                            <td><input value="" id="position" type="text" name="position" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">入库重量：</td>
                            <td><input id="jingzhong" type="text" value="" name="jingzhong" onkeyup="value=value.replace(/[^\d.]/g,'')" class="form-control"></td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2">包裹单号：</td>
                            <td><input type="text" id="sn" name="sn" value="" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right">会员编号：</td>
                            <td><input id="ucode" type="text" value="" name="ucode" class="form-control">
                                <span class="help-block">当客户没有预报，后台直接扫描入库的情况下，需要填写会员编号，已预报过的包裹，不需要填写会自动显示，会员编号不区分大小写</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="show" class="text-center"></div>
                    <div class="text-center"><button class="btn blue" type="submit">入 库</button></div>
                </div>
            </div>
        </div>
        <div class="note note-info">
            <h4 class="block fa fa-warning bold font-red-thunderbird"> 温馨提示：</h4>
            <p>1、入库操作之前，请连接好热敏打印机，入库的同时，将会打印包裹入库标签，方便上架。 </p>
            <p>2、热敏纸尺寸：高：3inch*宽2inch。 </p>
            <p>3、打印无反应时，请先下载打印插件，安装到本机，<a href="lodop.zip" target="_blank"> 点击下载打印插件</a>。</p>
        </div>
    </form>
</div>
<script type="text/javascript" src="js/LodopFuncs.js"></script>
<script type="text/javascript" src="<?php echo add_js('package_print.js');?>"></script>
<script type="text/javascript">
    function save()
    {
        var url = get_url('package','storage_setting');
        $("#ordersave").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                if(rs.status == 'ok'){
                    var sn = $("#sn").val();
				    package_print(sn);
                    $('#sound')[0].play(); //播放声音
                    $("#show").html('<span class="font-red-thunderbird">包裹'+sn+'，入库成功</span>').show();
                    $("#jingzhong").focus();
                    $("#jingzhong").val("");
                    $("#sn").val("");
                    $("#ucode").val("");
                    setTimeout("document.getElementById('show').style.display='none'",1000);
                }else{
                    $('#chatAudio')[0].play(); //播放声音
                    document.getElementById('show').style.display='block';
                    $("#show").html('<span class="font-red-thunderbird">'+rs.content+'</span>');
                    return false;
                }
            }
        });
        return false;
    }
    function ucode(){
        var sn = $('#sn').val();
        var url = get_url('package','get_ucode','sn='+sn);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $('#ucode').val(rs.content.ucode);
            save();
        }else{
            $('#chatAudio')[0].play(); //播放声音
            document.getElementById('show').style.display='block';
            $("#show").html('<span class="font-red-thunderbird">'+rs.content+'</span>');
            $("#ucode").focus();
            return false;
        }
    }
    $(document).ready(function()
    {
        var position = $('#position').val();
        if(!position){
            $("#position").focus();
        }else{
            $("#jingzhong").focus();
        }
        $('#sn').bind("keydown", function (e) {
            if (e.which == 13) {
                ucode();
                return false;
            }
        });
        /*$('#ucode').bind("keydown", function (e) {
            if (e.which == 13) {
                save();
                return false;
            }
        });*/
        $("#ordersave").submit(function(){
            save();
            return false;
        });
        $('<audio id="chatAudio"><source src="tpl/www/images/error.mp3" type="audio/mpeg"><source src="tpl/www/images/error.wav" type="audio/wav"></audio>').appendTo('body');//载入声音文件
        $('<audio id="sound"><source src="tpl/www/images/right.mp3" type="audio/mpeg"><source src="tpl/www/images/right.wav" type="audio/wav"></audio>').appendTo('body');//载入声音文件
    });
</script>
<?php $this->output("foot","file"); ?>