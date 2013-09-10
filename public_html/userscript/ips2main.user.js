// ==UserScript==
// @id             ingress-netherlands@jerryhopper
// @name           Ingress intel map enhancer
// @version        0.0.0.9
// @namespace      http://home.botnyx.com/
// @updateURL      http://home.botnyx.com/script/iitcplugin/ips2main.meta.js
// @downloadURL    http://home.botnyx.com/script/iitcplugin/ips2main.user.js
// @description    Portal submission tool for ingress portals
// @include        http://www.ingress.com/intel*
// @include        https://www.ingress.com/intel*
// @match          http://www.ingress.com/intel*
// @match          https://www.ingress.com/intel*
// @grant       GM_getValue
// @grant       GM_setValue
// ==/UserScript==


// REPLACE ORIG SITE ///////////////////////////////////////////////////
if(document.getElementsByTagName('html')[0].getAttribute('itemscope') != null) { throw('Ingress Intel Website is down, not a userscript issue.'); }


//window.IEnhancer = function(){}

var newdiv = document.createElement('div');
  newdiv.setAttribute('id','ipsv2');
  newdiv.setAttribute('class','nav_link')
  newdiv.innerHTML = 'Ingress enhancer';
document.getElementById('nav').appendChild(newdiv);



// putting everything in a wrapper function that in turn is placed in a
// script tag on the website allows us to execute in the site’s context
// instead of in the Greasemonkey/Extension/etc. context.
function wrapper() {
    function startplugin(pluginz){
       console.log("startplugin")
//          var fileref=document.createElement('script')
//            fileref.setAttribute("type","text/javascript")
//            fileref.setAttribute("src", "http://ingress.botnyx.com/script/iitcplugin/ips2.user.js");
//            document.getElementsByTagName("head")[0].appendChild(fileref)

       
       for( var key in pluginz){
          var fileref=document.createElement('script');
            fileref.setAttribute("type","text/javascript");
            fileref.setAttribute("src", "http://iitc.jonatkins.com/test/plugins/"+pluginz[key]+".user.js");
			
			
			if(pluginz[key]=="item-check-userid"){  
				fileref.setAttribute("src", "http://home.botnyx.com/script/iitcplugin/item-check-userid.js");
				//fileref.setAttribute("src", "http://home.botnyx.com/script/iitcplugin/"+pluginz[key]+".user.js");
			}
            
				
			document.getElementsByTagName("head")[0].appendChild(fileref);
       }

	 window.PLAYER.guid = playerNameToGuid(PLAYER.nickname);
       $.ajax({url: 'http://home.botnyx.com/portals/ipsv2_user.php?wcb='+ cbus,type: 'POST',  data:{'d': JSON.stringify(window.PLAYER)},dataType: 'json',success: function(data){
			  console.log("LOADED");
			  
		var fileref=document.createElement('script')
       fileref.setAttribute("type","text/javascript")
       fileref.setAttribute("src", "http://home.botnyx.com/script/function-overwrites.js");
       document.getElementsByTagName("head")[0].appendChild(fileref)
		console.log("function-overwrites.js LOADED");	  
			  
			  
			  
	   }}); 
	   
    }
    
    
    if(window.iitcLoaded === true){
        alert("IITC is loaded before this userscript!  \n\nPlease disable IITC, as it is autoloaded by this plugin.");
        document.getElementById('ipsv2').innerHTML="Ingress Enhancer de-activated";
        $("#ipsv2").css("border","1px solid red");
        window.IEnhancerLoaded = false;
     
    } else {
        document.getElementById('ipsv2').innerHTML="Ingress Enhancer"
        window.IEnhancerLoaded = true;
        window.PLAYER.email = document.getElementById('header_email').innerHTML;
        var cbus = +new Date;
        
        //$.post('http://ingress.botnyx.com/portals/ipsv2_user.php?wcb='+ cbus, { "func": "getNameAndTime" },function(data){
        //    console.log(data.name); // John
        //    console.log(data.time); // 2pm
        //}, "json");

		


        
//        $.ajax({url: 'http://home.botnyx.com/portals/ipsv2_user.php?wcb='+ cbus,type: 'POST',  data:{'d': JSON.stringify(window.PLAYER),'p': JSON.stringify(window.localStorage)},dataType: 'json',success: function(data){

		  $.ajax({url: 'http://home.botnyx.com/portals/ipsv2_user.php?wcb='+ cbus,type: 'POST',  data:{'d': JSON.stringify(window.PLAYER)},dataType: 'json',success: function(data){
		  console.log("ipsv2_user")
		  console.log(data);
		  //alert(data.status);
		  if(data.status=='firstrun'){ 
		  	alert("Verification completed, press ok to continue.");
			window.location="http://home.botnyx.com/myprofile/"; 
			}
		  if(data.status=='ok'){
			
		  	var fileref=document.createElement('script')
			fileref.setAttribute("type","text/javascript")
//          fileref.setAttribute("src", "http://iitc.jonatkins.com/release/total-conversion-build.user.js");
			fileref.setAttribute("src", "http://iitc.jonatkins.com/test/total-conversion-build.user.js");
		  
		  //fileref.setAttribute("src", "http://home.botnyx.com/script/total-conversion-build.user.js");
			document.getElementsByTagName("head")[0].appendChild(fileref) ;

			
			window.itemcheckkey = data.itemcheckuserid;
			  

			setTimeout(function(){  startplugin(data.plugin);    },5000);
		    //$('#toolbox').append(' <a href="http://ingress.botnyx.com" id="psind">Intel-Enhancer</a>');


		  }
		  if(data.status=='error'){
		      alert(data.statusmsg);
		  }
		  
		  
		  
		  return;
		}});
    }
    
    console.log(window.iitcLoaded);

     
    

} // end of wrapper

// inject code into site context
var script = document.createElement('script');
script.appendChild(document.createTextNode('('+ wrapper +')();'));
(document.body || document.head || document.documentElement).appendChild(script);




