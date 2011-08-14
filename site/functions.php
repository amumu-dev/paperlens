<?php

function renderFirstPaper($paper)
{
	echo "<div id=\"paper0\">";
	$paper_id = $paper->getElementsByTagName('id')->item(0)->nodeValue;
	$title = $paper->getElementsByTagName('title');
	echo "<h2 class=\"title\">" . $title->item(0)->nodeValue . "</h2><br />";
	$booktitle = $paper->getElementsByTagName('booktitle');
	$year = $paper->getElementsByTagName('year');
	if(strlen($booktitle->item(0)->nodeValue) > 0)
		echo "<span class=\"info\">" . $booktitle->item(0)->nodeValue . "&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
	$authors = $paper->getElementsByTagName('author');
	$k = 0;
	echo "<span class=\"author\">by&nbsp;";
	while($author = $authors->item($k++) )
	{
		$author_id = $author->getElementsByTagName('id')->item(0)->nodeValue;
		$author_name = $author->getElementsByTagName('name')->item(0)->nodeValue;
		echo "<a href=/site/author.php?author=".$author_id."&name=".str_replace(' ','+',$author_name).">" . $author_name . "</a>&nbsp;";
	}
	echo "</span><br />";
	echo "</div>";
}

function renderPaperFeedback($j, $title, $paper_id)
{
	echo "<span class=feedback><font color=#647B0F>&#9679;&nbsp;</font><a id=\"recommend" .$j. "\" onclick=\"recommend('" . $_SESSION['uid'] 
		. "','" . $paper_id. "', '1', '1', 'recommend" . $j . "')\">Recommend</a>&nbsp;"
		. "<font color=#FFCC00>&#9679;&nbsp;</font><a id=\"google" .$j. "\" onclick=\"google_search('" . $_SESSION['uid'] 
		. "','" . $paper_id. "', '2', '1', 'google" . $j . "')\" href=\"http://www.google.com/search?hl=en&q="
		. str_replace('', '+', $title->item(0)->nodeValue) . "\" target=_blank>Google It</a>&nbsp;</span>";
}

function renderRelatedFeedback($j, $title, $paper_id, $src_paper_id)
{
	echo "<span class=feedback><font color=#647B0F>&#9679;&nbsp;</font><a id=\"recommend" .$j. "\" onclick=\"recommend('" . $_SESSION['uid'] 
		. "','" . $paper_id. "', '1', '1', 'recommend" . $j . "')\">Recommend</a>&nbsp;"
		. "<font color=#FFCC00>&#9679;&nbsp;</font><a id=\"google" .$j. "\" onclick=\"google_search('" . $_SESSION['uid'] 
		. "','" . $paper_id. "', '2', '1', 'google" . $j . "')\" href=\"http://www.google.com/search?hl=en&q="
		. str_replace('', '+', $title->item(0)->nodeValue) . "\" target=_blank>Google It</a>&nbsp;"
		. "<font color=#FF0000>&#9679;&nbsp;</font><a id=\"related" .$j. "\" onclick=\"related('" . $_SESSION['uid'] 
		. "','" . $paper_id. "', '" . $src_paper_id . "', '1', 'related" . $j . "')\" >Related</a>&nbsp;"
		. "</span>";
}

function renderRecommendUsers($paper)
{
	$recusers = $paper->getElementsByTagName('user');
	$n = count($recusers);
	if($n == 0) return;
	echo $n;
	echo "<span class=\"recusers\">recommend by ";
	$k = 0;
	foreach($recusers as $user)
	{
		echo "<a href=/site/user.php>" . $user->nodeValue . "</a>&nbsp;";
		++$k;
		if($k >= 5) break;
	}
	if($n > 5) echo "and other " . $n - 5 . " users";
	echo "</span>";
}

function renderPapers($papers_dom, $src_paper_id = -1)
{
	$related_authors = array();
	$j = 0;
	foreach($papers_dom as $paper)
	{
		++$j;
		if($j == 11)
		{
			echo "<span id=show_more style=\"width:100%;float:left;text-align:center;display=block;\"><a style=\"cursor:pointer;\" onclick=\"showMore();\">More</a></span>";
			echo "<div id=paper_more style=\"display:none;\">";
		}
		echo "<div class=\"paper\" onmouseover=\"this.style.backgroundColor='#FFF8E7';\" onmouseout=\"this.style.backgroundColor='#FFF';\">";
		$paper_id = $paper->getElementsByTagName('id')->item(0)->nodeValue;
		$title = $paper->getElementsByTagName('title');
		echo "<span class=\"title\"><a href=/site/paper.php?id=".$paper_id.">" . strTruncate($title->item(0)->nodeValue, 85) . "</a></span><br />";
		$booktitle = $paper->getElementsByTagName('booktitle');
		$year = $paper->getElementsByTagName('year');
		if(strlen($booktitle->item(0)->nodeValue) > 0)
			echo "<span class=\"info\">" . $booktitle->item(0)->nodeValue . "&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
		$authors = $paper->getElementsByTagName('author');
		$k = 0;
		echo "<span class=\"author\">by&nbsp;";
		while($author = $authors->item($k++) )
		{
			$author_id = $author->getElementsByTagName('id')->item(0)->nodeValue;
			$author_name = $author->getElementsByTagName('name')->item(0)->nodeValue;
			echo "<a href=/site/author.php?author=".$author_id."&name=".str_replace(' ','+',$author_name).">" . $author_name . "</a>&nbsp;";
			if(!array_key_exists($author_id . "|" . $author_name, $related_authors))
			{
				$related_authors[$author_id . "|" . $author_name] = 1;
			}
			else
			{
				$related_authors[$author_id . "|" . $author_name]++;
			}
		}
		echo "</span><br />";
		renderRecommendUsers($paper);
		if($src_paper_id < 0) renderPaperFeedback($j, $title, $paper_id);
		else renderRelatedFeedback($j, $title, $paper_id, $src_paper_id);
		echo "</div>";
	}
	if($j > 11) echo "</div>";
	return $related_authors;
}

function renderRelatedAuthors($related_authors)
{
	arsort($related_authors);
	$related_authors = array_slice($related_authors, 0, 16);
	foreach($related_authors as $author=>$weight)
	{
		$id_name = explode("|", $author, 2);
		echo "<span class=\"author\"><a href=/site/author.php?author=".$id_name[0]."&name=".str_replace(' ','+',$id_name[1]).">" . $id_name[1] ."</a></span><br>";
	}
}

function strTruncate($buf, $maxLength)
{
	$tks = explode(" ", $buf);
	$ret = "";
	$all = TRUE;
	foreach($tks as $word)
	{
		if(strlen($ret) + strlen($word) >= $maxLength)
		{
			$all = FALSE;
			break;
		}
		$ret .= $word . " ";
	}
	if(!$all) $ret .= "...";
	return $ret;
}

?>