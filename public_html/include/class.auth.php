<?php 

class auth {

	var $log;
	function log($data)
	{
		//error_log($data);
		$l=$this->log;
		$l[]=$data;
		$this->log=$l;
	}	

	function gauth(){	
		require_once CBASE.'/google-api-php-client/src/Google_Client.php';
		require_once CBASE.'/google-api-php-client/src/contrib/Google_Oauth2Service.php';
	
	//	if(!$_SESSION){ session_start(); }
	
		$client = new Google_Client();
		$client->setApplicationName("Google UserInfo PHP Starter Application");
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
		// $client->setClientId('insert_your_oauth2_client_id');
		// $client->setClientSecret('insert_your_oauth2_client_secret');
		// $client->setRedirectUri('insert_your_redirect_uri');
		// $client->setDeveloperKey('insert_your_developer_key');
		$oauth2 = new Google_Oauth2Service($client);
		
		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['token'] = $client->getAccessToken();
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		  return;
		}
		
		if (isset($_SESSION['token'])) {
		 $client->setAccessToken($_SESSION['token']);
		}
		
		if (isset($_REQUEST['logout'])) {
		  unset($_SESSION['token']);
		  $client->revokeToken();
		  unset($_COOKIE['ires']);
		  setcookie("ires", "", time()-3600,"/");
		  setcookie("PHPSESSID", "", time()-3600,"/");
		  unset($_SESSION['PHPSESSID']);
		}
		
		if ($client->getAccessToken()) {
		  
		  $user = $oauth2->userinfo->get();
		  $this->google($user);
		  
		
		
		
		
		  // These fields are currently filtered through the PHP sanitize filters.
		  // See http://www.php.net/manual/en/filter.filters.sanitize.php
		  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
		  $personMarkup = "$email<div><a href='/myprofile'><img class=\"img-rounded\" src='$img?sz=38'></a></div>";
		
		  // The access token may have been updated lazily.
		  $_SESSION['token'] = $client->getAccessToken();
		  
		} else {
		  $authUrl = $client->createAuthUrl();
		  $this->authUrl = $authUrl;
		}
	
	

	
	}
	
	
	
	function __construct(){
		if (isset($_REQUEST['logout'])) {
		  unset($_SESSION['token']);
		  unset($_COOKIE['ires']);	
		}
	
		//$this->loggedin = FALSE;
		$this->log("construct");

		$this->gauth();
		
		$res = $this->get_cookie();
		if(!$res){
			$this->loggedin = FALSE;
			$this->log("cookie niet set.");
			if(isset($_REQUEST['logout'])){ $this->logout(); header("Location: /");}
			return;
		}
		$this->log("re-set cookie");
		$this->set_cookie($res);
		
		
		if($this->is_verified_player()){
			$this->log("player is verified! ");
			
		}
		else
		{
			$this->log("player is not verified ");
			
			
			$this->msg  = "<h2>One more step to take..</h2>";
			$this->msg  .= "<p>In order to use this website, we need to verify you as an agent on the field.";
			$this->msg  .=" Use our custom intel enhancer, to verify your agent status and faction. ";
			$this->msg  .="Once you are verified, all tools will be unlocked</p>" ;
			
			$this->msg  .="<ul><li><a href='/script/iitcplugin/ips2main.user.js'>Download</a> and install the plugin below.</li>";
			$this->msg  .="<li>Navigate to the <a href='http://ingress.com/intel'>ingress.com/intel</a> website, and login.</li>";
			$this->msg  .="<li>You will be redirected to this website and check your <a href='/myprofile'>settings</a>.</li>";
			
			
			
			$redir = "/script/";
			if($_SERVER['REQUEST_URI']!=$redir){
					header("Location: ".$redir);
			};
			
		}
		
		
		
		$this->personMarkup = "<a href='/myprofile'>".$this->googledata['email']."<div><img class=\"img-rounded\" src='".$this->googledata['picture']."?sz=38'></a></div>";
		
		
	}


	function is_verified_player(){
		global $ob_database;
		
		$sql= "SELECT * FROM ingressv2_verified WHERE email='".addslashes($this->googledata['email'])."'";
		$res = $ob_database->get_single($sql);
		if($res){
			unset($res['google']);
			$this->verified = $res;
			$res['guid'];
			$res['faction'];
			$res['nickname'];
			return true;
		}else{
			return false;	
		}
		
		
	}

	function get_cookie(){
		$this->log("get_cookie()");
		if(!isset($_COOKIE['ires'])){
			$this->log("no cookie found");
			return false;
		}
		
		
		if( isset($_COOKIE['ires']) ){
			$this->log("cookie found");
		}
		$userid = $_COOKIE['ires'];
		
		$this->user_exists($userid);
		
		return $userid;

		//if(!$this->user_exists($userid)){ die("!hack!"); }
#		var_dump($userid);
#		die();
		
	}
	
	function set_cookie($id){
		#$c['id']=102634396402324749838
		#$c['ip']=$_SERVER['REMOTE_ADDR'];
		#$value=json_encode($array);
		setcookie("ires", $id, time()+(3600*24), '/');  /* expire in 1 hour */
		
		
		
		
		$this->googledata ;
		
	}



	function user_exists($gid){
		global $ob_database;
		$res = $ob_database->get_single("SELECT * FROM web_users WHERE id='".$gid."'");
		if ($res==NULL){ 
			$this->log("user doesnt exist.");
			return FALSE;
		}else{ 
			$this->log("user exists.");	
			
			$this->googledata = $res;
			$this->loggedin = TRUE;
			return TRUE; 
		}		
	}


	function google($user){
		$this->log("google()  -> invoked from oauthcallback.");
		$this->googledata = $user;
		
		if(!$this->user_exists($user['id'])){
			$this->log("newuser");
			$this->newuser($user);
			$this->set_cookie($user['id']); 
		}else { 
			$this->log("existinguser");
			$this->loggedin = TRUE;
			
			$this->is_verified_player();
			$this->set_cookie($user['id']); 
			$this->personMarkup = "<a href='/myprofile'>".$this->googledata['email']."<div><img class=\"img-rounded\" src='".$this->googledata['picture']."?sz=38'></a></div>";
			
		}
		
		
		
		
	}





	
/*
    $g[id] => 102634396402324749838
    $g[email] => hopper.jerry@gmail.com
    $g[verified_email] => 1
    $g[name] => Jerry Hopper
    $g[given_name] => Jerry
    $g[family_name] => Hopper
    $g[link] => https://plus.google.com/102634396402324749838
    $g[picture] => https://lh4.googleusercontent.com/-Ndls8XYHihY/AAAAAAAAAAI/AAAAAAAAcCs/c0q3CQyzniI/photo.jpg
    $g[gender] => male
    $g[birthday] => 0000-02-15
    $g[locale] => en
*/
	function newuser($g){
		global $ob_database;
		
		
		$isql = "INSERT INTO web_users (`id`, `email`, `verified_email`, `name`, `given_name`, `family_name`, `link`, `picture`, `gender`, `birthday`, `locale`) 
		VALUES 
		('".$g['id']."', '".$g['email']."', '".$g['verified_email']."', '".$g['name']."', '".$g['given_name']."', '".$g['family_name']."', '".$g['link']."', '".$g['picture']."', '".$g['gender']."', '".$g['birthday']."', '".$g['locale']."');";
		$ob_database->execute($isql);
		
		
		
		
	
	}

	function logout(){
			global $client;
		  unset($_SESSION['token']);
		  
		  unset($_COOKIE['ires']);
		  setcookie("ires", "", time()-3600,"/");
		  setcookie("PHPSESSID", "", time()-3600,"/");
		  unset($_SESSION['PHPSESSID']);
		if($client){$client->revokeToken();}
	}

		
}









$ob_auth = new auth();


?>