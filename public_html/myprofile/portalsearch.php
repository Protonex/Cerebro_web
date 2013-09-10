<?php
include("../var.php");
include(CBASE."common.php");



if(isset($_GET['q'])){ 
	$q = urldecode($_GET['q']);
	
	if(!isset($_GET['m'])){  $max = 6;}else{$max = (int)$_GET['m'];}

}else {
	
	$q = urldecode($_POST['q']);
	
	if(!isset($_POST['m'])){  $max = 6;}else{$max = (int)$_GET['m'];}
}



//$array = explode(" ",$q);


$s = " MATCH ( TITLE,ADRESS) AGAINST ('%".addslashes($q)."%')   ";




$sql = "SELECT count(guid)AS total FROM ingress_portals WHERE ".$s;
$cnt= $ob_database->get_single($sql);
//echo $sql;
#print_r($cnt);

$sql = "SELECT guid,lat,lng,TITLE,ADRESS,imageByUrl FROM ingress_portals WHERE ".$s." ORDER BY ADRESS LIMIT ".$max;
$res= $ob_database->get_array($sql);
foreach($res as $r){
	$r['ADRESS']=str_replace(",","<br>",$r['ADRESS']);
	$rr[]=$r;	
}
#echo "<hr>".$sql;


$json['results'] = $cnt['total'];
$json['data']	 = $rr;


die(json_encode($json));








?>