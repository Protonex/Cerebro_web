<?php

class player
{
	var $log;
	
	function check_user($u)
	{
		global $ob_database,$redis;
		if(USE_REDIS){
			$redis->select(0); /*select databaseID*/ 
			#echo "using redis\n";
			
			$test = $this->get_by_guid($u['guid']);
			#var_dump($test);
			
			if( !$test )
			{
				$this->log("key does not exist in redis and not in mysql");
				#echo "key does not exist in redis and not in mysql\n";
				
				$sql = "INSERT INTO ingress_players ( `guid` , `name` ,  `faction`   )VALUES ('".$u['guid']."','".$u['plain']."','".$u['team']."')";
				$ob_database->execute($sql);
				
				$u['plain'];
				$u['guid'];
				$u['team'];
				$u['_level'];
				


				$redis->set('playerguid:'.$u['guid'].':guid', $u['guid'] );
				$redis->set('playerguid:'.$u['guid'].':faction', $u['team'] );
				$redis->set('playerguid:'.$u['guid'].':nick', $u['plain'] );
				//$redis->set('playerguid:'.$plguid.':level', $u['_level'] );
				//$redis->set('playerguid:'.$plguid.':lastupdated', $u['lastupdated'] );	
				//$redis->set('playerguid:'.$plguid.':lastlocation', $u['lastseenat'] );
				$redis->set('playername:'.$u['plain'],$u['guid'] );
				#print_r($u);
				#die("!");
				
			} 
			else 
			{
				$this->log("key exist in redis ( and so it does in mysql)");	
				
				#print_r($test);
				#print_r($u);
				#die("key does exist in redis ( and so it does in mysql)");
				
				
				
				
			}
			
			
			
		} else {
		
				$sql = "INSERT INTO ingress_players ( `guid` , `name` ,  `faction`   )VALUES ('".$u['guid']."','".$u['plain']."','".$u['team']."')";
				$ob_database->execute($sql);					
		}			




		echo $u['guid']."\n";
		#die("stop");
		
	}
	
	
	
	
	
	
	
	function get_by_name($name){
		if(USE_REDIS)
		{	
			$redis->select(0); # select databaseID
			if( $redis->exists('playername:'.$name))
			{
				$plguid = $redis->get('playername:'.$name);
				return get_by_guid($plguid);
			}
			else
			{
				$sql = "SELECT * FROM ingress_players WHERE name='".addslashes($name)."'";
				$usr = $ob_database->get_single($sql);
				if($usr==NULL)
				{ 
					//echo "plname not in mysql\n";
					return false; 
				}	
				
				
				# store everything in redis.
				
				$redis->set('playername:'.$usr['name'],$usr['guid'] );
				
				
				$out['guid']		 = $usr['guid'];
				$redis->set('playerguid:'.$plguid.':guid', $out['guid'] );
				
				$out['faction']		 = $usr['faction'];
				$redis->set('playerguid:'.$plguid.':faction', $out['faction'] );
				
				$out['nick']		 = $usr['name'];
				$redis->set('playerguid:'.$plguid.':nick', $out['nick'] );
				
				$out['level']		 = $usr['level'];
				$redis->set('playerguid:'.$plguid.':level', $out['level'] );
				
				$out['lastupdated']  = $usr['lastupdated'];
				$redis->set('playerguid:'.$plguid.':lastupdated', $out['lastupdated'] );
				
				$out['lastlocation'] = $usr['lastseenat'];			
				$redis->set('playerguid:'.$plguid.':lastlocation', $out['lastseenat'] );
				
				return $out;					
				
				
								
			}
			
		}
		else
		{
			$sql = "SELECT * FROM ingress_players WHERE name='".addslashes($name)."'";
			$usr = $ob_database->get_single($sql);
			if($usr==NULL)
			{ 
				//echo "plguid not in mysql\n";
				return false; 
			}
				$out['guid']		 = $usr['guid'];
				$out['faction']		 = $usr['faction'];
				$out['nick']		 = $usr['name'];
				$out['level']		 = $usr['level'];
				$out['lastupdated']  = $usr['lastupdated'];
				$out['lastlocation'] = $usr['lastseenat'];			
				return $out;	
		}
		
		
	}
	
	function get_by_guid($plguid){
		global $ob_database,$redis;

		$this->log("get_by_guid($plguid)");
		if(USE_REDIS)
		{	
			$this->log("using redis");
			$redis->select(0); /* select databaseID */	
			
			if( $redis->exists('playerguid:'.$plguid.':lastupdated')){
				//echo "found key in redis!";
				$this->log("plguid exist in redis lookuptable");
				$out['guid']		 = $plguid;
				$out['faction']		 = $redis->get('playerguid:'.$plguid.':faction');
				$out['nick']		 = $redis->get('playerguid:'.$plguid.':nick');
				$out['level']		 = $redis->get('playerguid:'.$plguid.':level');
				$out['lastupdated']  = $redis->get('playerguid:'.$plguid.':lastupdated');
				$out['lastlocation'] = $redis->get('playerguid:'.$plguid.':lastlocation');
				return $out;
				
			} else {
				$this->log("plguid key not in redis");
				
				# playerguid doesnt exist, get from mysql 
				
				$sql = "SELECT * FROM ingress_players WHERE guid='".addslashes($plguid)."'";
				$usr = $ob_database->get_single($sql);
				if($usr==NULL)
				{ 
					$this->log("plguid not in mysql");
					return false; 
				}

							
				# store everything in redis.
				$this->log("store everything in redis");
				
				$out['guid']		 = $usr['guid'];
				$redis->set('playerguid:'.$plguid.':guid', $out['guid'] );
				
				$out['faction']		 = $usr['faction'];
				$redis->set('playerguid:'.$plguid.':faction', $out['faction'] );
				
				$out['nick']		 = $usr['name'];
				$redis->set('playerguid:'.$plguid.':nick', $out['nick'] );
				
				$out['level']		 = $usr['level'];
				$redis->set('playerguid:'.$plguid.':level', $out['level'] );
				
				$out['lastupdated']  = $usr['lastupdated'];
				$redis->set('playerguid:'.$plguid.':lastupdated', $out['lastupdated'] );
				
				$out['lastlocation'] = $usr['lastseenat'];			
				$redis->set('playerguid:'.$plguid.':lastlocation', $out['lastseenat'] );
				
				return $out;		
			}				
		
		}
		else
		{
				$this->log("NOT using redis");
				$sql = "SELECT * FROM ingress_players WHERE guid='".addslashes($plguid)."'";
				$usr = $ob_database->get_single($sql);
				//$USER = $usr['name'];
				if($usr==NULL)
				{ 
					//echo "plguid not in mysql\n";
					return false; 
				}

				$out['guid']		 = $usr['guid'];
				$out['faction']		 = $usr['faction'];
				$out['nick']		 = $usr['name'];
				$out['level']		 = $usr['level'];
				$out['lastupdated']  = $usr['lastupdated'];
				$out['lastlocation'] = $usr['lastseenat'];	
				
				return $out;				
		}

		
		

	

		
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