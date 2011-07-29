<?php
$link = $_GET['link'];
echo file_get_contents(urldecode($link));
?>