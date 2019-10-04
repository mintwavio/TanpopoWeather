<?php
/*
	author:Kouji Sugibayashi
*/
require_once '/home/kouji/public_html/html/AutoTweet/TanpopoWeather.php';
$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/5-10-005-2.jpg");
//$img = ImageCreateFromJPEG("/home/kouji/public_html/html/AutoTweet/image/sea17.jpg");

$TT = new Twitter_Class;

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

function color_set($y,$m){
	
	global $img;

	$c1 = ImageColorAllocate($img, 0x00,0xcc,0xcc);
	$c2 = ImageColorAllocate($img, 0xff,0xff,0xff);
	$c3 = ImageColorAllocate($img, 0x00,0xcc,0x00);
	$c4 = ImageColorAllocate($img, 0xff,0xff,0xff);

	$cA = ImageColorAllocate($img, 0xff,0xaa,0x00);
	$cB = ImageColorAllocate($img, 0x88,0x88,0x88);
	$cC = ImageColorAllocate($img, 0xff,0x33,0x88);
	$cD = ImageColorAllocate($img, 0x88,0x88,0x88);

/*	
	if ($y == 0 && $m == 0 ) {
		$color[] = $cA;
		$color[] = $cB;
		$color[] = $cC;
		$color[] = $cD;
	} else { */	
		$color[] = $c1;
		$color[] = $c2;
		$color[] = $c3;
		$color[] = $c4;
//	}

	return($color);
}

$win10 = $TT->rekijitsu(2025,10,15,0,1,0);
$win8  = $TT->rekijitsu(2023,1,11,0,1,0);
$win7  = $TT->rekijitsu(2020,1,15,0,1,0);
$vista = $TT->rekijitsu(2017,4,12,0,1,0);
$winserv2008 = $TT->rekijitsu(2020,1,15,0,1,0);
$olympic = $TT->rekijitsu(2020,7,24,20,1,0);
$homepage = $TT->rekijitsu_3(1992,9,30,0,0,0);

if ($vista == NULL || $win7 == NULL || $win8 == NULL || $win10 == NULL){
	echo "ERROR";
	exit;
} else {
	
	$now_txt       = mb_convert_encoding(date("Y年m月d日〔".youbi_japan()."〕 H時i分 現在"), 'UTF-8', 'auto');

	$Olympic_txt1  = mb_convert_encoding("東京2020オリンピック・パラリンピック開催\n",'UTF-8', 'auto');
	$Olympic_txt2  = mb_convert_encoding("2020年07月24日 20時00分まで、\n【".$olympic["days"]."】\n（".$olympic["y"].$olympic["m"].$olympic["d"]." ".$olympic["h"].$olympic["i"]."）" ,'UTF-8','auto');

	$title_txt  = mb_convert_encoding("Microsoft Windows 延長サポート終了\n", 'UTF-8', 'auto');

	$Win10_txt  = mb_convert_encoding("Windows 10 サポート 残り日数" ,"UTF-8", "auto");
	$Win10_txt2 = mb_convert_encoding("2025年10月14日まで、\n【".$win10["days"]."】\n（".$win10["y"].$win10["m"].$win10["d"]." ".$win10["h"].$win10["i"]."）" ,'UTF-8','auto');
	
	$Win8_txt   = mb_convert_encoding("Windows 8.1 サポート 残り日数","UTF-8","auto");
	$Win8_txt2  = mb_convert_encoding("2023年01月10日まで、\n【".$win8["days"]."】\n（".$win8["y"].$win8["m"].$win8["d"]." ".$win8["h"].$win8["i"]."）" ,'UTF-8','auto');
	
	$Win7_txt   = mb_convert_encoding("Windows 7 サポート 残り日数","UTF-8","auto");
	$Win7_txt2  = mb_convert_encoding("2020年01月14日まで、\n【".$win7["days"]."】\n（".$win7["y"].$win7["m"].$win7["d"]." ".$win7["h"].$win7["i"]."）" ,'UTF-8','auto');

	$Vista_txt  = mb_convert_encoding("Windows Vista サポート 残り日数","UTF-8","auto");
	$Vista_txt2 = mb_convert_encoding("2017年04月11日まで、\n【".$vista["days"]."】\n（".$vista["y"].$vista["m"].$vista["d"]." ".$vista["h"].$vista["i"]."）", 'UTF-8', 'auto');

	$WinServ2008_txt  = mb_convert_encoding("Windows Server 2008 / 2008 R2 サポート 残り日数","UTF-8","auto");
	$WinServ2008_txt2 = mb_convert_encoding("2020年01月14日まで、\n【".$winserv2008["days"]."】\n（".$winserv2008["y"].$winserv2008["m"].$winserv2008["d"]." ".$winserv2008["h"].$winserv2008["i"]."）", 'UTF-8', 'auto');

	$CAA  = ImageColorAllocate($img, 0xcc,0x14,0x93);
	$CBB  = ImageColorAllocate($img, 0xff,0xff,0xff);

	$blue  = ImageColorAllocate($img, 0x00,0x00,0xff);
	$white = ImageColorAllocate($img, 0xff,0xff,0xff);
	$VL_P = '/usr/share/fonts/truetype/vlgothic/VL-PGothic-Regular.ttf';

	$m    = 255;
	$m2   = 230;
	$mo   = 11;
	$xx   =-1020;

	ImageTTFText($img, 21+$mo, 0, 1153+$xx, -57 + $m2, $blue,	$VL_P, $now_txt);
	ImageTTFText($img, 21+$mo, 0, 1150+$xx, -60 + $m2, $white,	$VL_P, $now_txt);

	$color = color_set($olympic["y"],$olympic["m"]);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 3  + $m2, $color[0],	$VL_P, $Olympic_txt1);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 0  + $m2, $color[1],	$VL_P, $Olympic_txt1);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 38 + $m2, $color[2],	$VL_P, $Olympic_txt2);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 35 + $m2, $color[3],	$VL_P, $Olympic_txt2);

	ImageTTFText($img, 21+$mo, 0, 1153+$xx, 173 + $m, $CAA,	$VL_P, $title_txt);
	ImageTTFText($img, 21+$mo, 0, 1150+$xx, 170 + $m, $CBB,	$VL_P, $title_txt);

	$color = color_set($winserv2008["y"],$winserv2008["m"]);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 233 + 340 + $m, $color[0],	$VL_P, $WinServ2008_txt);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 230 + 340 + $m, $color[1],	$VL_P, $WinServ2008_txt);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 268 + 340 + $m, $color[2],	$VL_P, $WinServ2008_txt2);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 265 + 340 + $m, $color[3],	$VL_P, $WinServ2008_txt2);

	$color = color_set($win8["y"],$win8["m"]);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 403 - 170 + $m, $color[0],	$VL_P, $Win8_txt);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 400 - 170 + $m, $color[1],	$VL_P, $Win8_txt);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 438 - 170 + $m, $color[2],	$VL_P, $Win8_txt2);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 435 - 170 + $m, $color[3],	$VL_P, $Win8_txt2);

	$color = color_set($win7["y"],$win7["m"]);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 573 - 170 + $m, $color[0],	$VL_P, $Win7_txt);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 570 - 170 + $m, $color[1],	$VL_P, $Win7_txt);
	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 608 - 170 + $m, $color[2],	$VL_P, $Win7_txt2);
	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 605 - 170 + $m, $color[3],	$VL_P, $Win7_txt2);
	
#	$color = color_set($vista["y"],$vista["m"]);
#	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 743 + $m, $color[0],	$VL_P, $Vista_txt);
#	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 740 + $m, $color[1],	$VL_P, $Vista_txt);
#	ImageTTFText($img, 20+$mo, 0, 1153+$xx, 778 + $m, $color[2],	$VL_P, $Vista_txt2);
#	ImageTTFText($img, 20+$mo, 0, 1150+$xx, 775 + $m, $color[3],	$VL_P, $Vista_txt2);

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
