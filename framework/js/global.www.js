/***********************************************************
	Filename: js/global.www.js
	Note	: 前台公共JS加载
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013年9月12日
***********************************************************/
//前台通用弹出窗口
function dsy_open(id,title)
{
	if(id == "login" || id == "register")
	{
		var url = get_url("ajax","exit","filename="+id);
	}
	else
	{
		var url = id;
	}
	if(!title || title == "undefined") title = '弹出窗';
	$.dialog.open(url,{
		 "title":title
		,"resize":true
		,"lock":true
		,"id":"dsy_open_frame"
		,"fixed":true
		,"drag":false
	});
}

//前台常用JS函数封装
;(function($){
//定义验证码
$.fn.dsy_vcode = function(ext){
	var url = api_url('vcode');
	if(ext && ext != 'undefined')
	{
		url += "&id="+ext;
	}
	$(this).attr('src',url);
}
$.dsy_www = {
	comment:function(id,tid,callback){
		if(!tid || tid == 'undefined')
		{
			$.dialog.alert(lang.commentNotId);
			return false;
		}
		//直接通过JS判断是否恶意灌水
		var spam = $("#"+id+"_spam").val();
		if(!spam)
		{
			$.dialog.alert(lang.commentSpamEmpty);
			return false;
		}
		var content = $("#"+id).val();
		if(!content)
		{
			$.dialog.alert(lang.commentEmpty);
			return false;
		}
		var url = api_url('comment','save','id='+tid);
		url += "&content="+$.str.encode(content);
		url += "&_spam="+spam;
		//提交评论
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
			if(callback && callback != 'undefined')
			{
				eval("callback()");
			}
			else
			{
				$.dialog.alert(lang.commentSuccess,function(){
					$.dsy.reload();
				},'succeed');
			}
		}
		else
		{
			$.dialog.alert(rs.content,'','error');
			return false;
		}
	}
};
})(jQuery);