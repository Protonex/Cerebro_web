<?php
include("../var.php");
include(CBASE."common.php");



if(!isset($_POST['guid'])){
	$json['results'] = 0;
	$json['data']	 = "";	
	die(json_encode($json));
}



if($ob_auth->verified['guid'] && ($_POST['type']=='work' || $_POST['type']=='home' || $_POST['type']=='other') ){
	
	$sql = "UPDATE ingressv2_verified SET ".$_POST['type']."portal = '".addslashes($_POST['guid'])."' WHERE guid='".$ob_auth->verified['guid']."'";
	$ob_database->execute($sql);

}


$sql = "SELECT guid,lat,lng,TITLE,ADRESS,imageByUrl FROM ingressv2_portals WHERE guid = '".addslashes($_POST['guid'])."'";

$r = $ob_database->get_single($sql);
#echo $sql;
#echo "<pre>";
#print_r($r);
$r['ADRESS']=str_replace(",","<br>",$r['ADRESS']);	
	
 
if($r){
	$json['results'] = 1;
	$json['data']	 = $r;
	
}else {
	$json['results'] = 0;
	$json['data']	 = "";
	
}

die(json_encode($json));
?>