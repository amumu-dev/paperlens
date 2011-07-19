<?php
require_once("../../db.php");
require_once("default_related_items.php")

$id = $_GET['id'];
$related_items = GetRelatedItems($id, 10);
header('Content-Type: text/xml');
echo '<relate>';
foreach($related_items as $id => $weight)
{
	//echo $id . ',' . $weight . '    ';
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $id) ;
}
echo '</relate>';
?>