<?php

require_once("../../api/email/send.php");

$email = $_GET['email'];
$key = time();
$body = "Hi <br>";
$body = "You can change your password in <a href=\"http://www.reculike.com/user/reset_password.php?key=$key\">http://www.reculike.com/user/reset_password.php?key=$key</a><br>";
$body = "Thanks! Reculike.com";
if(!send_mail($email, $email, "Reset your password in reculike", $body)) return;
?>

<html><head><title>Reset Password - RecULike.com</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body><h2>You can check your email box to reset your password</h2></body></html>