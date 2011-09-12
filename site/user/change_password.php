<?php

require_once("../../api/email/send.php");
require_once("../../api/db.php");

$email = $_GET['email'];
$key = time();
$body = "Hi <br>";
$body .= "You can change your password in <a href=\"http://www.reculike.com/user/reset_password.php?key=$key&email=$email\" target=_blank>http://www.reculike.com/user/reset_password.php?key=$key&email=$email</a><br>";
$body .= "Thanks! Reculike.com";
mysql_query("update user set password_key=$key where email='$email'");
if(!send_mail($email, $email, "Reset your password in reculike", $body)) return;
?>

<html><head><title>Reset Password - RecULike.com</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body><h2>You can check your email box to reset your password</h2></body></html>