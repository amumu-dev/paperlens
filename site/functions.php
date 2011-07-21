<?php

function renderPapers($papers_dom)
{
	$related_authors = array();
	foreach($papers_dom as $paper)
	{
		echo "<div class=\"paper\">";
		$title = $paper->getElementsByTagName('title');
		echo "<span class=\"title\"><a href=/site/paper.php>" . strTruncate($title->item(0)->nodeValue, 85) . "</a></span><br />";
		$booktitle = $paper->getElementsByTagName('booktitle');
		$year = $paper->getElementsByTagName('year');
		if(strlen($booktitle->item(0)->nodeValue) > 0)
			echo "<span class=\"info\"><a href=/site/paper.php>" . $booktitle->item(0)->nodeValue . "</a>&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
		$authors = $paper->getElementsByTagName('author');
		$k = 0;
		echo "<span class=\"author\">by&nbsp;";
		while($author = $authors->item($k++) )
		{
			echo "<a href=/site/author.php>" . $author->nodeValue . "</a>&nbsp;";
			if(!array_key_exists($author->nodeValue, $related_authors))
			{
				$related_authors[$author->nodeValue] = 1;
			}
			else
			{
				$related_authors[$author->nodeValue]++;
			}
		}
		echo "</span><br />";
		echo "<span class=feedback><font color=#647B0F>&#9679;&nbsp;</font><a>Recommend</a>&nbsp;"
			. "<font color=#FFCC00>&#9679;&nbsp;</font><a>Like</a>&nbsp;"
			. "<font color=#BE1A21>&#9679;&nbsp;</font><a>Dislike</a>&nbsp;</span>";
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