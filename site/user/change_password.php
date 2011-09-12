<?php

require_once("../../api/email/send.php");

$email = $_GET['email'];
$key = mktime();
$body = "Hi <br>";
$body = "You can change your password in <a href=\"http://www.reculike.com/user/reset_password.php?key=$key\">http://www.reculike.com/user/reset_password.php?key=$key</a><br>";
$body = "Thanks! Reculike.com";
send_mail($email, $email, "Reset your password in reculike", $body);

echo "<h2>You can check your email box to reset your password</h2>";
?>