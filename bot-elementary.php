#!/usr/bin/php
<?php
/***
	author: Kouji Sugibayashi
***/

require_once("TanpopoWeather.php");

$image_pm  		= "image/419.png";
$image_am  		= "image/404.jpg";
$image_etc 		= "http://linuxparadise.net/wp-content/uploads/2015/12/7d5cc83583b3cd15d65f2344ff78eeb5.png";
$image["TokyoDenryoku"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=553&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg8qwj";
$image["TokyoTemp"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=558&screenid=22&width=349&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg9i1j";
$image["memory"] 	= "https://linuxparadise.net/zabbix/chart2.php?graphid=534&screenid=22&width=679&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg9ive";

$LAT = "35.6371215";
$LON = "139.727614";
//--------------------------------------------------------------------------
function file_write($wm,$Power){
	global $message_time;
	if (date("H") == 20) {
		$message_time = "PM 8:00";
		$aisatsu = "";
	} elseif (date("H") == 8){
		$message_time = "AM 8:00";
		$aisatsu = " #ohayo";
	} else {
		$message_time = "じゃない";
		$aisatsu = "";
	}

	$message  = "elementary OS♥の時間\n".$message_time."です！　\n";
	$message .= "東京都心▶\n";
	$message .= "天気：".$wm["description"]."　\n";
        $message .= "気温：".$wm["temp_now"]."℃　\n";
        $message .= "湿度：".$wm["humidity_now"]."％　\n";
        $message .= "風向：".$wm["wind_dir"]."　\n";
        $message .= "風速：".$wm["wind_speed"]."㎧　\n";
	$message .= "気圧：".$wm["pressure"]."㍱　\n";

	$setsuden = "節電をしましょう。　\n";
	$kansya   = "ご協力感謝します。　\n";
        if ($Power["Tokyo"]["den_alive"] == "good"){
		$message .= "東京電力電力使用率：".floor($Power["Tokyo"]["den_per"])."％　\n";
		if ($Power["Tokyo"]["den_per"] >= 90) {
			$message .= $setsuden;
		} else {
			$message .= $kansya;
		}
	}

	$message .= "#Linux🐧";
	$message .= $aisatsu;
	return($message);
}
//-------------------------------------------------------------------------
$DD = new Denryoku_Class;
$WW = new Weather_Class;
$TT = new Twitter_Class;

$Power = $DD->StoreDenryoku();
//$wg = $WW->wunderground();
$wm = $WW->openweathermap();

if ($Power == NULL || $wm == NULL) {
	echo "ERROR 1";
	exit;
} else {
	$message = file_write($wm,$Power);
//-------------------------------------------------------------------------

	$image["weather_icon"] = $wm["weather_icon"];
	if ($wm["weather_icon"] == NULL) {
		unset($image["weather_icon"]);
	}

	if ($message_time == "PM 8:00") {
		$image["Elementary"] = $image_pm;
	} elseif ($message_time == "AM 8:00") {
		$image["Elementary"] = $image_am;
	} else {
		$image["Elementary"] = $image_etc;
	}

	$status = $message;
//-------------------------------------------------------------------------
    	$twitter = new tmhOauth(
        array(
            "consumer_key"		=> $consumer_key,
            "consumer_secret"		=> $consumer_secret,
            "token"			=> $token,
            "secret"			=> $secret,
            "curl_ssl_verifypeer"	=> false,
        )
        );
	$c = 0;
	$media = count($image);
	$result_media_id = $TT->upload_image($twitter,$image);
//	echo count($result_media_id);
	while (count($result_media_id) != $media && $c < 10) {
		$result_media_id = $TT->upload_image($twitter,$image);
		$c++;
	}

//-------------------------------------------------------------------------
	if ($wm["weather_icon"] == NULL) {
		$update_params = array(
			'media_ids'    =>  $result_media_id["Elementary"].",".$result_media_id["memory"].",".$result_media_id["TokyoTemp"].",".$result_media_id["TokyoDenryoku"], //先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	} else {
		$update_params = array(
			'media_ids'    =>  $result_media_id["Elementary"].",".$result_media_id["weather_icon"].",".$result_media_id["TokyoTemp"].",".$result_media_id["TokyoDenryoku"], //先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}
	
		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
}
?>
