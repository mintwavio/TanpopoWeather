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
$abe  = $TT->rekijitsu_3(2012,12,1,0,0,0);

$abe["days"]  = sprintf("%04d日",$abe["days"]);

if ($abe == NULL){
	echo "ERROR";
	exit;
} else {
	$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/abenomikusu.jpg");
	
	# 必要に応じてUTF8へ変換(環境依存)
	$now_txt = null;
	$abe0_txt = null;
	$abe1_txt = null;
	$abe2_txt = null;
	$iza1_txt = null;

	$now_txt    = mb_convert_encoding(date("Y年m月d日〔".youbi_japan()."〕 H時i分 現在"), 'UTF-8', 'auto');
	$abe0_txt   = mb_convert_encoding("2012年12月(1日）以降、アベノミクスが始まってからの日数","UTF-8","auto");
	$abe1_txt   = mb_convert_encoding($abe["days"],"UTF-8","auto");
	$abe2_txt   = mb_convert_encoding("（".$abe["y"].$abe["m"].$abe["d"]."）","UTF-8","auto");
	$iza1_txt   = mb_convert_encoding("戦後最長の景気回復＝アベノミクス景気","UTF-8","auto");
	$font       = "/usr/share/fonts/truetype/vlgothic/VL-PGothic-Regular.ttf";
	$font_d     = "/usr/share/fonts/truetype/UpperEastSide.ttf";
	
	$blue	 = ImageColorAllocate($img, 0x44, 0x44, 0xff);
	$green	 = ImageColorAllocate($img, 0x22, 0x88, 0x22);
	$ash     = ImageColorAllocate($img, 0x30, 0x30, 0x30);
	$white   = ImageColorAllocate($img, 0xff, 0xff, 0xff);
	$cyan    = ImageColorAllocate($img, 0xff, 0x88, 0xff);
        $red     = ImageColorAllocate($img, 0xff, 0x22, 0x55);
        $yellow  = ImageColorAllocate($img, 0xbb, 0xbb, 0x33);
	
	$m = 95;	

	ImageTTFText($img,  40, 0, 120+20, 160+$m, $yellow, $font, $now_txt);
	ImageTTFText($img,  40, 0, 117+20, 157+$m, $white,$font, $now_txt);

	ImageTTFText($img,  40, 0, 120+20, 255+$m, $blue, $font, $abe0_txt);
	ImageTTFText($img,  40, 0, 117+20, 252+$m, $white,$font, $abe0_txt);
	
        ImageTTFText($img, 120, 0, 120+20, 480+$m, $red,$font, "【".$abe1_txt."】");
        ImageTTFText($img, 120, 0, 115+20, 475+$m, $white,$font, "【".$abe1_txt."】");

        ImageTTFText($img, 40, 0, 1020+20, 480+$m, $red,$font, $abe2_txt);
        ImageTTFText($img, 40, 0, 1017+20, 477+$m, $white,$font, $abe2_txt);

        ImageTTFText($img, 40, 0, 160+20, 650+$m, $blue,$font, $iza1_txt);
        ImageTTFText($img, 40, 0, 157+20, 647+$m, $white,$font, $iza1_txt);

        ImageTTFText($img, 40, 0, 1120+20, 750+$m, $blue,$font, $iza2_txt);
        ImageTTFText($img, 40, 0, 1117+20, 747+$m, $white,$font, $iza2_txt);

	$width = ImageSx($img);
	$height = ImageSy($img);
	
	$out = ImageCreateTrueColor(960,540);
	ImageCopyResampled($out, $img,
	    0,0,0,0, 960, 540, $width, $height);
	
	header('Content-Type: image/jpeg');
	ImageJPEG($out);

        }
?>


