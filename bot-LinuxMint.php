#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/

require_once("TanpopoWeather.php");
//------------------------------------------------------------------------
$LAT = "35.6371215";
$LON = "139.727614";
$image_am  		= "image/407.jpg";
$image_pm  		= "https://thanks.linuxparadise.net/uploads/photos/365.jpg";
//$image_pm  		= "https://linuxparadise.net/wp-content/uploads/2019/08/406.jpg";
$image_etc 		= "image/rosa.png";
$image["TokyoDenryoku"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=553&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg8qwj";
$image["TokyoTemp"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=558&screenid=22&width=349&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg9i1j";
$image["memory"] 	= "https://linuxparadise.net/zabbix/chart2.php?graphid=534&screenid=22&width=679&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg9ive";
//--------------------------------------------------------------------------
function file_write($wm,$hozon){
	global $message_time;
	if (date("H") == 15) {
		$message_time = "PM 3:10";
	} elseif (date("H") == 3){
		$message_time = "AM 3:10";
	} else {
		$message_time = "じゃない";
	}

	$message  = "ミントの時間".$message_time."です！　\n";
	$message .= "東京都心▶\n";
	$message .= "天気：".$wm["description"]."　\n";
	$message .= "気温：".$wm["temp_now"]."℃　\n";
	$message .= "湿度：".$wm["humidity_now"]."％　\n";
        $message .= "風向：".$wm["wind_dir"]."　\n";
        $message .= "風速：".$wm["wind_speed"]."㎧　\n";
	$message .= "気圧：".$wm["pressure"]."㍱　\n";
	$message .= "不快指数：".$wm["fukai"]."　\n";

	$message .= "サーバー連続稼働時間▶\n";
	$message .= $hozon."　\n";
	$message .= "Linux Mint🍃は、今日も元気！\n";
	return($message);
}
//-------------------------------------------------------------------------
$TT = new Twitter_Class;
$WW = new Weather_Class;

$hozon = $TT->get_uptime();
//$wg = $WW->wunderground();
$wm = $WW->openweathermap();

//if ($wg["rfc_alive"] == "bad"){
//	exit;
//}
//$d_index = $WW->discomfort_index($wg["fukai"]);

if ($hozon == NULL || $wm == NULL) {
	echo "ERROR 1";
	exit;
} else {
	$message = file_write($wm,$hozon);
//-------------------------------------------------------------------------

	$image["weather_icon"] = $wm["weather_icon"];
	if ($wm["weather_icon"] == NULL) {
		unset($image["weather_icon"]);
	}

	if ($message_time == "PM 3:10") {
		$image["Mint"] = $image_pm;
	} elseif ($message_time == "AM 3:10") {
		$image["Mint"] = $image_am;
	} else {
		$image["Mint"] = $image_etc;
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
//	echo $media;
	while (count($result_media_id) != $media && $c < 10) {
		$result_media_id = $TT->upload_image($twitter,$image);
		$c++;
	}
//-------------------------------------------------------------------------
	if ($wm["weather_icon"] == NULL){
		$update_params = array(
			'media_ids'    =>  $result_media_id["Mint"].",".$result_media_id["memory"].",".$result_media_id["TokyoTemp"].",".$result_media_id["TokyoDenryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}else {
		$update_params = array(
			'media_ids'    =>  $result_media_id["Mint"].",".$result_media_id["weather_icon"].",".$result_media_id["TokyoTemp"].",".$result_media_id["TokyoDenryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}	

		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
	}
?>
