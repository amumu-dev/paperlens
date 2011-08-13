function recommend(uid, paper, behavior, w, node_id)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
	
	node = document.getElementById(node_id);
	node.innerHTML = 'thanks!';
}

function browser(uid, paper, behavior, w, node_id)
{
	node_body = document.getElementById("feedbackcode");
	alert('browser');
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
}

function google_search(uid, paper, behavior, w, node_id)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&w=' + w + ' />';
}

function related(uid, dst_id, src_id, w, node_id)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/related.php?uid=' + uid + '&src=' + src_id + '&dst=' + dst_id + '&w=' + w + ' />';
	
	node = document.getElementById(node_id);
	node.innerHTML = 'thanks!';
}

function showMore()
{
	node_show_more = document.getElementById("show_more");
	node_show_more.innerHTML = '';
	node_paper_more = document.getElementById("paper_more");
	node_paper_more.style.display = 'block';
}
