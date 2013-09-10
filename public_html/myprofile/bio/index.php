<?php
include("../../var.php");
include(CBASE."common.php");

if(isset($_SERVER['HTTP_REFERER'])){
	if($_SERVER['HTTP_REFERER']=="http://www.ingress.com/intel"){ header("Location: /myprofile/intelenhancer/");}
}
if(!$ob_auth->verified){ header("Location: /?you_need_to_login"); }








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
		<?php if(isset($_SERVER['HTTP_REFERER'])){ if($_SERVER['HTTP_REFERER']!="http://www.ingress.com/intel"){ ?>
        <div class="btn-group" data-toggle=""> 
        <a href='/myprofile/' class="btn btn-primary"><i class="icon-info-sign" style='background-image: url("../../img/glyphicons-halflings.png");'></i>&nbsp;</a>
        <a href='/myprofile/bio' class="btn btn-primary active">Bio</a>
        
        <a href='/myprofile/favoriteportals' class="btn btn-primary">Favorite portals</a>
        <a href='/myprofile/intelenhancer' class="btn btn-primary" ><i class="icon-wrench" style='background-image: url("../../img/glyphicons-halflings.png");'></i> IntelEnhancer settings</a>
        </div>
        <?php } } ?>
        
        
        <h4>Bio</h4>
        <p>..</p>
        
      </div>
      <div class="span5" >
		
        <?php 
		
		$sql = "";
		#echo "<pre>";
		#print_r($ob_auth->verified['homeportal']);

		
		?>
        
        <h5>Home portal</h5>
        <p>Which portal is closest to your home?</p>
        <ul id='homeportal' class="nav nav-list"></ul>
        <hr>
        <h5>Work portal</h5>
        <p>Which portal is closest to your work?</p>
        <ul id='workportal' class="nav nav-list"></ul>
        <hr>
        <h5>Other portal</h5>
        <p>Which other portal do you visit on a regular basis?</p>
		<ul id='otherportal' class="nav nav-list"></ul>      
      </div>
      <script>
		$(document).keypress(function(e) {
			if(e.which == 13) {
				var q = $("#searchfield").val();
				$.post("../portalsearch.php", { "q": q },
				  function(data){
					presults(data.results,data.data);
				  }, "json");
			}
		});

	  	$(document).ready(function() {
		// Handler for .ready() called.
		poi('home','<?php echo $ob_auth->verified['homeportal'];?>');
		poi('work','<?php echo $ob_auth->verified['workportal'];?>');
		poi('other','<?php echo $ob_auth->verified['otherportal'];?>');
			
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
				$("#portallist").append("<li style='width:100%' class='btn' id='"+value.guid+"' ><div  class='img-rounded pull-left' style='margin-right:5px;background: url("+ value.imageByUrl +") no-repeat center; width:80px;height:80px;'></div><div style='text-align:left;'>"+value.TITLE+"</div><div style='text-align:left'>"+value.ADRESS+"</div>  <div onclick='poi(\"home\",$(this).parent().attr(\"id\"))'>Home</div><div onclick='poi(\"work\",$(this).parent().attr(\"id\"))'>Work</div><div onclick='poi(\"other\",$(this).parent().attr(\"id\"))'>other</div></li>");
				
				
			}
			if( parseInt(amount) >15 ) { $("div#searchstatus").html("More than 15 results ("+amount+")- be more specific."); } 
			
			
			
			
		}
		
		function poi(type,guid){
			
			$.post("../portalbyguid.php", { "guid": guid,"type":type },function(data){
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
					var rdata ='<div style="margin-right:5px;background: url('+data.data.imageByUrl+') no-repeat center; width:80px;height:80px;" class="img-rounded pull-left"></div><div style="text-align:left;">'+data.data.TITLE+'</div><div style="text-align:left">'+data.data.ADRESS+'</div>';
					$("#"+ type +"portal").html(rdata);
					
								
			
			}, "json");
				  
				  
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
            <?php include(CBASE."web/footer.php");?>
          </footer>      
      </div>
      
      
     </div>

      


<?php


?>
    </div> <!-- /container -->

</body>
</html>
