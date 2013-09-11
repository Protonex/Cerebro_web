<?php 
header('Access-Control-Allow-Origin: *'); 
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("../var.php");
include(CBASE."common.php");

if(!$_POST['d']){die();}



$raw_data = file_get_contents("php://input");
parse_str(urldecode($raw_data),$out) ;

$playerdata = (array)json_decode($out['d']);
//$playernames = (array)json_decode($out['p']);

//foreach($playernames as $k=>$value){ $USERS[$value] = $k;}
/*
if( strlen( $USERS[$playerdata['nickname']])>20 ){
		#userguid = known.
		$playerdata['guid']=$USERS[$playerdata['nickname']];
	}else {
		#userguid is unknown
		$sql = "SELECT * FROM ingress_players WHERE name='".addslashes($playerdata['nickname'])."'";
		$res = $ob_database->get_single($sql); 	
		if(!$res){
			#user nickname is unknown, go play some ingress!
			$_OUT = array();
			$_OUT['status'] = "ok";
			//$_OUT['statusmsg'] = "user nickname is unknown, go play some ingress!";
			
			die(json_encode($_OUT));	
		} else {
			
			//if($playerdata['email']!=$res['email'] AND !is_null($res['email'])){
			//	$_OUT = array();
			//	$_OUT['status'] = "error";
			//	$_OUT['statusmsg'] = "wtf? are you trying to hack me?";
			//	die(json_encode($_OUT));
			//}else{
				$playerdata['guid'] = $res['guid'];
			//}
			
			#user is known.
			
		}
	}




*/




#echo "!";
#echo $USERS[$playerdata['nickname']];



#check if user is verified.
$ss= "SELECT * FROM ingress_verified WHERE email='".addslashes($playerdata['email'])."'";
$usr = $ob_database->get_single($ss);




$sql = "SELECT guid FROM ingress_players WHERE guid='".addslashes($usr['guid'])."'";
#echo $sql;
$ress = $ob_database->get_single($sql);

if($ress==NULL & strlen($usr['guid'])>8 ){
	
	
	$sql = "SELECT nickname,team FROM ingress_playerdata WHERE guid='".$usr['guid']."' AND guid<>''";
	$temp = $ob_database->get_single($sql);
	#echo $usr['guid'];
	#echo $temp['nickname'];
	
	$sql = "INSERT INTO ingress_players (`guid` ,`name` ,`level` ,`faction` )
	VALUES ( '".addslashes($usr['guid'])."', '".addslashes($temp['nickname'])."', '0', '".addslashes($temp['team'])."' );";
	//$sql = "UPDATE ingress_verified SET guid='".addslashes($playerdata['guid'])."' WHERE email='".addslashes($playerdata['email'])."' ";
	$ob_database->execute($sql);
	#echo $usr['name'];
	#var_dump($temp['nickname']);
	#die();	
}






#print_r($usr['guid']);



if($usr==NULL){ /*nonverified user*/
	
	$sql= "SELECT * FROM web_users WHERE email='".addslashes($playerdata['email'])."'";
	$usr = $ob_database->get_single($sql);
	if($usr==NULL){ error_log("unknown user!");die(); }
	
	
	$sql = "INSERT INTO ingress_verified (`nickname`,`email`,`faction`,`guid`,`google`)VALUES('".addslashes($playerdata['nickname'])."','".addslashes($playerdata['email'])."','".addslashes($playerdata['team'])."','NULL','".addslashes( json_encode($usr) )."')";
	$res = $ob_database->execute($sql);





	if($res){
		$_OUT['status'] = "firstrun";
		$x = json_encode($_OUT);
		die($x);
	}else{
		$_OUT['status'] = "error";
		$x = json_encode($_OUT);
		die($x);	
	}	

}
#echo "<pre>";
#print_r($playerdata['guid']);

#echo "</pre>";
//var_dump($usr);
if($usr['guid']=="NULL"||$usr['guid']==""){ 
	$sql = "UPDATE ingress_verified SET guid='".addslashes($playerdata['guid'])."' WHERE email='".addslashes($playerdata['email'])."' ";
	//echo $sql;
	$ob_database->execute($sql);
	
	

	
}
//die();

function intelitemcheckkeys($agentid){
	global $ob_database;
	if ($stream = fopen('http://ingress-item-check.appspot.com/api/getkey/'.$agentid, 'r')) {
		$data = json_decode( stream_get_contents($stream) );
		fclose($stream);
	}
	//if(!is_object($data->nickname)){ return; }
	$nickname = $data->nickname;
	$keys = (array)$data->portalKeys;	
	if(count($keys)<1){return;}
	$sql = "DELETE FROM ingressv2_keys WHERE `nickname`='".addslashes($nickname)."'";
	$ob_database->execute($sql);
	$t=1;
	foreach ($keys as $key=>$value){
		$sql = "INSERT INTO ingressv2_keys (`nickname`,`portalguid`,`keyamount`) VALUES('".addslashes($nickname)."','".addslashes($key)."','".addslashes($value)."')";
		if($ob_database->execute($sql)) { $t++; };
	}
	return $t;
}

$sql = "INSERT INTO ingress_playerdata (`guid`,`email`,`available_invites`,`energy`,`ap`,`team`,`action`,`nickname`)VALUES('".addslashes($playerdata['guid'])."','".addslashes($playerdata['email'])."','".addslashes($playerdata['available_invites'])."','".addslashes($playerdata['energy'])."','".addslashes($playerdata['ap'])."','".addslashes($playerdata['team'])."','intel-login','".addslashes($playerdata['nickname'])."')";
$res = $ob_database->execute($sql);

if($res){ 
	
	
	
//	$ss= "SELECT * FROM ingressv2_verified WHERE email='".addslashes($playerdata['email'])."'";
//	$usr = $ob_database->get_single($ss);
	
	
	if($usr){
		$t= (array)json_decode($usr['settings']);
		foreach($t as $k=>$v){
			$_OUT['plugin'][]=$k;	
		}
		//$_OUT['itemcheckuserid']=$t['item-check-userid'];
		
		//intelitemcheckkeys($t['item-check-userid']);
		
		
		
	}else {
	
	
	
	
	$_OUT['plugin'][] = "ap-list";
	$_OUT['plugin'][] = "compute-ap-stats";
	#$_OUT['plugin'][] = "draw-tools";
#	$_OUT['plugin'][] = "guess-player-levels";
#	$_OUT['plugin'][] = "ipas-link";
#	$_OUT['plugin'][] = "keys-on-map";
#	$_OUT['plugin'][] = "keys";	
#	$_OUT['plugin'][] = "portal-level-numbers";
#	$_OUT['plugin'][] = "resonator-display-zoom-level-decrease";	
	$_OUT['plugin'][] = "scoreboard";
	
	#$_OUT['plugin'][] = "max-links";
	#$_OUT['plugin'][] = "pan-control";
	$_OUT['plugin'][] = "player-tracker";
	/*
	$_OUT['plugin'][] = "portal-counts";
	$_OUT['plugin'][] = "portals-list";
	$_OUT['plugin'][] = "privacy-view";	
	$_OUT['plugin'][] = "render-limit-increase";	
	$_OUT['plugin'][] = "reso-energy-pct-in-portal-detail";	

	$_OUT['plugin'][] = "scale-bar";	
	;	
	$_OUT['plugin'][] = "show-address";	
	$_OUT['plugin'][] = "show-linked-portals";	
	$_OUT['plugin'][] = "show-portal-weakness";	
	$_OUT['plugin'][] = "zoom-slider";	
	
	*/
	}
	$_OUT['status'] = "ok";
	$x = json_encode($_OUT);
	die($x); 
	
}else {
	echo $sql;
	die("error");
	
}
echo "<pre>";
print_r($playerdata);
print_r($playernames);
die();


$data = json_decode($_POST['d']);

echo "!!!<pre>";
print_r($data);



function o2a($obj) {
	if(!is_array($obj) && !is_object($obj)) return $obj;
	if(is_object($obj)) $obj = get_object_vars($obj);
	return array_map(__FUNCTION__, $obj);
}


?>