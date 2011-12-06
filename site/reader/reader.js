function getCookie(name){ 
	var strCookie=document.cookie; 
	var arrCookie=strCookie.split("; "); 
	for(var i=0;i<arrCookie.length;i++)
	{ 
		var arr=arrCookie[i].split("="); 
		if(arr[0]==name)return arr[1]; 
	} 
	return ""; 
}

function deleteCookie(name){ 
	var date=new Date(); 
	date.setTime(date.getTime()-10000); 
	document.cookie=name+"=; expire="+date.toGMTString(); 
}

function addHistory(feed_id)
{
	oldHistory = getCookie("his");
	var tks = oldHistory.split("_");
	var strCookie = "his=" + feed_id;
	for(var i = 0; i < 20 && i < tks.length; i++)
	{
		strCookie += "_" + tks[i];
	}
	document.cookie = strCookie;
	
	var node = document.getElementById('feed_' + feed_id)
	node.innerHTML = "лл";
}

function getHistory()
{
	return getCookie("his");
}