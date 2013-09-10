<?php 
#check php version
if (strnatcmp( phpversion(),'5.3.3') >= 0)
{
	# equal or newer
}
else
{
	# not sufficiant
	die("php version outdated ( ".phpversion()." )");
} 

session_start();

class applog
{
	var $log;
	function log($data)
	{
		error_log($data);
		$l=$this->log;
		$l[]=$data;
		$this->log=$l;
	}

}
$ob_log = new applog();



#database stuff...  
include( INCLUDES. "class.mysql.php");
if(defined('CDBUSER') && defined('CDBPASS') && defined('CDBHOST') && defined('CDBNAME'))
{



	$ob_database = new obmysqli();
	$ob_database->set_host(CDBHOST);
	$ob_database->set_user(CDBUSER);
	$ob_database->set_pass(CDBPASS);
	$ob_database->set_dbname(CDBNAME);
		
	
	$ob_database->connect();
	$ob_database->execute('SET NAMES "UTF8"');

#print_r($ob_database);
	

	function database_shutdown(){
		global $ob_database,$ob_mysqli;
		#$ob_database->close();
		$ob_database->close();
	///	$ob_tariffdatabase->close();
		
		if(!empty($ob_database->ar_error) && error_reporting()){
	/*		echo '<pre>';
			print_r($ob_database->ar_error);
			echo '</pre>';
			echo '<pre>';
			print_r($ob_mysqli->ar_error);
			echo '</pre>';*/

		}
	}
	register_shutdown_function('database_shutdown');
	
} 
else 
{
	die("FATAL :  Database variables aren't set.  ");
}


require CBASE.'include/Credis/Client.php';
$redis = new Credis_Client('localhost');


include( INCLUDES. "class.auth.php");




include(CBASE."include/paginator.class.php");

class user {
	
	var $email;
	var $guid;
	var $playename;
	var $faction;
		
}









$ob_user = new user();



require_once CBASE.'include/Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();




#start ingres + google auth

include(CBASE."include/class.ingress.php");
#$ob_ingress = new ingress();








function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}



?>