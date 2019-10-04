#!/usr/bin/php
<?php

function PrefNow() {
	extract($GLOBALS);

	for ($i = 0; $i < 10; $i++){
		if ($argv[1] == $power_company[$i]) {
			print(sprintf("%d",$Power[$power_company[$i]]["den_now"] * 10000 * 1000));
			echo "\n";
			print(sprintf("%d",$Power[$power_company[$i]]["den_max"] * 10000 * 1000));
		}
	}

	if ($argv[1] == "temp-and-feelslike"){
		print(sprintf("%.1f",$temp_now));
		echo "\n";
		print(sprintf("%.1f",$temp_now));
	} elseif ($argv[1] == "humidity-and-clouds"){
		print(sprintf("%d",$humidity_now));
		echo "\n";
		print(sprintf("%d",$clouds_value));
	} elseif ($argv[1] == "pressure"){
		print(sprintf("%.1f",$pressure_now));
		echo "\n";
		print(sprintf("%.1f",$pressure_now));
	} elseif ($argv[1] == "windspeed"){
		print(sprintf("%.1f",$wind_speed));
		echo "\n";
		print(sprintf("%.1f",$wind_speed));
	} elseif ($argv[1] == "kazamuki"){
		print(sprintf("%d",$wind_dir_value));
		echo "\n";
		print(sprintf("%d",$wind_dir_value));
	} elseif ($argv[1] == "discomfort"){
		print(sprintf("%.1f",$discomfort));
		echo "\n";
		print(sprintf("%.1f",$discomfort));
	} elseif ($argv[1] == "sun"){
		print(sprintf("%d",(float)$OhaKon["sunrise"] * 100));
		echo "\n";
		print(sprintf("%d",(float)$OhaKon["sunset"] * 100));
	} elseif ($argv[1] == "moon"){
		print(sprintf("%d",(float)$OhaKon["moonrise"] * 100));
		echo "\n";
		print(sprintf("%d",(float)$OhaKon["moonset"] * 100));
	} elseif ($argv[1] == "MOON-AGE"){
		print(sprintf("%d",(float)$OhaKon["moon_age"] * 100));
		echo "\n";
		print(sprintf("%d",(float)$OhaKon["moon_age"] * 100));
	} elseif ($argv[1] == "MOON-PHASE"){
		print(sprintf("%d",(int)$OhaKon["moon_phase"]));
		echo "\n";
		print(sprintf("%d",(int)$OhaKon["moon_phase"]));
	}
}
//--------------------------------------------------------------------------------------------
if (!isset($argv[1]) || !isset($argv[2])) {
	echo "（例１）MRTG-EX.php humidity-and-clouds ise_prefs.xml\n";
	echo "（例２）MRTG-Ex.php Tokyo power_prefs.xml\n";
	exit;
} else {
	$FILE = $argv[2];
}

$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");

$filename = '/etc/zabbix/externalscripts/'.$FILE;
$xml = @simplexml_load_file($filename);
if ($xml && ($argv[2] == "ise_prefs.xml" || $argv[2] == "okinawa_prefs.xml")) {

	$description      = ($xml->pref[0]->description);
	$temp_now         = ($xml->pref[0]->temp_now);
	$humidity_now     = ($xml->pref[0]->humidity_now);
	$pressure_now     = ($xml->pref[0]->pressure_now);
	$wind_speed       = ($xml->pref[0]->wind_speed);
	$wind_dir_value   = ($xml->pref[0]->wind_dir_value);
	$wind_dir         = ($xml->pref[0]->wind_dir);
	$clouds_value     = ($xml->pref[0]->clouds_value);
	$discomfort       = ($xml->pref[0]->discomfort);

} elseif ($xml && ($argv[2] == "power_prefs.xml")) {
	for ($i = 0; $i < 10; $i++){
//		$Power[$power_company[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
		$Power[$power_company[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
		$Power[$power_company[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
//		$Power[$power_company[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
	}
} elseif ($xml && ($argv[2] == "ise_sunmoon_prefs.xml" || $argv[2] == "okinawa_sunmoon_prefs.xml")) {
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
	echo "ERROR-1";
	exit;
}

PrefNow();
?>
