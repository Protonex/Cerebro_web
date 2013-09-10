<?php




class obmysqli
{
  	var $host;
	var $user;
	var $pass;
	var $dbname;
	
	var $dblink;
	var $last_query;
	var $last_queryresults;
	var $log;
	
	function set_host($data)
	{
		$this->host=$data;
	}	
	function set_user($data)
	{
		$this->user=$data;
	}
	function set_pass($data)
	{
		$this->pass=$data;
	}
	function set_dbname($data)
	{
		$this->dbname=$data;
	}

	function log($data)
	{
		$d=$this->log;
		$d[]=$data;
		$this->log=$d;
	}

	function connect()
	{
		global $ob_log;
		//$ob_log->log("-----------" .$this->dbname );
		//$this->host,$this->user, $this->pass, $this->dbname
		
		
		$this->dblink = mysqli_connect($this->host,$this->user, $this->pass, $this->dbname);
		  /* check connection */
		if (mysqli_connect_errno()) {
			$this->error( "x ".mysqli_connect_error() );
			exit();
		}
	}
	
	function execute($sql)
	{ 
		if($result = @mysqli_query($this->dblink, $sql))
		{
			return $result;
		}else {$this->error($sql); }
	}
	
    function execute_get_id($sql)
	{ 
        $result = @mysqli_query($this->dblink, $sql) ;
        if(!$id = @mysqli_insert_id($this->dblink)) { $this->error($sql);}
        return $id; 
    } 	

    function get_single($sql, $type=MYSQL_ASSOC)
	{ 
        $result = @mysqli_query($this->dblink, $sql) ; 
        if(!$data = @mysqli_fetch_array($result, $type)){$this->error($sql);}
        return $data; 
    } 
		 
    function get_value($sql)
	{ 
        $result = @mysqli_query($this->dblink, $sql) ; 
        $data = @mysqli_num_rows($result) ? @mysqli_result($result,0,0) : FALSE;
		$this->error($sql);
        return $data; 
    } 
		 
	function num_rows($sql)
	{ 
		$this->last_query=$sql;
   		
		/* Select queries return a resultset */
		if ($result = mysqli_query($this->dblink, $sql)) {
			$this->queryresults = $result;
  			return mysqli_num_rows($result);
		} else { $this->error($sql); }

   	} 

	function error($sql)
	{
		$error = @mysqli_error($this->dblink);
		 if(!empty($error)) $this->ar_error[] = $sql . "\n" . $error;
		$this->log($sql . " - " . $error);
	}
	
	function close()
	{
		mysqli_close($this->dblink);
	}
	
	function get_array($sql)
	{
		$this->last_query=$sql;
   		$out =array();
		/* Select queries return a resultset */
		if ($result = mysqli_query($this->dblink, $sql)) {
			$this->last_queryresults = $result;
			#print_r($result->num_rows);
  			//while ($row = $result->mysqli_fetch_assoc()) {$out[]=$row;}
			while ($row = mysqli_fetch_assoc($result)) {$out[]=$row;}
			mysqli_free_result($result);
  		}
		
		#mysqli_close($link);
		return $out;
   	}

	function get_large_array($sql)
 	{
		$this->last_query=$sql;
  		/* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
  		if ($result = mysqli_query($link, $sql, MYSQLI_USE_RESULT)) {
  			$this->queryresults = $result;
			
    		while ($row = $result->fetch_assoc()) {$out[]=$row;}

			
		}
		mysqli_free_result($result);
  	
  
  		#mysqli_close($link);	
		return $out;
	}

  
  

	
}


?>