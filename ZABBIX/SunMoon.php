#!/usr/bin/php
<?php

require_once ("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");

function file_write(){

	extract ($GLOBALS);

	$ohakon["sunrise_hm"] 	= str_replace(":","時",$ohakon["sunrise_hm"])."分";
	$ohakon["sunset_hm"]  	= str_replace(":","時",$ohakon["sunset_hm"])."分";
	$ohakon["moonrise_hm"]	= str_replace(":","時",$ohakon["moonrise_hm"])."分";
	$ohakon["moonset_hm"] 	= str_replace(":","時",$ohakon["moonset_hm"])."分";
		
	$dom = new DomDocument('1.0','UTF-8');
	$prefs = $dom->appendChild($dom->createElement('prefs'));

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','SunMoon');
	$pref->appendChild($dom->createElement('sunrise',sprintf("%05s",$ohakon["sunrise"])));
	$pref->appendChild($dom->createElement('sunset',sprintf("%05s",$ohakon["sunset"])));
	$pref->appendChild($dom->createElement('sunrise_hm',sprintf("%010s",$ohakon["sunrise_hm"])));
	$pref->appendChild($dom->createElement('sunset_hm',sprintf("%010s",$ohakon["sunset_hm"])));
	$pref->appendChild($dom->createElement('moonrise',sprintf("%05s",$ohakon["moonrise"])));
	$pref->appendChild($dom->createElement('moonset',sprintf("%05s",$ohakon["moonset"])));
	$pref->appendChild($dom->createElement('moonrise_hm',sprintf("%010s",$ohakon["moonrise_hm"])));
	$pref->appendChild($dom->createElement('moonset_hm',sprintf("%010s",$ohakon["moonset_hm"])));
	$pref->appendChild($dom->createElement('moon_age',$ohakon["moon_age"]));
	$pref->appendChild($dom->createElement('moon_phase',$ohakon["moon_phase"]));
//	$pref->appendChild($dom->createElement('fullmoon_wait',$ohakon["fullmoon_wait"]));
//	$pref->appendChild($dom->createElement('newmoon_wait',$ohakon["newmoon_wait"]));
	$pref->appendChild($dom->createElement('tide_name',$tide_name));

	$weekjp = array(
	  '日', //0
	  '月', //1
	  '火', //2
	  '水', //3
	  '木', //4
	  '金', //5
	  '土'  //6
	);
	 
	$weekno = date('w');
	 
	$week_X = "〔".$weekjp[$weekno]."〕";

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','DATE');
	$pref->appendChild($dom->createElement('time',date("Y年m月j日").$week_X.date(" H時i分")));
	$pref->appendChild($dom->createElement('time_ampm',date("Y年m月j日").$week_X.date(" A h:i")));

	$dom->formatOutput = true;
	$save_file = "/etc/zabbix/externalscripts/".$OUT_FILE;
	$dom->save($save_file);
}
if ($argc != 4) {
	$LAT = "35.6811856";
	$LON = "139.7638611";
	$OUT_FILE = "sunmoon_prefs.xml";

} else {
	$LAT = $argv[1];
	$LON = $argv[2];
	$OUT_FILE = $argv[3];
}

echo "LAT=".$LAT."\nLON=".$LON."\nOUT_FILE=".$OUT_FILE."\nで出力します。\n";

$SunMoon = new Moon_Class;
$ohakon = $SunMoon->ohakon($LAT,$LON);
$tide_name = $SunMoon->shiomei($ohakon["moon_age"]);
file_write();
?>
