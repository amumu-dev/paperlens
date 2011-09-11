<?php

$redis_options = array(
    'namespace' => 'relateditems_',
    'servers'   => array(
       array('host' => '127.0.0.1', 'port' => 6379)
    )
);
$redis_updatecallcount = 20;
$redis_maxcount = 2000;

function GetRelatedItemsFromDB($item, $table_name, $topN)
{
	if($table_name == "default") return GetDefaultRelatedItems($item, "default", $topN);
	$ret = array();
	$result = mysql_query("select dst_id,weight from " . $table_name . " where src_id=" . $item . " order by weight desc limit " . $topN);
	if (!$result)
	{
		return $ret;
	}
	while ($row = mysql_fetch_row($result))
	{
		$dst_id = $row[0];
		$weight = $row[1];
		$ret[$dst_id] = $weight;
	}
	return $ret;
}

function GetRelatedItems($item,$table_name,$topN){
    global $redis_updatecallcount;
    global $redis_options;
    $rediska = new Rediska($redis_options);//need to check the link status, not implement
    if((int)($rediska->get("rcallcount")) > $redis_updatecallcount){
	//$cmd = "nohup php /data/www/test/api/recommendation/relate/redis_update_related_items.php ";
        //exec($cmd);
	$btime = time();
	updateRedisRelatedItems();
	$etime = time();
	$rediska->set("rupdatetime",$etime-$btime);
        $rediska->set("rcallcount",0);
    }

    $key = "r_".$table_name."_".$item."_".$topN;
    $countkey = "rc_".$table_name."_".$item."_".$topN;
    //check key exist
    if($rediska->exists($key)){
        $tmpresult = json_decode($rediska->get($key),true);
        $rediska->increment($countkey);
        return $tmpresult;
    }
    
    //check the topN number is bigger than the $topN
    $regex = "r_".$table_name."_".$item."_*";
    $keys = $rediska->getKeysByPattern($regex);
    foreach($keys as $tkey){
        //if the n is bigger than the given
        $numindex = strrchr($tkey,'_');
        $count = int(substr($tkey,$numindex+1,strlen($tkey)-$numindex));
        if($count > $topN){
            //create the result
            $tmplist = json_decode($rediska->get($tkey),true);
            $tmpcount = 0;
            $tmpresult = array();
            foreach($tmplist as $k=>$v){
                $tmpresult[$k] = $v;
                $tmpcount += 1;
                if($tmpcount > $topN){
                    break;
                }
            }

            //store the result in redis
            $rediska->setAndExpire($key,json_encode($tmpresult),24*3600);
		if(!$rediska->exists($countkey)){//do not clear the count
        	    $rediska->set($countkey,1);#count has no time limit
		}
    		$rediska->increment("rcallcount");
            return $tmpresult;
        }
    }

    //if no good, call GetRelatedItems 
    $tmpresult = GetRelatedItemsFromDB($item,$table_name,$topN);
    $rediska->setAndExpire($key,json_encode($tmpresult),24*3600);
	if(!$rediska->exists($countkey)){//do not clear the count
		$rediska->set($countkey,1);#count has no time limit,accumlate all 
	}
    $rediska->increment("rcallcount");//increment on change
    return $tmpresult;
}

function updateRedisRelatedItems(){
    global $redis_maxcount;
    global $redis_options;
    $rediska = new Rediska($redis_options);

    $keys = $rediska->getKeysByPattern("r_*");
    if(count($keys) < $redis_maxcount)return;#not many items in redis

    $tmpdic = array();//dic from the key to the count
    foreach($keys as $k)
    {
        $rckey = "rc".substr($k,1,strlen($k));
        $tmpdic[$k] = $rediska->get($rckey);
        //print $k.":".$rckey.":".$tmpdic[$k];
    }

    $curcount = 1;//delete the items according to the count,from 1 to 2,3...
        $curnum = count($keys);
    while(true){
        foreach($keys as $k){
                if((int)($tmpdic[$k]) == (int)($curcount)){
                //delete the value,count
                        $rediska->delete($k);
                        $rediska->delete("rc".substr($k,1,strlen($k)));
                        $curnum -= 1;
                }
                if($curnum < $redis_maxcount)break;
        }
        if($curnum < $redis_maxcount)break;
        $curcount += 1;
        if($curcount > $redis_maxcount)break;
    }
    return;
}


?>

