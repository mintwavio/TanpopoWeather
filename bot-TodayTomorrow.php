#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/
require_once("TanpopoWeather.php");
//------------------------------------------------------------------------
$DAYNAME	= $argv[1];
$PLACE_NAME 	= $argv[2];
$LAT 		= $argv[3];
$LON 		= $argv[4];
$SHARP		= $argv[5];
//------------------------------------------------------------------------
	$image["Denryoku_graph"]= "http://linuxparadise.net/Denryoku.php";

	$graph["Hokkaido"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_hokkaido-week.png";
	$graph["Tohoku"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_tohoku-week.png";
	$graph["Tokyo"]		= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_tokyo-week.png";
	$graph["Chubu"]		= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_chubu-week.png";
	$graph["Hokuriku"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_hokuriku-week.png";
	$graph["Kansai"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_kansai-week.png";
	$graph["Chugoku"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_chugoku-week.png";
	$graph["Shikoku"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_shikoku-week.png";
	$graph["Kyushu"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_kyushu-week.png";
	$graph["Okinawa"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/power_okinawa-week.png";
	$graph["Kaigai"] 	= "https://linuxparadise.net/bot-Win7-Image.php";

	$spring["lime"]		= "https://thanks.linuxparadise.net/uploads/photos/427.jpg";
	$spring["girl"]		= "https://thanks.linuxparadise.net/uploads/photos/428.jpg";
	$photo["tiger"]		= "./image/tiger.jpg";
//------------------------------------------------------------------------------
function file_write($wm){

	global $PLACE_NAME,$SHARP;

	$message  = $wm["day_moji"].$wm["day_jp"]."\n";
	$message .= $PLACE_NAME."天気予報▶\n";
	$message .= $wm["description"]."　\n";
	$message .= "最低気温：".$wm["temp_min"]."℃　\n";
	$message .= "最高気温：".$wm["temp_max"]."℃　\n";
	$message .= "湿度：".$wm["humidity"]."％　\n";
	$message .= "風向：".$wm["kazamuki"]."　\n";
	$message .= "風速：".$wm["windSpeed"]."㎧　\n";
	$message .= "気圧：".$wm["pressure"]."㍱　\n";
	$message .= "雲量：".$wm["clouds_value"]."％　\n";
//	$message .= "降水量：".$wm["precipitation"]."　\n";

	$message .= "#".$SHARP;

	return($message);		
}

$TT = new Twitter_Class;
$WW = new Weather_Class;
$wm = $WW->openweathermap_forecast();

$image["rain"] = $WW->rain_yahoo($LAT,$LON,10,600,600);

if ($wm == NULL) {
	echo "ERROR";
	exit;
} else {
	echo "GOOD DATA";
	$message = file_write($wm);
}

if (strstr($PLACE_NAME,"宮城県") || strstr($PLACE_NAME,"新潟県") || strstr($PLACE_NAME,"青森県")){
	if ($DAYNAME == "TODAY"){
		$image["Denryoku"] = $graph["Tohoku"];
	} else {
//		$image["Denryoku"] = $cacti["Tohoku"];
		$image["Denryoku"] = $graph["Tohoku"];
	}

} elseif (strstr($PLACE_NAME,"京都府") || strstr($PLACE_NAME,"奈良県") || strstr($PLACE_NAME,"兵庫県") || strstr($PLACE_NAME,"大阪府")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Kansai"];
	} else {
//		$image["Denryoku"] = $cacti["Kansai"];
		$image["Denryoku"] = $graph["Kansai"];
	}
} elseif (strstr($PLACE_NAME,"北海道")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Hokkaido"];
	} else {
//		$image["Denryoku"] = $cacti["Hokkaido"];
		$image["Denryoku"] = $graph["Hokkaido"];
	}
} elseif (strstr($PLACE_NAME,"愛知県") || strstr($PLACE_NAME,"三重県") || strstr($PLACE_NAME,"長野県") || strstr($PLACE_NAME,"浜松市")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Chubu"];
	} else {
//		$image["Denryoku"] = $cacti["Chubu"];
		$image["Denryoku"] = $graph["Chubu"];
	}
} elseif (strstr($PLACE_NAME,"愛媛県") || strstr($PLACE_NAME,"香川県")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Shikoku"];
	} else {
//		$image["Denryoku"] = $cacti["Shikoku"];
		$image["Denryoku"] = $graph["Shikoku"];
	}
} elseif (strstr($PLACE_NAME,"長崎県") || strstr($PLACE_NAME,"鹿児島県") || strstr($PLACE_NAME,"福岡県")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Kyushu"];
	} else {
//		$image["Denryoku"] = $cacti["Kyushu"];
		$image["Denryoku"] = $graph["Kyushu"];
	}
} elseif (strstr($PLACE_NAME,"沖縄県")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Okinawa"];
	} else {
//		$image["Denryoku"] = $cacti["Okinawa"];
		$image["Denryoku"] = $graph["Okinawa"];
	}
} elseif (strstr($PLACE_NAME,"岡山県") || strstr($PLACE_NAME,"広島県")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Chugoku"];
	} else {
//		$image["Denryoku"] = $cacti["Chugoku"];
		$image["Denryoku"] = $graph["Chugoku"];
	}
} elseif (strstr($PLACE_NAME,"石川県")){
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Hokuriku"];
	} else {
//		$image["Denryoku"] = $cacti["Hokuriku"];
		$image["Denryoku"] = $graph["Hokuriku"];
	}
} elseif (strstr($PLACE_NAME,"ヘルシンキ") || strstr($PLACE_NAME,"ハワイ") || strstr($PLACE_NAME,"台湾")) {
	$image["Denryoku"] = $graph["Kaigai"];
} else {
	if ($DAYNAME == "TODAY") {
		$image["Denryoku"] = $graph["Tokyo"];
	} else {
//		$image["Denryoku"] = $cacti["Tokyo"];
		$image["Denryoku"] = $graph["Tokyo"];
	}
}

if ($DAYNAME == "TODAY") {
	$image["screenshot"]  = $spring["girl"];
} elseif ($DAYNAME == "TOMORROW")  {
	$image["screenshot"]  = $photo["tiger"];
} else {
	$image["screenshot"]  = $photo["tiger"];
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
//-------------------------------------------------------------------------
	if (strstr($PLACE_NAME,"北海道") || strstr($PLACE_NAME,"青森県")) {
		$image["rain"] = $WW->rain_yahoo($LAT,$LON,10,600,600);
	//	$image["rain"] = $spring["lemon_tea"];
	} else {
		$image["rain"] = $WW->rain_yahoo($LAT,$LON,10,600,600);
	}
	$image["icon"] = $wm["weather_icon"];
	if ($wm["weather_icon"] == NULL) {
		unset($image["icon"]);
	}
	$c = 0;
	$media = count($image);
	$result_media_id = $TT->upload_image($twitter,$image);
//	echo count($result_media_id);
	while (count($result_media_id) != $media && $c < 10) {
		$result_media_id = $TT->upload_image($twitter,$image);
		$c++;
	}
//-----------------------------------------------------------
	if ($wm["weather_icon"] == NULL){
		$update_params = array(
			'media_ids'    => $result_media_id["screenshot"].",".$result_media_id["Denryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	} else {
		$update_params = array(
			'media_ids'    => $result_media_id["screenshot"].",".$result_media_id["icon"].",".$result_media_id["Denryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}

	$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
	echo "[".$code."]\n".$status."\n";
?>
