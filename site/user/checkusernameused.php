<?php

include_once('userfunc.php');

$username = $_GET['username'];
if($username == ""){
    //invalid
    $a = array('status'=>'good','desp'=>'username field empty');
    echo json_encode($a);
    return;
}

if(isUserNameUsed($username)){
    $a = array('status'=>'good','desp'=>'uid');
    echo json_encode($a);
    return;
}
else{
    $a = array('status'=>'bad','desp'=>'username is not one,may be not used');
    echo json_encode($a);
    return;
}

?>

