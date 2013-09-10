            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <!-- -->
			  <?php if($ob_auth->googledata) { ?>
              <!--<li><a href="/verify/">Agent Verification</a></li>
              <li><a href="/portals/">Portals</a></li>
              <li><a href="/players/">Players</a></li>-->
              <?php if($deviceType!="phone"){?>
              <li><a href="/script/">Intel Enhancer</a></li>
              <?php } 
			  }?>
              
            </ul>
            
            <form class="navbar-form pull-right">
<?php 

if(isset($ob_auth->personMarkup)){ print $ob_auth->personMarkup; } 


  if(!isset($ob_auth->googledata)) {
    print "<a class='login btn' href='".$ob_auth->authUrl."'>Connect Me!</a>";
  } else {
  
   print "<a class='logout btn' href='?logout'>Logout</a>";
  }
?> 
            </form>