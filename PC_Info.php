#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/

require_once("TanpopoWeather.php");

$TT = new Twitter_Class;

$image["CPU"] = "http://localhost/munin/localdomain/localhost.localdomain/sensors_temp-day.png";
$image["SSD"] = "http://localhost/munin/localdomain/localhost.localdomain/hddtemp2-day.png";
$image["PC"]  = "https://thanks.moe.hm/uploads/photos/385.jpg";
$image["UPTIME"] = "http://localhost/munin/localdomain/localhost.localdomain/uptime-day.png";

$LATX[0] = "35.6496419";
$LONX[0] = "139.7258827";

$LATX[1] = "35.6737072";
$LONX[1] = "139.7766995";

$LATX[2] = "35.6401758";
$LONX[2] = "139.7308182";

$LATX[3] = "35.6876676";
$LONX[3] = "139.742945";

$LATX[4] = "35.6633767";
$LONX[4] = "139.7240074";

function message_write() {

	extract($GLOBALS);

	$output  = shell_exec("sudo /etc/munin/plugins/sensors_temp");
	$output2 = shell_exec("sudo /etc/munin/plugins/hddtemp2");

//	echo strlen($output)."\n".strlen($output2)."\n";
	if (!(strlen($output) == 204 && strlen($output2) == 48)) {
		exit;
	}

	$message  = "都心天気：".$wg["weather"]."　\n";
	$message .= "気温：".$wg["temp_now2"]."℃　\n";
	$message .= "湿度：".$wg["humidity_now2"]."　\n";
	
	$message .= "CPU：";
	$message .= substr($output,29,2)."℃・";
	$message .= substr($output,46,2)."℃・";
	$message .= substr($output,63,2)."℃・";
	$message .= substr($output,80,2)."℃　\n";
	
	$message .= "SSD：".substr($output2,13,2)."℃・";
	$message .= substr($output2,29,2)."℃　\n";
	$message .= "HDD：".substr($output2,45,2)."℃　\n";
	
	$hozon = $TT->get_uptime_me();
	
	$message .= "連続稼働時間：\n";
	$message .= $hozon."　\n";

	$day = $TT->rekijitsu_3(2012,06,26,00,00,00);

	$message .= "購入日から：\n".$day["days"];
	$message .= "（".$day["y"].$day["m"].$day["d"]."）　\n";	
	
	$message .= "#".$argv[1];
	
	return($message);
}/*
for ($count = 3; $count < 5; $count++){
	$LAT = $LATX[$count];
	$LON = $LONX[$count];
	echo $count." \n";
	$WW = new Weather_Class;
	$wg = $WW->wunderground();
	if ($wg != NULL && $wg["icon_url"] != NULL){
		break;
	} else {
		sleep(60);
	}
	if ($count == 4) {
		echo "ERROR 200";
		exit;
	}
}*/

$LAT = $LATX[2];
$LON = $LONX[2];
$WW = new Weather_Class;
$wg = $WW->wunderground();

if ($wg == NULL){
	echo "ERROR 100";
	exit;
}

if ($wg["icon_url"] != NULL){
	$wg["icon_url"] = "./image/".substr($wg["icon_url"],33);
	$image["weather_icon"] = $wg["icon_url"];
} else {
	unset($image["weather_icon"]);
}

$message = message_write();
$status = $message;
//echo $status;exit;
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
	if ($wg["icon_url"] == NULL) {
		$update_params = array(
			'media_ids'    =>   $result_media_id["PC"].",".$result_media_id["UPTIME"].",".$result_media_id["CPU"].",".$result_media_id["SSD"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	} else {
		$update_params = array(
			'media_ids'    =>   $result_media_id["PC"].",".$result_media_id["weather_icon"].",".$result_media_id["CPU"].",".$result_media_id["SSD"],//先ほど取得したmedia_id
			'status'    =>  $status,//つぶやき内容
		);
	}
		$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
		echo "[".$code."]\n".$status;
?>
