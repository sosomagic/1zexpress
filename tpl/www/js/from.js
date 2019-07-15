$(document).ready(function(){
	$("#start_search").click(function(){
		var sn=$("#sn").val();
		if(!sn){
			$.dialog.alert("请填写运单号查询");
			return false;		
		}
		var arr = sn.split('\n');
		if(arr.length>1){
		   $.dialog.alert("一次最多只能查询1个运单号");
			return false;		
		}
        var url = get_url('order','search','sn='+sn);
        $.dialog.open(url,{
            'title':'运单号：'+sn+'物流查询信息',
            'width':'70%',
            'height':'70%',
            'lock':true
        });
        return false;
	});
    $("#count").click(function(){
        var channel=$("#channel").val();
        if(!channel){
            $.dialog.alert("请选择发货渠道");
            return false;
        }
        var weight = $("#weight").val();
        if(!weight){
            $.dialog.alert("请填写包裹重量");
            return false;
        }
        var length = $("#length").val();
        if(!length){
            length=0;
        }
        var width = $("#width").val();
        if(!width){
            width=0;
        }
        var height = $("#height").val();
        if(!height){
            height=0;
        }
        var volume = (length*width*height)/5000*2.2;
        var url = get_url('index','count','channel='+channel+'&weight='+weight+'&volume='+volume);
        $.dialog.open(url,{
            'title':'计算运费',
            'width':'50%',
            'height':'50%',
            'lock':true
        });
        return false;
    });
    $(".responsive").click(function(){
        var url = get_url("order","idcard");
        $.dialog.open(url,{
            title: "上传身份证",
            lock : true,
            width: "450px",
            height: "300px",
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
            'cancel':function(){
                return true;
            }
        });
        return false;
    });
    $(".customizable").click(function(){
        var url = get_url("index","delivery");
        $.dialog.open(url,{
            title: "免费预约上门取件",
            lock : true,
            width: "450px",
            height: "100%",
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
            'okVal':'提交申请',
            'cancel':function(){
                return true;
            }
        });
        return false;
    });
});
