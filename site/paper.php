<?php
require_once('db.php');
$id = $_GET['id'];
$result = mysql_query('select title from paper where id='.$id);
if (!$result) {
    die('Query failed: ' . mysql_error());
}

$record = mysql_fetch_field($result, 0);

echo $record->title;

mysql_free_result($result);

?>