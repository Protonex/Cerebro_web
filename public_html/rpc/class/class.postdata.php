<?php

class postdata
{
	var $log;
	var $content_length;
	var $guid;
	
	function check_json($class)
	{
		
		
		if( !property_exists($class,"guid") && !property_exists($class,"gameBasket") && !property_exists($class,"result") ) 
		{
			return false;	
		} 
		else 
		{
			return true;
		}
		
		
	}
	
	
	function checkpostdata()
	{
		# are there post variables?
		if(!$_POST)
		{
			$this->log("no postdata");
			return false;
		}
		#store content_length
		$this->content_length = formatBytes($_SERVER["CONTENT_LENGTH"],2);
		
		#is submitter guid known?
		if(!isset($_POST['guid']))
		{ 
			$this->log("no guid posted");
			die("err");
		}
		$this->guid = trim($_POST['guid']);
		
		
		#$RAW = json_decode(utf8_encode($_POST['data']));		
		$RAW = json_decode($_POST['data']);
		
		//$this->log($RAW);
		
		if( is_array($RAW) )
		{
			$this->rawpostdata = $RAW;
			return $RAW;				
		}
		
		
		if( is_null($RAW) )
		{  
			$this->log("RAW is_null");
			$RAW = $this->get_rawpostdata(); 
		
		}
		
		
		if( !is_object($RAW) )
		{
			$this->log("RAW !is_object");
			$RAW = json_decode($RAW);
			//$this->log($RAW);
			//echo "<pre>";
			//var_dump($RAW);
			//$this->log((bool)is_object($RAW));	
		}

		if( !is_object($RAW)  )
		{
			$this->log("RAW !is_object- false");
			
			if( !is_array($RAW) )
			{
				$this->log("RAW !is_array- false");
				
				
				return false;		
			} else { $this->log("RAW is an ARRAY"); }
			
		}
		
		$this->rawpostdata = $RAW;
		$this->rawpostdata->guid = $this->guid;
		
		return $RAW;
	}
	
	
	
	
	
	
	
	function get_rawpostdata(){
	


	//header("HTTP/1.0 404 Not Found");
//	error_log("[dashboard.getThinnedEntitiesV2][".$USER."] - try repair Invalid postdata");
//	error_log("No postdata");
	//echo (int) $_SERVER['CONTENT_LENGTH'];
	
	$ERAW = file_get_contents("php://input");
	
	
	
	
	$ERAW = str_replace("guid=".$this->guid."&data=","",$ERAW);
	$ERAW = json_decode(utf8_encode($ERAW));
	#echo "<pre>";
	#print_r($ERAW);
	
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
	
	return $RAW;
		
	}
	
	
	
	
	
	
	
	
	function log($data)
	{
		error_log($data);
		$l=$this->log;
		$l[]=$data;
		$this->log=$l;
	}
	function dblog($action,$data)
	{
		global $ob_database;
		if( $_SESSION['ECMS-'.CDOMAIN.'-user_id'] ){ $user=$_SESSION['ECMS-'.CDOMAIN.'-user_id']; }else{$user=0;}
		$sql = "INSERT INTO HK_userlog (`timestamp`, `user_id`, `action`, `data`) VALUES ('".time()."', '".$user."', '".addslashes($action)."', '".addslashes($data)."')";
		$ob_database->execute($sql);
		$this->log("Logged to database :".$action );

	}
}





?>