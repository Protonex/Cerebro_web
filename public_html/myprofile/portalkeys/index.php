<?php
include("../../var.php");
include(CBASE."common.php");

if($_SERVER['HTTP_REFERER']=="http://www.ingress.com/intel"){ header("Location: /myprofile/intelenhancer/");}
if(!$ob_auth->verified){ header("Location: /?you_need_to_login"); }







$sql = "SELECT settings FROM ingressv2_verified WHERE email='".addslashes($ob_auth->googledata['email'])."'";
$res = $ob_database->get_single($sql);
$settings = (array)json_decode($res['settings']);

//intelitemcheckkeys($ob_auth->);

$tmp = (array)json_decode($ob_auth->verified['settings']);
intelitemcheckkeys($tmp['item-check-userid']);

function intelitemcheckkeys($agentid){
	global $ob_database;
	if ($stream = fopen('http://ingress-item-check.appspot.com/api/getkey/'.$agentid, 'r')) {
		$data = json_decode( stream_get_contents($stream) );
		fclose($stream);
	}
	//if(!is_object($data->nickname)){ return; }
	$nickname = $data->nickname;
	$keys = (array)$data->portalKeys;	
	if(count($keys)<1){return;}
	$sql = "DELETE FROM ingressv2_keys WHERE `nickname`='".addslashes($nickname)."'";
	$ob_database->execute($sql);
	$t=1;
	foreach ($keys as $key=>$value){
		$sql = "INSERT INTO ingressv2_keys (`nickname`,`portalguid`,`keyamount`) VALUES('".addslashes($nickname)."','".addslashes($key)."','".addslashes($value)."')";
		if($ob_database->execute($sql)) { $t++; };
	}
	sleep(1);
	return $t;
}



?><!DOCTYPE html>
<html lang="en">
  <head>
    <?php include(CBASE."head.php");?>
    <title>Ingress tools</title>


    <!-- Le styles -->


    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          
          <div class="nav-collapse collapse">
<?php include(CBASE."/nav.php");?>
            
          </div>  <!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      
      <!-- Main hero unit for a primary marketing message or call to action -->

	  

      <!-- Example row of columns -->
      <div class="span12">
		<?php if($_SERVER['HTTP_REFERER']!="http://www.ingress.com/intel"){ ?>
        <div class="btn-group" data-toggle=""> 
        <a href='/myprofile/' class="btn btn-primary"><i class="icon-info-sign" style='background-image: url("../../img/glyphicons-halflings.png");'></i>&nbsp;</a>
        <a href='/myprofile/bio' class="btn btn-primary">Bio</a>
        <a href='/myprofile/portalkeys' class="btn btn-primary active">Portalkeys</a>
        <a href='/myprofile/favoriteportals' class="btn btn-primary">Favorite portals</a>
        <a href='/myprofile/intelenhancer' class="btn btn-primary" ><i class="icon-wrench" style='background-image: url("../../img/glyphicons-halflings.png");'></i> IntelEnhancer settings</a>
        </div>
        <?php } ?>
        
        
        <h4>Portalkeys</h4>
        <span class="help-block">Uses the portal keys plugin and the Ingress-item-check app for your phone. <a href="http://ingress-item-check.appspot.com/ingress-item-check.apk">Install</a> the ingress-item-check app, and find your ingress-item-check userID <a href="http://ingress-item-check.appspot.com/">here</a>.</span>
        <?php
		
		
		
		$sql = "SELECT 
		ingressv2_keys.nickname,ingressv2_keys.portalguid,ingressv2_keys.keyamount,ingressv2_keys.lastupdated, 
		ingressv2_portals.imageByUrl,ingressv2_portals.TITLE,ingressv2_portals.ADRESS,ingressv2_portals.lat,ingressv2_portals.lng
		FROM ingressv2_keys,ingressv2_portals WHERE ingressv2_keys.nickname='".$ob_auth->verified['nickname']."' AND ingressv2_keys.portalguid=ingressv2_portals.guid ORDER BY ingressv2_keys.keyamount DESC";
        $res = $ob_database->get_array($sql);
		
		foreach($res as $r){
			echo "<div class='btn span4' ><div class='nailthumb-container'><img src='".$r['imageByUrl']."'></div><h3 style=''>".$r['keyamount']."</h3><p style='display:inline;'>".$r['TITLE']."</p><p style='font-size:x-small;display:inline;'>".$r['ADRESS']."</p></div>";
		}
		
		#echo "<pre>";
		#print_r($res);
		#echo "</pre>";
		
		?>
        
        
      </div>
      <div class="span5" >
		<style>
	div.btn p ,div.btn h3 {
		text-align:left;
		float:right;	
		word-wrap: break-word;
	}
	
	
	.nailthumb-container {float:left;}
	
		
		.nailthumb-container { width:150px;height:150px;}
		 .square-thumb
	 {
	    width: 150px;
	    height: 150px;
	 }
		</style>
        <?php 
		
		$sql = "";
		#echo "<pre>";
		#print_r($ob_auth->verified['homeportal']);

		
		?>
        
        
		<ul id='otherportal' class="nav nav-list"></ul>      
      </div>
      <script>

jQuery(document).ready(function() {
	jQuery('.nailthumb-container').nailthumb({width:100,height:100,method:'resize',fitDirection:'top left'});
});	  
	  
	  
	  
	  
	  


	  	$(document).ready(function() {
		// Handler for .ready() called.
		
		
					
			$("#searchportals").click(function(e) {
                var q = $("#searchfield").val();
				$.post("../portalsearch.php", { "q": q },
				  function(data){
					presults(data.results,data.data);
				  }, "json");
            });
		
		});

		function presults(amount,data){
			//console.log(amount); // John
			//console.log(data); //  2pm		
			$("div#searchstatus").html("");
			$("#portallist").empty();
			var index,value;
			for (index in data) {
				value = data[index];
				
				console.log(value);
				$("#portallist").append("<li style='width:100%' class='btn' id='"+value.guid+"' ><div  class='img-rounded pull-left' style='margin-right:5px;background: url("+ value.imageByUrl +") no-repeat center; width:80px;height:80px;'></div><div style='text-align:left;'>"+value.TITLE+"</div><div style='text-align:left'>"+value.ADRESS+"</div>  </li>");
				
				
			}
			if( parseInt(amount) >15 ) { $("div#searchstatus").html("More than 15 results ("+amount+")- be more specific."); } 
			
			
			
			
		}
		
		function poi(type,guid){
			alert("!!!");
			//$.post("../portalbyguid.php", { "guid": guid,"type":type },function(data){
					//alert(data.guid);
//console.log(data);
					//console.log(data.TITLE);
					//alert(data.data.TITLE);
/*
					data.guid;
					data.lat;
					data.lng;
					data.TITLE;
					data.ADRESS;
					data.imageByUrl;
					//presults(data.results,data.data);
					*/
					var rdata ='<div class="nailthumb-container" ><img class="img-polaroid" src='+data.data.imageByUrl+'></div> <div>'+data.data.TITLE+'</div><div >'+data.data.ADRESS+'</div>';
					$("#"+ type +"portal").html(rdata);
					
								
			
			//}, "json");
				  
				  
			//$("ul#homeportal").html( $("li#"+guid).html() );
			//alert(type);
			//alert(guid);
				
		}
      </script>
      <div class="span5" >
		<p>
          <div class="btn-group input-append">
          <input type="text" id='searchfield' class="input-medium search-query add-on" placeholder='city keyword'>
          <div class="btn" id="searchportals">Search</div>
          
          </div>
        <div id='searchstatus'></div>
        <ul class="nav nav-list" id="portallist">
        	
        </ul>
        </p>
        
      </div>

      <div class="span12">
	      <hr>
	      <footer>
            <?php include(CBASE."footer.php");?>
          </footer>      
      </div>
      
      
     </div>

      


<?php


?>
    </div> <!-- /container -->

</body>
</html>
