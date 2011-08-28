<?php
require_once("../../api/db.php");
$paper_id = $_POST['paper_id'];
$user_id = $_POST['user_id'];
$message = $_POST['message'];
mysql_query("insert into recommend (user_id, paper_id, created_at, message) values ($user_id, $paper_id, '" . date("Y-m-d H:i:s") . "', '" . urlencode($message). "');");
?>