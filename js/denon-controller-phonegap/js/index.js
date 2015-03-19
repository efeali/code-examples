/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
        /*var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');*/

        console.log('Received Event: ' + id);
    }
};
//receiverStatus = {zone1:{power:"",mainPower:"",masterVolume:""},zone2:{power:"",mainPower:"",masterVolume:""}};
receiverStatus = {zone1:{},zone2:{}};
receiver = new Object();
receiver.ip = '192.168.2.10';

var zone2Power,zone1Power,zone1Volume,zone2Volume;

function turnOnOff(zNo,value)
{
	var action;
	value = parseInt(value);
	zNo = parseInt(zNo);
	switch(zNo){
		case 1:
			switch(value){
				case 0:
					action = "OFF";
					zone1Volume.slider('disable').slider('refresh');
					break;
				case 1:
					action = "ON";
					zone1Volume.slider('enable').slider('refresh');
					break;
				}
				$.post('http://'+receiver.ip+'/MainZone/index.put.asp',{cmd0:'PutZone_OnOff/'+action,cmd1:'aspMainZone_WebUpdateStatus/'});
			break; 
		case 2: 
				switch(value){
				case 0:
					action = "OFF";
					zone2Volume.slider('disable').slider('refresh');
					break;
				case 1:
					action = "ON";
					zone2Volume.slider('enable').slider('refresh');
					break;
				}
				$.post('http://'+receiver.ip+'/MainZone/index.put.asp',{cmd0:'PutZone_OnOff/'+action,cmd1:'aspMainZone_WebUpdateStatus/',ZoneName:'ZONE2'});
			break;
	}

}

function changeVolume(zone,value)
{
	
	value = parseInt(value);
	switch(zone){
		case 'zone1Volume':
			$.post('http://'+receiver.ip+'/MainZone/index.put.asp',{cmd0:'PutMasterVolumeSet/'+value});
			break;
		case 'zone2Volume':
			$.post('http://'+receiver.ip+'/MainZone/index.put.asp',{cmd0:'PutMasterVolumeSet/'+value,ZoneName:'ZONE2'});
			break;
	}
	
}

function getReceiverStatus()
{
	var flag =0;
	var date = new Date();
	var time = date.valueOf();
	$.ajax({
			url:"http://"+receiver.ip+"/goform/formMainZone_MainZoneXml.xml",
			type:'GET',
			dataType:"XML",
			data:{_:date.valueOf()},
			timeout:2000,
			success: function(resp){
				receiverStatus.zone1.power=$(resp).find('ZonePower').find('value').text();
				receiverStatus.zone1.mainPower = $(resp).find('Power').find('value').text();
				receiverStatus.zone1.masterVolume = $(resp).find('MasterVolume').find('value').text();
				}
			}).done(function(){
				$.ajax({
				url:"http://"+receiver.ip+"/goform/formMainZone_MainZoneXml.xml",
				type:'GET',
				dataType:"XML",
				data:{_:date.valueOf(), ZoneName:'ZONE2'},
				timeout:2000,
				success: function(resp){
					receiverStatus.zone2.power = $(resp).find('ZonePower').find('value').text();
					receiverStatus.zone2.mainPower = $(resp).find('Power').find('value').text();
					receiverStatus.zone2.masterVolume = $(resp).find('MasterVolume').find('value').text();
					}
				}).done(function()
				{	
					
				}); // 2nd ajax
		
		}); // 1st ajax
	
	setTimeout(populateReceiverStat,2000);

}

function populateReceiverStat(){

	if(typeof receiverStatus.zone1.masterVolume == "undefined")
		receiverStatus.zone1.masterVolume = -80;
	if(typeof receiverStatus.zone2.masterVolume == "undefined")
		receiverStatus.zone2.masterVolume = -80;

	if(typeof receiverStatus.zone1.power != "undefined")
	{
		if(receiverStatus.zone1.power=="ON")
		{
			zone1Power.val(1);
			zone1Volume.slider('enable').slider('refresh');
		}
		else
		{
			zone1Power.val(0);
		}
		zone1Power.slider('enable').slider('refresh');
	}
	else
	{	
		zone1Power.attr('disabled','disabled').slider('refresh');
	}
	
	if(typeof receiverStatus.zone2.power != "undefined")
	{
		if(receiverStatus.zone2.power == "ON")
		{
			zone2Power.val(1);	
			zone2Volume.slider('enable').slider('refresh');
		}
		else
		{
			zone2Power.val(0);
			
		}
		zone2Power.slider('enable').slider('refresh');
	}
	else
	{
		zone2Power.attr('disabled','disabled').slider('refresh');
	}
	
	zone1Volume.val(receiverStatus.zone1.masterVolume).slider('refresh');
	zone2Volume.val(receiverStatus.zone2.masterVolume).slider('refresh');

	
}
$(document).ready(function(e) {
	$(function() {
		FastClick.attach(document.body);
	});
   
});


$(document).delegate("#receiverControl","pageinit", function(){
	/*var exdate=new Date();
	exdate.setDate(exdate.getDate() + 1);
	var c_value=escape('MAIN ZONE') + ((1==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie='ZoneName' + "=" + c_value;*/
	zone2Power = $('#zone2Power');
	zone1Power = $('#zone1Power');
	zone1Volume = $('#zone1Volume');
	zone2Volume = $('#zone2Volume');
	
	zone1Volume.on('slidestop', function(event){changeVolume(this.id,this.value)}).delay(1000);
	zone2Volume.on('slidestop', function(event){changeVolume(this.id,this.value)}).delay(1000);
	
	//$('#zone1Power').slider('disable').slider('refresh');
	//$('#zone2Power').slider('disable').slider('refresh');
	zone1Volume.slider('disable').slider('refresh');
	zone2Volume.slider('disable').slider('refresh');
	
	
	zone1Power.slider('disable').slider('refresh');
	zone2Power.slider('disable').slider('refresh');
})

$(document).delegate('#receiverControl','pageshow',function(){
	getReceiverStatus();
});

