<?php
$db_connection = mysql_connect('127.0.0.1', 'paperlens', 'paper1ens');
if (!$db_connection) {
    die('Could not connect: ' . mysql_error());
}
?>