<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><script type="text/javascript">
function unlock_action(type,tip)
{
	$.dialog.confirm('确定要解除'+tip+'绑定吗？',function(){
		var url = api_plugin_url('loginext','unbindaccount','type='+type);
		var rs = $.dsy.json(url);
		if(rs.status){
			$.dialog.alert('成功解除'+tip+'绑定',function(){
				$.dsy.reload();
			},'succeed');
		}else{
			$.dialog.alert(rs.info);
		}
	})
}
</script>
<div id="usercp_bindaccount" style="display:none">
	<div class="table_lc" style="margin:5px;">
	<table width="100%">
	<tr>
		<th class="lft">登录方式</th>
		<th class="lft">说明</th>
		<th width="77px"></th>
		<th width="77px">操作</th>
	</tr>
	<?php if($qqlink){ ?>
	<tr>
		<td align="center"><img src="plugins/loginext/images/qq.png" /></td>
		<td>绑定 QQ 号码，实现 QQ 一键登录，安全更快捷</td>
		<?php if($binder['qq']){ ?>
		<td>已设置</td>
		<td><a href="javascript:unlock_action('qq','QQ');void(0);">解除</a></td>
		<?php } else { ?>
		<td>未绑定</td>
		<td><a href="<?php echo $qqlink;?>">现在绑定</a></td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php if($wxlink){ ?>
	<tr>
		<td align="center"><img src="plugins/loginext/images/weixin.png" /></td>
		<td>绑定微信号，使用微信扫一扫即可登录</td>
		<?php if($binder['weixin']){ ?>
		<td>已设置</td>
		<td><a href="javascript:unlock_action('weixin','微信');void(0);">解除</a></td>
		<?php } else { ?>
		<td>未绑定</td>
		<td><a href="<?php echo $wxlink;?>">现在绑定</a></td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php if($wblink){ ?>
	<tr>
		<td align="center"><img src="plugins/loginext/images/weibo.png" /></td>
		<td>绑定新浪微博，登录更便捷</td>
		<?php if($binder['weibo']){ ?>
		<td>已设置</td>
		<td><a href="javascript:unlock_action('weibo','微博');void(0);">解除</a></td>
		<?php } else { ?>
		<td>未绑定</td>
		<td><a href="<?php echo $wxlink;?>">现在绑定</a></td>
		<?php } ?>
	</tr>
	<?php } ?>
	</table>
	</div>
	<div style="text-align:center;padding:10px"><a href="<?php echo dsy_url(array('ctrl'=>'plugin','id'=>'loginext','exec'=>'bindaccount'));?>">单独界面管理</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.cp .right').append($("#usercp_bindaccount").html());
});
</script>
