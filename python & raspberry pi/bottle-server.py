__author__ = 'ali'

from bottle import route, run, template, response, hook
from datetime import datetime
from subprocess import call
import pyfirmata
import os.path
import urllib
import urllib2
import time
import json

board = pyfirmata.Arduino('/dev/ttyACM0')
analog_pin = board.get_pin('a:0:i')
temp_pin = board.get_pin('a:1:i')
it = pyfirmata.util.Iterator(board)
it.start()
analog_pin.enable_reporting()
temp_pin.enable_reporting()

@hook('after_request')
def enable_cors():
	response.headers['Access-Control-Allow-Origin'] = '*'
	response.headers['Server'] = 'AEHomeServer'

@route('/')
def index():
	f = open('index.htm')
	s = f.read()
	f.close()
	return s

@route('/functions.js')
def get_functions():
	response.content_type = 'application/x-javascript'
	f = open('functions.js')
	s = f.read()
	f.close()
	return s

@route('/<file>')
def index(file):
	if os.path.exists(file):
		f = open(file)
		s = f.read()
		f.close()
		return s

@route('/lamp/<unit>/<cmd>/')
def index(unit,cmd):
	if unit == '1' and cmd == '1':
		call("sudo send 1 1",shell=True)
		return "1 i ac"
	elif unit =='1' and cmd =='0':
		call("sudo send 1 0",shell=True)
		return "1 i kapa"
	elif unit =='2' and cmd =='1':
		call("sudo send 2 1",shell=True)
		return "2 i ac"
	elif unit =='2' and cmd =='0':
		call("sudo send 2 0",shell=True)
		return "2 i kapa"
	elif unit =='3' and cmd =='1':
		call("sudo send 3 1",shell=True)
		return "3 u ac"
	elif unit =='3' and cmd =='0':
		call("sudo send 3 0",shell=True)
		return "3 u kapa"
	elif unit == 'all' and cmd == '1':
		call("sudo send 1 1", shell=True)
		time.sleep(0.2)
		call("sudo send 2 1", shell=True)
		time.sleep(0.2)
		call("sudo send 3 1", shell=True)
	elif unit == 'all' and cmd == '0':
		call("sudo send 1 0", shell=True)
		time.sleep(0.2)
		call("sudo send 2 0", shell=True)
		time.sleep(0.2)
		call("sudo send 3 0", shell=True)
	else:
		return "nothing"

@route('/lamp/<num>/')
def index(num):
	content = '<h3>This is for controlling ' \
	          'lamps</h3><br/>Controlling lamp '+num
	return content

@route('/receiver/<command>/<num>/')
@route('/receiver/<command>/<num>/<val:re:[\-0-9]*>')
def receiver_control(command,num,val=""):
	if command == 'status':
		if num == '1':
			contents = urllib2.urlopen("http://192.168.1.10/goform/formMainZone_MainZoneXml.xml?_="+str(int(time.time(

			)))).read()
		elif num == '2':
			contents = urllib2.urlopen("http://192.168.1.10/goform/formMainZone_MainZoneXml.xml?_="+str(int(time.time(

			)))+"&ZoneName=ZONE2").read()
	elif command == 'powerOn':
		if num == '1':
			query_args = {'cmd0':'PutZone_OnOff/ON','cmd1':'aspMainZone_WebUpdateStatus/'}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp",encoded_args).read()
			return "1 on"
		elif num =='2':
			query_args = {'cmd0':'PutZone_OnOff/ON','cmd1':'aspMainZone_WebUpdateStatus/','ZoneName':'ZONE2'}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp",encoded_args).read()
			return "2 on"
	elif command == 'powerOff':
		if num == '1':
			query_args = {'cmd0':'PutZone_OnOff/OFF','cmd1':'aspMainZone_WebUpdateStatus/'}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp",encoded_args).read()
			return "1 off"
		elif num =='2':
			query_args = {'cmd0':'PutZone_OnOff/OFF','cmd1':'aspMainZone_WebUpdateStatus/','ZoneName':'ZONE2'}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp",encoded_args).read()
			return "2 off"
	elif command == 'volume' and val != "":
		if num =='1':
			query_args = {'cmd0':'PutMasterVolumeSet/'+str(val)}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp", encoded_args)
			return contents
		elif num == '2':
			query_args = {'cmd0':'PutMasterVolumeSet/'+str(val),'ZoneName':'ZONE2'}
			encoded_args = urllib.urlencode(query_args)
			contents = urllib2.urlopen("http://192.168.1.10/MainZone/index.put.asp", encoded_args)
			return contents


	response.content_type = 'text/xml'
	return contents

@route('/brightness/')
def index():
	reading = analog_pin.read()
	if reading!= None:
		R = {'light':reading, 'voltage':(reading*5.0)}
	else:
		R = {'light':0, 'voltage':5}
	response.content_type = 'text/json'
	return R


@route('/temp/')
def index():
	reading = temp_pin.read()
	if reading!= None:
		R = {'result':reading}
	else:
		R = {'result':'no data'}
	response.content_type = 'text/json'
	return R

run(host='192.168.1.5', port=80)