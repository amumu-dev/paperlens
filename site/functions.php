<?php

function renderFirstPapers($papers_dom)
{
	foreach($papers_dom as $paper)
	{
		echo "<div class=\"paper\">";
		$paper_id = $paper->getElementsByTagName('id')->item(0)->nodeValue;
		$title = $paper->getElementsByTagName('title');
		echo "<span class=\"title\"><a href=/site/paper.php?id=".$paper_id.">" . $title->item(0)->nodeValue . "</a></span><br />";
		$booktitle = $paper->getElementsByTagName('booktitle');
		$year = $paper->getElementsByTagName('year');
		if(strlen($booktitle->item(0)->nodeValue) > 0)
			echo "<span class=\"info\"><a href=/site/paper.php?id=".$paper_id.">" . $booktitle->item(0)->nodeValue . "</a>&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
		$authors = $paper->getElementsByTagName('author');
		$k = 0;
		echo "<span class=\"author\">by&nbsp;";
		while($author = $authors->item($k++) )
		{
			$author_id = $author->getElementsByTagName('id')->item(0)->nodeValue;
			$author_name = $author->getElementsByTagName('name')->item(0)->nodeValue;
			echo "<a href=/site/author.php?author=".str_replace(' ','+', $author_name).">" . $author_name . "</a>&nbsp;";
			if(!array_key_exists($author_name, $related_authors))
			{
				$related_authors[$author_name] = 1;
			}
			else
			{
				$related_authors[$author_name]++;
			}
		}
		echo "</span><br />";
		echo "</div>";
	}
	return $related_authors;
}

function renderPapers($papers_dom)
{
	$related_authors = array();
	$j = 0;
	foreach($papers_dom as $paper)
	{
		++$j;
		echo "<div class=\"paper\">";
		$paper_id = $paper->getElementsByTagName('id')->item(0)->nodeValue;
		$title = $paper->getElementsByTagName('title');
		echo "<span class=\"title\"><a href=/site/paper.php?id=".$paper_id.">" . strTruncate($title->item(0)->nodeValue, 85) . "</a></span><br />";
		$booktitle = $paper->getElementsByTagName('booktitle');
		$year = $paper->getElementsByTagName('year');
		if(strlen($booktitle->item(0)->nodeValue) > 0)
			echo "<span class=\"info\"><a href=/site/paper.php?id=".$paper_id.">" . $booktitle->item(0)->nodeValue . "</a>&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
		$authors = $paper->getElementsByTagName('author');
		$k = 0;
		echo "<span class=\"author\">by&nbsp;";
		while($author = $authors->item($k++) )
		{
			$author_id = $author->getElementsByTagName('id')->item(0)->nodeValue;
			$author_name = $author->getElementsByTagName('name')->item(0)->nodeValue;
			echo "<a href=/site/author.php?author=".str_replace(' ','+', $author_name).">" . $author_name . "</a>&nbsp;";
			if(!array_key_exists($author_name, $related_authors))
			{
				$related_authors[$author_name] = 1;
			}
			else
			{
				$related_authors[$author_name]++;
			}
		}
		echo "</span><br />";
		echo "<span class=feedback><font color=#647B0F>&#9679;&nbsp;</font><a id=\"recommend" .$j. "\" onclick=\"recommend('" . $_SESSION['uid'] 
			. "','" . $paper_id. "', '1', '1', 'recommend" . $j . "')\">Recommend</a>&nbsp;"
			. "<font color=#FFCC00>&#9679;&nbsp;</font><a id=\"google" .$j. "\" onclick=\"google_search('" . $_SESSION['uid'] 
			. "','" . $paper_id. "', '2', '1', 'google" . $j . "')\" href=\"http://www.google.com/search?hl=en&q="
			. str_replace('', '+', $title->item(0)->nodeValue) . "\" target=_blank>Google It</a>&nbsp;</span>";
		echo "</div>";
	}
	return $related_authors;
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