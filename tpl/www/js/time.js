var ddate=document.getElementById("ddate");
var ddate2=document.getElementById("ddate2");
function updatedate(){
	var dd1=new Date();
	dd1.setMinutes(dd1.getMinutes()+dd1.getTimezoneOffset()-420); //取当地时间加上和格林威治的时差减要求地区和格林的时差分钟，这里是-300，代表美国东部纽约和格林的时差
	//先设置setMinutes再取getHours才有效
	var month = dd1.getMonth()+1;
	//month =(month<10 ? "0"+month:month);
	if(month < 10)
	{
		month = '0'+month.toString();
	}
	var date = dd1.getDate();
	if(date < 10)
	{
		date = '0'+date.toString();
	}
	var hour = dd1.getHours();
	if(hour < 10)
	{
		hour = '0'+hour.toString();
	}
	var minutes = dd1.getMinutes();
	if(minutes < 10)
	{
		minutes = '0'+minutes.toString();
	}
	var seconds = dd1.getSeconds();
	if(seconds < 10)
	{
		seconds = '0'+seconds.toString();
	}
	ddate.innerHTML='美国西部时间：'+dd1.getFullYear()+"年"+month+"月"+date+"日 "+hour+":"+minutes+":"+seconds;
	var dd2=new Date();
	dd2.setMinutes(dd2.getMinutes()+dd2.getTimezoneOffset()+13*60-300);//纽约和中国相差13个小时，夏天是12个小时所以这里加上13*60 夏天改为12*60
	var month = dd2.getMonth()+1;
	//month =(month<10 ? "0"+month:month);
	if(month < 10)
	{
		month = '0'+month.toString();
	}
	var date = dd2.getDate();
	if(date < 10)
	{
		date = '0'+date.toString();
	}
	var hour = dd2.getHours();
	if(hour < 10)
	{
		hour = '0'+hour.toString();
	}
	var minutes = dd2.getMinutes();
	if(minutes < 10)
	{
		minutes = '0'+minutes.toString();
	}
	var seconds = dd2.getSeconds();
	if(seconds < 10)
	{
		seconds = '0'+seconds.toString();
	}
	ddate2.innerHTML='中国北京时间：'+ dd2.getFullYear()+"年"+month+"月"+date+"日 "+hour+":"+minutes+":"+seconds;
	var t=setTimeout("updatedate()",1000);
}
updatedate();