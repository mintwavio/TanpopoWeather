#!/usr/bin/php
<?php

require_once ("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");

function file_write(){

	extract ($GLOBALS);
	
	$dom = new DomDocument('1.0','UTF-8');
	$prefs = $dom->appendChild($dom->createElement('prefs'));

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','WinOS');
	$pref->appendChild($dom->createElement('Win10',sprintf("%d",$Win10)));
	$pref->appendChild($dom->createElement('Win81',sprintf("%d",$Win81)));
	$pref->appendChild($dom->createElement('Win7',sprintf("%d",$Win7)));
	$pref->appendChild($dom->createElement('Vista',sprintf("%d",$Vista)));
	$pref->appendChild($dom->createElement('WinServ2008',sprintf("%d",$WinServ2008)));

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','Olympic');
	$pref->appendChild($dom->createElement('Olympic',sprintf("%d",$Olympic)));

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
//---------------------------------------------------------------------------------------
$OUT_FILE = "WinOS_prefs.xml";

echo "OUT_FILE=".$OUT_FILE."\nで出力します。\n";
$TT = new Twitter_Class;
$Win10 = $TT->rekijitsu_2(2025,10,15,00,00,00);
$Win81 = $TT->rekijitsu_2(2023, 1,11,00,00,00);
$Win7  = $TT->rekijitsu_2(2020, 1,15,00,00,00);
$Vista = $TT->rekijitsu_2(2017, 4,12,00,00,00);
$WinServ2008 = $TT->rekijitsu_2(2020, 1,15,00,00,00);
$Olympic = $TT->rekijitsu_2(2020, 7,24,20,01,00);
file_write();
?>
