#!/usr/bin/php
<?php

require_once ("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");

function file_write(){

	extract($GLOBALS);

	$dom = new DomDocument('1.0','UTF-8');
	$prefs = $dom->appendChild($dom->createElement('prefs'));
	$pref = $prefs->appendChild($dom->createElement('pref'));

	$pref->setAttribute('code','OpenWeatherMap');
	$pref->appendChild($dom->createElement('place',$owm["place"]));
	$pref->appendChild($dom->createElement('description',$owm["description"]));
	$pref->appendChild($dom->createElement('temp_now',$owm["temp_now"]));
	$pref->appendChild($dom->createElement('humidity_now',$owm["humidity_now"]));
	$pref->appendChild($dom->createElement('pressure_now',$owm["pressure"]));
	$pref->appendChild($dom->createElement('wind_dir',$owm["wind_dir"]));
	$pref->appendChild($dom->createElement('wind_dir_value',$owm["wind_dir_value"]));
	$pref->appendChild($dom->createElement('wind_speed',$owm["wind_speed"]));
	$pref->appendChild($dom->createElement('clouds_value',$owm["clouds_value"]));
	$pref->appendChild($dom->createElement('discomfort',$owm["fukai"]));
		
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
//-----------------------------------------------------------------------
if ($argc != 4) {
	$LAT = "35.6811856";
	$LON = "139.7638611";
	$OUT_FILE = "weather_prefs.xml";

} else {
	$LAT = $argv[1];
	$LON = $argv[2];
	$OUT_FILE = $argv[3];
}

echo "LAT=".$LAT."\nLON=".$LON."\nOUT_FILE=".$OUT_FILE."\nで出力します。\n";

$WW = new Weather_Class;
$owm = $WW->openweathermap();

file_write();
?>
