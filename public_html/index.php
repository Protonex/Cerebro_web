<?php
include("var.php");
include(CBASE."common.php");



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include(CBASE."web/head.php");?>
    <title>Ingress [Resistance] tools</title>


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
<?php include(CBASE."web/nav.php");?>
            
          </div>  <!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
<?php if($warning=="nu"){?>
<div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Warning!</h4>
		<p>The script has been updated, please REINSTALL the script.</p>
		<p>Be sure to remove the old version.</p>

</div>
      <?php } ?>
      <!-- Main hero unit for a primary marketing message or call to action -->
      
      <?php if($deviceType=="phone"){ ?>
      

<button class="btn" onclick="lookup_location();return false; ">refresh location </button>
<p id="geocontent">please wait while we get your coordinates:</p>
<?php 
global $ob_auth;
#echo "<pre>";
#print_r($ob_auth);
#$ob_auth->loggedin;
#print_r($ob_auth->verified['guid']);
#print_r($ob_auth->verified['faction']);
#print_r($ob_auth->verified['nickname']);


#echo "</pre>";

 ?>

      
      
      
	  <?php }?>
	  
	  <?php if($deviceType!="phone"){ ?>
<a href='/web/img/ingress-cerebro.jpg'><img class='pull-right' src='/web/img/ingress-cerebro.jpg' width="193px" height="193px"></a>
      <div class="hero-unit">


        <h1>C E R E B R O</h1>
        
<!--<p id="bookmarklet"> Keep calm, and Resist!</p>-->

<p><?php echo $ob_ingress->loginmessage;?></p>
    
          
        

        
      </div>
      <?php }?>

<?php if($deviceType=="phone"){ ?>
<div class="row">

</div>
<?php } ?>


      <!-- Example row of columns -->
<?php if($deviceType!="phone"){ ?>
      <div class="row">
        
       <?php 
	   //print_r($_SESSION['token']);
	   
	   
	$_OUT['plugin'][] = "ap-list";
	$_OUT['plugin'][] = "compute-ap-stats";
	#$_OUT['plugin'][] = "draw-tools";
	$_OUT['plugin'][] = "guess-player-levels";
	$_OUT['plugin'][] = "ipas-link";
	$_OUT['plugin'][] = "keys-on-map";
	$_OUT['plugin'][] = "keys";	
	$_OUT['plugin'][] = "portal-level-numbers";
	$_OUT['plugin'][] = "resonator-display-zoom-level-decrease";	
	$_OUT['plugin'][] = "scoreboard";
	
	#$_OUT['plugin'][] = "max-links";
	#$_OUT['plugin'][] = "pan-control";
	$_OUT['plugin'][] = "player-tracker";
	/*
	$_OUT['plugin'][] = "portal-counts";
	$_OUT['plugin'][] = "portals-list";
	$_OUT['plugin'][] = "privacy-view";	
	$_OUT['plugin'][] = "render-limit-increase";	
	$_OUT['plugin'][] = "reso-energy-pct-in-portal-detail";	

	$_OUT['plugin'][] = "scale-bar";	
	;	
	$_OUT['plugin'][] = "show-address";	
	$_OUT['plugin'][] = "show-linked-portals";	
	$_OUT['plugin'][] = "show-portal-weakness";	
	$_OUT['plugin'][] = "zoom-slider";	
	
	*/
		?>
        
        
        <div class="span4">
          <h2>What is Ingress?</h2>
          <p>Ingress is a near-realtime augmented reality massively multiplayer online video game created by NianticLabs@Google and released for Android devices. The game has a complex backstory that Google is revealing piece by piece.</p>
          <p><a class="btn" href="http://en.wikipedia.org/wiki/Ingress_%28game%29">View details &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>How to get it?</h2>
          <p>Ingress is not available for the public, however the ongoing closed beta began November 2012 and more and more 'invites' are being issued. on the main website you can request an invite.</p>
          <p><a class="btn" href="http://ingress.com/">Get invited &raquo;</a></p>
       </div>
        <div class="span4">
          <?php if(!$ob_auth->googledata){?>
          <iframe width="400" height="300" src="http://www.youtube.com/embed/ooMsC2HkpbU" frameborder="0" allowfullscreen></iframe> 
     
          <?php }else{?>
          <h2>Whois</h2>
          <p>Identify a player by entering it's email adress or ingame name</p>
          <p id='whoisdata'></p>
          <p><input type='text' name='whois' id='whois'><div class='btn' id='whoisbutton'>Whois</div></p>
          <p><!--<a class="btn" href="#">View details &raquo;</a>--></p>
          <?php }?>
        </div>
      </div>
<?php } ?>
      <hr>

      <footer>
        <?php include(CBASE."web/footer.php");?>
      </footer>
<?php
echo "<pre>";
#print_r($ob_ingres);

#print_r($ob_auth->googdata['email']);
echo "</pre>";

?>
    </div> <!-- /container -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="/web/js/modernizr.js"></script>
<script src="/web/js/geo.js"></script>
<script>
	$(document).ready(function() {
		// Handler for .ready() called.
		$("div#whoisbutton").click( function(e){
			var txt = $("input#whois").val();
			$.post("/verify/whois.php", { txt: txt }).done(function(data) {
				$("p#whoisdata").html(data);
				//alert("Data Loaded: " + data);
			});
			//alert(txt );
		});
	});
</script>
<script>
//	$(document).ready(function() {
//		if (navigator.geolocation) {
//		  //alert("geo!");
//		  navigator.geolocation.getCurrentPosition(success, error);
//		} else {
//		  error('not supported');
//		}
//	}
</script>
<script>

	var x=document.getElementById("demo");


	function success(position) {
	  
	  $.post("/verify/geo.php", { email:'<?php echo $ob_auth->googdata['email'];?>', lat: position.coords.latitude,lng:position.coords.longitude,acc:position.coords.accuracy }).done(function(data) {
					$("p#whoisdata").html(data);
					//alert("Data Loaded: " + data);
				});
	  
	
	
	}
	
	function error(msg) {
	  var s = document.querySelector('#status');
	  s.innerHTML = typeof msg == 'string' ? msg : "failed";
	  s.className = 'fail';
	  
	  // console.log(arguments);
	}

	function lookup_location() {
	  geo_position_js.getCurrentPosition(geo_success, geo_error);
	}

	function geo_success(p) {
		//alert("Found you at latitude " + p.coords.latitude + ", longitude " + p.coords.longitude);
		$.post("/api/test/fptest.php", { lat: p.coords.latitude, lng: p.coords.longitude , plguid: "<?php echo $ob_auth->verified['guid'];?>" }).done(function(data) {
			$("p#geocontent").html(data);
			//alert("Data Loaded: " + data);
		});		
		//alert("Data Loaded: " + data);
	}
	function geo_error() {
		alert("Could not find you!");
	}
	function showPosition(position)
  	{
		x.innerHTML="xLatitude: " + position.coords.latitude + "<br>xLongitude: " + position.coords.longitude;
		success(position);	
 	}

	if (geo_position_js.init()) {
		  geo_position_js.getCurrentPosition(geo_success, geo_error);
	}


</script>
</body>
</html>
