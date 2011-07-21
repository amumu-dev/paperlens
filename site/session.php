<?php
$login = FALSE;
$uid = -1;
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true)
{
	$login = TRUE;
	if(isset($_GET["uid"]))
		$uid = $_GET["uid"];
}
if($uid < 0) $login = FALSE;
if(!$login) Header("Location: index.php");
?>