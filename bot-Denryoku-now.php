#!/usr/bin/php
<?php
/*
	author:Kouji Sugibayashi
*/

require_once 'TanpopoWeather.php';

//------------------------------------------------------------------------
function file_write(){

	global $DEN,$argv,$power_company,$PowerCom_JP;

	$message  = "現在の電力使用率　\n";

	for ($i = 0; $i < 10; $i++){
		$message .= $DEN[$power_company[$i]]["dig"].$PowerCom_JP[$i].$DEN[$power_company[$i]]["den_updown"].$DEN[$power_company[$i]]["den_per"]."　\n";
	}

	$message .= "#".$argv[2];

	return($message);
}
//-----------------------------------------------------------------------------------
$DD = new Denryoku_Class;
$TT = new Twitter_Class;

$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
$PowerCom_JP   = array("北海道電力","東北電力　","東京電力　","中部電力　","北陸電力　","関西電力　","中国電力　","四国電力　","九州電力　","沖縄電力　");

$Power = $DD->StoreDenryoku();

for ($i = 0; $i < 10; $i++){
	$DEN[$power_company[$i]] = $Power[$power_company[$i]];
}

$Denryoku[] = array("ID" => 0,"dpercent" => $DEN["Hokkaido"]["den_per"],"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=575&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=taffwjqm");
$Denryoku[] = array("ID" => 1,"dpercent" => $DEN["Tohoku"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=565&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg0vmm");
$Denryoku[] = array("ID" => 2,"dpercent" => $DEN["Tokyo"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=553&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg1ogh");
$Denryoku[] = array("ID" => 3,"dpercent" => $DEN["Chubu"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=572&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg1ogh");
$Denryoku[] = array("ID" => 4,"dpercent" => $DEN["Hokuriku"]["den_per"],"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=577&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg2gf8");
$Denryoku[] = array("ID" => 5,"dpercent" => $DEN["Kansai"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=573&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg398i");
$Denryoku[] = array("ID" => 6,"dpercent" => $DEN["Chugoku"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=578&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg422e");
$Denryoku[] = array("ID" => 7,"dpercent" => $DEN["Shikoku"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=576&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg4su1");
$Denryoku[] = array("ID" => 8,"dpercent" => $DEN["Kyushu"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=574&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg4su1");
$Denryoku[] = array("ID" => 9,"dpercent" => $DEN["Okinawa"]["den_per"],	"image" => "https://linuxparadise.net/zabbix/chart2.php?graphid=579&screenid=22&width=624&height=200&legend=1&profileIdx=web.screens.filter&profileIdx2=22&from=now-7d&to=now&_=tafg5lns");

/////////////////////////////////////////

foreach($Denryoku as $key => $value){
	$ID[$key] = $value["ID"];
	$dpercent[$key] = $value["dpercent"];
	$image[$key] = $value["image"];
}
array_multisort($dpercent, SORT_DESC, SORT_NUMERIC, $ID, SORT_ASC, SORT_STRING, $Denryoku);

$DenHigh3 = array_slice($Denryoku , 0, 3);

$count = 0;
$flag = false;
$dc = 0;
foreach($DEN as $key => $val) {
	$DEN[$key]["den_per"] = floor($DEN[$key]["den_per"])."％";
	$DEN[$key]["dig"] = "　";
	if       ($DEN[$key]["den_per"] == 0 OR $DEN[$key]["den_alive"] == "bad") {
//	if       ($DEN[$key]["den_per"] == 0 OR $DEN[$key]["den_alive"] == "bad" OR $key == "Kyushu") {
		$DEN[$key]["den_per"] = "--";
		$DEN[$key]["den_updown"] = "　";
		$dc++;
	} elseif ($DEN[$key]["den_per"] >= 90 && $DEN[$key]["den_per"] < 95) {
		$flag = true;
		for($X = 0; $X < 10; $X++){
	  		$a = $Denryoku[$X]["ID"];
			if ($a == $count){
				$dig_A = bin2hex("➀");
				$dig_A = hexdec($dig_A);
				$dig_A = dechex($dig_A + $X);
				$dig_AA = hex2bin($dig_A);

				$DEN[$key]["dig"] = $dig_AA;
			}
		}
	} elseif ($DEN[$key]["den_per"] >= 95) {
		$flag = true;
		for($X = 0; $X < 10; $X++){
	  		$a = $Denryoku[$X]["ID"];
			if ($a == $count){
			//	$dig_B = bin2hex("❶");
				$dig_B = bin2hex("➊");
				$dig_B = hexdec($dig_B);
				$dig_B = dechex($dig_B + $X);
				$dig_BB = hex2bin($dig_B);

				$DEN[$key]["dig"] = $dig_BB;
			}
		}
	} elseif ($DEN[$key]["den_alive"] == "bad") {
		echo "den_aliveが、badのデータがあるので、ツイートを中止します。";
		exit;
	}
	$count++;
}

if ($dc == 10) {
		echo "power_pref.xmlファイルが、更新されていないようです。終了します。";
		exit;
}

$message = file_write();
if ($flag == true){
	echo "注意か警報があります。ツイートします。";
} elseif ($argc == 1) {
	echo "NORMALかCHECKと引数を渡して下さい。";
	exit;
} elseif ($argv[1] == "CHECK") {
		echo "ツイートしません。";
		exit;
} elseif ($argv[1] == "NORMAL") {
	echo "ツイートします。";
} else {
	echo "NORMALかCHECKと引数を渡して下さい。";
	exit;
}
//-------------------------------------------------------------------
$image = NULL;
$image["Denryoku"] = "http://linuxparadise.net/Denryoku.php";
foreach($DenHigh3 as $key => $val) {
	$image[$key] = $val["image"];
}

$status = $message;
//-------------------------------------------------------------------
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
while (count($result_media_id) != $media && $c < 10) {
	$result_media_id = $TT->upload_image($twitter,$image);
	$c++;
}
//-------------------------------------------------------------------
	$update_params = array(
  		'media_ids'    =>  $result_media_id["Denryoku"].",".$result_media_id[0].",".$result_media_id[1].",".$result_media_id[2], //先ほど取得したmedia_id
		'status'    =>  $status,//つぶやき内容
	);

	$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
	echo "[".$code."]\n".$status;
?>
