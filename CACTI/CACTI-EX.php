#!/usr/bin/php
<?php

function PrefNow() {
	extract($GLOBALS);
	for ($i = 0; $i < 10; $i++){
		if ($argv[1] == $power_company[$i]) {
			print("usage:".sprintf("%d",$Power[$power_company[$i]]["den_now"] * 10000 * 1000));
			echo " ";
			print("capacity:".sprintf("%d",$Power[$power_company[$i]]["den_max"] * 10000 * 1000));
			echo " ";
			print("yosou:".sprintf("%d",$Power[$power_company[$i]]["den_yosou"] * 10000 * 1000));
		}
	}
	if ($argv[1] == "temp"){
		print("temp:".sprintf("%.1f",$temp_now));
		print(" ");
		print("feelslike:".sprintf("%.1f",$feelslike));
	} elseif ($argv[1] == "humidity-and-clouds"){
		print("humidity:".sprintf("%d",$humidity_now));
		print(" ");
		print("clouds:".sprintf("%d",$clouds_value));
		print(" ");
		print("discomfort:".sprintf("%d",$discomfort));
	} elseif ($argv[1] == "pressure"){
		print(sprintf("%d",$pressure_now));
	} elseif ($argv[1] == "windspeed"){
		print(sprintf("%.1f",$windspeed_now));
	} elseif ($argv[1] == "kazamuki"){
		print(sprintf("%d",$kazamuki_degrees));
	}

	if ($argv[1] == "sunmoon"){
		print("sunrise:".sprintf("%.2f",$OhaKon["sunrise"]));
		echo " ";
		print("sunset:".sprintf("%.2f",$OhaKon["sunset"]));
		echo " ";
		print("moonrise:".sprintf("%.2f",$OhaKon["moonrise"]));
		echo " ";
		print("moonset:".sprintf("%.2f",$OhaKon["moonset"]));
	} elseif ($argv[1] == "moonage"){
		print(sprintf("%.2f",$OhaKon["moon_age"]));
	} elseif ($argv[1] == "moonphase"){
		print(sprintf("%.2f",$OhaKon["moon_phase"]));
	}
}
//---------------------------------------------------------------------------------------

if (!isset($argv[1]) || !isset($argv[2])) {
	echo "（例１）CACTI-EX.php humidity-and-clouds ise_prefs.xml\n";
	echo "（例２）CACTI-EX.php Tokyo power_prefs.xml\n";
	exit;
}
$FILE = $argv[2];

$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");

$filename = '/etc/zabbix/externalscripts/'.$FILE;
$xml = @simplexml_load_file($filename);
if ($xml && ($argv[2] == "ise_prefs.xml" || $argv[2] == "okinawa_prefs.xml" || $argv[2] == "kitamoto_prefs.xml")) {

	$description      = ($xml->pref[0]->description);
	$clouds_value     = ($xml->pref[0]->clouds_value);

	$weather	  = ($xml->pref[1]->weather);
	$kazamuki_degrees = ($xml->pref[1]->kazamuki_degrees);
	$kazamuki         = ($xml->pref[1]->kazamuki2);
	$temp_now         = ($xml->pref[1]->temp_now2);
	$temp_now_f       = ($xml->pref[1]->temp_now_f);
	$feelslike	  = ($xml->pref[1]->feelslike);
	$humidity_now     = ($xml->pref[1]->humidity_now2);
	$windspeed_now    = ($xml->pref[1]->windspeed_now2);
	$pressure_now     = ($xml->pref[1]->pressure_now2);
        $discomfort       = ($xml->pref[1]->discomfort);

} elseif ($xml && ($argv[2] == "power_prefs.xml")) {
	for ($i = 0; $i < 10; $i++){
		$Power[$power_company[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
		$Power[$power_company[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
		$Power[$power_company[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
		$Power[$power_company[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
	}
} elseif ($xml && ($argv[2] == "ise_sunmoon_prefs.xml" || $argv[2] == "okinawa_sunmoon_prefs.xml" || $argv[2] == "kitamoto_sunmoon_prefs.xml")) {
	$OhaKon["sunrise"]	= ($xml->pref[0]->sunrise);
	$OhaKon["sunset"]	= ($xml->pref[0]->sunset);
	$OhaKon["sunrise_hm"]	= ($xml->pref[0]->sunrise_hm);
	$OhaKon["sunset_hm"]	= ($xml->pref[0]->sunset_hm);
	$OhaKon["moonrise"]	= ($xml->pref[0]->moonrise);
	$OhaKon["moonset"]	= ($xml->pref[0]->moonset);
	$OhaKon["moonrise_hm"]	= ($xml->pref[0]->moonrise_hm);
	$OhaKon["moonset_hm"]   = ($xml->pref[0]->moonset_hm);
	$OhaKon["moon_age"]	= ($xml->pref[0]->moon_age);
	$OhaKon["moon_phase"]   = ($xml->pref[0]->moon_phase);
	$OhaKon["fullmoon_wait"]= ($xml->pref[0]->fullmoon_wait);
	$OhaKon["newmoon_wait"]	= ($xml->pref[0]->newmoon_wait);
	$OhaKon["tide_name"]	= ($xml->pref[0]->tide_name);
	$OhaKon["time"]		= ($xml->pref[1]->time);
} else {
	echo "ERROR";
	exit;
}

PrefNow();
?>
