#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/

require_once("TanpopoWeather.php");

$TT = new Twitter_Class;
$host = "localhost";

$image["CPU_Temp"]    = "http://$host/munin/localdomain/localhost.localdomain/cpu_temp-day.png";
$image["CPU_Usage"]   = "http://$host/munin/localdomain/localhost.localdomain/cpu-day.png";
$image["PC"]          = "./image/389.jpg";
$image["Tokyo_Power"] = "http://$host/munin/localdomain/localhost.localdomain/power_tokyo-day.png";


function message_write() {

	global $TT,$Power,$host;	
	$output  = shell_exec("vcgencmd measure_temp");
	$message  = "【From Raspberry Pi Zero】　\n";
//	$message .= "現在時刻▶\n".date("Y年m月d日 H時i分")."　\n";	
	$message .= "CPU温度▶".substr($output,5,4)."℃　\n";
	
	$hozon = $TT->get_uptime_raspi($host);
	
	$message .= "連続稼働時間▶\n";
	$message .= $hozon."　\n";

	$day = $TT->rekijitsu_3(2017,05,01,00,00,00);

	$setsuden = "節電をしましょう。　\n";
	$kansya   = "ご協力感謝します。　\n";
        if ($Power["Tokyo"]["den_alive"] == "good"){
		$message .= "東京電力電力使用率▶".$Power["Tokyo"]["den_per"]."％　\n";
		if ($Power["Tokyo"]["den_per"] >= 90) {
			$message .= $setsuden;
		} else {
			$message .= $kansya;
		}
	}
	$message .= "購入日からの経過日数▶\n".$day["days"];
	$message .= "（".$day["y"].$day["m"].$day["d"]."）　\n";	
	$message .= "#ラズパイ🍓";
	
	return($message);
}
$DD = new Denryoku_Class;
$Power = $DD->StoreDenryoku();

$message = message_write();
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
		$update_params = array(
			'media_ids'    =>   $result_media_id["PC"].",".$result_media_id["CPU_Temp"].",".$result_media_id["CPU_Usage"].",".$result_media_id["Tokyo_Power"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);

		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
?>
