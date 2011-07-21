function recommend(uid, paper, behavior, w)
{
	node_body = document.getElementsByTagName("body")[0];
	node_body.innerHTML += '<script type=\'text/javascript\' src=/site/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
}