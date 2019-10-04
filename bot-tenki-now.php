#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/

	require_once 'TanpopoWeather.php';
//------------------------------------------------------------------------
	$PLACE_NAME 	= $argv[1];
	$LAT 		= $argv[2];
	$LON 		= $argv[3];
	$SHARP 		= $argv[4];

	$graph["Hokkaido"] 	= "https://linuxparadise.net/zabbix/chart2.php?graphid=575&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=taffwjqm";
	$graph["Tohoku"] 	= "https://linuxparadise.net/zabbix/chart2.php?graphid=565&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg0vmm";
	$graph["Tokyo"]		= "https://linuxparadise.net/zabbix/chart2.php?graphid=553&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg1ogh";
	$graph["Chubu"]		= "https://linuxparadise.net/zabbix/chart2.php?graphid=572&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg1ogh";
	$graph["Hokuriku"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=577&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg2gf8";
	$graph["Kansai"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=573&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg398i";
	$graph["Chugoku"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=578&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg422e";
	$graph["Shikoku"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=576&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg4su1";
	$graph["Kyushu"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=574&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg4su1";
	$graph["Okinawa"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=579&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg5lns";
	$graph["Kaigai"]	= "https://linuxparadise.net/bot-Win7-Image-X.php";

	$temp["Ise"]		= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/ise_temp-week.png";
	$temp["Kagoshima"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/kagoshima_temp-week.png";
	$temp["Chiyoda"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=558&screenid=22&width=349&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafgmtbn";
	$temp["Naha"]		= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/naha_temp-week.png";
	$temp["Sendai"]		= "https://linuxparadise.net/zabbix/chart2.php?graphid=567&screenid=22&width=349&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafgmtbn";
	$temp["Ishikari"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/ishikari_temp-week.png";
	$temp["Nagano"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/nagano_temp-week.png";
	$temp["Kitamoto"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/kitamoto_temp-week.png";
	$temp["Suginami"]	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/suginami_temp-week.png";
	$temp["ETC"]		= "http://linuxparadise.net/bot-Coffee-Image.php";

//	$image["lemon_tea"] 	= "https://thanks.linuxparadise.net/uploads/photos/398.jpg";	
	$image["lime"]		= "image/lime.jpg";
	$image["memory"]	= "https://linuxparadise.net/zabbix/chart2.php?graphid=534&screenid=22&width=679&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafgnn0a";
//------------------------------------------------------------------------
function file_write(){
	extract($GLOBALS);
	$message  = "現在の".$PLACE_NAME."　\n";
	$message .= "天気：".$wm["description"]."　\n";
	$message .= "気温：".$wm["temp_now"]."℃　\n";
	$message .= "湿度：".$wm["humidity_now"]."％　\n";
	$message .= "風向：".$wm["wind_dir"]."　\n";
	$message .= "風速：".$wm["wind_speed"]."㎧　\n";
	$message .= "気圧：".$wm["pressure"]."㍱　\n";
	$message .= "雲量：".$wm["clouds_value"]."％　\n";
	$message .= "不快指数：".$wm["fukai"]."　\n";

	$setsuden = "節電をしましょう。　\n";
	$kansya   = "ご協力感謝します。　\n";

	if (strstr($PLACE_NAME,"宮城県") || strstr($PLACE_NAME,"新潟県") || strstr($PLACE_NAME,"青森県")) {
		if ($Power["Tohoku"]["den_alive"] == "good"){
			$message .= "東北電力電力使用率：".floor($Power["Tohoku"]["den_per"])."％　\n";
			if ($Power["Tohoku"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	   	}
	} elseif (strstr($PLACE_NAME,"北海道")) {
           	if ($Power["Hokkaido"]["den_alive"] == "good"){
			$message .= "北海道電力電力使用率：".floor($Power["Hokkaido"]["den_per"])."％　\n";
			if ($Power["Hokkaido"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	 	 }
	} elseif (strstr($PLACE_NAME,"愛知県") || strstr($PLACE_NAME,"三重県") || strstr($PLACE_NAME,"長野県") || strstr($PLACE_NAME,"浜松市")) {
		if ($Power["Chubu"]["den_alive"] == "good"){
			$message .= "中部電力電力使用率：".floor($Power["Chubu"]["den_per"])."％　\n";
			if ($Power["Chubu"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
		}
	} elseif (strstr($PLACE_NAME,"奈良県") || strstr($PLACE_NAME,"京都府") || strstr($PLACE_NAME,"兵庫県") || strstr($PLACE_NAME,"大阪府")) {
          	 if ($Power["Kansai"]["den_alive"] == "good"){
			$message .= "関西電力電力使用率：".floor($Power["Kansai"]["den_per"])."％　\n";
			if ($Power["Kansai"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	  	}
	} elseif (strstr($PLACE_NAME,"愛媛県") || strstr($PLACE_NAME,"香川県")) {
          	 if ($Power["Shikoku"]["den_alive"] == "good"){
			$message .= "四国電力電力使用率：".floor($Power["Shikoku"]["den_per"])."％　\n";
			if ($Power["Shikoku"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	 	 }
	} elseif (strstr($PLACE_NAME,"長崎県") || strstr($PLACE_NAME,"鹿児島県") || strstr($PLACE_NAME,"福岡県")) {
          	 if ($Power["Kyushu"]["den_alive"] == "good"){
			$message .= "九州電力電力使用率：".floor($Power["Kyushu"]["den_per"])."％　\n";
			if ($Power["Kyushu"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	 	 }
	} elseif (strstr($PLACE_NAME,"沖縄県")) {
         	  if ($Power["Okinawa"]["den_alive"] == "good"){
			$message .= "沖縄電力電力使用率：".floor($Power["Okinawa"]["den_per"])."％　\n";
			if ($Power["Okinawa"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
		  }
	} elseif (strstr($PLACE_NAME,"石川県")) {
          	 if ($Power["Hokuriku"]["den_alive"] == "good"){
			$message .= "北陸電力電力使用率：".floor($Power["Hokuriku"]["den_per"])."％　\n";
			if ($Power["Hokuriku"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	 	 }
	} elseif (strstr($PLACE_NAME,"岡山県") || strstr($PLACE_NAME,"広島県")) {
          	 if ($Power["Chugoku"]["den_alive"] == "good"){
			$message .= "中国電力電力使用率：".floor($Power["Chugoku"]["den_per"])."％　\n";
			if ($Power["Chugoku"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
	 	 }
	} elseif (strstr($PLACE_NAME,"ヘルシンキ") || strstr($PLACE_NAME,"ハワイ")) {
		$message .= "x 雨雲地図 o 地図のみ \n";
	} elseif (strstr($PLACE_NAME,"台湾")) {
		$message .= "";
	} else {
          	 if ($Power["Tokyo"]["den_alive"] == "good"){
			$message .= "東京電力電力使用率：".floor($Power["Tokyo"]["den_per"])."％　\n";
			if ($Power["Tokyo"]["den_per"] >= 90) {
				$message .= $setsuden;
			} else {
				$message .= $kansya;
			}
		  }
	}

	$message .= "#".$SHARP;

	if ($Moon["FullMoon_Wait"] == 0) {
		$message .= " #満月🌕";
	} elseif ($Moon["NewMoon_Wait"] == 0) {
		$message .= " #新月🌑";
	} elseif ($Moon["WaxingMoon_Wait"] == 0) {
		$message .= " #上弦🌓";
	} elseif ($Moon["WaningMoon_Wait"] == 0) {
		$message .= " #下弦🌗";
	}
	return($message);
}

//-----------------------------------------------------------------------------------
$TT = new Twitter_Class;
$DD = new Denryoku_Class;
$WW = new Weather_Class;
$MM = new Moon_Class;
$wm = $WW->openweathermap();
$Moon = $MM->StoreFullNewMoon();

$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");

$PP = $DD->StoreDenryoku();
for ($i = 0; $i < 10; $i++){
	$Power[$power_company[$i]] = $PP[$power_company[$i]];
}
if ($wm == NULL){
	echo "ERROR";
	exit;
}

$message = file_write();
//-------------------------------------------------------------------
$status = $message;

if (strstr($PLACE_NAME,"宮城県") || strstr($PLACE_NAME,"新潟県") || strstr($PLACE_NAME,"青森県")){
	$image["Denryoku"] = $graph["Tohoku"];
} elseif (strstr($PLACE_NAME,"京都府") || strstr($PLACE_NAME,"奈良県") || strstr($PLACE_NAME,"兵庫県") || strstr($PLACE_NAME,"大阪府")){
	$image["Denryoku"] = $graph["Kansai"];
} elseif (strstr($PLACE_NAME,"北海道")){
	$image["Denryoku"] = $graph["Hokkaido"];
} elseif (strstr($PLACE_NAME,"愛知県") || strstr($PLACE_NAME,"三重県") || strstr($PLACE_NAME,"長野県") || strstr($PLACE_NAME,"浜松市")){
	$image["Denryoku"] = $graph["Chubu"];
} elseif (strstr($PLACE_NAME,"愛媛県") || strstr($PLACE_NAME,"香川県")){
	$image["Denryoku"] = $graph["Shikoku"];
} elseif (strstr($PLACE_NAME,"長崎県") || strstr($PLACE_NAME,"鹿児島県") || strstr($PLACE_NAME,"福岡県")){
	$image["Denryoku"] = $graph["Kyushu"];
} elseif (strstr($PLACE_NAME,"沖縄県")){
	$image["Denryoku"] = $graph["Okinawa"];
} elseif (strstr($PLACE_NAME,"岡山県") || strstr($PLACE_NAME,"広島県")){
	$image["Denryoku"] = $graph["Chugoku"];
} elseif (strstr($PLACE_NAME,"石川県")){
	$image["Denryoku"] = $graph["Hokuriku"];
} elseif (strstr($PLACE_NAME,"フィンランド") || strstr($PLACE_NAME,"ハワイ") || strstr($PLACE_NAME,"台湾")) {
	$image["Denryoku"] = $graph["Kaigai"];
} else {
	$image["Denryoku"] = $graph["Tokyo"];
}

if (strstr($PLACE_NAME,"伊勢市")){
	$image["Temp"] = $temp["Ise"];
}elseif (strstr($PLACE_NAME,"那覇市")){
	$image["Temp"] = $temp["Naha"];
}elseif (strstr($PLACE_NAME,"千代田区")){
	$image["Temp"] = $temp["Chiyoda"];
}elseif (strstr($PLACE_NAME,"鹿児島市")){
	$image["Temp"] = $temp["Kagoshima"];
}elseif (strstr($PLACE_NAME,"仙台市")){
	$image["Temp"] = $temp["Sendai"];
}elseif (strstr($PLACE_NAME,"石狩市")){
	$image["Temp"] = $temp["Ishikari"];
}elseif (strstr($PLACE_NAME,"長野市")){
	$image["Temp"] = $temp["Nagano"];
}elseif (strstr($PLACE_NAME,"北本市")){
	$image["Temp"] = $temp["Kitamoto"];
}elseif (strstr($PLACE_NAME,"杉並区")){
	$image["Temp"] = $temp["Suginami"];
}else {
	$image["Temp"] = $temp["ETC"];
}

	$image["weather_icon"] = $wm["weather_icon"];
	if ($wm["weather_icon"] == NULL) {
		unset($image["weather_icon"]);
	}
$image["rain"] = $WW->rain_yahoo($LAT,$LON,10,600,600);
//echo $message;exit;
//----------------------------------------------------------
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
//-----------------------------------------------------------
	if ($wm["weather_icon"] == NULL){
		$update_params = array(
			'media_ids'    => $result_media_id["rain"].",".$result_media_id["memory"].",".$result_media_id["Temp"].",".$result_media_id["Denryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	} else {
		$update_params = array(
			'media_ids'    => $result_media_id["rain"].",".$result_media_id["weather_icon"].",".$result_media_id["Temp"].",".$result_media_id["Denryoku"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}
	$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
	echo "[".$code."]\n".$status."\n";
?>
