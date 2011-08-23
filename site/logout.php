<?php
session_start();
unset($_SESSION['admin']);
unset($_SESSION['uid']);
unset($_SESSION['email']);
if(isset($_SESSION['query'])) unset($_SESSION["query"]);
Header("Location: index.php");
?>