<?php
include("../var.php");
include(CBASE."common.php");







$agentid = "110107752946833684461";





echo intelitemcheckkeys($agentid);





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








?>