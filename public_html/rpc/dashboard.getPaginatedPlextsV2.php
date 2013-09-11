<?php

header('X-Error-Message: Not implemented... yet!', true, 500);

header('Access-Control-Allow-Origin: *'); 
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$noauth=true;
include("../var.php");
include(CBASE."common.php");

#
include(CBASE."rpc/class/class.postdata.php");

#die();

$ob_postdata = new postdata();
$DATA = $ob_postdata->checkpostdata();
$DATA->guid = $ob_postdata->guid;




echo "<br><pre>";
print_r($DATA);
#writejson($DATA);


function writejson ($data){
	
	
	$maptile = key($data->result->map); 
	$filename = "maptiles/".$maptile.".json";
	
	$somecontent = json_encode($data->result->map);

	$fp = fopen($filename, "w");
	if (flock($fp, LOCK_EX)) {  // acquire an exclusive lock
		ftruncate($fp, 0);      // truncate file
		fwrite($fp, $somecontent );
		fflush($fp);            // flush output before releasing the lock
		flock($fp, LOCK_UN);    // release the lock
		fclose($fp);
	} else {
		echo "Couldn't get the lock!";
	}


}





if(!isset($_POST['guid'])){ die("err");}
$guid = trim($_POST['guid']);

$sql = "SELECT * FROM ingress_players WHERE guid='".addslashes($guid)."'";
$usr = $ob_database->get_single($sql);
$USER = $usr['name'];




$RAW = json_decode(utf8_encode($_POST['data']));

 error_log("$USER: ".formatBytes($_SERVER["CONTENT_LENGTH"],2) );

error_log("[dashboard.getThinnedEntitiesV2][$USER] ");



if( is_null($RAW) ){ 
	//header("HTTP/1.0 404 Not Found");
//	error_log("[dashboard.getThinnedEntitiesV2][".$USER."] - try repair Invalid postdata");
//	error_log("No postdata");
	//echo (int) $_SERVER['CONTENT_LENGTH'];
	
	$ERAW = file_get_contents("php://input");
	
	$ERAW = str_replace("guid=".$guid."&data=","",$ERAW);
	$ERAW = json_decode(utf8_encode($ERAW));
	//echo "<pre>";
	if($ERAW==NULL){
		
		header('X-Error-Message: No valid postdata', true, 500);
		error_log("[dashboard.getThinnedEntitiesV2][".$USER."] - error NO VALID POSTDATA");
		die("no postdata"); 
		
		
		} else {
			$RAW = $ERAW;
			$ERAW = NULL ;
			}
	//print_r(json_decode(utf8_encode($ERAW)));
	
	
	
	
	//print_r($_POST['data']);
	
}



$RAW = json_decode($RAW);


echo "$USER posted: <b>".formatBytes($_SERVER["CONTENT_LENGTH"],2)."</b> of ingress data.";

echo " minLevelOfDetail:".$RAW->result->minLevelOfDetail;

echo "<pre>";
print_r($RAW );

die();







?>