function recommend(uid, paper, behavior, w, node_id, paper_title, query)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/behavior.php?uid=' + uid + '&paper=' + paper + '&behavior=' + behavior + '&search_query=' + query + '&w=' + w + ' />';
	
	node = document.getElementById('recommend' + node_id);
	//title = 'Recommend%20' + paper_title + '%20http://reculike.com/site/paper.php?id=' + paper + '%20in%20RecULike';
	//node.innerHTML = '<a href=\"http://v.t.sina.com.cn/share/share.php?title=' + title + '\" target=\"_blank\">Share to Sina</a>';
	node.innerHTML = 'thanks';
	
	//text_node = document.getElementById('recommend_text_' + node_id);
	//text_node.style.display = "block";
}

function bookmark(uid, paper, node_id, query)
{
	node_body = document.getElementById("feedbackcode");
	node_body.innerHTML = '<img width=0 height=0 src=/site/behavior/bookmark.php?uid=' + uid + '&paper=' + paper + '&search_query=' + query + ' />';
	
	node = document.getElementById(node_id);
	node.innerHTML = 'You can read it later';
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
