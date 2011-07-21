<?php
session_start();
unset($_SESSION['admin']);
Header("Location: index.php");
?>