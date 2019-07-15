<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('order');?>" title="返回运单列表">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>扫描揽收</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body" style="height:400px;">
                <div class="form-body form-horizontal" >
                    <div class="form-group" style="padding-top: 100px;">
                        <label class="col-md-2 control-label bold" style="font-size: 18px;">扫描运单号：</label>
                        <div class="col-md-10">
                            <input id="sn" name="sn" class="form-control input-lg" placeholder="扫描运单号" type="text">
                        </div>
                    </div>
                </div>
                <div id="show" class="text-center"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#sn").focus(); //停留
        $("#sn").keyup(function(event){
            var sn = $("#sn").val();
            if(!sn){
                $.dialog.alert('运单号不能为空',function(){
                    $("#sn").focus();
                },'succeed');
                return false;
            }
            if(event.keyCode == 13){
                var url = get_url('order','pickup_save','sn='+sn);
                var rs = json_ajax(url);
                if(rs.status == 'ok'){
                    $('#sound')[0].play(); //播放声音
                    $("#sn").val("");
                }else{
                    $('#chatAudio')[0].play(); //播放声音
                    $("#sn").val("");
					document.getElementById('show').style.display='block';
                    $("#show").html('<span class="font-red">'+rs.content+'</span>');
                    return false;
                }
            }
        });
        $('<audio id="chatAudio"><source src="tpl/www/images/error.mp3" type="audio/mpeg"><source src="tpl/www/images/error.wav" type="audio/wav"></audio>').appendTo('body');//载入声音文件
        $('<audio id="sound"><source src="tpl/www/images/right.mp3" type="audio/mpeg"><source src="tpl/www/images/right.wav" type="audio/wav"></audio>').appendTo('body');//载入声音文件
    });
</script>
<?php $this->output("foot","file"); ?>