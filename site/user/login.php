<?php
require_once('../../api/db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];

function IsEmailExist($mail)
{
        $result = mysql_query("select count(*) from user where email='$mail'");
        if($result)
        {
                $row = mysql_fetch_row($result);
                if($row[0] == 1) return TRUE;
        }
        return FALSE;
}

if(IsEmailExist($email))
{
        echo "exist email";
        $result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
        if ($result && mysql_num_rows($result) > 0)
        {
                $row = mysql_fetch_row($result);
                $uid = $row[0];
                echo $uid;
                session_start();
                $_SESSION["admin"] = true;
                $_SESSION["uid"] = $uid;
                $_SESSION["email"] = $email;
                Header("Location: index.php");
        } else {
                echo "User name and password error";
        }
}
else
{
        if(strpos($email, "@") === false)
        {
                echo "<h2>Email address is invalid!</h2>";
                return;
        }
        if(strlen($_POST["password"]) < 6)
        {
                echo "<h2>Password must exceed 6 characters</h2>";
                return;
        }
        echo "insert into user (email,passwd) values ('" . $email . "', '".$password."');";
        mysql_query("insert into user (email,passwd) values ('" . $email . "', '".$password."');");

        $result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
        if ($result && mysql_num_rows($result) > 0) 
        {
                $row = mysql_fetch_row($result);
                $uid = $row[0];
                session_start();
                $_SESSION["admin"] = true;
                $_SESSION["uid"] = $uid;
                $_SESSION["email"] = $email;
                Header("Location: index.php");
        }
}
?>