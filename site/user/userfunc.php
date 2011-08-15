<?php

$db_php_path = "../../api/db.php";


function IsEmailExist($mail)
{
    global $db_php_path;
    require_once($db_php_path);
	$result = mysql_query("select count(*) from user where email='$mail'");
	if($result)
	{
		$row = mysql_fetch_row($result);
		if($row[0] == 1) return TRUE;
	}
	return FALSE;
}

function isUserNameUsed($username){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select count(*) from user where username='$username';");
    if($result){
        $row = mysql_fetch_row($result);
        if($row[0] == 1){
            return true;
        }
        elseif($row[0] == 0){
            return false;
        }
        else{
            throw New Exception("one user name corresponse to more than a user");
        }
    }
    else{
        throw New Exception("check username error.".mysql_error());
    }
    return false;
}


function checkSinaIdExist($sid)
{
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select * from user where sinaid=".$sid);
    $num = mysql_num_rows($result);
    if($num == 1){
        return true;
    }    
    elseif($num == 0){
        return false;
    }
    else{
        throw new Exception("one sina id corresponse to more than one line");
    }
}

function checkDoubanIdExist($douban_id){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select * from user where doubanid=".$douban_id);
    $num = mysql_num_rows($result);
    if($num == 1){
        return true;
    }    
    elseif($num == 0){
        return false;
    }
    else{
        throw new Exception("one douban id corresponse to more than one line");
    }

}

/*
 * get uid by sinaid
 */
function getUidbySuid($sid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select id from user where sinaid=".$sid);
    $num = mysql_num_rows($result);
    if($num == 0){
        return -1;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    else{
        throw new Exception("one sina id coresponse to more than one line");
    }
}

function getUidbyDuid($did){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select id from user where doubanid=".$did);
    $num = mysql_num_rows($result);
    if($num == 0){
        return -1;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    else{
        throw new Exception("one douban id coresponse to more than one line");
    }
}

function getEmailbySuid($sid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select email from user where sinaid=".$sid);
    $num = mysql_num_rows($result);
    if($num == 0){
        return -1;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    else{
        throw new Exception("one sina id coresponse to more than one line");
    }
}

function getEmailbyDuid($did){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select email from user where doubanid=".$did);
    $num = mysql_num_rows($result);
    if($num == 0){
        return -1;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    else{
        throw new Exception("one douban id coresponse to more than one line");
    }
}


function getEmailbyUid($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select email from user where id=".$uid);
    $num = mysql_num_rows($result);
    if($num == 0){
        return -1;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        return $row[0];
    }
    else{
        throw new Exception("one id coresponse to more than one line");
    }

}


function initSinaUser($sid,$sname,$sackey,$sacsec)
{
    //return the uid
    if(getUidbySuid($sid) != -1){
        return getUidbySuid($sid);
    }

    global $db_php_path;
    require_once($db_php_path);
    
    $thesql = "insert into user (sinaid,sinaname,sinaackey,sinaacsec) values ('".$sid."','".$sname."','".$sackey."','".$sacsec."');";
    if(!mysql_query($thesql)){
        throw new Exception("mysql insert error".mysql_error()."sql:".$thesql);
    }

    return getUidbySuid($sid);
}

function initDoubanUser($did,$dname,$dackey,$dacsec){
    if(getUidbyDuid($did) != -1){
        return getUidbyDuid($did);
    }

    global $db_php_path;
    require_once($db_php_path);
    
    $thesql = "insert into user (doubanid,dname,dackey,dacsec) values ('".$did."','".$dname."','".$dackey."','".$dacsec."');";
    if(!mysql_query($thesql)){
        throw new Exception("mysql insert error".mysql_error()."sql:".$thesql);
    }

    return getUidbyDuid($did);

}



function checkSinaUserLinked($sid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select passwd from user where sinaid=".$sid);
    $num = mysql_num_rows($result);
    if($num == 0){
        return false;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        if($row[0] != NULL){
            return true;
        }else{
            return false;
        }
    }
    else{
        throw new Exception("one sina id coresponse to more than one line");
    }
}

function checkDoubanUserLinked($did){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select passwd from user where doubanid=".$did);
    $num = mysql_num_rows($result);
    if($num == 0){
        return false;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        if($row[0] != NULL){
            return true;
        }else{
            return false;
        }
    }
    else{
        throw new Exception("one douban id coresponse to more than one line");
    }

}

/*
 * cur con user is signup with what account
 */
function getLinkingType($linkinguid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select sinaid,doubanid from user where id='$linkinguid';");
    if(!$result){
        throw New Exception("check linking type error.".mysql_erro());
    }
    else{
        if(mysql_num_rows($result) == 0)
            throw New Exception("no result for linking uid".$linkinguid);
        $row = mysql_fetch_row($result);
        if($row[0] != NULL){
            return "sina";
        }

        if($row[1] != NULL){
            return "douban";
        }
    }
    return "error";
}

/*
 * check the user valid by id
 */
function checkUserValidbyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    throw New Exception("not implement");
}

function checkDoubanLinkedbyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select doubanid from user where id=".$uid);
    if(!$result){
        die("mysql error when get sina id by uid.".mysql_error());
    }
    $row = mysql_fetch_row($result);
    if($row[0] != NULL && $row[0] != ""){
        return true;
    }
    else{
        return false;
    }
}

function checkSinaLinkedbyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select sinaid from user where id=".$uid);
    if(!$result){
        die("mysql error when get sina id by uid.".mysql_error());
    }
    $row = mysql_fetch_row($result);
    if($row[0] != NULL && $row[0] != ""){
        return true;
    }
    else{
        return false;
    }
}

function getUserNamebyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select username from user where id=".$uid);
    if(!$result){
        die("mysql error when get sina id by uid.".mysql_error());
    }
    $row = mysql_fetch_row($result);
    return $row[0];
}

/*
 * get SinaInfo by uid
 * sinaid
 * sinaname
 * sinaackey
 * sinaacsec
 */
function getSinaInfobyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select sinaid,sinaname,sinaackey,sinaacsec from user where id='$uid';");
    if(!$result){
        die("query error".mysql_error());
        return;
    }
    $row = mysql_fetch_row($result);
    $a = array("sinaid"=>$row[0],"sinaname"=>$row[1],"sinaackey"=>$row[2],"sinaacsec"=>$row[3]);
    return $a;
}

/*
 * get DoubanInfo by uid
 * doubanid
 * dname
 * dackey
 * dacsec
 */
function getDoubanInfobyId($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("select doubanid,dname,dackey,dacsec from user where id='$uid';");
    if(!$result){
        die("query error".mysql_error());
        return;
    }
    $row = mysql_fetch_row($result);
    $a = array("doubanid"=>$row[0],"dname"=>$row[1],"dackey"=>$row[2],"dacsec"=>$row[3]);
    return $a;
}

/*
 * init a user with email account and password
 */
function getUidbyEmailPwd($email,$pwd){
    global $db_php_path;
    require_once($db_php_path);
    $pwd = MD5($pwd);
    $result = mysql_query("select id from user where email='$email' and passwd='$pwd';");
    if(!$result){
        throw New Exception("get id by email pwd error");
    }
    if(mysql_num_rows($result) == 0){
        return NULL;
    }

    $row = mysql_fetch_row($result);
    if($row){
        return $row[0];
    }
    else{
        return NULL;
    }
}

/*
 * session operation deal with the login of a user
 */
function loginUser($uid){
    global $db_php_path;
    require_once($db_php_path);
    $result = mysql_query("SELECT id,email FROM user WHERE id='$uid';");
    if ($result && mysql_num_rows($result) > 0) 
    {
	$row = mysql_fetch_row($result);
	session_start();
	$_SESSION["admin"] = true;
	$_SESSION["uid"] = $row[0];
	$_SESSION["email"] = $row[1];
    }
}

/*
 * check whether the user has already fill the passwd info
 * link to a site user
 * */
function checkUserLinkedbyId($id){
    global $db_php_path;
    require_once($db_php_path);
    
    $result = mysql_query("select passwd from user where id=".$id);
    $num = mysql_num_rows($result);
    if($num == 0){
        return false;
    }
    elseif($num == 1){
        $row = mysql_fetch_row($result);
        if($row[0] != NULL){
            return true;
        }else{
            return false;
        }
    }
    else{
        throw new Exception("one sina id coresponse to more than one line");
    }
}

/*
 * used when connect to a site user
 * */
function updateUserInfo($uid,$username,$email,$pwd){
        //check pwd NULL
        if(checkUserLinkedbyId($uid)){
            throw New Exception($uid."is already linked");
        }
        else{
            global $db_php_path;
            require_once($db_php_path);
            $pwd = MD5($pwd);
            $thesql = "update user set username='$username',email='$email',passwd='$pwd' where id='$uid';";
            $result = mysql_query($thesql);
            if(!$result){
                throw new Exception("error when updating to link a user".mysql_error());
            }
        }
}

/*
 * update the ac key and sec of an user
 */
function updateSinaAC($uid,$ackey,$acsec){
    global $db_php_path;
    require_once($db_php_path);
    $thesql = "update user set sinaackey='$ackey',sinaacsec='$acsec' where id='$uid';";
    $result = mysql_query($thesql);
    if(!$result){
        throw New Exception("error when updating key info".mysql_error());
    }
}

function updataDoubanAC($uid,$ackey,$acsec){
    global $db_php_path;
    require_once($db_php_path);
    $thesql = "update user set dackey='$ackey',dacsec='$acsec' where id='$uid';";
    $result = mysql_query($thesql);
    if(!$result){
        throw New Exception("error when updating key info".mysql_error());
    }

}

?>
