#!/usr/bin/php
<?php

require_once ("/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php");

function day_diff($date1, $date2) {
 
    // 日付をUNIXタイムスタンプに変換
    $timestamp1 = strtotime($date1);
    $timestamp2 = strtotime($date2);
 
    // 何秒離れているかを計算
    $seconddiff = ($timestamp2 - $timestamp1);
 
    // 日数に変換
    $daydiff = $seconddiff / (60 * 60 * 24);
 
    // 戻り値
    return $daydiff;
 
}
 
// 日付を関数に渡す
//$day = day_diff('2012-12-24','2012-12-10');
 
function file_write(){

	extract($GLOBALS);

	$dom = new DomDocument('1.0','UTF-8');
	$prefs = $dom->appendChild($dom->createElement('prefs'));

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','NewMoon');
	$pref->appendChild($dom->createElement('Date',$NM[0]."-".$NM[1]."-".$NM[2]));
	$pref->appendChild($dom->createElement('Time',$NM[3].":".$NM[4].":".$NM[5]));
	$day = day_diff(date('Y-m-d'),$NM[0]."-".$NM[1]."-".$NM[2]);
	if ($day < 0) {
		$day = "--";
	}
	$pref->appendChild($dom->createElement('Wait',$day));

	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','WaxingMoon');
	$pref->appendChild($dom->createElement('Date',$WAX[0]."-".$WAX[1]."-".$WAX[2]));
	$pref->appendChild($dom->createElement('Time',$WAX[3].":".$WAX[4].":".$WAX[5]));
	$day = day_diff(date('Y-m-d'),$WAX[0]."-".$WAX[1]."-".$WAX[2]);
	if ($day < 0) {
		$day = "--";
	}
	$pref->appendChild($dom->createElement('Wait',$day));
	
	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','FullMoon');
	$pref->appendChild($dom->createElement('Date',$FM[0]."-".$FM[1]."-".$FM[2]));
	$pref->appendChild($dom->createElement('Time',$FM[3].":".$FM[4].":".$FM[5]));
	$day = day_diff(date('Y-m-d'),$FM[0]."-".$FM[1]."-".$FM[2]);
	if ($day < 0) {
		$day = "--";
	}
	$pref->appendChild($dom->createElement('Wait',$day));
		
	$pref = $prefs->appendChild($dom->createElement('pref'));
	$pref->setAttribute('code','WaningMoon');
	$pref->appendChild($dom->createElement('Date',$WAN[0]."-".$WAN[1]."-".$WAN[2]));
	$pref->appendChild($dom->createElement('Time',$WAN[3].":".$WAN[4].":".$WAN[5]));
	$day = day_diff(date('Y-m-d'),$WAN[0]."-".$WAN[1]."-".$WAN[2]);
	if ($day < 0) {
		$day = "--";
	}
	$pref->appendChild($dom->createElement('Wait',$day));

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
	$save_file = "/etc/zabbix/externalscripts/F_N_Moon.xml";
	$dom->save($save_file);
}
//-----------------------------------------------------------------------
$MM = new Moon_Class;
list($NM,$WAX,$FM,$WAN) = $MM->moon_phase_time();

echo "新月＝".$NM[0]."-".$NM[1]."-".$NM[2]." ".$NM[3].":".$NM[4].":".$NM[5].",".$NM[6]."\n";
echo "上弦＝".$WAX[0]."-".$WAX[1]."-".$WAX[2]." ".$WAX[3].":".$WAX[4].":".$WAX[5].",".$WAX[6]."\n";
echo "満月＝".$FM[0]."-".$FM[1]."-".$FM[2]." ".$FM[3].":".$FM[4].":".$FM[5].",".$FM[6]."\n";
echo "下弦＝".$WAN[0]."-".$WAN[1]."-".$WAN[2]." ".$WAN[3].":".$WAN[4].":".$WAN[5].",".$WAN[6]."\n";
file_write();
?>
