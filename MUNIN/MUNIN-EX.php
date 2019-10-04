#!/usr/bin/php
<?php

require_once("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");

function PrefNow() {
	extract($GLOBALS);
	for ($i = 0; $i < 10; $i++){
		if ($argv[1] == $power_company[$i]) {
			$data1=intval($Power[$power_company[$i]]["den_max"])*10000*1000;
			$data2=intval($Power[$power_company[$i]]["den_now"])*10000*1000;
			$data3=intval($Power[$power_company[$i]]["den_yosou"])*10000*1000;
			print("capacity.value ".$data1);
			echo "\n";
			print("usage.value ".$data2);
			echo "\n";
			print("yosou.value ".$data3);
			echo "\n";
		}
	}

	if ($argv[1] == "temp"){
		print("temp.value ".sprintf("%.1f",$temp_now));
		echo "\n";
//		print("feelslike.value ".sprintf("%.1f",$feelslike));
//		echo "\n";
	} elseif ($argv[1] == "humidity-and-clouds"){
		print("humidity.value ".sprintf("%d",$humidity_now));
		echo "\n";
		print("clouds.value ".sprintf("%d",$clouds_value));
		echo "\n";
		print("discomfort.value ".sprintf("%.1f",$discomfort));
		echo "\n";
	} elseif ($argv[1] == "pressure"){
		print("pressure.value ".sprintf("%.1f",$pressure_now));
		echo "\n";
	} elseif ($argv[1] == "windspeed"){
		print("windspeed.value ".sprintf("%.1f",$windspeed_now));
		echo "\n";
	} elseif ($argv[1] == "kazamuki"){
		print("kazamuki.value ".sprintf("%d",$kazamuki_degrees));
		echo "\n";
	}
 
	if ($argv[1] == "sunmoon"){
		print("sunrise.value ".sprintf("%.2f",$OhaKon["sunrise"]));
		echo "\n";
		print("sunset.value ".sprintf("%.2f",$OhaKon["sunset"]));
		echo "\n";
		print("moonrise.value ".sprintf("%.2f",$OhaKon["moonrise"]));
		echo "\n";
		print("moonset.value ".sprintf("%.2f",$OhaKon["moonset"]));
		echo "\n";
	} elseif ($argv[1] == "moonage"){
		print("moonage.value ".sprintf("%.2f",$OhaKon["moon_age"]));
		echo "\n";
	} elseif ($argv[1] == "moonphase"){
		print("moonphase.value ".sprintf("%.2f",$OhaKon["moon_phase"]));
		echo "\n";
	}

	if ($argv[1] == "OS") {
		print("Olympic.value ".sprintf("%d",$OS["Olympic"]));
		echo "\n";
		print("Win10.value ".sprintf("%d",$OS["Win10"]));
		echo "\n";
		print("Win81.value ".sprintf("%d",$OS["Win81"]));
		echo "\n";
		print("Win7.value ".sprintf("%d",$OS["Win7"]));
		echo "\n";
	}
}
//--------------------------------------------------------------------------------

if (!isset($argv[1]) || !isset($argv[2])) {
	echo "（例１）MUNIN-EX.php humidity-and-clouds ise_prefs.xml\n";
	echo "（例２）MUNIN-EX.php Tokyo power_prefs.xml\n";
	exit;
} else {
	$FILE = $argv[2];
}

$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");

$filename = '/etc/zabbix/externalscripts/'.$FILE;
$xml = @simplexml_load_file($filename);
if ($xml && ($argv[2] == "ise_prefs.xml" || $argv[2] == "suginami_prefs.xml" || $argv[2] == "okinawa_prefs.xml" || $argv[2] == "ishikari_prefs.xml" || $argv[2] == "kagoshima_prefs.xml" || $argv[2] == "nagano_prefs.xml" || $argv[2] == "kitamoto_prefs.xml")) {

	$description      = ($xml->pref[0]->description);
	$temp_now         = ($xml->pref[0]->temp_now);
	$humidity_now     = ($xml->pref[0]->humidity_now);
	$pressure_now     = ($xml->pref[0]->pressure_now);
	$windspeed_now    = ($xml->pref[0]->wind_speed);
	$kazamuki_degrees = ($xml->pref[0]->wind_dir_value);
	$kazamuki         = ($xml->pref[0]->wind_dir);
	$clouds_value     = ($xml->pref[0]->clouds_value);
	$discomfort       = ($xml->pref[0]->discomfort);
	/*
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
	 */
} elseif ($xml && ($argv[2] == "power_prefs.xml")) {
	for ($i = 0; $i < 10; $i++){
		$Power[$power_company[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
		$Power[$power_company[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
		$Power[$power_company[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
		$Power[$power_company[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
	}
} elseif ($xml && ($argv[2] == "ise_sunmoon_prefs.xml" || $argv[2] == "suginami_sunmoon_prefs.xml" || $argv[2] == "kitamoto_sunmoon_prefs.xml" || $argv[2] == "ishikari_sunmoon_prefs.xml")) {
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
} elseif ($xml && ($argv[2] == "WinOS_prefs.xml")) {
	$OS["Win10"] = ($xml->pref[0]->Win10);
	$OS["Win81"] = ($xml->pref[0]->Win81);
	$OS["Win7"]  = ($xml->pref[0]->Win7);
	$OS["Vista"] = ($xml->pref[0]->Vista);
	$OS["Olympic"] = ($xml->pref[1]->Olympic);
} else {
	echo "ERROR-1";
	exit;
}

PrefNow();
?>
