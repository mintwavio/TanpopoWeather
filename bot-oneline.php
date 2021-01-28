#!/usr/bin/php
<?php
/*
        author:Kouji Sugibayashi
*/

	require_once 'TanpopoWeather.php';
//-----------------------------------------------------------

$image["picture"] = "https://thanks.linuxparadise.net/uploads/photos/320.jpg";

//-----------------------------------------------------------
function data_scroll() {
		$data = file_get_contents("./data.txt");
		$data = explode("\n",$data);
		$cnt = count($data);
		for($i=0; $i<$cnt; $i++){
		//	echo $data[$i]."\n";
			if (isset($data[$i+1])){
				$data_x[$i] = $data[$i+1]."\n";
			}
		}
		$data_x[$cnt-1] = $data[0];
		//var_dump($data_x);
	
		file_put_contents("./data.txt",$data_x);
		return array($data,$data_x,$cnt);
	}
//---------------------------------------------------------------

list($data,$data_x,$cnt) = data_scroll();

while(true){
	if ($data[0] == "" || $data[0] == null || $data[0] == "\n"){
		list($data,$data_x,$cnt) = data_scroll();
	} else {
		break;
	}
}

$status = $data[0];

//----------------------------------------------------------
$TT = new Twitter_Class;
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
	$update_params = array(
		'media_ids' => $result_media_id["picture"],//先ほど取得したmedia_id
		'status'    => $status,//つぶやき内容
	);
	$code = $twitter->request( 'POST', "https://api.twitter.com/1.1/statuses/update.json", $update_params);
	echo "[".$code."]\n".$status."\n";
?>
