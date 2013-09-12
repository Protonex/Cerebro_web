<?php



class thinnedentities {
	
	
	
	function submit_maptiles($map)
	{
		
		foreach($map as $tile=>$value){
			
			#print_r($tile);
			#print_r();
			#die();
		
			$t = explode("_",$tile);
			$zoomlevel = $t[0];
			$tile = $t[1]."_".$t[2];
			
			echo "tile: ".$tile." ";
			echo "zoomlevel ".$zoomlevel."\n";
			echo "portals :".count($value->gameEntities)."\n";
			#print_r($value);
			$this->check_portals($value->gameEntities);
			
			
			
				
		}
		
		
	}
	function type($te){
		
		$entt = explode(".",$te);
		$t = $entt[1]; 
		
		
		switch ($t) {
		case "11":
			return "portal";
			break;
		case "12":
			return "portal";
			break;
		case "16":
			return "portal";
			break;
		case "9":
			return "link";
			break;
		case "b":
			return "field";
			break;				
		case "c":
			return "player";
			break;
		case "d":
			return "chat";
			break;
			
			
		case "5":
			return "resource";
			break;
		case "6":
			return "xm";
			break;				
		case "4":
			return "media";
			break;
			
		default:
			return "UNKNOWN";
			break;	
	}
		
	  // portals end in â€œ.11â€ or â€œ.12â€œ, links in â€œ.9", fields in â€œ.bâ€
	  // .11 == portals
	  // .12 == portals
	  // .16 == portals
	  // .9  == links
	  // .b  == fields
	  // .c  == player/creator
	  // .d  == chat messages
	  //
	  // others, not used in web:
	  // .5  == resources (burster/resonator)
	  // .6  == XM
	  // .4  == media items, maybe all droppped resources (?)
	  // resonator guid is [portal guid]-resonator-[slot]
	  
		
	}	
	
	
	function E6toGEO($val){return ($val/1e6); }
	
	function check_portals($portals)
	{
		foreach ( $portals as $portal)
		{
			
			$type = $this->type($portal[0]);
			
			if($type=="portal"){
				$this->check_portal($portal);	
			} else 
			{ 
				echo "skipping $type \n"; 
			}
			
			#print_r( $this->type($portal[0]) );
			
			
			
			#die("even hier stoppen");
			#
			
			//die();
		}
	}
	
	
	
	function check_portal($portal){
		global $ob_database,$redis;


		$Pguid 	= $portal[0]; // Portal GUID
		$Pstamp = $portal[1]; // timestamp
		$Pinfo	= $portal[2]; // portal info object.


		
		$nUpdated = date("d/m/y H:i:s",$Pstamp/1000);
		
		#print_r($Pinfo);
#		echo "\n $Pstamp = ".$nUpdated."\n";
		/*
		echo $Pinfo->portalV2->descriptiveText->ADDRESS;
		echo $Pinfo->portalV2->descriptiveText->TITLE;			
		echo $Pinfo->imageByUrl->imageUrl;
		echo $Pinfo->locationE6->latE6;
		echo $Pinfo->locationE6->lngE6;

		
		echo $Pinfo->resonatorArray->resonators;
		echo $Pinfo->controllingTeam->team;
		echo $Pinfo->portalV2;
		echo $Pinfo->portalV2->descriptiveText;
		
		
		echo $Pinfo->captured;
		echo $Pinfo->captured->capturedTime;
		echo $Pinfo->captured->capturingPlayerId;
		*/
		
		$lat = (string)$this->E6toGEO($Pinfo->locationE6->latE6);
		$lng = (string)$this->E6toGEO($Pinfo->locationE6->lngE6);
		
		if(strlen($lat<4)){
			var_dump((string)$lat);
			print_r($portal);
			die();
		}
		$resonatorArray = json_encode($Pinfo->resonatorArray);
		$resonatorhash = hash('crc32', $resonatorArray );
					
		$portalV2 = json_encode($Pinfo->portalV2);
		$portalV2hash = hash('crc32', $portalV2 );
		
		
		if(USE_REDIS){
			//echo "using redis  => portalguid:".$Pguid."\n";
			
			
			if($redis->exists("portalguid:".$Pguid))
			{
				echo "found in redis!\n";

				$ERROR=0;
				$redis->get("portalguid:".$Pguid.":resonatorArray"); //JSON
				$old_resonatorhash = $redis->get("portalguid:".$Pguid.":resonatorhash");
				if($old_resonatorhash<>$resonatorhash){ 
					$ERROR++;
					
					//die("resonatorhash MISMATCH");
				}
				
				$redis->get("portalguid:".$Pguid.":portalV2"); //JSON
				$old_portalV2hash = $redis->get("portalguid:".$Pguid.":portalV2hash");
				if($old_portalV2hash<>$portalV2hash){ 
					$ERROR++;
					
					//die("portalV2hash MISMATCH");
				}				
				

				if($ERROR>0){
					echo "MISMATCH, UPDATING REDIS & MYSQL";
					
					$usql = "UPDATE ingress_portals SET  controllingTeam='".addslashes($Pinfo->controllingTeam->team)."',capturedTime='".$Pinfo->captured->capturedTime."', capturingPlayerId='".$Pinfo->captured->capturingPlayerId."' ,guidstamp='".$Pstamp."',resonatorarray='".addslashes($resonatorArray)."',resonatorhash='".$resonatorhash."',portalV2='".addslashes($portalV2)."',portalV2hash='".addslashes($portalV2hash)."' WHERE guid='".$Pguid."'";

					$redis->set("portalguid:".$Pguid,$Pstamp);  // = guidstamp !
					$redis->set("portalguid:".$Pguid.":TITLE",$Pinfo->portalV2->descriptiveText->TITLE); // STRING
					$redis->set("portalguid:".$Pguid.":ADDRESS",$Pinfo->portalV2->descriptiveText->ADDRESS); // STRING
					$redis->set("portalguid:".$Pguid.":imageUrl",$Pinfo->imageByUrl->imageUrl); // STRING
					$redis->set("portalguid:".$Pguid.":lat",$lat); // STRING
					$redis->set("portalguid:".$Pguid.":lng",$lng); // STRING
					$redis->set("portalguid:".$Pguid.":lngE6",$Pinfo->locationE6->lngE6); // STRING
					$redis->set("portalguid:".$Pguid.":latE6",$Pinfo->locationE6->latE6); // STRING				
	
					#dynamic values
					$redis->set("portalguid:".$Pguid.":resonatorArray",$resonatorArray); //JSON
					$redis->set("portalguid:".$Pguid.":resonatorhash",$resonatorhash); //JSON
					
					$redis->set("portalguid:".$Pguid.":controllingTeam",$Pinfo->controllingTeam->team); //JSON
					
					$redis->set("portalguid:".$Pguid.":portalV2",$portalV2); //JSON
					$redis->set("portalguid:".$Pguid.":portalV2hash",$portalV2hash);
					
					$redis->set("portalguid:".$Pguid.":capturedTime",$Pinfo->captured->capturedTime); //STRING
					$redis->set("portalguid:".$Pguid.":capturingPlayerId",$Pinfo->captured->capturingPlayerId); //STRING				
				
				

					
					
				}

/*
				#static values
				$redis->get("portalguid:".$Pguid);  // = guidstamp !
				$redis->get("portalguid:".$Pguid.":TITLE"); // STRING
				$redis->get("portalguid:".$Pguid.":ADDRESS"); // STRING
				$redis->get("portalguid:".$Pguid.":imageUrl"); // STRING
				$redis->get("portalguid:".$Pguid.":lat"); // STRING
				$redis->get("portalguid:".$Pguid.":lng"); // STRING
				$redis->get("portalguid:".$Pguid.":lngE6"); // STRING
				$redis->get("portalguid:".$Pguid.":latE6"); // STRING				

				#dynamic values
				
				$redis->get("portalguid:".$Pguid.":controllingTeam"); //JSON
								
				$redis->get("portalguid:".$Pguid.":capturedTime"); //STRING
				$redis->get("portalguid:".$Pguid.":capturingPlayerId"); //STRING
	*/			
				
				
				
			} else 
			{
				echo "Does not exist in redis!\n";
				
				$sql = "SELECT * FROM ingress_portals WHERE guid ='".$Pguid."'";
				$res = $ob_database->get_single($sql);
				if ($res==NULL)
				{
					echo "Does not exist in mysql! __\n";	
					
									
					#print_r($resonatorArray);
					#die("!");
						
					
					$isql = "INSERT INTO ingress_portals 
					(`guid`, `guidstamp`, `lat`, `lng`, `TITLE`, `ADRESS`, `POSTCODE`, `CITY`, `COUNTRY`, `controllingTeam`, `imageByUrl`, `capturedTime`, `capturingPlayerId`, `lastupdated`,`resonatorArray`,`resonatorhash`,`portalV2`, `portalv2hash`) VALUES 
					('".$Pguid."', '".$Pstamp."', '".$lat."', '".$lng."', '".addslashes($Pinfo->portalV2->descriptiveText->TITLE)."', '".addslashes($Pinfo->portalV2->descriptiveText->ADDRESS)."', '', '', '', '".addslashes($Pinfo->controllingTeam->team)."', '".addslashes($Pinfo->imageByUrl->imageUrl)."', '".$Pinfo->captured->capturedTime."', '".addslashes($Pinfo->captured->capturingPlayerId)."', CURRENT_TIMESTAMP, '".addslashes($resonatorArray)."','".addslashes($resonatorhash)."','".addslashes($portalV2)."','".addslashes($portalV2hash)."')";
					//echo $isql;
					$ob_database->execute($isql);
					
					
					$redis->set("portalguid:".$Pguid,$Pstamp);  // = guidstamp !
					$redis->set("portalguid:".$Pguid.":TITLE",$Pinfo->portalV2->descriptiveText->TITLE); // STRING
					$redis->set("portalguid:".$Pguid.":ADDRESS",$Pinfo->portalV2->descriptiveText->ADDRESS); // STRING
					$redis->set("portalguid:".$Pguid.":imageUrl",$Pinfo->imageByUrl->imageUrl); // STRING
					$redis->set("portalguid:".$Pguid.":lat",$lat); // STRING
					$redis->set("portalguid:".$Pguid.":lng",$lng); // STRING
					$redis->set("portalguid:".$Pguid.":lngE6",$Pinfo->locationE6->lngE6); // STRING
					$redis->set("portalguid:".$Pguid.":latE6",$Pinfo->locationE6->latE6); // STRING				
	
					#dynamic values
					$redis->set("portalguid:".$Pguid.":resonatorArray",$resonatorArray); //JSON
					$redis->set("portalguid:".$Pguid.":resonatorhash",$resonatorhash); //JSON
					
					$redis->set("portalguid:".$Pguid.":controllingTeam",$Pinfo->controllingTeam->team); //JSON
					
					$redis->set("portalguid:".$Pguid.":portalV2",$portalV2); //JSON
					$redis->set("portalguid:".$Pguid.":portalV2hash",$portalV2hash);
					
					$redis->set("portalguid:".$Pguid.":capturedTime",$Pinfo->captured->capturedTime); //STRING
					$redis->set("portalguid:".$Pguid.":capturingPlayerId",$Pinfo->captured->capturingPlayerId); //STRING				
				
				
				
				
				
				
				
				
				
				
				} else {
					
					
					$old_portalV2hash = $res['portalV2hash'];
					if($old_portalV2hash<>$portalV2hash){ 
						$ERROR++;
						
						//die("portalV2hash MISMATCH");
					}
					
					$old_resonatorhash = $res['resonatorhash'];
					
					if($old_resonatorhash<>$resonatorhash){ 
						$ERROR++;
						//die("resonatorhash MISMATCH");
					}
			
					
					
					if($ERROR>0){
						echo "MISMATCH, UPDATING REDIS & MYSQL";
						
						$usql = "UPDATE ingress_portals SET  controllingTeam='".addslashes($Pinfo->controllingTeam->team)."',capturedTime='".$Pinfo->captured->capturedTime."', capturingPlayerId='".$Pinfo->captured->capturingPlayerId."' ,guidstamp='".$Pstamp."',resonatorarray='".addslashes($resonatorArray)."',resonatorhash='".$resonatorhash."',portalV2='".addslashes($portalV2)."',portalV2hash='".addslashes($portalV2hash)."' WHERE guid='".$Pguid."'";
	
						$redis->set("portalguid:".$Pguid,$Pstamp);  // = guidstamp !
						$redis->set("portalguid:".$Pguid.":TITLE",$Pinfo->portalV2->descriptiveText->TITLE); // STRING
						$redis->set("portalguid:".$Pguid.":ADDRESS",$Pinfo->portalV2->descriptiveText->ADDRESS); // STRING
						$redis->set("portalguid:".$Pguid.":imageUrl",$Pinfo->imageByUrl->imageUrl); // STRING
						$redis->set("portalguid:".$Pguid.":lat",$lat); // STRING
						$redis->set("portalguid:".$Pguid.":lng",$lng); // STRING
						$redis->set("portalguid:".$Pguid.":lngE6",$Pinfo->locationE6->lngE6); // STRING
						$redis->set("portalguid:".$Pguid.":latE6",$Pinfo->locationE6->latE6); // STRING				
		
						#dynamic values
						$redis->set("portalguid:".$Pguid.":resonatorArray",$resonatorArray); //JSON
						$redis->set("portalguid:".$Pguid.":resonatorhash",$resonatorhash); //JSON
						
						$redis->set("portalguid:".$Pguid.":controllingTeam",$Pinfo->controllingTeam->team); //JSON
						
						$redis->set("portalguid:".$Pguid.":portalV2",$portalV2); //JSON
						$redis->set("portalguid:".$Pguid.":portalV2hash",$portalV2hash);
						
						$redis->set("portalguid:".$Pguid.":capturedTime",$Pinfo->captured->capturedTime); //STRING
						$redis->set("portalguid:".$Pguid.":capturingPlayerId",$Pinfo->captured->capturingPlayerId); //STRING				
					
					
	
						
						
					}else {
						$redis->set("portalguid:".$Pguid,$Pstamp);  // = guidstamp !
						$redis->set("portalguid:".$Pguid.":TITLE",$Pinfo->portalV2->descriptiveText->TITLE); // STRING
						$redis->set("portalguid:".$Pguid.":ADDRESS",$Pinfo->portalV2->descriptiveText->ADDRESS); // STRING
						$redis->set("portalguid:".$Pguid.":imageUrl",$Pinfo->imageByUrl->imageUrl); // STRING
						$redis->set("portalguid:".$Pguid.":lat",$lat); // STRING
						$redis->set("portalguid:".$Pguid.":lng",$lng); // STRING
						$redis->set("portalguid:".$Pguid.":lngE6",$Pinfo->locationE6->lngE6); // STRING
						$redis->set("portalguid:".$Pguid.":latE6",$Pinfo->locationE6->latE6); // STRING				
		
						#dynamic values
						$redis->set("portalguid:".$Pguid.":resonatorArray",$resonatorArray); //JSON
						$redis->set("portalguid:".$Pguid.":resonatorhash",$resonatorhash); //JSON
						
						$redis->set("portalguid:".$Pguid.":controllingTeam",$Pinfo->controllingTeam->team); //JSON
						
						$redis->set("portalguid:".$Pguid.":portalV2",$portalV2); //JSON
						$redis->set("portalguid:".$Pguid.":portalV2hash",$portalV2hash);
						
						$redis->set("portalguid:".$Pguid.":capturedTime",$Pinfo->captured->capturedTime); //STRING
						$redis->set("portalguid:".$Pguid.":capturingPlayerId",$Pinfo->captured->capturingPlayerId); //STRING								
					}
				
								
				}
				echo count($res);
				
				/*$redis->set("portalguid:".$Pguid);
				$redis->set("portalguid:".$Pguid.":TITLE");
				$redis->set("portalguid:".$Pguid.":ADDRESS");
				$redis->set("portalguid:".$Pguid.":imageUrl");
				$redis->set("portalguid:".$Pguid.":lat");
				$redis->set("portalguid:".$Pguid.":lng");
				
				#dynamic values
				
				$redis->set("portalguid:".$Pguid.":resonatorArray");
				$redis->set("portalguid:".$Pguid.":locationE6");
				$redis->set("portalguid:".$Pguid.":locationE6:lngE6");
				$redis->set("portalguid:".$Pguid.":locationE6:latE6");
				$redis->set("portalguid:".$Pguid.":controllingTeam");
				$redis->set("portalguid:".$Pguid.":portalV2");
				$redis->set("portalguid:".$Pguid.":captured");
				*/
					
				
			}
			
			
			
			
			
			
			
		}
		
		
		
	}
	
}


?>