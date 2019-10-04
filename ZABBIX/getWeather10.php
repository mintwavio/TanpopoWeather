#!/usr/bin/php
<?php
function OUTPUT() {
	extract($GLOBALS);

	if       ($DATATYPE == "NORMAL"){
		echo $weather;
	} elseif ($DATATYPE == "WIND-D"){
		echo $kazamuki;
	} elseif ($DATATYPE == "WIND-DEG"){
		print(sprintf("%d",$kazamuki_degrees));
	} elseif ($DATATYPE == "TEMP"){
		print(sprintf("%.1f",$temp_now));
	} elseif ($DATATYPE == "TEMP-F"){
		print(sprintf("%d",$temp_now_f));
	} elseif ($DATATYPE == "FEELSLIKE"){
		print(sprintf("%.1f",$feelslike));
	} elseif ($DATATYPE == "HUMIDITY"){
		print(sprintf("%d", $humidity_now));
	} elseif ($DATATYPE == "CLOUDS-V"){
		print(sprintf("%d",$clouds_value));
	} elseif ($DATATYPE == "WIND-S"){
		print(sprintf("%.1f",$windspeed_now));
	} elseif ($DATATYPE == "PRESSURE"){
		print(sprintf("%.1f",$pressure_now));
	} elseif ($DATATYPE == "DISCOMFORT"){
		print(sprintf("%.1f",$discomfort));
	}
	for ($i = 0; $i < 10; $i++){
		if       ($DATATYPE == $power_company[$i]."-DEN-PER"){
			print(sprintf("%.1f",	$Power[$PowerCom[$i]]["den_per"]));
		} elseif ($DATATYPE == $power_company[$i]."-DEN-NOW"){
			print(sprintf("%d", 	$Power[$PowerCom[$i]]["den_now"]));
		} elseif ($DATATYPE == $power_company[$i]."-DEN-MAX"){
			print(sprintf("%d",	$Power[$PowerCom[$i]]["den_max"]));
		} elseif ($DATATYPE == $power_company[$i]."-DEN-YOSOU"){
			print(sprintf("%d",	$Power[$PowerCom[$i]]["den_yosou"]));
		}
	}

	if ($DATATYPE == "SUNRISE"){
		print(sprintf("%.1f",$OhaKon["sunrise"]));
	} elseif ($DATATYPE == "SUNSET"){
		print(sprintf("%.1f",$OhaKon["sunset"]));
	} elseif ($DATATYPE == "MOONRISE"){
		print(sprintf("%.1f",$OhaKon["moonrise"]));
	} elseif ($DATATYPE == "MOONSET"){
		print(sprintf("%.1f",$OhaKon["moonset"]));
	} elseif ($DATATYPE == "MOON-AGE"){
		print(sprintf("%.1f",$OhaKon["moon_age"]));
	} elseif ($DATATYPE == "MOON-PHASE"){
		print(sprintf("%.1f",$OhaKon["moon_phase"]));
	} elseif ($DATATYPE == "TIDE-NAME"){
		echo $OhaKon["tide_name"];
	}
}
//-----------------------------------------------------------------------------

if (isset($argv[1]) and isset($argv[2])) {
	$DATATYPE = $argv[1];
	$FILE = $argv[2];
} else {
	echo "（例１）getWeather10.php TEMP toshin_prefs.xml\n";
	echo "（例２）getWeather10.php TOKYO-DEN-PER power_prefs.xml\n";
	exit;
}

$power_company = array("HOKKAIDO","TOHOKU","TOKYO","CHUBU","HOKURIKU","KANSAI","CHUGOKU","SHIKOKU","KYUSHU","OKINAWA");
$PowerCom      = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
$filename = '/etc/zabbix/externalscripts/'.$FILE;
$xml = @simplexml_load_file($filename);
if ($xml && ($argv[2] == "sendai_prefs.xml" || $argv[2] == "toshin_prefs.xml")) {

	$weather          = ($xml->pref[0]->description);
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
		$Power[$PowerCom[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
		$Power[$PowerCom[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
		$Power[$PowerCom[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
		$Power[$PowerCom[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
	}

} elseif ($xml && ($argv[2] == "sendai_sunmoon_prefs.xml" || $argv[2] == "toshin_sunmoon_prefs.xml")) {
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
OUTPUT();

?>
