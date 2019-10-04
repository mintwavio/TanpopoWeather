#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/
require_once 'TanpopoWeather.php';

$image["summer"] 	= "http://linuxparadise.net/bot-Win7-Image-X.php";
$image["MUNIN-WEEK"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/os-week.png";
$image["MUNIN-MONTH"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/os-month.png";
$image["MUNIN-UPTIME"] 	= "http://linuxparadise.net/munin/localdomain/localhost.localdomain/uptime-week.png";
$image["Windows"]       = "http://linuxparadise.net/bot-Coffee-Image.php";
//------------------------------------------------------------------------
function file_write($server,$win7){
	$message  = "Windows 7 延長サポート終了の\n";
	$message .= "2020年1月14日まで、残り▶";
	$message .= $win7["days"]."\n（";
	$message .= $win7["y"].$win7["m"].$win7["d"]." ".$win7["h"].$win7["i"];
	$message .= "）　\n";
	$message .= "VPS連続稼働時間▶\n";
	$message .= "【".$server."】\n";
	$yuki = "❄";
	$penguin = "🐧";
	$happa = "🍃";
	$heart = "♥";
	$computer = "🖥";
	$message .= "#Linux".$penguin." #LinuxMint".$happa." #elementaryOS".$heart." #ZorinOS".$yuki." \n";
	return($message);
}
//-----------------------------------------------------------------------------------
$TT = new Twitter_Class;
$win7  = $TT->rekijitsu(2020,1,15,0,1,0);
$server = $TT->get_uptime();

if ($server == NULL || $win7 == NULL) {
	echo "ERROR 1";
	exit;
} else {
	$message = file_write($server,$win7);

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
	  		'media_ids'    =>  $result_media_id["summer"].",".$result_media_id["Windows"].",".$result_media_id["MUNIN-MONTH"].",".$result_media_id["MUNIN-UPTIME"], //先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	
		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
	}
?>
