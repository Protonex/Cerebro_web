<?php
header('Access-Control-Allow-Origin: *'); 
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if( isset($_POST['guid']) && $_POST['action']=="save" ){
	
	$r = json_decode(utf8_encode($_POST['data']));
	$RAW = json_decode( utf8_encode($r) );
	foreach($RAW->portals as $key=>$value){
		//echo "<br>".$key;
		$l[$key]=$key;
		
	}
	
	echo "<pre>";
	print_r($RAW->portals);
	die();
	$out['status']="ok";
	$out['data']=$RAW;
	//echo "!!!";
	die( utf8_encode(json_encode($out)) );
	
}


if( isset($_POST['guid']) && $_POST['action']=="load" ){
	
	$json_decode = json_decode('["8b0627a4bb1546d7a314cdec00b9ebe5.12","b0ab002ca7ab4c999aa95b0eaa0de429.16","aaa3afbec42f4268b9c80d94b0dcb470.16"]');
	
	$out['status']="ok";
	$out['data']=$json_decode;
	die(utf8_encode(json_encode($out)));

	
}






//echo "<pre>";
//print_r( json_encode($l) );
?>