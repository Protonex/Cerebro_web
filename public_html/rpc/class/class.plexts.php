<?php

class plexts {

	var $log;




	function process($id,$plguid){
		global $ob_database,$usr,$ob_plexts;	
		
		#print_r($id);
		
		#genereated by
		$genby 		= $id[2]->plext->team;
		#type
		$msgtype	= $id[2]->plext->plextType; // PLAYER_GENERATED , SYSTEM_NARROWCAST 
		#uniqueid
		$uniqueid  	= $id[0];
		#timestamp
		$timestamp 	= $id[1];
		#text
		$plaintxt  	= $id[2]->plext->text;
		#array
		$markup 	= $id[2]->plext->markup;
		
		if($msgtype!="PLAYER_GENERATED" && $msgtype!="SYSTEM_NARROWCAST"){
			
			//header("HTTP/1.0 404 Not Found");	
			#echo "<pre>$msgtype\n";
			
			#print_r($id);
			
			#echo "</pre>";
			
		}else { echo $msgtype."\n"; }
		
	
	
		//die($msgtype);
		
		
		switch($msgtype){
			case "SYSTEM_NARROWCAST":
				break;
				## return!!!! - debug
				
				//die("SYSTEM_NARROWCAST");
				//error_log("system_narrowcast");
				//echo  $id[2]->plext->team ." --> ".$id[2]->plext->plextType. "<br>";
				$result = $this->system_narrowcast($plguid,$genby,$uniqueid,$timestamp,$plaintxt,$markup);
				$result['plaintxt']=$plaintxt;
				$result['markup']=json_encode($markup);
				
				
				
		
				if(!isset($result['OWNEDBY'])){$result['OWNEDBY']="";}
				if(!isset($result['AFFECTED'])){$result['AFFECTED']="";}	
				$sql = "INSERT INTO ingressv2_broadcast
				(`guid`,`team`,`where`,`wherelat`,`wherelng`,`geopoint`,`ownedby`,`what`,`who`,`affectedportals`,`submitter_plguid`,`submitter_ip`,`plaintext`,`markup`,`timestamp`) 
				VALUES 
				('".addslashes($uniqueid)."','".addslashes($genby)."','".addslashes($result['WHERE'])."','".addslashes(E6topos($result['WHERELAT']))."','".addslashes(E6topos($result['WHERELNG']))."',( GeomFromText( 'POINT(".$result['WHERELAT']."  ".$result['WHERELNG'].") ' ) ) ,'".addslashes($result['OWNEDBY'])."','".addslashes($result['WHAT'])."','".addslashes($result['WHO'])."','".addslashes($result['AFFECTED'])."','".addslashes($plguid)."','".addslashes($_SERVER['REMOTE_ADDR'])."','".addslashes(str_replace("Your",$usr['name']."'s", $plaintxt))."','".addslashes(json_encode($markup))."','".($timestamp/1000)."')";
				if($ob_database->execute($sql)){
					echo "ok.";
					return true;
				}else { 
					return false;
					#echo "<pre>";
					#print_r($ob_database->log);
				}			
				
				
				break;
			case "PLAYER_GENERATED":
				break;  
				## return!!!! - debug
				
				
				die("PLAYER_GENERATED");
				#echo  $id[2]->plext->team ." --> ".$id[2]->plext->plextType. "<br>";
				$result = $this->player_generated($plguid,$genby,$uniqueid,$timestamp,$plaintxt,$markup);
				
	
				
				
				
				break;
			
			case "SYSTEM_BROADCAST":
				//break;
				$result = $this->system_broadcast($plguid,$genby,$uniqueid,$timestamp,$plaintxt,$markup);
				#print_r($result);
				
				//die("SYSTEM_BROADCAST");
				//echo  $id[2]->plext->team ." --> ".$id[2]->plext->plextType. "<br>";
				$result['plaintxt']=$plaintxt;
				$result['markup']=json_encode($markup);
				
				
	
		
				if(!isset($result['OWNEDBY'])){$result['OWNEDBY']="";}
				if(!isset($result['AFFECTED'])){$result['AFFECTED']="";}
				
				
				//add_the_player($guid,$name=NULL,$level=0,$faction=NULL);
				
				break;
				## return!!!! - debug
					
				$sql = "INSERT INTO ingress_broadcast
				(`guid`,`team`,`where`,`wherelat`,`wherelng`,`geopoint`,`ownedby`,`what`,`who`,`affectedportals`,`submitter_plguid`,`submitter_ip`,`plaintext`,`markup`,`timestamp`) 
				VALUES 
				('".addslashes($uniqueid)."','".addslashes($genby)."','".addslashes($result['WHERE'])."','".addslashes(E6topos($result['WHERELAT']))."','".addslashes(E6topos($result['WHERELNG']))."',( GeomFromText( 'POINT(".$result['WHERELAT']."  ".$result['WHERELNG'].") ' ) ) ,'".addslashes($result['OWNEDBY'])."','".addslashes($result['WHAT'])."','".addslashes($result['WHO'])."','".addslashes($result['AFFECTED'])."','".addslashes($plguid)."','".addslashes($_SERVER['REMOTE_ADDR'])."','".addslashes(str_replace("Your",$usr['name']."'s", $plaintxt))."','".addslashes(json_encode($markup))."','".($timestamp/1000)."')";
				if($ob_database->execute($sql)){
					echo "ok.";
					return true;
				}else { 
					return false;
					#echo "<pre>";
					#print_r($ob_database->log);
				}
	
				break;
			
	
			
			default:
				echo "UNKNOWN!";
				echo   "UNKNOWN! ".$id[2]->plext->team ." --> ".$id[2]->plext->plextType. "<br>";
				
				mail("hopper.jerry@gmail.com","UNKNOWN BROADCAST DETECTED", $id[2]->plext->team ." --> ".$id[2]->plext->plextType. " ".json_encode($i) );
				
				
				break;
		}
	
	
	
	
	
		
		#genereated by
	#	$genby 		= $id[2]->plext->team;
		#type
	#	$msgtype	= $id[2]->plext->plextType; // PLAYER_GENERATED , SYSTEM_NARROWCAST 
		#uniqueid
	#	$uniqueid  	= $id[0];
		#timestamp
	#	$timestamp 	= $id[1];
		#text
	#	$plaintxt  	= $id[2]->plext->text;
		#array
	#	$markup 	= $id[2]->plext->markup;	
	#	echo "<pre>";
		//print_r($i);
	
	#	print_r($result);
	#	echo "</pre>";
	
	
	
	
	
	
		
		
		#echo "<pre>";
		//print_r($id[2]->plext);
		#	print_r($msgtype);
		#decode_markup($id[2]->plext->plextType);
		#$id[2]->plext->team;
	
			
			
	}






	function player_generated($plguid,$genby,$uniqueid,$timestamp,$plaintext,$markup){
	
		$X=(array)$markup[1][1];
		if($markup[1][1]->team=="ALIENS"||$markup[1][1]->team=="RESISTANCE"){
			add_the_player($markup[1][1]->guid,$markup[1][1]->plain,$level=0,$markup[1][1]->team);
		}
	
	}





	function system_broadcast($plguid,$genby,$uniqueid,$timestamp,$plaintext,$markup){
		$print = true;
		$known = false;
		error_log("system_broadcast");
		#echo "---<br>";
		//print_r($markup);
		#print_r($plguid);
		if($markup[0][1]->plain=='The Link '){
			//$WHO		= $markup[0][1]->guid;
			if($markup[4][1]->plain==" has decayed"){
				$WHERE		= $markup[1][1]->guid;//
				$WHERELAT	= $markup[1][1]->latE6;
				$WHERELNG	= $markup[1][1]->lngE6;
				$WHAT		= "decay-link";
				
				
				$out['AFFECTED'] = $markup[1][1]->guid.",".$markup[3][1]->guid;	
				
				
				
				$known = true;
				$print = false;
			}
		}
		
		
		if($markup[0][1]->plain=='Control Field @'){
			
			
			if($markup[2][1]->plain==" has decayed -"){
	
				$WHERE		= $markup[1][1]->guid;//
				$WHERELAT	= $markup[1][1]->latE6;
				$WHERELNG	= $markup[1][1]->lngE6;
				$WHAT		= "decay-field";
				//$out['AFFECTED'] = $markup[2][1]->guid.",".$markup[4][1]->guid;	
				
				
				
				$known = true;
				$print = false;
				
			}
			
	
		}
		
		
		if($markup[0][1]->guid){
			
			#print_r($markup[0][1]);
			$this->adduser($markup[0][1]);
			#global  $ob_user;
			#print_r($ob_user);
			
			$WHO		= $markup[0][1]->guid;
			if($markup[1][1]->plain==" linked "){
				$WHERE		= $markup[2][1]->guid;//
				$WHERELAT	= $markup[2][1]->latE6;
				$WHERELNG	= $markup[2][1]->lngE6;
				$WHAT		= "link";
				$out['AFFECTED'] = $markup[2][1]->guid.",".$markup[4][1]->guid;	
				
				
				
				$known = true;
				$print = false;
			}
			if($markup[1][1]->plain==" deployed an "){
				
				
				error_log("_____________________________________________________");
				
				$this->adduser($markup[0][1],$markup[2][1]->plain);
				
				
				
				
				
				
				$WHAT		= "deploy";
				$WHERE		= $markup[4][1]->guid;
				$WHERELAT	= $markup[4][1]->latE6;
				$WHERELNG	= $markup[4][1]->lngE6;
				//$OWNEDBY	= $markup[4][1];
				//print_r($OWNEDBY);die();
				
				$known = true;
				$print = false;
			}
	
			if($markup[1][1]->plain==" captured "){
				$WHERE		= $markup[2][1]->guid;//
				$WHERELAT	= $markup[2][1]->latE6;
				$WHERELNG	= $markup[2][1]->lngE6;
				$WHAT		= "capture";
				$known = true;
				$print = false;
			}
	
			if($markup[1][1]->plain==" created a Control Field @"){
				$WHERE		= $markup[2][1]->guid;//
				$WHERELAT	= $markup[2][1]->latE6;
				$WHERELNG	= $markup[2][1]->lngE6;			
				$WHAT		= "field";
				
	
				
				
				
				$known = true;
				$print = false;
			}
			if($markup[1][1]->plain==" destroyed an "){
				$WHERE		= $markup[4][1]->guid;//
				$WHERELAT	= $markup[4][1]->latE6;
				$WHERELNG	= $markup[4][1]->lngE6;		
				$WHAT		= "destroy";	
				$known = true;
				$print = false;
			}
			if($markup[1][1]->plain==" destroyed the Link "){
				$WHERE		= $markup[2][1]->guid;//
				$WHERELAT	= $markup[2][1]->latE6;
				$WHERELNG	= $markup[2][1]->lngE6;	
				$WHAT		= "destroy-link";
				
				$out['AFFECTED'] = $markup[2][1]->guid.",".$markup[4][1]->guid;	
				
				
							
				$known = true;
				$print = false;
			}
			if($markup[1][1]->plain==" destroyed a Control Field @"){
				$WHERE		= $markup[2][1]->guid;//
				$WHERELAT	= $markup[2][1]->latE6;
				$WHERELNG	= $markup[2][1]->lngE6;	
				$WHAT		= "destroy-field";	
				
				
				
				$known = true;
				$print = false;
			}
	
	
			
		}
		
		if($known==false){
			mail("hopper.jerry@gmail.com","UNKNOWN system BROADCAST DETECTED", $plguid." ".$genby." ".$uniqueid." ".$timestamp." ".$plaintext. " ".json_encode($markup) );
		}
		
		
		//$print = true;
		#if($print == true){
			#echo "<hr>--<br>";
		#	echo "<pre>";
			//echo $timestamp." ".$genby." parts: ".count($markup) ;
			#echo "<br>";
		#	print_r($markup);
		#	echo "</pre>";
	
	
	
		
				#$WHERE		= $markup[1][1]->guid;//
				#$WHERELAT	= $markup[1][1]->latE6;
				#$WHERELNG	= $markup[1][1]->lngE6;
				#$OWNEDBY	= $plguid;
				#$WHO		= $markup[5][1]->guid;
				#$WHAT		= " link destroyed";
	
		#}
		$out['WHERE']	=$WHERE;
		$out['WHERELAT']=$WHERELAT;
		$out['WHERELNG']=$WHERELNG;
		if(!isset($OWNEDBY)){ $OWNEDBY=""; }
		$out['OWNEDBY']	=$OWNEDBY;
		$out['WHO']		=$WHO;
		$out['WHAT']	=$WHAT;
		
		return $out;
	}



	  
	  
	  function system_narrowcast($plguid,$genby,$uniqueid,$timestamp,$plaintext,$markup){
		  
		  $known = false;
		  $print = true;
		  
		  $markup[0][0]=="TEXT";
		  if($markup[0][1]->plain=="Your "){
				  if($markup[3][1]->plain=="Portal Shield"){
						  
	  
						  
						  if($markup[6][1]->plain==" was destroyed by "){
							$known = true;
							$WHERE	= $markup[5][1]->guid;//$markup[3][1]->latE6;$markup[3][1]->lngE6;
							$WHERELAT	= $markup[5][1]->latE6;
							$WHERELNG	= $markup[5][1]->lngE6;
							$OWNEDBY	= $plguid;
							$this->adduser($markup[7][1]);
							$WHO		= $markup[7][1]->guid;
							$WHAT		= "destroy-shield";						
							  
						  }
						  
						  
		  
				  }
				  
				  
				  if($markup[2][1]->plain==" Resonator on "){
					  //$print = true;
						$WHERE	= $markup[3][1]->guid;//$markup[3][1]->latE6;$markup[3][1]->lngE6;
						$WHERELAT	= $markup[3][1]->latE6;
						$WHERELNG	= $markup[3][1]->lngE6;
						$OWNEDBY	= $plguid;
						if(isset($markup[5]) ){ 
						  $WHO		= $markup[5][1]->guid;
						  $this->adduser($markup[5][1]);
						} else { $WHO =''; }
						
						$WHAT		= "destroy-resonator";
						$known = true;
						  if(!isset($markup[5][1]->guid)){
							  $known = false;
						  }
						  #echo "<pre>";
						  #print_r($markup);
						  
						  //var_dump($markup[4][1]->plain);
						  #die();
						  if($markup[4][1]->plain==" has decayed"){
							  $WHO		= NULL;
							  $WHAT		= "decay-resonator";
							  $known = true;
						  }
						  
					  
				  }
				  $print = false;
				  
				  
				  
				  
				  
				  
		  }
		  
		  if($markup[0][1]->plain=="Your Portal "){
				  $print = true;
				  
				  
				  if($markup[2][1]->plain==" is under attack by "){
					  $print = false;
					  $known = true;
					  
					  $WHAT		= "attack";
					  
				  }
				  if($markup[2][1]->plain==" neutralized by "){
					  $print = false;
					  $known = true;
					  $WHAT		= "defeat";
				  }			
				  
				  $WHERE		= $markup[1][1]->guid;//$markup[3][1]->latE6;$markup[3][1]->lngE6;
				  $WHERELAT	= $markup[1][1]->latE6;
				  $WHERELNG	= $markup[1][1]->lngE6;
				  $OWNEDBY	= $plguid;
				  $this->adduser($markup[3][1]);
				  $WHO		= $markup[3][1]->guid;
	  
				  
		  }
	  
		  if($markup[0][1]->plain=="Your Link "){
				  $print = true;
				  if($markup[4][1]->plain==" destroyed by "){
					  $print = false;
					  $known = true;
					  $this->adduser($markup[5][1]);
					  $WHO		= $markup[5][1]->guid;
					  $WHAT		= "link-destroy";
				  }
				  
				  
				  if($markup[4][1]->plain==" has decayed"){
					  $WHO	="decay";
					  $WHAT		= "link-decay";	
				  }
				  
				  $out['AFFECTED'] = $markup[1][1]->guid.",".$markup[3][1]->guid;				
	  
				  
				  $WHERE		= $markup[1][1]->guid;//
				  $WHERELAT	= $markup[1][1]->latE6;
				  $WHERELNG	= $markup[1][1]->lngE6;
				  $OWNEDBY	= $plguid;
				  
				  //$this->adduser($markup[5][1]);
				  
				  //error_log("--->".$markup[4][1]->plain);
				  
				  
				  
		  }	
	  
	  
	  
		  //$print = true;
		  if($print == true){
			  //	echo "<hr>";
	  
		  //echo "<pre>";
		  //echo $timestamp." ".$genby." parts: ".count($markup) ;
		  
		  
			  //echo "<br>";
			  //print_r($markup);
			  //echo "</pre>";
		  
		  
		  
		  }
		  
		  if($known==false){
			  mail("hopper.jerry@gmail.com","UNKNOWN system_narrowcast DETECTED", $plguid." ".$genby." ".$uniqueid." ".$timestamp." ".$plaintext. " ".json_encode($markup) );
		  }
	  
		  
	  //	WHERE - OWNEDBY - WHAT - WHO 
	  
		  $out['WHERE']	=$WHERE;
		  $out['WHERELAT']=$WHERELAT;
		  $out['WHERELNG']=$WHERELNG;
		  $out['OWNEDBY']	=$OWNEDBY;
		  $out['WHO']		=$WHO;
		  $out['WHAT']	=$WHAT;
		  
		  return $out;	
	  }
	  
	  






	function adduser($x,$level=1){
			global $ob_user;
			
			foreach($x as $key=>$value){
				error_log($key."->".$value);
			}
			
			$ob_user->addusers($x,$level);
	}









	function log($data)
	{
		error_log($data);
		$l=$this->log;
		$l[]=$data;
		$this->log=$l;
	}
	
}


?>