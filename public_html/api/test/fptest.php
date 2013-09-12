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






$earth		=	6371;


$mytable	=	"ingress_portals";
$latcolumn	=	"lat";
$lngcolumn	=	"lng";

$radius = "0.09";

$latvalue = $_POST['lat']; //"51.9266757";
$lngvalue = $_POST['lng']; //"4.2474398";


#echo "!";

#print_r($_POST);


$sql = "SELECT guid,lat,lng, TITLE, ADRESS, controllingteam ,imageByUrl,
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
	echo "<div style='width:200px'>";
	echo "<img src='".$rr['imageByUrl']."'>";
	echo "</div>";
	echo $rr['TITLE'];
	echo $rr['ADRESS'];
	echo $rr['photoStreamInfo'];
	echo $rr['captured_by'];
	echo $rr['controllingteam'];
	echo $rr['portalV2'];
	
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