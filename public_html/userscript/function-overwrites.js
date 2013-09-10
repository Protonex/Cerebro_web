//============================================================================================================================
//  Function overwrites.
//============================================================================================================================

window.chat.handleFaction = function(data, olderMsgs) {
  chat._requestFactionRunning = false;

  if(!data || !data.result) {
    window.failedRequestCount++;
    return console.warn('faction chat error. Waiting for next auto-refresh.');
  }

  if(data.result.length === 0) return;

	// #jerryhopper chat
	// console.log(data.result);
	window.sendChatData_hook(data,"faction");
	
	
  var old = chat._faction.oldestTimestamp;
  chat.writeDataToHash(data, chat._faction, false, olderMsgs);
  var oldMsgsWereAdded = old !== chat._faction.oldestTimestamp;

  runHooks('factionChatDataAvailable', {raw: data, processed: chat._faction.data});

  window.chat.renderFaction(oldMsgsWereAdded);

  if(data.result.length >= CHAT_FACTION_ITEMS) chat.needMoreMessages();
}

window.chat.handlePublic = function(data, olderMsgs) {
  chat._requestPublicRunning = false;

  if(!data || !data.result) {
    window.failedRequestCount++;
    return console.warn('public chat error. Waiting for next auto-refresh.');
  }

  if(data.result.length === 0) return;

	// #jerryhopper chat
	// console.log(data.result);
	window.sendChatData_hook(data,"public");

	
	

  var old = chat._public.oldestTimestamp;
  chat.writeDataToHash(data, chat._public, true, olderMsgs);
  var oldMsgsWereAdded = old !== chat._public.oldestTimestamp;

  runHooks('publicChatDataAvailable', {raw: data, processed: chat._public.data});

  switch(chat.getActive()) {
    case 'public': window.chat.renderPublic(oldMsgsWereAdded); break;
    case 'compact': window.chat.renderCompact(oldMsgsWereAdded); break;
    case 'full': window.chat.renderFull(oldMsgsWereAdded); break;
  }

  if(data.result.length >= CHAT_PUBLIC_ITEMS) chat.needMoreMessages();
}

// handles the map and portaldata.

window.MapDataRequest.prototype.handleResponse = function (data, tiles, success) {

  this.activeRequestCount -= 1;

  for (var i in tiles) {
    var id = tiles[i];
    delete this.requestedTiles[id];
  }


  if (!success || !data || !data.result) {
    console.warn("Request.handleResponse: request failed - requeing...");

    //request failed - requeue all the tiles(?)
    for (var i in tiles) {
      var id = tiles[i];
      this.requeueTile(id, true);
    }

    window.runHooks('requestFinished', {success: false});

  } else {
	  
	// #jerryhopper map & portaldata.
	// console.log(data.result);
	window.sendMapData_hook(data,tiles);
    
	
	// TODO: use result.minLevelOfDetail ??? stock site doesn't use it yet...

    var m = data.result.map;

    for (var id in m) {
      var val = m[id];

      if ('error' in val) {
        // server returned an error for this individual data tile

        if (val.error == "TIMEOUT") {
          // TIMEOUT errors for individual tiles are 'expected'(!) - and result in a silent unlimited retries
          this.requeueTile(id, false);
        } else {
          console.warn('map data tile '+id+' failed: error=='+val.error);
          this.requeueTile(id, true);
        }
      } else {
        // no error for this data tile - process it

        // store the result in the cache
        this.cache && this.cache.store (id, val);

        // if this tile was in the render list, render it
        // (requests aren't aborted when new requests are started, so it's entirely possible we don't want to render it!)
        if (id in this.tileBounds) {
          this.debugTiles.setState (id, 'ok');

          this.render.processTileData (val);

          delete this.tileBounds[id];
          this.successTileCount += 1;

        } // else we don't want this tile (from an old non-cancelled request) - ignore
      }

    }

    window.runHooks('requestFinished', {success: true});
  }

  this.processRequestQueue();
}


//============================================================================================================================

// resolves all player GUIDs that have been added to the list. Reruns
// renderPortalDetails when finished, so that then-unresolved names
// get replaced by their correct versions.
window.resolvePlayerNames = function() {
  if(window.playersToResolve.length === 0) return;

  //limit per request. stock site is never more than 13 (8 res, 4 mods, owner)
  //testing shows 15 works and 20 fails
  var MAX_RESOLVE_PLAYERS_PER_REQUEST = 15;

  var p = window.playersToResolve.slice(0,MAX_RESOLVE_PLAYERS_PER_REQUEST);
  window.playersToResolve = playersToResolve.slice(MAX_RESOLVE_PLAYERS_PER_REQUEST);

  var d = {guids: p};
  window.playersInResolving = window.playersInResolving.concat(p);

  postAjax('getPlayersByGuids', d, function(dat) {
    if(dat.result) {
		// #jerryhopper
		//console.log(dat.result);
		//console.log("player!");
		window.playerresolve_hook(dat);
      $.each(dat.result, function(ind, player) {
        window.setPlayerName(player.guid, player.nickname);
		//console.log( player.guid + " " + player.nickname );
        //alert("player!");
		// remove from array
        window.playersInResolving.splice(window.playersInResolving.indexOf(player.guid), 1);
      });
    } else {
      //no 'result' - a successful http request, but the returned result was an error of some kind
      console.warn('getplayers problem - no result in response: '+dat);

      //likely to be some kind of 'bad request' (e.g. too many names at once, or otherwise badly formatted data.
      //therefore, not a good idea to automatically retry by adding back to the playersToResolve list
    }

    //TODO: have an event triggered for this instead of hard-coded single function call
    if(window.selectedPortal)
      window.renderPortalDetails(window.selectedPortal);

    //if more to do, run again
    if(window.playersToResolve.length>0) resolvePlayerNames();
  },
  function() {
    // append failed resolves to the list again
    console.warn('resolving player guids failed: ' + p.join(', '));
    window.playersToResolve.concat(p);
  });
}

//============================================================================================================================
window.playerresolve_hook = function(data){
	console.log("window.playerresolve_hook ");
	window.sendPlayerDataresponse(JSON.stringify(data));
}

window.sendChatData_hook = function(data,type){
	console.log("window.sendChatData_hook "+ type);
	window.sendChatDataresponse(JSON.stringify(data));
}

window.sendChatDataresponse = function(data){
	var http = new XMLHttpRequest();
	window.PLAYER.guid = playerNameToGuid(PLAYER.nickname);
	var url = "http://cerberus.botnyx.com/rpcV2/dashboard.getPaginatedPlextsV3.php?version=0";
	var params = "guid="+window.PLAYER.guid+"&data="+data;
	http.open("POST", url, true);
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	http.setRequestHeader("Content-length", params.length);
//	http.setRequestHeader("Connection", "close");
	//alert("testchat");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			//alert("testchat" + http.responseText);
		}
	}
	http.send(params);
}

window.sendMapData_hook = function(data,tiles){
	console.log("window.sendMapData_hook");
	//console.log(tiles);
	window.sendMapDataresponse(JSON.stringify(data));
}


window.sendMapDataresponse = function(data){
	var http = new XMLHttpRequest();
	window.PLAYER.guid = playerNameToGuid(PLAYER.nickname);
	var url = "http://cerberus.botnyx.com/rpc/dashboard.getThinnedEntitiesV4.php?version=1";
	var params = "guid="+window.PLAYER.guid+"&data="+JSON.stringify(data);
	http.open("POST", url, true);
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//http.setRequestHeader("Content-length", params.length);
	//http.setRequestHeader("Connection", "close");
	//alert("testchat");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			//alert("testchat" + http.responseText);
		}
	}
	http.send(params);
}

window.sendPlayerDataresponse = function(data){
	var http = new XMLHttpRequest();
	window.PLAYER.guid = playerNameToGuid(PLAYER.nickname);
	var url = "http://cerberus.botnyx.com/rpc/dashboard.getPlayersByGuids.php?version=1";
	var params = "guid="+window.PLAYER.guid+"&data="+JSON.stringify(data);
	http.open("POST", url, true);
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//http.setRequestHeader("Content-length", params.length);
	//http.setRequestHeader("Connection", "close");
	//alert("testchat");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			//alert("testchat" + http.responseText);
		}
	}
	http.send(params);
}


//============================================================================================================================

// timer functions.

window.t60 = function(){
	console.log("T60 timer function");
	//this will repeat every 60 seconds
}

window.t300 = function(){
	console.log("T300 timer function");
	//this will repeat every 300 seconds
	//window.debug.forceSync();
	//requestData();
	//alert("refresh!");
}

//============================================================================================================================

// timers.
 window.setInterval(window.t60, 60000);
window.setInterval(window.t300, 300000);


//============================================================================================================================

$("div#toolbox").prepend("<a href='http://cerberus.botnyx.com/myprofile/intelenhancer/' title='Add or remove plugins, like playertracker and other visualisation improvements'>Plugin Settings</a>");

var currentTime = new Date()
alert("latest!" + currentTime);