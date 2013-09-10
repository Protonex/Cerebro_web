<?php
include("../../var.php");
include(CBASE."common.php");


if(!$ob_auth->verified){ header("Location: /?you_need_to_login"); }


if(isset($_POST['s'])){
if($_POST['s']){
	foreach($_POST['s'] as $key=>$value){
		
		$array[$key]=$value;
		
	}
	$array['item-check-userid'] = $_POST['item-check-userid'];
	
	#print_r($array);
	#die();
	$sql = "UPDATE ingressv2_verified SET settings='".json_encode($array)."' WHERE email='".addslashes($ob_auth->googledata['email'])."'";
	$ob_database->execute($sql);
	header("Location: http://ingress.com/intel");
}
}



$sql = "SELECT settings FROM ingress_verified WHERE email='".addslashes($ob_auth->googledata['email'])."'";
$res = $ob_database->get_single($sql);
$settings = (array)json_decode($res['settings']);




?><!DOCTYPE html>
<html lang="en">
  <head>
    <?php include(CBASE."web/head.php");?>
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
<?php include(CBASE."web/nav.php");?>
            
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
        <a href='/myprofile/favoriteportals' class="btn btn-primary">Favorite portals</a>
        <a href='/myprofile/intelenhancer' class="btn btn-primary active" ><i class="icon-wrench" style='background-image: url("../../img/glyphicons-halflings.png");'></i> IntelEnhancer settings</a>
        </div>
        <?php } ?>

    <h4>Intel Enhancer settings</h4><br>
	<p>Choose the plugins that you want to use with <a href='http://ingress.com/intel'>ingress.com/intel</a></p>   



    <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#general" data-toggle="tab">General</a></li>
    <li><a href="#portalinfo" data-toggle="tab">Portal info</a></li>
    <li><a href="#info" data-toggle="tab">Info</a></li>
	<li><a href="#keys" data-toggle="tab">Keys</a></li>
    <li><a href="#controls" data-toggle="tab">Controls</a></li>
    <li><a href="#highlighter" data-toggle="tab">Highlighter</a></li>
    <li><a href="#layer" data-toggle="tab">Layer</a></li>
    <li><a href="#maptiles" data-toggle="tab">Map Tiles</a></li>
    <li><a href="#tweaks" data-toggle="tab">Tweaks</a></li>
    <li><a href="#misc" data-toggle="tab">Misc</a></li>
    
    
    </ul>

   <form action="" method="post">
  <fieldset>

     
    <div class="tab-content">
    <div class="tab-pane active" id="general">


    <label class="checkbox">
      <input type="checkbox" id="checkbox-privacy" name="s[privacy-mode]" <?php if(isset($settings['privacy-mode'])){ echo "checked"; } ?>> Privacy mode
    	<br><i>Hides real name, email and other Google profile info from others.</i>
    </label>
    <hr>
    </div>
    <div class="tab-pane" id="portalinfo">
    <label for="checkbox-ipas-link">
    	<input type="checkbox" id="checkbox-ipas-link" name="s[ipas-link]" <?php if(isset($settings['ipas-link'])){ echo "checked"; } ?>> Simulate an attack on portal.<br>Adds a link to the portal details to open the portal in IPAS - Ingress Portal Attack Simulator on http://ipas.graphracer.com
    </label>
    <label for="checkbox-reso-energy-pct-in-portal-detail">
    	<input type="checkbox" id="checkbox-reso-energy-pct-in-portal-detail" name="s[reso-energy-pct-in-portal-detail]" <?php if(isset($settings['reso-energy-pct-in-portal-detail'])){ echo "checked"; } ?>>  reso energy pct in portal detail
    </label>
    <label for="checkbox-show-address">
    	<input type="checkbox" id="checkbox-show-address" name="s[show-address]" <?php if(isset($settings['show-address'])){ echo "checked"; } ?>> show portal address in sidebar
    </label>
    <label for="checkbox-show-linked-portals">
    	<input type="checkbox" id="checkbox-show-linked-portals" name="s[show-linked-portals]" <?php if(isset($settings['show-linked-portals'])){ echo "checked"; } ?>>  Show linked portals
    </label>        
    </div>
    <div class="tab-pane" id="info">
    <label for="checkbox-ap-list">
    	<input type="checkbox" id="checkbox-ap-list" name="s[ap-list]" <?php if(isset($settings['ap-list'])){ echo "checked"; } ?>> ap-list<br>
    </label>
    <label for="checkbox-compute-ap-stats">
    	<input type="checkbox" id="checkbox-compute-ap-stats" name="s[compute-ap-stats]" <?php if(isset($settings['compute-ap-stats'])){ echo "checked"; } ?>> compute-ap-stats <br>
    </label>
    <label for="checkbox-guess-player-levels">
    	<input type="checkbox" id="checkbox-guess-player-levels" name="s[guess-player-levels]" <?php if(isset($settings['guess-player-levels'])){ echo "checked"; } ?>> guess-player-levels<br>
    </label>
    <label for="checkbox-portal-counts">
    	<input type="checkbox" id="checkbox-portal-counts" name="s[portal-counts]" <?php if(isset($settings['portal-counts'])){ echo "checked"; } ?>> portal-counts<br>
    </label>
    <label for="checkbox-portals-list">
    	<input type="checkbox" id="checkbox-portals-list" name="s[portals-list]" <?php if(isset($settings['portals-list'])){ echo "checked"; } ?>> portals-list<br>
    </label>
    <label for="checkbox-scoreboard">
    	<input type="checkbox" id="checkbox-scoreboard" name="s[scoreboard]" <?php if(isset($settings['scoreboard'])){ echo "checked"; } ?>> scoreboard<br>
    </label>

    
    </div>
    <div class="tab-pane" id="keys">
    
    <label for="checkbox-keys">
    	<input type="checkbox" id="checkbox-keys" name="s[keys]" <?php if(isset($settings['keys'])){ echo "checked"; } ?>> Keys<br>
    </label>


   <label>Itemcheck userID</label>
    <input type="text" id='item-check-userid' name="item-check-userid" placeholder="Item-check-userid" <?php if(isset($settings['item-check-userid'])){ echo "value='".$settings['item-check-userid']."'"; } ?> >
    <span class="help-block"><a href='http://ingress-item-check.appspot.com/ingress-item-check.apk' >Install</a> and find your ingress-item-check userID <a href='http://ingress-item-check.appspot.com/'>here</a>.</span>
        
    
    </div>
    <div class="tab-pane" id="controls">


    <label for="checkbox-minimap">
    	<input type="checkbox" id="checkbox-minimap" name="s[minimap]" <?php if(isset($settings['minimap'])){ echo "checked"; } ?>> minimap<br>
    </label>  

    <label for="checkbox-pan-control">
    	<input type="checkbox" id="checkbox-pan-control" name="s[pan-control]" <?php if(isset($settings['pan-control'])){ echo "checked"; } ?>> pan-control<br>
    </label>  
    

    <label for="checkbox-scale-bar">
    	<input type="checkbox" id="checkbox-scale-bar" name="s[scale-bar]" <?php if(isset($settings['scale-bar'])){ echo "checked"; } ?>> scale-bar<br>
    </label>  
    

    <label for="checkbox-zoom-slider">
    	<input type="checkbox" id="checkbox-zoom-slider" name="s[zoom-slider]" <?php if(isset($settings['zoom-slider'])){ echo "checked"; } ?>> zoom-slider<br>
    </label>  
    
    
    
    
    </div>
    <div class="tab-pane" id="highlighter">

    <label for="checkbox-portal-highlighter-can-make-level">
    	<input type="checkbox" id="checkbox-portal-highlighter-can-make-level" name="s[portal-highlighter-can-make-level]" <?php if(isset($settings['portal-highlighter-can-make-level'])){ echo "checked"; } ?>> portal-highlighter-can-make-level<br>
    </label>  
    
    <label for="checkbox-portal-highlighter-level-color">
    	<input type="checkbox" id="checkbox-portal-highlighter-level-color" name="s[portal-highlighter-level-color]" <?php if(isset($settings['portal-highlighter-level-color'])){ echo "checked"; } ?>> portal-highlighter-level-color<br>
    </label>  
       <label for="checkbox-portal-highlighter-missing-resonators">
    	<input type="checkbox" id="checkbox-portal-highlighter-missing-resonators" name="s[portal-highlighter-missing-resonators]" <?php if(isset($settings['portal-highlighter-missing-resonators'])){ echo "checked"; } ?>> portal-highlighter-missing-resonators<br>
    </label>  
       <label for="checkbox-portal-highlighter-my-8-portals">
    	<input type="checkbox" id="checkbox-portal-highlighter-my-8-portals" name="s[portal-highlighter-my-8-portals]" <?php if(isset($settings['portal-highlighter-my-8-portals'])){ echo "checked"; } ?>> portal-highlighter-my-8-portals<br>
    </label>  
       <label for="checkbox-portal-highlighter-my-portals">
    	<input type="checkbox" id="checkbox-portal-highlighter-my-portals" name="s[portal-highlighter-my-portals]" <?php if(isset($settings['portal-highlighter-my-portals'])){ echo "checked"; } ?>> portal-highlighter-my-portals<br>
    </label>  
       <label for="checkbox-portal-highlighter-needs-recharge">
    	<input type="checkbox" id="checkbox-portal-highlighter-needs-recharge" name="s[portal-highlighter-needs-recharge]" <?php if(isset($settings['portal-highlighter-needs-recharge'])){ echo "checked"; } ?>> portal-highlighter-needs-recharge<br>
    </label>  
       <label for="checkbox-portal-highlighter-portal-ap">
    	<input type="checkbox" id="checkbox-portal-highlighter-portal-ap" name="s[portal-highlighter-portal-ap]" <?php if(isset($settings['portal-highlighter-portal-ap'])){ echo "checked"; } ?> <?php if(isset($settings[''])){ echo "checked"; } ?>> portal-highlighter-portal-ap<br>
    </label>  
       <label for="checkbox-portal-highlighter-portal-ap-energy-relative">
    	<input type="checkbox" id="checkbox-portal-highlighter-portal-ap-energy-relative" name="s[portal-highlighter-portal-ap-energy-relative]" <?php if(isset($settings['portal-highlighter-portal-ap-energy-relative'])){ echo "checked"; } ?>> portal-highlighter-portal-ap-energy-relative<br>
    </label>  
       <label for="checkbox-portal-highlighter-portal-ap-relative">
    	<input type="checkbox" id="checkbox-portal-highlighter-portal-ap-relative" name="s[portal-highlighter-portal-ap-relative]" <?php if(isset($settings['portal-highlighter-portal-ap-relative'])){ echo "checked"; } ?>> portal-highlighter-portal-ap-relative<br>
    </label>  
       <label for="checkbox-portal-highlighter-portals-my-level">
    	<input type="checkbox" id="checkbox-portal-highlighter-portals-my-level" name="s[portal-highlighter-portals-my-level]" <?php if(isset($settings['portal-highlighter-portals-my-level'])){ echo "checked"; } ?>> portal-highlighter-portals-my-level<br>
    </label>  
       <label for="checkbox-portal-highlighter-portals-upgrade">
    	<input type="checkbox" id="checkbox-portal-highlighter-portals-upgrade" name="s[portal-highlighter-portals-upgrade]" <?php if(isset($settings['portal-highlighter-portals-upgrade'])){ echo "checked"; } ?>> portal-highlighter-portals-upgrade<br>
    </label>  
       <label for="checkbox-portal-highlighter-with-lvl8-resonators">
    	<input type="checkbox" id="checkbox-portal-highlighter-with-lvl8-resonators" name="s[portal-highlighter-with-lvl8-resonators]" <?php if(isset($settings['portal-highlighter-with-lvl8-resonators'])){ echo "checked"; } ?>> portal-highlighter-with-lvl8-resonators<br>
    </label>  
       <label for="checkbox-show-portal-weakness">
    	<input type="checkbox" id="checkbox-show-portal-weakness" name="s[show-portal-weakness]" <?php if(isset($settings['show-portal-weakness'])){ echo "checked"; } ?>> show-portal-weakness<br>
    </label>  


       </div>
    <div class="tab-pane" id="layer">

    <label for="checkbox-draw-resonators">
    	<input type="checkbox" id="checkbox-draw-resonators" name="s[draw-resonators]" <?php if(isset($settings['draw-resonators'])){ echo "checked"; } ?>> draw-resonators<br>
    </label>  


    <label for="checkbox-portal-defense">
    	<input type="checkbox" id="checkbox-portal-defense" name="s[portal-defense]" <?php if(isset($settings['portal-defense'])){ echo "checked"; } ?>> portal-defense<br>
    </label>  

    <label for="checkbox-zaprange">
    	<input type="checkbox" id="checkbox-zaprange" name="s[zaprange]" <?php if(isset($settings['zaprange'])){ echo "checked"; } ?>> zaprange<br>
    </label>  

    <label for="checkbox-draw-tools">
    	<input type="checkbox" id="checkbox-draw-tools" name="s[draw-tools]" <?php if(isset($settings['draw-tools'])){ echo "checked"; } ?>> draw-tools<br>
    </label>    
    

    <label for="checkbox-max-links">
    	<input type="checkbox" id="checkbox-max-links" name="s[max-links]" <?php if(isset($settings['max-links'])){ echo "checked"; } ?>> max-links<br>
    </label>    
    

    <label for="checkbox-player-tracker">
    	<input type="checkbox" id="checkbox-player-tracker" name="s[player-tracker]" <?php if(isset($settings['player-tracker'])){ echo "checked"; } ?>> player-tracker<br>
    </label>    
    


    <label for="checkbox-portal-level-numbers">
    	<input type="checkbox" id="checkbox-portal-level-numbers" name="s[portal-level-numbers]" <?php if(isset($settings['portal-level-numbers'])){ echo "checked"; } ?>> portal-level-numbers<br>
    </label>    
    
    </div>
    <div class="tab-pane" id="maptiles">
    
    <label for="checkbox-basemap-blank">
    	<input type="checkbox" id="checkbox-basemap-blank" name="s[basemap-blank]" <?php if(isset($settings['basemap-blank'])){ echo "checked"; } ?>> basemap-blank<br>
    </label>
        
    <label for="checkbox-basemap-cloudmade">
    	<input type="checkbox" id="checkbox-basemap-cloudmade" name="s[basemap-cloudmade]" <?php if(isset($settings['basemap-cloudmade'])){ echo "checked"; } ?>> basemap-cloudmade<br>
    </label>
        
    <label for="checkbox-basemap-opencyclemap">
    	<input type="checkbox" id="checkbox-basemap-opencyclemap" name="s[basemap-opencyclemap]" <?php if(isset($settings['basemap-opencyclemap'])){ echo "checked"; } ?>> basemap-opencyclemap<br>
    </label>
        
    <label for="checkbox-basemap-openstreetmap">
    	<input type="checkbox" id="checkbox-basemap-openstreetmap" name="s[basemap-openstreetmap]" <?php if(isset($settings['basemap-openstreetmap'])){ echo "checked"; } ?>> basemap-openstreetmap<br>
    </label>
        
    <label for="checkbox-basemap-yandex">
    	<input type="checkbox" id="checkbox-basemap-yandex" name="s[basemap-yandex]" <?php if(isset($settings['basemap-yandex'])){ echo "checked"; } ?>> basemap-yandex<br>
    </label>      
    
    
    </div>
    <div class="tab-pane" id="tweaks">
    
    <label for="checkbox-render-limit-increase">
    	<input type="checkbox" id="checkbox-render-limit-increase" name="s[render-limit-increase]" <?php if(isset($settings['render-limit-increase'])){ echo "checked"; } ?>> render-limit-increase<br>
    </label>    

    <label for="checkbox-resonator-display-zoom-level-decrease">
    	<input type="checkbox" id="checkbox-resonator-display-zoom-level-decrease" name="s[resonator-display-zoom-level-decrease]" <?php if(isset($settings['resonator-display-zoom-level-decrease'])){ echo "checked"; } ?>> resonator-display-zoom-level-decrease<br>
    </label>    

    </div>
    <div class="tab-pane" id="misc">
    <label for="checkbox-bookmarks-by-zaso">
    	<input type="checkbox" id="checkbox-bookmarks-by-zaso" name="s[bookmarks-by-zaso]" <?php if(isset($settings['bookmarks-by-zaso'])){ echo "checked"; } ?>> bookmarks-by-zaso<br>
    </label>
    <label for="checkbox-favorite-portals">
    	<input type="checkbox" id="checkbox-favorite-portals" name="s[favorite-portals]" <?php if(isset($settings['favorite-portals'])){ echo "checked"; } ?>> favorite-portals<br>
    </label>
    <label for="checkbox-players-resonators">
    	<input type="checkbox" id="checkbox-players-resonators" name="s[players-resonators]" <?php if(isset($settings['players-resonators'])){ echo "checked"; } ?>> players-resonators<br>
    </label>
    
    
    <label for="checkbox-layer-farms-find">
    	<input type="checkbox" id="checkbox-layer-farms-find" name="s[layer-farms-find]" <?php if(isset($settings['layer-farms-find'])){ echo "checked"; } ?>> layer-farms-find<br>
    </label>
    
    
    
    <label for="checkbox-debug-raw-portal-data">
    	<input type="checkbox" id="checkbox-debug-raw-portal-data" name="s[debug-raw-portal-data]" <?php if(isset($settings['debug-raw-portal-data'])){ echo "checked"; } ?>> debug-raw-portal-data<br>
    </label>    
   
    
    </div>
    
    
    
        <button type="submit" class="btn btn-warning">Save and show intelmap</button>
  </fieldset>
</form>
         
        
    
    </div>
     
    
       
        
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
        
        
        <div class="span12">
         
         



 

    <hr>

         <footer>
        <?php include(CBASE."web/footer.php");?>
      </footer>      

       </div>
      
      
      </div>

      


<?php


?>
    </div> <!-- /container -->

</body>
</html>
