#!/usr/bin/php
<?php
require_once ("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");
function file_write(){

	extract ($GLOBALS);
	
	$dom = new DomDocument('1.0','UTF-8');
	$prefs = $dom->appendChild($dom->createElement('prefs'));

	for ($i = 0; $i < 10; $i++){
		$pref = $prefs->appendChild($dom->createElement('pref'));
		$pref->setAttribute('code',$power_company[$i].'_Denryoku');
		$pref->appendChild($dom->createElement('den_per',$Power[$power_company[$i]]["den_per"]));
		$pref->appendChild($dom->createElement('den_old',$Power[$power_company[$i]]["OLD_DATA"]));
		$pref->appendChild($dom->createElement('den_now',$Power[$power_company[$i]]["usage"]));
		$pref->appendChild($dom->createElement('den_updown',$Power[$power_company[$i]]["up_down"]));
		$pref->appendChild($dom->createElement('den_max',$Power[$power_company[$i]]["capacity"]));
		$pref->appendChild($dom->createElement('den_yosou',$Power[$power_company[$i]]["yosou"]));
		$pref->appendChild($dom->createElement('den_date',$Power[$power_company[$i]]["DATE"]));
		$pref->appendChild($dom->createElement('den_time',$Power[$power_company[$i]]["time"]));
		$pref->appendChild($dom->createElement('den_alive',$Power[$power_company[$i]]["alive"]));
	
	}
/*
	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','Holidays');

	foreach ($holidays as $key => $value){
		$pref->appendChild($dom->createElement("_".$key,$value));
	}
*/
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
	$pref->appendChild($dom->createElement('time',date("Y年m月d日").$week_X.date(" H時i分")));
	$pref->appendChild($dom->createElement('time_ampm',date("Y年m月d日").$week_X.date(" A h:i")));
	$pref->appendChild($dom->createElement('time_x',date("Ymd")));
	$pref->appendChild($dom->createElement('time_Ymd',date("Y-m-d")));
	$pref->appendChild($dom->createElement('time_His',date("H:i:s")));

	$dom->formatOutput = true;
	$save_file = "/etc/zabbix/externalscripts/".$OUT_FILE;
	$dom->save($save_file);
	copy($save_file,"/home/kouji/public_html/html/linuxmint/HTML/power_prefs.xml");
}
//-------------------------------------------------------------------------------------------------
if (!isset($argv[1])) {
	echo "\"/etc/zabbix/externalscripts/power_prefs.xml\"　を出力します。\n";
	$OUT_FILE = "power_prefs.xml";
} else {
	$OUT_FILE = $argv[1];
	echo "\"/etc/zabbix/externalscripts/".$OUT_FILE."\"　を出力します。\n";
}

$DD = new Denryoku_Class;
$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
for ($i = 0; $i < 10; $i++){
	$Power[$power_company[$i]] = $DD->Denryoku($power_company[$i]);
	$Ans = $Power[$power_company[$i]];
	if ($Power[$power_company[$i]]["den_per"] == 0 || $Ans == NULL ){
		if ($Power[$power_company[$i]]["DATE"] != "休止中") {
			exit;
		}
	}
}

file_write();
?>
