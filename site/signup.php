<?php
require_once('../api/db.php');
require_once('./user/userfunc.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];
$keywords = $_POST["keywords"];

if(IsEmailExist($email))
{
	echo "<h2>This email have been used</h2>";
}

if(strpos($email, "@") === false)
{
        echo "<h2>Email address is invalid!</h2>";
        return;
}

if(strlen($keywords) == 0)
{
        echo "<h2>You must input research areas, seprated by comma</h2>";
        return;
}

if(strlen($_POST["password"]) < 6)
{
        echo "<h2>Password must exceed 6 characters</h2>";
        return;
}



mysql_query("replace into user (email,passwd,keywords) values ('" . $email . "', '".$password."', '" . $keywords . "');");

echo "SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'";
$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
if ($result) 
{
        $row = mysql_fetch_row($result);
        $uid = $row[0];
        session_start();
        $_SESSION["admin"] = true;
        $_SESSION["uid"] = $uid;
        Header("Location: index.php?uid=" . $uid);
}
?>