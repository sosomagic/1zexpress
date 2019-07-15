/**
 * Created by lark on 16-12-6.
 */
function package_print(sn)
{
    var url = get_url("package","package_print","sn="+sn);
    var rs = json_ajax(url);
    if(rs.status == "ok")
    {
        var html= '';
        var info = rs.content;
        var sn = info.sn;
        html += '<div class="box">';
        html += '<div class="box_n">';
        html += '<div class="boxA">'+sn.substr(sn.length-10)+'</div>';
        html += '<div class="boxB"></div>';
        html += '<div class="boxC">';
        html += '<div class="boxC_a"></div>';
        html += '<div class="boxC_b">'+info.user_id+'</div>';
        html += '<div class="boxC_c">'+info.ucode+'</div>';
        html += '</div>';
        html += '<div class="boxD">';
        html += '<div class="boxD_a">'+info.jingzhong+' 磅</div>';
        html += '<div class="boxD_b">'+getLocalTime(info.rukutime)+'</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        prn1_print(html,sn);
    }
    var LODOP; //声明为全局变量
    function prn1_print(data,sn){
        LODOP=getLodop();
        //if(!confirm("您确定要批量打印吗？")) return;
        LODOP.PRINT_INIT("包裹标签打印");
        LODOP.PRINT_INITA(0,0,310,210,"批量打印")
        var strStyleCSS="<link href='css/storage.css' type='text/css' rel='stylesheet'>";
        var strFormHtml=strStyleCSS+"<body>"+data+"</body>";
        LODOP.ADD_PRINT_HTM(2,2,310,210,strFormHtml);
        LODOP.ADD_PRINT_BARCODE(74,43,225,59,"Code39",sn);
        //LODOP.PRINT();
        LODOP.PRINT_SETUP();
    }
}
function getLocalTime(nS) {
    return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');
}