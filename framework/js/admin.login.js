//验证码
function login_code(appid)
{
    var src_url = api_url("vcode","","id="+appid);
    $("#src_code").attr("src",$.dsy.nocache(src_url));
}
//验证并登录
function admlogin()
{
    $("#adminlogin").ajaxSubmit({
        'url':get_url('login','ok'),
        'type':'post',
        'dataType':'json',
        'success':function(rs){
            if(rs.status){
                $.dsy.go(get_url('index'));
                return true;
            }
            $.dialog.alert(rs.info,function(){
                $("#code_id").val('');
                login_code('admin');
            },'error');
            return false;
        }
    });
    return false;
}