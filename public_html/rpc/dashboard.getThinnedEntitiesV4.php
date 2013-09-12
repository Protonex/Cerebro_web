<?php
#header('HTTP/1.0 404 Not Found');
header('X-Error-Message: currently under development', true, 500);


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
#include(CBASE."rpc/class/class.plext.userarray.php");
include(CBASE."rpc/class/class.thinnedentities.php");
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


#print_r($RAW->result->map);





$ob_thinned = new thinnedentities();
$ob_thinned->submit_maptiles($RAW->result->map);


?>