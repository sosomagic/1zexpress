/**
 * Created by lark on 16-10-29.
 */
function order_pldy(id)
{
    if(id && id!=0){
        var url = get_url("batch","print","id="+id);
    }else{
        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要批量打印的运单');
            return false;
        }
        var url = get_url("order","front_print",'ids='+$.str.encode(ids));
    }
    var rs = json_ajax(url);
    if(rs.status == "ok")
    {
        var html= '';
        //var customer = new Array();
        var sn = new Array();
        var info = rs.content;
        for(var i=0;i<info.length;i++){
            var pro = info[i].product;
            //customer[i] = info[i].customer_sn;
            sn[i] = info[i].sn;
            html += '<div id="box">';
				html += '<div class="top border-bot">';
				html += '<div class="logo fl"><img src="css/images/plogo.jpg" width="115" height="48" /></div>';
				html += '<div class="top_rig fr"></div>';
				html += '</div>';
				html += '<div class="send">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">寄件人/From:</div>';
				html += '<div class="send_b fr">'+info[i].consignor+'</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">电话/Tel:</div>';
				html += '<div class="send_e fr">'+info[i].consignor_tel+'</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_f fl">地址/Address:</div>';
				html += '<div class="send_c fr">'+info[i].consignor_address+'</div>';
				html += '</div>';
				html += '<div class="send">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">收件人/To:</div>';
				html += '<div class="send_b fr">'+info[i].address.fullname+'</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">电话/Tel:</div>';
				html += '<div class="send_e fr">'+info[i].address.mobile+'</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot" style="height:40px;">';
				html += '<div class="send_f fl">地址/Address:</div>';
				html += '<div class="send_c fr">'+info[i].address.province;
                if(info[i].address.province != info[i].address.city){
                    html += info[i].address.city;
                }
                html += info[i].address.county+info[i].address.address;
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">大客户代码:</div>';
				html += '<div class="send_b fr"></div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_g fl">邮编/Post Code:</div>';
				html += '<div class="send_d fr">'+info[i].address.zipcode+'</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="msgL fl">';
				html += '<div class="msgL_na">内件描述/Descriptions:</div>';
				html += '<div class="msgL_nb">';
				for(var j=0;j<pro.length;j++){
					html += pro[j].title+'*'+pro[j].qty+';';
				}
				html += '</div>';
				html += '</div>';
				html += '<div class="msgR fr">';
				html += '<div class="msgR_n">';
				html += '<div class="msgR_a fl">实际重量:</div>';
				html += '<div class="msgR_b fr"><span>'+info[i].jingzhong+'</span>Kg</div>';
				html += '</div>';
				html += '<div class="msgR_n">';
				html += '<div class="msgR_a fl">体积重量:</div>';
				html += '<div class="msgR_b fr"><span>&nbsp;</span>Kg</div>';
				html += '</div>';
				html += '<div class="msgR_z">';
				html += '<div class="msgR_zn">';
				html += '<span>长/L</span>';
				html += '<span>宽/W</span>';
				html += '<span>高/H</span>';
				html += '</div>';
				html += '<div class="msgR_zn">';
				html += '<span> cm</span>';
				html += '<span> cm</span>';
				html += '<span> cm</span>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_n fl">';
				html += '<div class="send_g fl">申报价值/Value:</div>';
				html += '<div class="send_a fr">'+info[i].val+' CNY</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_g fl">原产地/Origin:</div>';
				html += '<div class="send_d fr">美国</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">收件人签名:</div>';
				html += '<div class="send_b fr"></div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_g fl">签收时间:</div>';
				html += '<div class="send_d fr"></div>';
				html += '</div>';
				html += '</div> ';
				html += '<div class="send ">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">寄件人/From:</div>';
				html += '<div class="send_b fr">'+info[i].consignor+'</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">电话/Tel:</div>';
				html += '<div class="send_e fr">'+info[i].consignor_tel+'</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_f fl">地址/Address:</div>';
				html += '<div class="send_c fr">'+info[i].consignor_address+'</div>';
				html += '</div>';
				html += '<div class="send">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">收件人/To:</div>';
				html += '<div class="send_b fr">'+info[i].address.fullname+'</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">电话/Tel:</div>';
				html += '<div class="send_e fr">'+info[i].address.mobile+'</div>';
				html += '</div>';
				html += '</div>';
				html += '<div class="send border-bot" style="height:40px;">';
				html += '<div class="send_f fl">地址/Address:</div>';
				html += '<div class="send_c fr">'+info[i].address.province;
                if(info[i].address.province != info[i].address.city){
                    html += info[i].address.city;
                }
                html += info[i].address.county+info[i].address.address;
				html += '</div>';
				html += '</div>';
				html += '<div class="send" style="height:50px;"></div>';
				html += '<div class="send border-bot">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">进口口岸:</div>';
				html += '<div class="send_b fr">北京</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">原寄地:</div>';
				html += '<div class="send_e fr">美国</div>';
				html += '</div>';
				html += '</div> ';
				html += '<div class="send">';
				html += '<div class="send_n fl">';
				html += '<div class="send_a fl">客服电话:</div>';
				html += '<div class="send_b fr">11183</div>';
				html += '</div>';
				html += '<div class="send_n fr">';
				html += '<div class="send_d fl">关联单号：</div>';
				html += '<div class="send_e fr">'+sn[i]+'</div>';
				html += '</div>';
				html += '</div>';   	
				html += '</div>';
            html += '||';
        }
        prn1_print(html,sn);
    }
}
function prn1_print(data,sn){
    var LODOP; //声明为全局变量
    LODOP=getLodop();
    if(!confirm("您确定要批量打印吗？")) return;
    var strs= new Array(); //定义一数组
    strs = data.split("||"); //字符分割
    LODOP.PRINT_INIT("批量打印");
    var strStyleCSS="<link href='css/print.css' type='text/css' rel='stylesheet'>";
    for(var i=0;i<strs.length-1;i++){
        var strFormHtml=strStyleCSS+"<body>"+strs[i]+"</body>";
		LODOP.ADD_PRINT_HTM(2,2,"100%","100%",strFormHtml);
        LODOP.ADD_PRINT_BARCODE(6,174,210,49,"128A",sn[i]);
        LODOP.ADD_PRINT_BARCODE(457,88,235,47,"128A",sn[i]);
        LODOP.PRINT();
		//LODOP.PRINT_SETUP();
    }
}