#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/
require_once 'TanpopoWeather.php';

$image["green"]		= "http://linuxparadise.net/bot-Win7-Image.php";
$image["MUNIN-MONTH"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/os-month.png";
$image["MUNIN-UPTIME"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/uptime-week.png";
$image["Windows"] 	= "http://linuxparadise.net/bot-Coffee-Image.php";
//------------------------------------------------------------------------
function file_write($win7){
	$message  = "Windows 7 延長サポート終了 \n";
	$message .= "2020年1月14日まで、残り\n";
	$message .= "【".$win7["days"]."】";
	$message .= "になりました。 \n";
	$message .= "Windows 7は、楽しめましたか？(●╹◡╹●) \n";
	$yuki = "❄";
	$penguin = "🐧";
	$happa = "🍃";
	$heart = "♥";
	$computer = "🖥";
	$message .= "#Linux".$penguin." #LinuxMint".$happa;
	$message .= " #elementaryOS".$heart." #ZorinOS".$yuki." #Microsoft";
	return($message);
}
//-----------------------------------------------------------------------------------
$TT = new Twitter_Class;
$win7  = $TT->rekijitsu(2020,1,15,0,1,0);

if ($win7 == NULL) {
	echo "ERROR 1";
	exit;
} else {
	$message = file_write($win7);
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
		$update_params = array(
	  		'media_ids'    =>  $result_media_id["green"].",".$result_media_id["Windows"].",".$result_media_id["MUNIN-MONTH"].",".$result_media_id["MUNIN-UPTIME"], //先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	
		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
	}
?>
