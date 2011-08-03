<?php
require_once("../../db.php");
require_once("related_items.php");

$id = $_GET['id'];
$tks = explode("|", $_GET['tables']);
$tables = array();

foreach($tks as $buf)
{
	$name_weight = explode("-", $buf);
	$tables[$name_weight[0]] = $name_weight[1];
}

$related_items = GetRelatedItemsFromMultiTables($id, $tables, 10);
header('Content-Type: text/xml');
echo '<relate>';
foreach($related_items as $id => $weight)
{
	//echo $id . ',' . $weight . '    ';
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $id) ;
}
echo '</relate>';

?>