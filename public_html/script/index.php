<?php
include("../var.php");
include(CBASE."common.php");

//if(!$ob_auth->googledata){ header("Location: /?you_need_to_login"); }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
<?php include(CBASE."web/head.php");?>
    <title>Portal and player submission plugin for IITC</title>


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
            <form class="navbar-form pull-right">
              <!--<input class="span2" type="text" placeholder="Email">
              <input class="span2" type="password" placeholder="Password">
              <button type="submit" class="btn">Sign in</button>
            --></form>
          </div>  <!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">


      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <?php echo $ob_auth->msg;?>
        
        <h1>Ingress/intel Enhancer</h1>
        

<p id="bookmarklet">Agent verification</p>
<p><a class='btn btn-info' href='/userscript/ips2main.user.js'>Download the intel enhancer plugin</a></p>
    
        
        
        

        
      </div>
	  <div class='row'>
      	<div class='span14'>
      	  <p id="bookmarklet2">In order to verify your agent status, you should have used this Intel Enhancer script at least one time.Installation is </p>
          <h3>            Installation requirements</h3>
          <p> IITC will work in the Chrome or Firefox browsers. It should also work with Opera and other browsers supporting  userscripts, but these are far less tested. </p>
          <h4>Chrome</h4>
          <p> Although it is possible to install userscripts directly as extensions, the recommended method is to use <a href="https://chrome.google.com/webstore/detail/tampermonkey/dhdgffkkebhmkfjojejmpbldmpobfkfo">Tampermonkey</a>.  Once Tampermonkey is installed, click on the "Download" button below and click "OK" on the two dialogs to install. </p>
          <h4>Firefox</h4>
          <p> Install the <a href="https://addons.mozilla.org/en-US/firefox/addon/greasemonkey/">Greasemonkey</a> Firefox add-on.  Once installed, click the "Download" then "Install" on the dialog. </p>
          <p></p>
      	</div>
      </div>
      <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
          <h2>What does it do?</h2>
          <p>This script enables autoloading IITC. IITC is a browser add-on that modifies the Ingress intel map. It is faster than the standard site, and offers many more features.
          Our plugin submit all portal information to a central database. With this information in the database, we can provide you with information that will come in handy during gameplay.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>How does it work</h2>
          <p>Every 5 minutes all screendata will be send to the central database. You can also manually submit the information. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
       </div>
        <div class="span4">
          
          <article>
      <p> <span id="status">checking...</span></p>
    		</article>
          <!--<h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        --></div><div id='status'></div>
        
      </div>
    <script>
function success(position) {
  var s = document.querySelector('#status');
  
  if (s.className == 'success') {
    // not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
    return;
  }
  
  s.innerHTML = "found you!  "+ position.coords.latitude + " " + position.coords.longitude ;
  s.className = 'success';
  
  var mapcanvas = document.createElement('div');
  mapcanvas.id = 'mapcanvas';
  mapcanvas.style.height = '220px';
  mapcanvas.style.width = '300px';
    
  document.querySelector('article').appendChild(mapcanvas);
  
  var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
  //alert(latlng);
  var myOptions = {
    zoom: 15,
    center: latlng,
    mapTypeControl: false,
    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
  
  var marker = new google.maps.Marker({
      position: latlng, 
      map: map, 
      title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
  });
}

function error(msg) {
  var s = document.querySelector('#status');
  s.innerHTML = typeof msg == 'string' ? msg : "failed";
  s.className = 'fail';
  
  // console.log(arguments);
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(success, error);
} else {
  error('not supported');
}

</script>

      <hr>

      <footer>
        <?php include(CBASE."web/footer.php");?>
      </footer>

    </div> <!-- /container -->



</body>
</html>
