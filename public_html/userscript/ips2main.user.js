// ==UserScript==
// @id             ingress-netherlands@jerryhopper
// @name           Ingress Cerebro
// @version        0.0.0.2
// @namespace      http://cerebro.botnyx.com/
// @updateURL      http://cerebro.botnyx.com/userscript/ips2main.meta.js
// @downloadURL    http://cerebro.botnyx.com/userscript/ips2main.user.js
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


// rescue user data from original page
var scr = document.getElementsByTagName('script');
for(var x in scr) {
  var s = scr[x];
  if(s.src) continue;
  if(s.type !== 'text/javascript') continue;
  var d = s.innerHTML.split('\n');
  break;
}


if(!d) {
  // page doesn’t have a script tag with player information.
  if(document.getElementById('header_email')) {
    // however, we are logged in.
    setTimeout('location.reload();', 3*1000);
    throw('Page doesn’t have player data, but you are logged in. Reloading in 3s.');
  }
  // FIXME: handle nia takedown in progress
  throw('Couldn’t retrieve player data. Are you logged in?');
}


for(var i = 0; i < d.length; i++) {
  if(!d[i].match('var PLAYER = ')) continue;
  eval(d[i].match(/^var /, 'window.'));
  break;
}
// player information is now available in a hash like this:
// window.PLAYER = {"ap": "123", "energy": 123, "available_invites": 123, "nickname": "somenick", "team": "ENLIGHTENED||RESISTANCE"};



// putting everything in a wrapper function that in turn is placed in a
// script tag on the website allows us to execute in the site’s context
// instead of in the Greasemonkey/Extension/etc. context.
function wrapper() {
 


	function startplugin(pluginz){
       console.log("startplugin")
 
       for( var key in pluginz){
          var fileref=document.createElement('script');
            fileref.setAttribute("type","text/javascript");
            fileref.setAttribute("src", "http://iitc.jonatkins.com/test/plugins/"+pluginz[key]+".user.js");
			
			

            
				
			document.getElementsByTagName("head")[0].appendChild(fileref);
       }

	 window.PLAYER.guid = playerNameToGuid(PLAYER.nickname);
       $.ajax({url: 'http://cerebro.botnyx.com/rpc/ipsv2_user.php?wcb='+ cbus,type: 'POST',  data:{'d': JSON.stringify(window.PLAYER)},dataType: 'json',success: function(data){
			  console.log("LOADED");
			  
		var fileref=document.createElement('script')
       fileref.setAttribute("type","text/javascript")
       fileref.setAttribute("src", "http://cerebro.botnyx.com/userscript/function-overwrites.js");
       document.getElementsByTagName("head")[0].appendChild(fileref)
		console.log("function-overwrites.js LOADED");	  
			  
			  
			  
	   }}); 
	   
    }






 //console.log(window.PLAYER.nickname);
	var em = document.getElementById('header_email').innerHTML;
	window.PLAYER.email = em;
	console.log( window.PLAYER );
     
	var cbus = +new Date;
	
//	alert( JSON.stringify(window.PLAYER)) ;

	
	
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://cerebro.botnyx.com/rpc/ipsv2_user.php?wcb='+ cbus , true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function () {
		// do something to response
		var data = JSON.parse(this.responseText); 
		console.log("---");
		console.log(data);
		
	
		if(data.status=='firstrun'){ 
		  	alert("Verification completed, press ok to continue.");
			window.location="http://cerebro.botnyx.com/myprofile/"; 
		}
		
		if(data.status=='ok'){
			
		  	var fileref=document.createElement('script')
			fileref.setAttribute("type","text/javascript")
//          fileref.setAttribute("src", "http://iitc.jonatkins.com/release/total-conversion-build.user.js");
			fileref.setAttribute("src", "http://iitc.jonatkins.com/test/total-conversion-build.user.js");
		  
		  //fileref.setAttribute("src", "http://home.botnyx.com/script/total-conversion-build.user.js");
			document.getElementsByTagName("head")[0].appendChild(fileref) ;

			
			window.itemcheckkey = data.itemcheckuserid;
			  

			setTimeout(function(){  startplugin(data.plugin);    },5500);
		    $('#toolbox').append(' <a href="http://cerebro.botnyx.com" id="psind">CEREBRO</a>');


		 }
		 
		 if(data.status=='error'){
		      alert(data.statusmsg);
		 }

	
		//alert(this.responseText);
		
		
	};
	xhr.send('d='+JSON.stringify(window.PLAYER) );	
	
  

} // end of wrapper

// inject code into site context
var script = document.createElement('script');
script.appendChild(document.createTextNode('('+ wrapper +')();'));
(document.body || document.head || document.documentElement).appendChild(script);

