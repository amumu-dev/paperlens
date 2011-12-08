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
	var strCookie = "his=";
	var exist = 0;
	for(var i = 0; i < 100 && i < tks.length; i++)
	{
		if(tks[i] == feed_id){
			exist = 1;
			continue;
		}
		strCookie += tks[i] + "_";
	}
	if(exist == 1)
	{
		document.cookie = strCookie;
		var node = document.getElementById('feed_' + feed_id)
		node.innerHTML = "订阅";
		node.style.background = "#000";
	}
	else
	{
		document.cookie = strCookie + feed_id;
		var node = document.getElementById('feed_' + feed_id)
		node.innerHTML = "取消";
		node.style.background = "#AAA";
	}
}

function addLoadHistory(feed_id)
{
	oldHistory = getCookie("loadhis");
	var tks = oldHistory.split("_");
	var strCookie = "loadhis=" + feed_id;
	for(var i = 0; i < 100 && i < tks.length; i++)
	{
		if(tks[i] == feed_id) continue;
		strCookie += "_" + tks[i];
	}
	document.cookie = strCookie;
}

function deleteHistory(feed_id)
{
	document.cookie = "his=";
	document.cookie = "loadhis=";
}

function getHistory()
{
	return getCookie("his");
}