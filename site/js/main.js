function recommend(uid, paper, behavior, w)
{
	node_body = document.getElementsByTagName("body")[0];
	alter('<img width=0 height=0 src=/site/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />');
	node_body.innerHTML += '<img width=0 height=0 src=/site/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
}