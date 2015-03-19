/*!CK:3375653073!*//*1422851187,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["p8UiB"]); }

__d("PerfXClientMetricsConfig",[],function(a,b,c,d,e,f){e.exports={LOGGER_CONFIG:"PerfXClientMetricsLoggerConfig"};},null);
__d("PixelRatioConst",[],function(a,b,c,d,e,f){e.exports={cookieName:"dpr"};},null);
__d("SyncRequestStatusEnum",[],function(a,b,c,d,e,f){e.exports={PENDING:0,ACCEPTED:1,REJECTED:2,EXPIRED:3,CANCELED:4,namesByValue:["PENDING","ACCEPTED","REJECTED","EXPIRED","CANCELED"]};},null);
__d("ModuleDependencies",[],function(a,b,c,d,e,f){function g(k,l,m){var n=b.__debug.modules[m],o=b.__debug.deps;if(l[m])return;l[m]=true;if(!n){o[m]&&(k[m]=true);return;}if(!n.dependencies||!n.dependencies.length){if(n.waiting)k[m]=true;return;}n.dependencies.forEach(function(p){g(k,l,p);});}function h(k){if(b.__debug){var l={};g(l,{},k);var m=Object.keys(l);m.sort();return m;}return null;}function i(){var k={loading:{},missing:[]};if(!b.__debug)return k;var l={},m=b.__debug.modules,n=b.__debug.deps;for(var o in m){var p=m[o];if(p.waiting){var q={};g(q,{},p.id);delete q[p.id];k.loading[p.id]=Object.keys(q);k.loading[p.id].sort();k.loading[p.id].forEach(function(r){if(!(r in m)&&n[r])l[r]=1;});}}k.missing=Object.keys(l);k.missing.sort();return k;}var j={setRequireDebug:function(k){b.__debug=k;},getMissing:h,getNotLoadedModules:i};e.exports=j;},null);
__d("AvailableListConstants",["fbt"],function(a,b,c,d,e,f,g){var h={ON_AVAILABILITY_CHANGED:'buddylist/availability-changed',ON_UPDATE_ERROR:'buddylist/update-error',ON_UPDATED:'buddylist/updated',ON_CHAT_NOTIFICATION_CHANGED:'chat-notification-changed',OFFLINE:0,IDLE:1,ACTIVE:2,MOBILE:3,WEB_STATUS:'webStatus',FB_APP_STATUS:'fbAppStatus',MESSENGER_STATUS:'messengerStatus',OTHER_STATUS:'otherStatus',ACTIVE_ON_WEB:"Web",ACTIVE_ON_MOBILE:"Mobile",LEGACY_OVERLAY_OFFLINE:-1,LEGACY_OVERLAY_ONLINE:0,LEGACY_OVERLAY_IDLE:1,STATUS_ACTIVE:'active',STATUS_IDLE:'idle',STATUS_OFFLINE:'offline',legacyStatusMap:{'0':2,'1':1,'-1':0,'2':3},reverseLegacyStatusMap:{0:-1,1:1,2:0,3:2}};a.AvailableListConstants=e.exports=h;},null);
__d("ChatConfig",["ChatConfigInitialData","copyProperties"],function(a,b,c,d,e,f,g,h){var i={},j={get:function(k,l){return k in i?i[k]:l;},set:function(k){if(arguments.length>1){var l={};l[k]=arguments[1];k=l;}h(i,k);},getDebugInfo:function(){return i;}};j.set(g);e.exports=j;},null);
__d("PresencePrivacy",["Arbiter","AsyncRequest","ChannelConstants","CurrentUser","PresencePrivacyInitialData","JSLogger","PresenceUtil","copyProperties"],function(a,b,c,d,e,f,g,h,i,j,k,l,m,n){var o='/ajax/chat/privacy/settings.php',p='/ajax/chat/privacy/online_policy.php',q='/ajax/chat/privacy/visibility.php',r='friend_visibility',s='visibility',t='online_policy',u=n({},k.privacyData),v=k.visibility,w=n({},k.privacyData),x=v,y=k.onlinePolicy,z=y,aa=[],ba=false;function ca(){return l.create('blackbird');}var da=n(new g(),{WHITELISTED:1,BLACKLISTED:-1,UNLISTED:0,ONLINE:1,OFFLINE:0,ONLINE_TO_WHITELIST:0,ONLINE_TO_BLACKLIST:1});function ea(qa){var ra;for(ra in qa){var sa=qa[ra];if(ra==j.getID()){ca().error('set_viewer_visibility');throw new Error("Invalid to set current user's visibility");}switch(sa){case da.WHITELISTED:case da.BLACKLISTED:case da.UNLISTED:break;default:ca().error('set_invalid_friend_visibility',{id:ra,value:sa});throw new Error("Invalid state: "+sa);}}for(ra in qa)u[ra]=qa[ra];da.inform('privacy-changed');}function fa(qa,ra){var sa={};sa[qa]=ra;ea(sa);}function ga(qa){switch(qa){case da.ONLINE:case da.OFFLINE:break;default:ca().error('set_invalid_visibility',{value:qa});throw new Error("Invalid visibility: "+qa);}v=qa;da.inform('privacy-changed');da.inform('privacy-user-presence-changed');g.inform('chat/visibility-changed',{sender:this});}function ha(qa){switch(qa){case da.ONLINE_TO_WHITELIST:case da.ONLINE_TO_BLACKLIST:break;default:throw new Error("Invalid default online policy: "+qa);}y=qa;da.inform('privacy-user-presence-changed');da.inform('privacy-changed');}function ia(qa,ra){ba=true;qa.send();}function ja(qa,ra){aa.push({request:qa,data:ra});if(!ba){var sa=aa.shift();ia(sa.request,sa.data);}}function ka(qa,ra){var sa=qa.type;if(sa===r){var ta=ra.payload.user_availabilities;if(!Array.isArray(ta)){da.inform('privacy-availability-changed',{user_availabilities:ta});for(var ua in qa.settings)w[ua]=qa.settings[ua];}}else{if(sa===s){x=qa.visibility;}else if(sa===t)z=qa.online_policy;da.inform('privacy-user-presence-response');}ca().log('set_update_response',{data:qa,response:ra});}function la(qa,ra){if(v!==x)ga(x);if(y!==z)ha(z);n(u,w);da.inform('privacy-changed');aa=[];ca().log('set_error_response',{data:qa,response:ra});}function ma(qa){ba=false;if(aa.length>0){var ra=aa.shift();ia(ra.request,ra.data);}}function na(qa,ra){if(m!=null){var sa=qa.getData();sa.window_id=m.getSessionID();qa.setData(sa);}qa.setHandler(ka.bind(this,ra)).setErrorHandler(la.bind(this,ra)).setTransportErrorHandler(la.bind(this,ra)).setFinallyHandler(ma.bind(this)).setAllowCrossPageTransition(true);return qa;}function oa(qa,ra,sa){return na(new h(qa).setData(ra),sa);}function pa(qa,ra){var sa=ra.obj;if(sa.viewer_id!=j.getID()){ca().error('invalid_viewer_for_channel_message',{type:qa,data:ra});throw new Error("Viewer got from the channel is not the real viewer");}if(sa.window_id===m.getSessionID())return;var ta=sa.data;if(sa.event=='access_control_entry'){ta.target_ids.forEach(function(va){fa(va,ta.setting);w[va]=ta.setting;});}else{if(sa.event=='visibility_update'){var ua=!!ta.visibility?da.ONLINE:da.OFFLINE;ga(ua);x=ua;}else if(sa.event=='online_policy_update'){ha(ta.online_policy);z=ta.online_policy;}da.inform('privacy-user-presence-response');}ca().log('channel_message_received',{data:ra.obj});}n(da,{WHITELISTED:1,BLACKLISTED:-1,UNLISTED:0,ONLINE:1,OFFLINE:0,ONLINE_TO_WHITELIST:0,ONLINE_TO_BLACKLIST:1,init:function(qa,ra,sa){},setVisibility:function(qa){x=v;ga(qa);var ra={visibility:qa},sa={type:s,visibility:qa},ta=oa(q,ra,sa);ja(ta,sa);ca().log('set_visibility',{data:ra});return qa;},getVisibility:function(){return v;},setOnlinePolicy:function(qa){z=y;ha(qa);var ra={online_policy:qa},sa={type:t,online_policy:qa},ta=oa(p,ra,sa);ja(ta,sa);ca().log('set_online_policy',{data:ra});return qa;},getOnlinePolicy:function(){return y;},getFriendVisibility:function(qa){return u[qa]||da.UNLISTED;},isUserOffline:function(){return this.getVisibility()===da.OFFLINE;},allows:function(qa){if(this.isUserOffline())return false;var ra=this.getOnlinePolicy();return ra===da.ONLINE_TO_WHITELIST?u[qa]==da.WHITELISTED:u[qa]!=da.BLACKLISTED;},setFriendsVisibility:function(qa,ra){if(qa.length>0){var sa={};for(var ta=0;ta<qa.length;ta++){var ua=qa[ta];w[ua]=u[ua];sa[ua]=ra;}ea(sa);var va=ra;if(va==da.UNLISTED)va=w[qa[0]];var wa={users:qa,setting:ra,setting_type:va},xa={type:r,settings:sa},ya=oa(o,wa,xa);ja(ya,xa);ca().log('set_friend_visibility',{data:wa});}return ra;},setFriendVisibilityMap:function(qa,ra){for(var sa in qa)w[sa]=u[sa];ea(qa);var ta={type:r,settings:qa};ja(na(ra,ta),ta);ca().log('set_friend_visibility_from_map',{data:qa});},allow:function(qa){if(this.allows(qa)){ca().error('allow_already_allowed');throw new Error("allow() should only be called for users that "+"are not already allowed");}if(this.getVisibility()===da.OFFLINE){ca().error('allow_called_while_offline');throw new Error("allow() should only be called when the user is already online");}var ra=this.getOnlinePolicy()===da.ONLINE_TO_WHITELIST?da.WHITELISTED:da.UNLISTED;return this.setFriendsVisibility([qa],ra);},disallow:function(qa){if(!this.allows(qa)){ca().error('disallow_already_disallowed');throw new Error("disallow() should only be called for users that "+"are not already disallowed");}if(this.getVisibility()===da.OFFLINE){ca().error('disallow_called_while_offline');throw new Error("disallow() should only be called when the user is already online");}var ra=this.getOnlinePolicy()===da.ONLINE_TO_BLACKLIST?da.BLACKLISTED:da.UNLISTED;return this.setFriendsVisibility([qa],ra);},getBlacklist:function(){var qa=[];for(var ra in u)if(u[ra]===da.BLACKLISTED)qa.push(ra);return qa;},getWhitelist:function(){var qa=[];for(var ra in u)if(u[ra]===da.WHITELISTED)qa.push(ra);return qa;},getMapForTest:function(){return u;},setMapForTest:function(qa){u=qa;}});da.inform('privacy-changed');da.inform('privacy-user-presence-changed',g.BEHAVIOR_STATE);ca().log('initialized',{visibility:v,policy:y});g.subscribe(l.DUMP_EVENT,function(qa,ra){ra.presence_privacy={initial:k.privacyData,current:u};});g.subscribe(i.getArbiterType('privacy_changed'),pa.bind(this));g.subscribe(i.ON_CONFIG,function(qa,ra){var sa=ra.getConfig('visibility',null);if(sa!==null&&typeof(sa)!=='undefined'){var ta=sa?da.ONLINE:da.OFFLINE;ga(ta);ca().log('config_visibility',{vis:ta});}}.bind(this));a.PresencePrivacy=e.exports=da;},3);
__d("ChatVisibility",["Arbiter","JSLogger","PresencePrivacy"],function(a,b,c,d,e,f,g,h,i){var j={isOnline:function(){return i.getVisibility()===i.ONLINE;},hasBlackbirdEnabled:function(){return this.isVisibleToMostFriends()||this.isVisibleToSomeFriends();},isVisibleToMostFriends:function(){return i.getOnlinePolicy()===i.ONLINE_TO_BLACKLIST&&i.getBlacklist().length>0;},isVisibleToSomeFriends:function(){return i.getOnlinePolicy()===i.ONLINE_TO_WHITELIST&&i.getWhitelist().length>0;},goOnline:function(k){if(i.getVisibility()===i.OFFLINE){h.create('blackbird').log('chat_go_online');i.setVisibility(i.ONLINE);g.inform('chat-visibility/go-online');}k&&k();},goOffline:function(k){if(i.getVisibility()===i.ONLINE){h.create('blackbird').log('chat_go_offline');i.setVisibility(i.OFFLINE);g.inform('chat-visibility/go-offline');}k&&k();},toggleVisibility:function(){if(j.isOnline()){j.goOffline();}else j.goOnline();}};e.exports=j;},null);
__d("LastMobileActiveTimes",["ServerTime","fbt"],function(a,b,c,d,e,f,g,h){var i={};function j(n){if(!n||n<0)return '';var o=(g.get()/1000)-n,p=Math.floor(o/60),q=Math.floor(p/60),r=Math.floor(q/24);if(p<=1){return h._("{count}m",[h.param("count",1)]);}else if(p<60){return h._("{count}m",[h.param("count",p)]);}else if(q<24){return h._("{count}h",[h.param("count",q)]);}else if(r<3){return h._("{count}d",[h.param("count",r)]);}else return '';}function k(n,o){if(!(n in i)||i[n]<o)i[n]=o;}function l(n){if(n in i){return i[n];}else return 0;}var m={update:function(n){for(var o in n)k(o,n[o]);},getShortDisplay:function(n){return j(l(n));},get:function(n){return l(n);}};e.exports=m;},null);
__d("PresenceStatus",["AvailableListConstants","BanzaiODS","ChatVisibility","CurrentUser","LastMobileActiveTimes","LogHistory","PresencePrivacy","ServerTime","createObjectFrom"],function(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o){h.setEntitySample('presence',.0001);var p=l.getInstance('presence_status'),q={},r={},s={},t={},u={},v={},w={},x={resetPresenceData:function(){r={};s={};w={};v={};u={};},reset:function(){x.resetPresenceData();t={};},get:function(y){if(y==j.getID())return i.isOnline()?g.ACTIVE:g.OFFLINE;var z=g.OFFLINE;if(y in r)z=r[y];if(z===g.OFFLINE||z===g.IDLE)if(t[y])z=g.MOBILE;if(!m.allows(y))z=g.OFFLINE;return z;},getCapabilities:function(y){var z=x.get(y);if(z==g.OFFLINE)return 0;var aa=s[y];return aa?aa:0;},getDetailedActivePresence:function(y){var z=w[y];if(!z)return g.ACTIVE_ON_WEB;var aa=z[g.WEB_STATUS],ba=z[g.FB_APP_STATUS],ca=z[g.MESSENGER_STATUS],da=z[g.OTHER_STATUS];if(ba===g.STATUS_ACTIVE||ca===g.STATUS_ACTIVE){return g.ACTIVE_ON_MOBILE;}else if(aa===g.STATUS_ACTIVE||da===g.STATUS_ACTIVE){return g.ACTIVE_ON_WEB;}else{if(!q[y]){p.error('inconsistent_presence',{id:y,presence:x.getDebugInfo(y)});h.bumpEntityKey('presence','inconsistent_presence');q[y]=true;}return null;}},isMessengerUser:function(y){var z=w[y];if(z)if(z[g.MESSENGER_STATUS]==g.STATUS_ACTIVE)return true;return t[y];},hasDetailedPresenceData:function(y){return w[y]!=null;},getGroup:function(y){return y.some(function(z){if(z==j.getID())return false;return (x.get(z)===g.ACTIVE);})?g.ACTIVE:g.OFFLINE;},set:function(y,z,aa,ba,ca,da){if(y==j.getID())return false;switch(z){case g.OFFLINE:case g.IDLE:case g.ACTIVE:case g.MOBILE:break;default:return false;}var ea=x.get(y),fa=ea!=z;if(fa&&ea==g.ACTIVE){var ga={};ga[y]=n.get()/1000;k.update(ga);}var ha=false;if(!fa&&ca)ha=x.getCapabilities(y)!=ca;if(aa){u[y]=n.get();v[y]=ba;}r[y]=z;if(ca)s[y]=ca;if(da)w[y]=da;return fa||ha;},setMobileFriends:function(y){t=o(y);},getOnlineIDs:function(){var y,z=[];for(y in r)if(x.get(y)===g.ACTIVE)z.push(y);return z;},getAvailableIDs:function(){var y=x.getOnlineIDs(),z;for(z in t){if(r[z])continue;y.push(z);}return y;},getOnlineCount:function(){return x.getOnlineIDs().length;},getPresenceStats:function(){var y=0,z=0,aa=0,ba=0,ca=0;for(var da in r){y+=1;switch(x.get(da)){case g.OFFLINE:z+=1;break;case g.IDLE:aa+=1;break;case g.ACTIVE:ba+=1;break;case g.MOBILE:ca+=1;break;default:break;}}return {total:y,offline:z,idle:aa,active:ba,mobile:ca};},getDebugInfo:function(y){return {id:y,presence:r[y],detailedPresence:w[y],overlaySource:v[y],overlayTime:u[y],mobile:t[y]};}};e.exports=x;},null);
__d("RequiredFormListener",["Event","Input"],function(a,b,c,d,e,f,g,h){g.listen(document.documentElement,'submit',function(i){var j=i.getTarget().getElementsByTagName('*');for(var k=0;k<j.length;k++)if(j[k].getAttribute('required')&&h.isEmpty(j[k])){j[k].focus();return false;}},g.Priority.URGENT);},null);
__d("CSSClassTransition",["copyProperties"],function(a,b,c,d,e,f,g){var h=[];function i(){}g(i,{go:function(j,k,l,m){var n;for(var o=0;o<h.length;o++)if(h[o](j,k,l,m)===true)n=true;if(!n)j.className=k;},registerHandler:function(j){h.push(j);return {remove:function(){var k=h.indexOf(j);if(k>=0)h.splice(k,1);}};}});e.exports=i;},null);
__d("DocumentTitle",["Arbiter"],function(a,b,c,d,e,f,g){var h=document.title,i=null,j=1500,k=[],l=0,m=null,n=false;function o(){if(k.length>0){if(!n){p(k[l].title);l=++l%k.length;}else q();}else{clearInterval(m);m=null;q();}}function p(s){document.title=s;n=true;}function q(){r.set(i||h,true);n=false;}var r={get:function(){return h;},set:function(s,t){document.title=s;if(!t){h=s;i=null;g.inform('update_title',s);}else i=s;},blink:function(s){var t={title:s};k.push(t);if(m===null)m=setInterval(o,j);return {stop:function(){var u=k.indexOf(t);if(u>=0){k.splice(u,1);if(l>u){l--;}else if(l==u&&l==k.length)l=0;}}};}};e.exports=r;},null);
__d("LayerHideOnSuccess",["copyProperties"],function(a,b,c,d,e,f,g){function h(i){"use strict";this._layer=i;}h.prototype.enable=function(){"use strict";this._subscription=this._layer.subscribe('success',this._layer.hide.bind(this._layer));};h.prototype.disable=function(){"use strict";if(this._subscription){this._subscription.unsubscribe();this._subscription=null;}};g(h.prototype,{_subscription:null});e.exports=h;},null);
__d("Overlay",["CSS","DataStore","DOM","Layer","LayerButtons","LayerDestroyOnHide","LayerFadeOnHide","LayerFadeOnShow","LayerFormHooks","LayerHideOnBlur","LayerHideOnEscape","LayerHideOnSuccess","LayerHideOnTransition","LayerMouseHooks","LayerTabIsolation","copyProperties"],function(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v){for(var w in j)if(j.hasOwnProperty(w))y[w]=j[w];var x=j===null?null:j.prototype;y.prototype=Object.create(x);y.prototype.constructor=y;y.__superConstructor__=j;function y(z,aa){"use strict";z=v({buildWrapper:true},z||{});this._shouldBuildWrapper=z.buildWrapper;j.call(this,z,aa);}y.prototype._configure=function(z,aa){"use strict";x._configure.call(this,z,aa);var ba=this.getRoot();this._overlay=i.scry(ba,'div.uiOverlay')[0]||ba;g.hide(ba);i.appendContent(this.getInsertParent(),ba);h.set(this._overlay,'overlay',this);var ca=h.get(this._overlay,'width');ca&&this.setWidth(ca);if(this.setFixed)this.setFixed(h.get(this._overlay,'fixed')=='true');if(h.get(this._overlay,'fadeonshow')!='false')this.enableBehavior(n);if(h.get(this._overlay,'fadeonhide')!='false')this.enableBehavior(m);if(h.get(this._overlay,'hideonsuccess')!='false')this.enableBehavior(r);if(h.get(this._overlay,'hideonblur')=='true')this.enableBehavior(p);if(h.get(this._overlay,'destroyonhide')!='false')this.enableBehavior(l);return this;};y.prototype._getDefaultBehaviors=function(){"use strict";return x._getDefaultBehaviors.call(this).concat([k,o,t,q,s,u]);};y.prototype.initWithoutBuildingWrapper=function(){"use strict";this._shouldBuildWrapper=false;return this.init.apply(this,arguments);};y.prototype._buildWrapper=function(z,aa){"use strict";aa=x._buildWrapper.call(this,z,aa);if(!this._shouldBuildWrapper){this._contentRoot=aa;return aa;}this._contentRoot=i.create('div',{className:'uiOverlayContent'},aa);return i.create('div',{className:'uiOverlay'},this._contentRoot);};y.prototype.getContentRoot=function(){"use strict";return this._contentRoot;};y.prototype.destroy=function(){"use strict";h.remove(this.getRoot(),'overlay');x.destroy.call(this);};e.exports=y;},null);
__d("PixelRatio",["Arbiter","Cookie","PixelRatioConst","Run"],function(a,b,c,d,e,f,g,h,i,j){var k=i.cookieName,l,m;function n(){return window.devicePixelRatio||1;}function o(){h.set(k,n());}function p(){h.clear(k);}function q(){var s=n();if(s!==l){o();}else p();}var r={startDetecting:function(s){l=s||1;p();if(m)return;m=[g.subscribe('pre_page_transition',q)];j.onBeforeUnload(q);}};e.exports=r;},null);
__d("Poller",["ArbiterMixin","AsyncRequest","CurrentUser","copyProperties","emptyFunction","mixin","setTimeoutAcrossTransitions"],function(a,b,c,d,e,f,g,h,i,j,k,l,m){var n=l(g);for(var o in n)if(n.hasOwnProperty(o))q[o]=n[o];var p=n===null?null:n.prototype;q.prototype=Object.create(p);q.prototype.constructor=q;q.__superConstructor__=n;function q(t){"use strict";this._config=j({clearOnQuicklingEvents:true,setupRequest:k,interval:null,maxRequests:Infinity,dontStart:false},t);this._handle=null;if(!this._config.dontStart)this.start();}q.prototype.start=function(){"use strict";if(this._polling)return this;this._requests=0;this.request();return this;};q.prototype.stop=function(){"use strict";this._cancelRequest();return this;};q.prototype.mute=function(){"use strict";this._muted=true;return this;};q.prototype.resume=function(){"use strict";if(this._muted){this._muted=false;if(this._handle===null&&this._polling)return this.request();}return this;};q.prototype.skip=function(){"use strict";this._skip=true;return this;};q.prototype.reset=function(){"use strict";return this.stop().start();};q.prototype.request=function(){"use strict";this._cancelRequest();this._polling=true;if(!s())return this._done();if(this._muted)return this;if(++this._requests>this._config.maxRequests)return this._done();var t=new h();t.setIsBackgroundRequest(true);var u=false;t.setInitialHandler(function(){return !u;});this._cancelRequest=function(){u=true;this._cleanup();}.bind(this);t.setFinallyHandler(r.bind(this));t.setInitialHandler=k;t.setFinallyHandler=k;this._config.setupRequest(t,this);if(this._skip){this._skip=false;setTimeout(r.bind(this),0);}else t.send();return this;};q.prototype.isPolling=function(){"use strict";return this._polling;};q.prototype.isMuted=function(){"use strict";return this._muted;};q.prototype.setInterval=function(t){"use strict";if(t){this._config.interval=t;this.start();}};q.prototype.getInterval=function(){"use strict";return this._config.interval;};q.prototype._cleanup=function(){"use strict";if(this._handle!==null)clearTimeout(this._handle);this._handle=null;this._cancelRequest=k;this._polling=false;};q.prototype._done=function(){"use strict";this._cleanup();this.inform('done',{sender:this});return this;};q.MIN_INTERVAL=2000;j(q.prototype,{_config:null,_requests:0,_muted:false,_polling:false,_skip:false,_cancelRequest:k});function r(){if(!this._polling)return;if(this._requests<this._config.maxRequests){var t=this._config.interval;t=typeof t==='function'?t(this._requests):t;t=(t>q.MIN_INTERVAL)?t:q.MIN_INTERVAL;if(this._config.clearOnQuicklingEvents){this._handle=setTimeout(this.request.bind(this),t);}else this._handle=m(this.request.bind(this),t);}else this._done();}function s(){return i.isLoggedInNow();}e.exports=q;},null);
__d("SystemEvents",["Arbiter","ErrorUtils","SystemEventsInitialData","UserAgent_DEPRECATED","copyProperties","setIntervalAcrossTransitions"],function(a,b,c,d,e,f,g,h,i,j,k,l){var m=new g(),n=[],o=1000;l(function(){for(var y=0;y<n.length;y++)n[y]();},o);function p(){return (/c_user=(\d+)/.test(document.cookie)&&RegExp.$1)||0;}function q(){return i.ORIGINAL_USER_ID;}var r=q(),s=navigator.onLine;function t(){if(!s){s=true;m.inform(m.ONLINE,s);}}function u(){if(s){s=false;m.inform(m.ONLINE,s);}}if(j.ie()){if(j.ie()>=11){window.addEventListener('online',t,false);window.addEventListener('offline',u,false);}else if(j.ie()>=8){window.attachEvent('onload',function(){document.body.ononline=t;document.body.onoffline=u;});}else n.push(function(){(navigator.onLine?t:u)();});}else if(window.addEventListener)if(!j.chrome()){window.addEventListener('online',t,false);window.addEventListener('offline',u,false);}var v=r;n.push(function(){var y=p();if(v!=y){m.inform(m.USER,y);v=y;}});var w=Date.now();function x(){var y=Date.now(),z=y-w,aa=z<0||z>10000;w=y;if(aa)m.inform(m.TIME_TRAVEL,z);return aa;}n.push(x);n.push(function(){if(window.onerror!=h.onerror)window.onerror=h.onerror;});k(m,{USER:'SystemEvents/USER',ONLINE:'SystemEvents/ONLINE',TIME_TRAVEL:'SystemEvents/TIME_TRAVEL',isPageOwner:function(y){return (y||p())==r;},isOnline:function(){return j.chrome()||s;},checkTimeTravel:x});e.exports=m;},null);
__d("PerfXLogger",["Arbiter","BanzaiLogger","PerfXClientMetricsConfig","Run","performance"],function(a,b,c,d,e,f,g,h,i,j,k){var l={},m='BigPipe/init',n='tti_bigpipe',o='pagelet_event',p='ajaxpipe/onload_callback',q=i.LOGGER_CONFIG,r={},s={listenersSetUp:false,setupListeners:function(){if(this.listenersSetUp)return;g.subscribe(m,function(event,t){var u=t.arbiter;this.subscribeToTTI(u);this.subscribeToPageletEvents(u);this.subscribeToAjaxPipeOnload(u);}.bind(this));this.listenersSetUp=true;},init:function(t,u,v){l[t]={perfx_page:u,perfx_page_type:v};this.setupListeners();},initForPageLoad:function(t,u,v){this.init(t,u,v);j.onAfterLoad(this.finishPageload.bind(this,t));},initForQuickling:function(t,u,v,w){this.init(t,u,v);r[t]=w;},subscribeToTTI:function(t){t.subscribe(n,function(event,u){var v=u.lid;if(!(v in l))return;l[v].tti=u.ts;});},subscribeToPageletEvents:function(t){t.subscribe(o,function(event,u){var v=u.lid;if(!(v in l))return;var w=l[v].e2e;if(!w||w<u.ts)l[v].e2e=u.ts;});},subscribeToAjaxPipeOnload:function(t){t.subscribe(p,function(event,u){this.finishQuickling(u.lid);}.bind(this));},generatePayload:function(t,u){var v=l[t],w=Object.assign({},v);if(!this.adjustAndValidateValues(w,u))return;return w;},getPageloadPayload:function(t){if(!(t in l))return;if(!k.timing){delete l[t];return;}var u=k.timing.navigationStart;return this.generatePayload(t,u);},getQuicklingPayload:function(t){if(!(t in r)||!(t in l))return;if(!k.timing||!k.getEntriesByName){delete r[t];delete l[t];return;}var u=r[t],v=k.getEntriesByName(u);if(v.length===0)return;var w=v[0],x=k.timing.navigationStart+w.startTime;return this.generatePayload(t,x);},finishPageload:function(t){var u=this.getPageloadPayload(t);if(u){this.log(t,u);delete l[t];}},finishQuickling:function(t){var u=this.getQuicklingPayload(t);if(u){this.log(t,u);delete l[t];}},log:function(t,u){u.lid=t;h.log(q,u);},adjustAndValidateValues:function(t,u){var v=['e2e','tti'],w=true;for(var x=0;x<v.length;x++){var y=v[x],z=t[y];if(!z){w=false;break;}t[y]=z-u;}return w;},getPayload:function(t){if(!(t in l))return;var u=l[t].perfx_page_type;if(u==="normal"){return this.getPageloadPayload(t);}else if(u==="quickling")return this.getQuicklingPayload(t);}};e.exports=s;},null);
__d("ContextualDialogFooterLink",["CSS","DOM","Event","copyProperties"],function(a,b,c,d,e,f,g,h,i,j){function k(l){"use strict";this._layer=l;}k.prototype.enable=function(){"use strict";var l=this._layer.getRoot(),m=h.scry(l,'.uiContextualDialogFooterLink')[0],n='uiContextualDialogHoverFooterArrow';this._subscriptions=[i.listen(m,'mouseenter',g.addClass.bind(null,l,n)),i.listen(m,'mouseleave',g.removeClass.bind(null,l,n))];};k.prototype.disable=function(){"use strict";this._subscriptions.forEach(function(l){l.remove();});this._subscriptions=null;};j(k.prototype,{_subscriptions:null});e.exports=k;},null);
__d("LegacyContextualDialog",["Arbiter","ArbiterMixin","ARIA","Bootloader","ContextualDialogFooterLink","ContextualThing","CSS","DataStore","DOM","Event","LayerAutoFocus","LayerRefocusOnHide","Locale","Overlay","Parent","Style","Vector","$","copyProperties","getOverlayZIndex","shield"],function(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,aa){for(var ba in t)if(t.hasOwnProperty(ba))da[ba]=t[ba];var ca=t===null?null:t.prototype;da.prototype=Object.create(ca);da.prototype.constructor=da;da.__superConstructor__=t;function da(){"use strict";if(t!==null)t.apply(this,arguments);}da.prototype._configure=function(ea,fa){"use strict";ca._configure.call(this,ea,fa);var ga=this.getRoot(),ha=n.get.bind(null,ga);this.setAlignH(ha('alignh','left'));this.setOffsetX(ha('offsetx',0));this.setOffsetY(ha('offsety',0));this.setPosition(ha('position','above'));this._hasFooter=ha('hasfooter',false);if(this._hasFooter){var ia=o.scry(ga,'.uiContextualDialogFooterLink')[0];ia&&this.enableBehavior(k);}this.enableBehaviors(this._getDefaultBehaviors());this._setContextSubscription=this.subscribe('beforeshow',function(){this.unsubscribe(this._setContextSubscription);this._setContextSubscription=null;var ka=ha('context');if(ka){this.setContext(x(ka));}else{ka=ha('contextselector');if(ka)this.setContext(o.find(document,ka));}}.bind(this));this._content=o.scry(ga,'.uiContextualDialogContent')[0];if(this._content){this._content.setAttribute('role','dialog');var ja=o.scry(this._content,'.legacyContextualDialogTitle')[0];if(ja)this._content.setAttribute('aria-labelledby',o.getID(ja));}this._showSubscription=this.subscribe('show',function(){var ka=aa(this.updatePosition,this);this._resizeListener=p.listen(window,'resize',ka);this._reflowSubscription=g.subscribe('reflow',ka);this._setupScrollListener(this._scrollParent);l.register(ga,this.context);g.inform('layer_shown',{type:'ContextualDialog'});}.bind(this));this._hideSubscription=this.subscribe('hide',function(){this._teardownResizeAndReflowListeners();this._teardownScrollListener();g.inform('layer_hidden',{type:'ContextualDialog'});}.bind(this));return this;};da.prototype._getDefaultBehaviors=function(){"use strict";return ca._getDefaultBehaviors.call(this).concat([q,r]);};da.prototype._buildWrapper=function(ea,fa){"use strict";var ga=ca._buildWrapper.call(this,ea,fa);if(!this._shouldBuildWrapper)return ga;m.addClass(ga,'uiContextualDialog');return o.create('div',{className:'uiContextualDialogPositioner'},ga);};da.prototype.setWidth=function(ea){"use strict";this._width=Math.floor(ea);return this;};da.prototype.setFixed=function(ea){"use strict";ea=!!ea;m.conditionClass(this.getRoot(),'uiContextualDialogFixed',ea);this._fixed=ea;return this;};da.prototype.setAlignH=function(ea){"use strict";this.alignH=ea;this._updateAlignmentClass();this._shown&&this.updatePosition();this.position&&this._updateArrow();return this;};da.prototype.getContent=function(){"use strict";return this._content;};da.prototype.getContext=function(){"use strict";return this.context;};da.prototype.setContext=function(ea){"use strict";if(this._setContextSubscription){this.unsubscribe(this._setContextSubscription);this._setContextSubscription=null;}ea=x(ea);if(this.context&&this.context!=ea)n.remove(this.context,'LegacyContextualDialog');this.context=ea;i.setPopup(this.getCausalElement(),this.getRoot());var fa=u.byClass(ea,'fbPhotoSnowlift');this.setInsertParent(fa||document.body);if(this._scrollListener&&this._scrollParent!==fa){this._teardownScrollListener();this._setupScrollListener(fa);}this._scrollParent=fa;var ga=z(ea,this._insertParent);v.set(this.getRoot(),'z-index',ga>200?ga:'');n.set(this.context,'LegacyContextualDialog',this);return this;};da.prototype.getCausalElement=function(){"use strict";return ca.getCausalElement.call(this)||this.context;};da.prototype.listen=function(ea,fa){"use strict";return p.listen(this.getRoot(),ea,fa);};da.prototype.setOffsetX=function(ea){"use strict";this.offsetX=parseInt(ea,10)||0;this._shown&&this.updatePosition();return this;};da.prototype.setOffsetY=function(ea){"use strict";this.offsetY=parseInt(ea,10)||0;this._shown&&this.updatePosition();return this;};da.prototype.setPosition=function(ea){"use strict";this.position=ea;for(var fa in da.POSITION_TO_CLASS)m.conditionClass(this.getRoot(),da.POSITION_TO_CLASS[fa],ea==fa);this._updateAlignmentClass();this._shown&&this.updatePosition();this._updateArrow();return this;};da.prototype.updatePosition=function(){"use strict";if(!this.context)return false;if(this._width)v.set(this._overlay,'width',this._width+'px');var ea=this._fixed?'viewport':'document',fa=w.getElementPosition(this.context,ea),ga=this._scrollParent;if(ga)fa=fa.sub(w.getElementPosition(ga,'document')).add(ga.scrollLeft,ga.scrollTop);var ha=w.getElementDimensions(this.context),ia=this.position=='above'||this.position=='below',ja=s.isRTL();if((this.position=='right'||(ia&&this.alignH=='right'))!=ja)fa=fa.add(ha.x,0);if(this.position=='below')fa=fa.add(0,ha.y);var ka=new w(0,0);if(ia&&this.alignH=='center'){ka=ka.add((ha.x-this._width)/2,0);}else{var la=ia?ha.x:ha.y,ma=2*da.ARROW_INSET;if(la<ma){var na=la/2-da.ARROW_INSET;if(ia&&(this.alignH=='right'!=ja))na=-na;ka=ka.add(ia?na:0,ia?0:na);}}ka=ka.add(this.offsetX,this.offsetY);if(ja)ka=ka.mul(-1,1);fa=fa.add(ka);if(this._fixed)fa=new w(fa.x,fa.y,'document');fa.setElementPosition(this.getRoot());this._adjustVerticalPosition();this._adjustHorizontalPosition();return true;};da.prototype.scrollTo=function(){"use strict";if(this.context)j.loadModules(["DOMScroll"],function(ea){ea.scrollTo(this.context,true,true);}.bind(this));};da.prototype.destroy=function(){"use strict";this.unsubscribe(this._showSubscription);this.unsubscribe(this._hideSubscription);if(this._setContextSubscription){this.unsubscribe(this._setContextSubscription);this._setContextSubscription=null;}this._teardownScrollListener();this._teardownResizeAndReflowListeners();this.context&&n.remove(this.context,'LegacyContextualDialog');ca.destroy.call(this);};da.prototype._adjustVerticalPosition=function(){"use strict";if(this.position!='left'&&this.position!='right'){v.set(this._overlay,'top','');return;}var ea=this.getRoot(),fa=w.getElementPosition(ea,'viewport').y,ga=w.getElementDimensions(this._overlay).y,ha=w.getViewportDimensions().y,ia=Math.min(Math.max(fa,da.MIN_TOP_GAP),da.TOP_MARGIN),ja=Math.min(Math.max(0,fa+ga+da.BOTTOM_MARGIN-ha),Math.max(-ia,fa-ia),ga-2*da.ARROW_INSET);v.set(this._overlay,'top',(-1*ja)+'px');v.set(this._arrow,'top',da.ARROW_OFFSET+'px');v.set(this._arrow,'marginTop',ja+'px');};da.prototype._adjustHorizontalPosition=function(){"use strict";if((this.position!='above'&&this.position!='below')||this.alignH!='left'){v.set(this._overlay,'left','');v.set(this._overlay,'right','');return;}var ea=this.getRoot(),fa=w.getElementPosition(ea,'viewport').x,ga=w.getElementDimensions(this._overlay).x,ha=w.getViewportDimensions().x,ia=s.isRTL(),ja;if(!ia){ja=fa+ga+da.RIGHT_MARGIN-ha;}else ja=da.LEFT_MARGIN+ga-fa;ja=Math.min(Math.max(0,ja),ga-2*da.ARROW_INSET);v.set(this._overlay,ia?'right':'left',-1*ja+'px');v.set(this._arrow,ia?'right':'left',da.ARROW_OFFSET+'px');v.set(this._arrow,ia?'marginRight':'marginLeft',ja+'px');};da.prototype._updateArrow=function(){"use strict";var ea=0;if(this.position=='above'||this.position=='below')switch(this.alignH){case 'center':ea=50;break;case 'right':ea=100;break;}this._renderArrow(da.POSITION_TO_ARROW[this.position],ea);};da.prototype._renderArrow=function(ea,fa){"use strict";for(var ga in da.ARROW_CLASS)m.conditionClass(this._overlay,da.ARROW_CLASS[ga],ea==ga);m.conditionClass(this._overlay,'uiContextualDialogWithFooterArrowBottom',ea=='bottom'&&this._hasFooter);if(ea=='none')return;if(!this._arrow){this._arrow=o.create('i',{className:'uiContextualDialogArrow'});o.appendContent(this._overlay,this._arrow);}v.set(this._arrow,'top','');v.set(this._arrow,'left','');v.set(this._arrow,'right','');v.set(this._arrow,'margin','');var ha=ea=='top'||ea=='bottom',ia=ha?(s.isRTL()?'right':'left'):'top';fa=fa||0;v.set(this._arrow,ia,fa+'%');var ja=da.ARROW_LENGTH+da.ARROW_OFFSET*2,ka=-(ja*fa/100-da.ARROW_OFFSET);v.set(this._arrow,'margin-'+ia,ka+'px');};da.prototype._updateAlignmentClass=function(){"use strict";m.conditionClass(this.getRoot(),da.RIGHT_ALIGNED_CLASS,(this.position=='above'||this.position=='below')&&this.alignH=='right');};da.prototype._setupScrollListener=function(ea){"use strict";this._scrollListener=p.listen(ea||window,'scroll',aa(this._adjustVerticalPosition,this));};da.prototype._teardownScrollListener=function(){"use strict";if(this._scrollListener){this._scrollListener.remove();this._scrollListener=null;}};da.prototype._teardownResizeAndReflowListeners=function(){"use strict";if(this._resizeListener){this._resizeListener.remove();this._resizeListener=null;}if(this._reflowSubscription){this._reflowSubscription.unsubscribe();this._reflowSubscription=null;}};da.getInstance=function(ea){"use strict";var fa=n.get(ea,'LegacyContextualDialog');if(!fa){var ga=u.byClass(ea,'uiOverlay');if(ga)fa=n.get(ga,'overlay');}return fa;};y(da,h,{ARROW_OFFSET:15,ARROW_LENGTH:16,ARROW_INSET:22,TOP_MARGIN:50,BOTTOM_MARGIN:30,LEFT_MARGIN:15,RIGHT_MARGIN:30,MIN_TOP_GAP:5,POSITION_TO_CLASS:{above:'uiContextualDialogAbove',below:'uiContextualDialogBelow',left:'uiContextualDialogLeft',right:'uiContextualDialogRight'},RIGHT_ALIGNED_CLASS:'uiContextualDialogRightAligned',ARROW_CLASS:{bottom:'uiContextualDialogArrowBottom',top:'uiContextualDialogArrowTop',right:'uiContextualDialogArrowRight',left:'uiContextualDialogArrowLeft'},POSITION_TO_ARROW:{above:'bottom',below:'top',left:'right',right:'left'}});y(da.prototype,{_scrollListener:null,_scrollParent:null,_width:null,_fixed:false,_hasFooter:false,_showSubscription:null,_hideSubscription:null,_setContextSubscription:null,_resizeListener:null,_reflowSubscription:null});e.exports=da;},null);