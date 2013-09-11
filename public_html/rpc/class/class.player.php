<?php

class player
{
	var $log;
	
	
	function get_by_name(){
		$redis->select(0); # select databaseID
		
	}
	
	function get_by_guid($guid){
		global $ob_database,$redis;

		$redis->select(0); # select databaseID
		
	#playerguid:782372983:faction
	#playerguid:782372983:nick
	#playerguid:782372983:level
	#playerguid:782372983:lastupdated
	#playerguid:782372983:lastlocation
	
		if($redis->exists('playerguid:'.$plguid.':lastupdated')){
			# plguid exist in lookuptable
			$out['guid']		= $plguid;
			$out['faction']		= $redis->get('playerguid:'.$plguid.':faction');
			$out['nick']		= $redis->get('playerguid:'.$plguid.':nick');
			$out['level']		= $redis->get('playerguid:'.$plguid.':level');
			$out['lastupdated'] = $redis->get('playerguid:'.$plguid.':lastupdated');
			$out['lastlocation'] = $redis->get('playerguid:'.$plguid.':lastlocation');
		} else {
			# playerguid doesnt exist, get from mysql 
			
			
			
			
				
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