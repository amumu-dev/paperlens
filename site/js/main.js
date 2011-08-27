function recommend(uid, paper, behavior, w, node_id, paper_title, query)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&search_query=' + query + '&w=' + w + ' />';
	
	node = document.getElementById(node_id);
	title = 'Recommend%20' + paper_title + '%20http://reculike.com/site/paper.php?id=' + paper + '%20in%20RecULike';
	
	input_node = document.createElement("div");
	input_node.style.display = "absolute";
	input_node.style.width = 500;
	input_node.style.height = 400;
	input_node.style.marginLeft = (document.body.clientWidth - 500) / 2;
	input_node.style.marginTop = (document.body.clientHeight - 400) / 2;
	input_node.style.border = "#888 solid 1px";
	input_node.innerHTML = "Hello World";
	node.innerHTML = '<a href=\"http://v.t.sina.com.cn/share/share.php?title=' + title + '\" target=\"_blank\">Share to Sina</a>';
}

function google_search(uid, paper, behavior, w, node_id, query)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&search_query=' + query + '&w=' + w + ' />';
}

function related(uid, dst_id, src_id, w, node_id)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/related.php?uid=' + uid + '&src=' + src_id + '&dst=' + dst_id + '&w=' + w + ' />';
	
	node = document.getElementById(node_id);
	node.innerHTML = 'thanks!';
}

function opensignup()
{
	node = document.getElementById("login");
	node.style.display = "none";
	
	node2 = document.getElementById("signup");
	node2.style.display = "block";
}

function showMore()
{
	node_show_more = document.getElementById("show_more");
	node_show_more.innerHTML = '';
	node_paper_more = document.getElementById("paper_more");
	node_paper_more.style.display = 'block';
}
