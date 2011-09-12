<?php

require_once("../lib/PHPMailer/class.phpmailer.php");

function send_mail($to_address, $to_name ,$subject, $body)
{
	$mail = new PHPMailer();
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->CharSet = 'utf-8';
	$mail->Encoding = 'base64';
	$mail->From = 'reculike@163.com';
	//$mail->SMTPSecure = "ssl";
	$mail->FromName = 'reculike';
	$mail->Host = 'smtp.163.com';
	$mail->Port = 25; //default is 25, gmail is 465 or 587
	$mail->SMTPAuth = true;
	$mail->Username = 'reculike@163.com';
	$mail->Password = 'RecULike';
	$mail->addAddress($to_address, $to_name);
	$mail->WordWrap = 50;
	$mail->IsHTML(false);
	$mail->Subject = $subject;
	$mail->Body = $body;
	if(!$mail->Send())
	{
		echo "Mail send failed.\r\n";
		echo "Error message: " . $mail->ErrorInfo;
		return false;
	}
	else
	{
		return true;
	}
}

send_mail("xlvector@gmail.com", "xlvector", "Hello", "Hello");

?>