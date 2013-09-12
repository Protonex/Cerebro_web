<?php 

class userarray {
	var $users = array();
	function addusers($usr,$level){
		
		error_log("################");
		
		
#		print_r($usr);
		
		#echo "adduser ".$usr->plain." $level\n";
		
		
		
		$users = $this->users;
		$tmp="";
		if(count((array)$usr)>0 ){
			$tmp = (array)$usr;
			
			error_log("#####----###### ".$tmp['guid']." ".$tmp['plain']." ".$tmp['team']);
			
			
			//foreach($tmp as $key=>$value){  error_log($key."->".$value); }
			
			$level = (int)str_replace("L","",trim($level));
			//error_log("-----------------------------------");;
			
			if(!isset($tmp['_level'])){  $tmp['_level'] =0; }
			if($tmp['_level']<$level){
			
				$tmp['_level']=$level;
				
			
			};
			$users[$usr->plain]= $tmp;
			
		}
		$this->users = (array)$users;
		
		
			
	}
}

?>