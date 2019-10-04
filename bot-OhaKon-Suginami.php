#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/
require_once("TanpopoWeather.php");
//------------------------------------------------------------------------
if ($argc != 4) {
	echo "bot-OhaKon3.php 引数１ 引数２ CHECK(NORMAL)　のように、引数を渡します。\n";
	echo "自動ツイートの中で、#引数１ #引数２ というように、表示されます。\n";
	echo "現在のこのバージョンでは、date('H')が日の出の時間か、日の入りの時間か、12の時にツイートします。\n";
	echo "必ずツイートしたければ、引数３に'NORMAL'を設定しましょう。\n";
	echo "時間を調べてからツイートしたければ、引数３は、'CHECK'にします。\n";
	exit;
}
 
//$image["Wallpaper"]     = "/home/kouji/public_html/html/AutoTweet/image/planets_9-wallpaper-1920x1080.jpg";
$image["Wallpaper"]     = "http://linuxparadise.net/bot-Coffee-Image.php";
$image["sun_moon"] 	= "https://linuxparadise.net/munin/localdomain/localhost.localdomain/suginami_sunmoon-week.png";
$image["moon_age"] 	= "https://linuxparadise.net/munin/localdomain/localhost.localdomain/suginami_moonage-week.png";
$image["moon_phase"] 	= "https://linuxparadise.net/munin/localdomain/localhost.localdomain/suginami_moonphase-week.png";
//$image["temperature"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/ise_temp-week.png";

function make_message(){
	global $SunMoon,$argv,$Moon;
	$message  = "現在の東京都杉並区　\n";
	$message .= "日の出　".$SunMoon["sunrise_hm"]	."　\n";
	$message .= "日の入　".$SunMoon["sunset_hm"]	."　\n";
	$message .= "月の出　".$SunMoon["moonrise_hm"]	."　\n";
	$message .= "月の入　".$SunMoon["moonset_hm"]	."　\n";

	if ($SunMoon["moon_age"] != "--") {
		$message .= "正午月齢　".sprintf("%.1f",$SunMoon["moon_age"])	."日　\n";
	}
	$message .= "月相　".sprintf("%.1f",$SunMoon["moon_phase"])	."°　\n";
	
	if ($SunMoon["moon_age"] != "--") {
		$message .= "潮汐　".$SunMoon["tide_name"]."　\n";
	}

	if ($Moon["NewMoon_Wait"] == 0) {
		$message .= "今日は新月🌑です。　\n";
	} else {
		$message .= "新月まで".sprintf("%02d",$Moon["NewMoon_Wait"])."日　\n";
	}

	if ($Moon["WaxingMoon_Wait"] == 0) {
		$message .= "今日は上弦🌓です。　\n";
	} else {
		$message .= "上弦まで".sprintf("%02d",$Moon["WaxingMoon_Wait"])."日　\n";
	}

	if ($Moon["FullMoon_Wait"] == 0){
		$message .= "今日は満月🌕です。　\n";
	} else {
		$message .= "満月まで".sprintf("%02d",$Moon["FullMoon_Wait"])."日　\n";
	}

	if ($Moon["WaningMoon_Wait"] == 0){
		$message .= "今日は下弦🌗です。　\n";
	} else {
		$message .= "下弦まで".sprintf("%02d",$Moon["WaningMoon_Wait"])."日　\n";
	}

	if ($argv[2] == "努力") {
		$message .= "#".$argv[1]." #".$argv[2];
	} else {
		$message .= "#".$argv[1]." #".$argv[2];
	}

	return ($message);
}

function timecheck($sun) {
	global $argv;
	$timea["P"] = mb_strpos($sun,"時");
	$timea["H"] = mb_strcut($sun,0,$timea["P"]);
	$timea["M"] = mb_strcut($sun,$timea["P"] + 3,2);
	$timea["M1"] = substr($timea["M"],1,1);
	$timea["M10"] = $timea["M"] - $timea["M1"];

	if ($argv[3] == "NORMAL") {
		echo "ツイートします。\n";
		return(1);
	} elseif ($argv[3] == "CHECK") {
		if ($timea["M10"] == date('i') && $timea["H"] == date('H')){
			echo "ツイートします。\n";
			return(1);
		} else {
			echo "ツイートしません（２）。\n";
			return(0);
		}
	} else {
		echo "ツイートしません（１）。\n";
		return(0);
	}
}
//--------------------------------------------------------------------------
$MM = new Moon_Class;
$TT = new Twitter_Class;
$SunMoon = $MM->StoreSunMoon("suginami_sunmoon_prefs.xml");
$Moon    = $MM->StoreFullNewMoon();
if (date("H") <= 12) { 
	$Answer = timecheck($SunMoon["sunrise_hm"]);
} else {
	$Answer = timecheck($SunMoon["sunset_hm"]);
}
if (!$Answer){
	exit;
}
$status = make_message();
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
		$update_params = array(
			'media_ids'    =>  $result_media_id["sun_moon"].",".$result_media_id["moon_age"].",".$result_media_id["moon_phase"].",".$result_media_id["Wallpaper"], //先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);

		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
?>
