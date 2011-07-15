<?php
$link = mysql_connect('127.0.0.1', 'paperlens', 'paper1ens');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
?>