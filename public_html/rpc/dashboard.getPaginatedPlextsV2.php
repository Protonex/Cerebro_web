<?php
#header('X-Error-Message: Not implemented... yet!', true, 500);

header('Access-Control-Allow-Origin: *'); 
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/javascript');

//error_log("dashboard.getPaginatedPlextsV2");

if(!$_POST['guid']){ die('playerid error');}



//	application/json
$noauth=true;
include("../var.php");
include(CBASE."common.php");

#functions
include(CBASE."rpc/functions/GEOtoE6.php");
include(CBASE."rpc/functions/E6toGEO.php");
include(CBASE."rpc/class/dblog.php");

#classes
include(CBASE."rpc/class/class.postdata.php");
include(CBASE."rpc/class/class.player.php");
include(CBASE."rpc/class/class.plext.userarray.php");
include(CBASE."rpc/class/class.plexts.php");
#normalize/check postdata.
$ob_postdata = new postdata();
$RAW = $ob_postdata->checkpostdata();

if(!$ob_postdata->check_json($RAW)){ die("INVALID JSON"); }


#check player
$player = new player();
$playerinfo  = $player->get_by_guid($RAW->guid);
#print_r($player->log);


#print_r($playerinfo);
$playerinfo['guid'];
$playerinfo['faction'];
$playerinfo['nick'];
$playerinfo['level'];
$playerinfo['lastupdated'];
$playerinfo['lastlocation'];

#some check
if(!$playerinfo) { die("no such user known."); }


//  $RAW->guid ; submitterGUID
//  $RAW->gameBasket ; 
//  $RAW->result ; 


#log
$nickname= addslashes("");
$email= addslashes("");
$faction= addslashes("");
$guid	= addslashes($RAW->guid);
$data= addslashes(formatBytes($_SERVER['CONTENT_LENGTH']) );
$action= addslashes('comms-submit');
$remote_addr= addslashes($_SERVER['REMOTE_ADDR']);
$lat= NULL;
$lng= NULL;



# ?
$USER = $playerinfo['nick'];







# check data
if( count($RAW->result[0])<2 ){
//	print_r($RAW);	
	die("bad data");
}




# set plguid
$plguid = trim($playerinfo['guid']);
	
	



# create userobject for mined-users
$ob_user = new userarray();






# initialize plexts class
$ob_plexts = new plexts();




#echo count($RAW->result)."items.";
#reset counters
$fails=0;$msgs=0;
# LOOP THRU ALL MESSAGES
foreach($RAW->result as $i){
	
	#echo "i";
	if($ob_plexts->process($i,$plguid)){
		$msgs++;
	}else{$fails++;};
	
}	






//error_log("Inserted $msgs broadcasts ($fails failed)");
	#echo "no id";
	#test($r['24']);




//

// guid,team, where,wherelat,wherelng,geopoint,ownedby,what,who,affectedportals
// submitter_plguid,submitter_ip,plaintext,markup
/*
			$WHERE		= $markup[1][1]->guid;//
			$WHERELAT	= $markup[1][1]->latE6;
			$WHERELNG	= $markup[1][1]->lngE6;
			$OWNEDBY	= $plguid;
			$WHO		= $markup[5][1]->guid;
			$WHAT		= " link destroyed";
*/
//


#echo "<pre>";
#print_r($ob_user);
//echo "</pre>";


# loop thru all users that we have collected.
$inserts = 0;
$teller = 0;


#print_r($ob_user->users);
foreach($ob_user->users as $u){
	
	error_log("------------> ".$u['team']." ".$u['plain']." ");
	//echo $u['plain']."\n";
	
	$player->check_user($u);
	

}



#print_r($ob_user);
#print_r($player);

die("!");









#error_log("[dashboard.getPaginatedPlextsV2][$USER] $msgs new broadcasts ($fails failed) --- Players new/upd: ".$inserts."/".$teller."",0);


# The end!







function adduser($x,$level=1){
		global $ob_user;
		
		foreach($x as $key=>$value){
		error_log($key."->".$value);
			
		}
		
		
		
		
		
		$ob_user->addusers($x,$level);

		#echo "<pre>";
		#print_r($x);
		#echo "</pre>";
		
		//$_USER[$x['plain']]=$x;
}





/*

$markup[0][0] == "TEXT"
$markup[0][1] == " Resonator on "

$markup[1][0] == "TEXT"
$markup[2][0] == "TEXT"

$markup[3][0] == "PORTAL"

$markup[4][0] == "TEXT"
$markup[4][1] == " has decayed "




/*

$markup[0][0] == "TEXT"
$markup[1][0] == "PORTAL"   WHERE
$markup[2][0] == "TEXT"
$markup[2][1] == " is under attack by "   WHAT
$markup[3][0] == "PLAYER"   WHO


*/

?>