<?php
session_start();
require_once('session.php');
require_once('../api/db.php');
$keywords = $_POST["keywords"];

mysql_query("update user set keywords='$keywords' where id=$uid;");
Header("Location: index.php");
?>