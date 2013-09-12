<?php 

//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
#echo $_GET['user'];
#echo $_GET['lastlat'];
#echo $_GET['lastlon'];

include("../../var.php");
include(CBASE."common.php");
function pr($var)
{
	ob_start();
	echo "<pre>"; 
	print_r($var);
	echo "</pre>";
	$res = ob_get_contents();
	ob_end_clean();
	echo $res;	
}
function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
{
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;
 
	$r = 6372.797; // mean radius of Earth in km
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$km = $r * $c;
 
	return ($miles ? ($km * 0.621371192) : $km);
}


function resopercent($cur,$level){
	$reso_energy[1]=1000;
	$reso_energy[2]=1500;
	$reso_energy[3]=2000;
	$reso_energy[4]=2500;
	$reso_energy[5]=3000;
	$reso_energy[6]=4000;
	$reso_energy[7]=5000;
	$reso_energy[8]=6000;
	
	$pct = $cur*100/$reso_energy[$level];
	if($pct>0){ return " L".$level." ". $pct."%";}
	
	return ;
}


include(CBASE."rpc/class/class.player.php");
#check player
$player = new player();

$earth		=	6371;


$mytable	=	"ingress_portals";
$latcolumn	=	"lat";
$lngcolumn	=	"lng";

$radius = "0.5";

$latvalue = $_POST['lat']; //"51.9266757";
$lngvalue = $_POST['lng']; //"4.2474398";


#echo "!";

#print_r($_POST);


$sql = "SELECT guid,lat,lng, TITLE,capturingPlayerId, ADRESS, controllingteam ,imageByUrl,resonatorArray,
       ACOS(SIN(RADIANS($latvalue)) * SIN(RADIANS(`$latcolumn`)) + 
            COS(RADIANS($latvalue)) * COS(RADIANS(`$latcolumn`)) * COS( 
                 RADIANS(`$lngcolumn`) - RADIANS($lngvalue))) * $earth AS `distance` 
FROM   (SELECT * 
        FROM   `$mytable` 
        WHERE  $actionfilter
			    `$latcolumn` >= $latvalue - DEGREES($radius / $earth) 
               AND `$latcolumn` <= $latvalue + DEGREES($radius / $earth) 
               AND `$lngcolumn` >= $lngvalue - DEGREES($radius / $earth) / 
                                          COS(RADIANS($latvalue)) 
               AND `$lngcolumn` <= $lngvalue + DEGREES($radius / $earth) / 
                                          COS(RADIANS($latvalue)) 
       ) AS haystack 
HAVING distance <= $radius 


ORDER  BY distance ASC";

#pr($ob_database->log);


$dres = $ob_database->get_array($sql);

#echo "<pre>";
#print_r($dres );


$x=0;
foreach($dres as $rr ){
	$rr;
	
	
	#echo $rr['capturingPlayerId'];
	

	
	#print_r( $owner['nick'] );
	#die("debug - error");
	#echo $rr['capturingPlayerId'];
	
	#die($player->get_by_guid($plguid) );
	


	
	$reso_arr = json_decode($rr['resonatorArray']);
	
//	print_r($reso_arr);
	
	#max = 1000;
	$reso_arr->resonators[0]->level;
	$reso_arr->resonators[0]->ownerGuid;
	$reso_arr->resonators[0]->energyTotal;
	
	#max = 1500;
	$reso_arr->resonators[1]->level;
	$reso_arr->resonators[1]->ownerGuid;
	$reso_arr->resonators[1]->energyTotal;
	
	#max = 2000;
	$reso_arr->resonators[2]->level;
	$reso_arr->resonators[2]->ownerGuid;
	$reso_arr->resonators[2]->energyTotal;
	
	#max = 2500;
	$reso_arr->resonators[3]->level;
	$reso_arr->resonators[3]->ownerGuid;
	$reso_arr->resonators[3]->energyTotal;
	
	#max = 3000;
	$reso_arr->resonators[4]->level;
	$reso_arr->resonators[4]->ownerGuid;
	$reso_arr->resonators[4]->energyTotal;
	
	#max = 4000;
	$reso_arr->resonators[5]->level;
	$reso_arr->resonators[5]->ownerGuid;
	$reso_arr->resonators[5]->energyTotal;
	
	#max = 5000;
	$reso_arr->resonators[6]->level;	
	$reso_arr->resonators[6]->ownerGuid;	
	$reso_arr->resonators[6]->energyTotal;
	
	#max = 6000;
	$reso_arr->resonators[7]->level;
	$reso_arr->resonators[7]->ownerGuid;
	$reso_arr->resonators[7]->energyTotal;
	
	
	$PortalLevel = round( ($reso_arr->resonators[0]->level+$reso_arr->resonators[1]->level+$reso_arr->resonators[2]->level+$reso_arr->resonators[3]->level+$reso_arr->resonators[4]->level+$reso_arr->resonators[5]->level+$reso_arr->resonators[6]->level+$reso_arr->resonators[7]->level)/8) ;
	
	
	
	
	
	
	
	$color = "grey";
	if($rr['controllingteam']=="ENLIGHTENED"){$color='green';}
	if($rr['controllingteam']=="ALIENS"){$color='green';}
	if($rr['controllingteam']=="RESISTANCE"){$color='darkblue';}
	
	

	
	//5100 cur *100/6000 -max
	
	
	
	echo "<div style=\"padding:2px;border:5px solid $color;height:100px;width:100%;background-size:cover;background: $color url('".$rr['imageByUrl']."') no-repeat center ;\" >";
	echo "<p style='text-shadow: -4px 0 black, 0 4px black, 4px 0 black, 0 -4px black;margin:3px;color:white;font-size:xx-large;float:right'>L$PortalLevel</p>";
	echo "<p style='text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;margin:3px;color:white;font-size:xx-large' >".$rr['TITLE']."</p>";
	echo "<p style='text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;margin:3px;color:white;font-size:large' >Distance ".round($rr['distance']*1000) ." M</p>";
	echo "<p style='text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;margin:3px;color:white;'><span >Captured by</span> <span style='text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;margin:3px;color:white;font-size:large;' >".$owner['nick']."</span></p>";
	//echo "<img class='img-thumbnail'  src='".$rr['imageByUrl']."'>";
	echo "</div >";
	echo "<div style='width:100%'>";   
	
	$owner0 = $player->get_by_guid($reso_arr->resonators[0]->ownerGuid);
	$owner0['nick'];
	
	$owner1 = $player->get_by_guid($reso_arr->resonators[1]->ownerGuid);
	$owner1['nick'];
	
	$owner2 = $player->get_by_guid($reso_arr->resonators[2]->ownerGuid);
	$owner2['nick'];
	
	$owner3 = $player->get_by_guid($reso_arr->resonators[3]->ownerGuid);
	$owner3['nick'];
	
	$owner4 = $player->get_by_guid($reso_arr->resonators[4]->ownerGuid);
	$owner4['nick'];

	$owner5 = $player->get_by_guid($reso_arr->resonators[5]->ownerGuid);
	$owner5['nick'];
	
	$owner6 = $player->get_by_guid($reso_arr->resonators[6]->ownerGuid);
	$owner6['nick'];
	
	$owner7 = $player->get_by_guid($reso_arr->resonators[7]->ownerGuid);
	$owner7['nick'];
	
	
	
	echo "<div style='width:49%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[0]->energyTotal,$reso_arr->resonators[0]->level)." ".$owner0['nick']."</div><div style='width:50%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[1]->energyTotal,$reso_arr->resonators[1]->level)." ".$owner1['nick']."</div>";
	echo "<div style='width:49%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[1]->energyTotal,$reso_arr->resonators[1]->level)." ".$owner1['nick']."</div><div style='width:50%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[3]->energyTotal,$reso_arr->resonators[3]->level)." ".$owner3['nick']."</div>";
	echo "<div style='width:49%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[3]->energyTotal,$reso_arr->resonators[3]->level)." ".$owner3['nick']."</div><div style='width:50%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[5]->energyTotal,$reso_arr->resonators[5]->level)." ".$owner5['nick']."</div>";
	echo "<div style='width:49%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[5]->energyTotal,$reso_arr->resonators[5]->level)." ".$owner5['nick']."</div><div style='width:50%;float:left;border:1px solid black;'>".resopercent($reso_arr->resonators[7]->energyTotal,$reso_arr->resonators[7]->level)." ".$owner7['nick']."</div>";
	echo "</div>";
	echo "<div >";
	
	
	echo "<p>".$rr['ADRESS']."</p>";
	echo $rr['photoStreamInfo'];
	echo $rr['captured_by'];
	echo $rr['controllingteam'];
	echo $rr['portalV2'];
	
	echo "</div>";
	echo "<br>\n";
	$x++;
}
//print_r($res );



#echo $_GET['lastlat'];
#echo $_GET['lastlon'];



# distance between last portal and new portal.
#$dist = distance($_GET['lastlat'], $_GET['lastlon'], $res['lat'], $res['lng']);

#print_r($ob_auth);



?>