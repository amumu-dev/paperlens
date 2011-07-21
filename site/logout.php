<?php
session_start();
unset($_SESSION['admin']);
unset($_SESSION['uid']);
unset($_SESSION['email']);
Header("Location: index.php");
?>