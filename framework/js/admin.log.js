;(function($){
	$.admin_log = {
		search:function(name,val)
		{
			if(name == 'start_time'){
				$("input[name=start_time]").val(val);
				$("button[type=submit]").click();
			}
		},
		del:function(id)
		{
			$.dialog.confirm(p_lang('确定要删除这条日志吗？'),function(){
				var url = get_url('log','delete','id='+id);
				$.dsy.json(url,function(rs){
					if(rs.status){
						$.dsy.reload();
						return true;
					}
					$.dialog.alert(rs.info,true,'error');
				})
			})
		},
		delete30:function()
		{
			$.dialog.confirm(p_lang('确定要删除30天之前日志吗？'),function(){
				var url = get_url('log','delete','date=30');
				$.dsy.json(url,function(rs){
					if(rs.status){
						$.dsy.reload();
						return true;
					}
					$.dialog.alert(rs.info,true,'error');
				})
			})
		},
		delete_selected:function()
		{
			var ids = $.checkbox.join();
			if(!ids){
				$.dialog.alert(p_lang('未选择要删除的日志'));
				return false;
			}
			$.dialog.confirm(p_lang('确定要删除选中的日志吗？'),function(){
				var url = get_url('log','delete','ids='+$.str.encode(ids));
				$.dsy.json(url,function(rs){
					if(rs.status){
						$.dsy.reload();
						return true;
					}
					$.dialog.alert(rs.info,true,'error');
				})
			})
		}
	}
})(jQuery);