<?php

require_once '/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php';

function youbi_japan() {
	//日本語の曜日配列
	$weekjp = array(
	  '日', //0
	  '月', //1
	  '火', //2
	  '水', //3
	  '木', //4
	  '金', //5
	  '土'  //6
	);
	 
	//現在の曜日番号（日:0  月:1  火:2  水:3  木:4  金:5  土:6）を取得
	$weekno = date('w');
	 
	//日本語曜日を出力
	return($weekjp[$weekno]);
}

$TT = new Twitter_Class;
$win7 = $TT->rekijitsu(2020,1,15,0,1,0);
$www  = $TT->rekijitsu_3(1991,8,6,0,0,0);
$hp   = $TT->rekijitsu_3(1992,9,30,0,0,0);
$www["days"] = sprintf("%05d日",$www["days"]);
$hp["days"]  = sprintf("%05d日",$hp["days"]);

if ($win7 == NULL){
	echo "ERROR";
	exit;
} else {
//	$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/361.jpg");
//	$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/mame003.jpg");
	$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/green-home.jpg");
	
	# 必要に応じてUTF8へ変換(環境依存)
	$now_txt    = mb_convert_encoding(date("Y年m月d日〔".youbi_japan()."〕 H時i分 現在"), 'UTF-8', 'auto');
	$nokori	    = mb_convert_encoding("残り日数", 'UTF-8', 'auto');
	$nokori_day = mb_convert_encoding($win7["days"], 'UTF-8', 'auto');
	$Win7_days  = mb_convert_encoding("（".$win7["y"].$win7["m"].$win7["d"]." ".$win7["h"].$win7["i"]."）", 'UTF-8', 'auto');
	$www_days   = mb_convert_encoding("1991年08月06日 World Wide Web開始から【".$www["days"]."】", 'UTF-8','auto');
	$www_daysx  = mb_convert_encoding("（".$www["y"].$www["m"].$www["d"]."）", 'UTF-8','auto');
	$hp_days    = mb_convert_encoding("1992年09月30日 日本初のホームページ開始から【".$hp["days"]."】", 'UTF-8','auto');
	$hp_daysx   = mb_convert_encoding("（".$hp["y"].$hp["m"].$hp["d"]."）", 'UTF-8','auto');
	$font       = "/usr/share/fonts/truetype/vlgothic/VL-PGothic-Regular.ttf";
	$font_d     = "/usr/share/fonts/truetype/UpperEastSide.ttf";
	$font_dx    = "/usr/share/fonts/truetype/Let_s_go_Digital_Regular.ttf";
	
	$blue	 = ImageColorAllocate($img, 0x44, 0x44, 0xff);
	$green	 = ImageColorAllocate($img, 0x22, 0x88, 0x22);
	$red	 = ImageColorAllocate($img, 0x88, 0x22, 0x22);
	$ash     = ImageColorAllocate($img, 0x50, 0x50, 0x50);
	$yellow  = ImageColorAllocate($img, 0xbb, 0xbb, 0x33);
	$white   = ImageColorAllocate($img, 0xff, 0xff, 0xff);
	$cyan    = ImageColorAllocate($img, 0xff, 0x88, 0xff);
        $red     = ImageColorAllocate($img, 0xff, 0x22, 0x55);

	$m = 40;
//	$nokori_day = "0888";

	ImageTTFText($img,  40, 0, 210+20, 150+$m, $yellow, $font, $now_txt);
	ImageTTFText($img,  40, 0, 207+20, 145+$m, $white,$font, $now_txt);

	ImageTTFText($img,  60, 0, 545+20, 255+$m, $blue, $font, $nokori);
	ImageTTFText($img,  60, 0, 540+20, 250+$m, $white,$font, $nokori);

        ImageTTFText($img, 120, 0, 245+40, 410+$m, $green,$font, "【         日】");
        ImageTTFText($img, 120, 0, 240+40, 405+$m, $white,$font, "【         日】");

        ImageTTFText($img, 144, 0, 260+20+155, 410+$m, $green,$font_dx, substr($nokori_day,0,4));
        ImageTTFText($img, 144, 0, 255+20+155, 405+$m, $white,$font_dx, substr($nokori_day,0,4));

	ImageTTFText($img,  40, 0, 250+20, 495+$m, $cyan, $font, $Win7_days);
	ImageTTFText($img,  40, 0, 247+20, 492+$m, $white,$font, $Win7_days);

	ImageTTFText($img,  24, 0, 122+20, 570+$m, $blue,$font, $www_days.$www_daysx);
	ImageTTFText($img,  24, 0, 120+20, 568+$m, $white,$font, $www_days.$www_daysx);

	ImageTTFText($img,  24, 0,  84+20, 610+$m, $blue,$font, $hp_days.$hp_daysx);
	ImageTTFText($img,  24, 0,  82+20, 608+$m, $white,$font, $hp_days.$hp_daysx);

	$width = ImageSx($img);
	$height = ImageSy($img);

	$out = ImageCreateTrueColor(960,540);
	ImageCopyResampled($out, $img,
	    0,0,0,0, 960, 540, $width, $height);
	
	header('Content-Type: image/jpeg');
	ImageJPEG($out);
//	header('Content-Type: image/jpeg');
//	ImageJPEG($img);
}
?>


