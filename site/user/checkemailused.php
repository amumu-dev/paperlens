<?php

//how to return the json text??

require_once('../../api/db.php');

$email = $_GET['email'];
if($email == ""){
    $a = array('status'=>'good','desp'=>'email field empty');
    echo json_encode($a);
    return;
}

$result = mysql_query("select * from user where email='$email';");
if(mysql_num_rows($result) == 1){
    $a = array('status'=>'good');
    echo json_encode($a);
    return;
}
else{
    $a = array('status'=>'bad','desp'=>'email is not one,may be not used');
    echo json_encode($a);
    return;
}
?>
