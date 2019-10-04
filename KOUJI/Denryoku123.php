<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
<!--    <meta http-equiv="refresh" content="60"> -->
    <title>大手電力会社　電力使用率</title>
    <meta content="Kouji Sugibayashi" name="author">
  </head>
    <style type="text/css">
    body {
  background-image: url('https://linuxparadise.net/wp-content/uploads/2018/04/GUM13_PA01004.jpg'); 
  background-attachment: fixed;
}  
    </style>
  <body style="font-size: larger;">
    <div align="center">
    <div style="font-size: smaller;" align="center"><br>
<iframe src="https://linuxparadise.net/Denryoku.php" id="Denryoku"
        style="" scrolling="no" width="965" height="533" frameborder="0"></iframe><br>
        <br>
        <table style="width: 960px; text-align: left; margin-left: auto; margin-right: auto;"
          border="0">
          <tbody>
            <tr>
              <td style="background-color: rgba(204, 204, 204, 0.5); font-size: 14px; text-align: center;">【△・▽・▲・▼・＝】 各電力会社の電力消費量の最新の物と、その一つ前の時間の情報との比較を表わしております。<br>
                （△：少し増えました。／▽：少し減りました。／▲：沢山増えました。／▼：沢山減りました。／＝：同じでした。）<br>
                【×】 各電力会社から出力されたCSVデータの異常の判定を表わしております。正常の場合、表示されません。</td>
            </tr>
          </tbody>
        </table>
        <br>
    </div></div>
<?php
	function rekijitsu_3($Y,$M,$D,$H,$I,$S){
	
		$date1 = new DateTime();
		$date1->setDate(date('Y'),date('m'),date('d'))
		      ->setTime(date('H'),date('i'),date('s'));
		$date2 = new DateTime();
		$date2->setDate($Y,$M,$D)
		      ->setTime($H,$I,$S);
		$interval = $date1->diff($date2);
		$day = array();
		$day["days"]	= sprintf("%04d",$interval->days)."日";
		$day["y"]	= sprintf("%02d",$interval->y)."ヶ年";
		$day["m"]	= sprintf("%02d",$interval->m)."ヶ月";
		$day["d"] 	= sprintf("%02d",$interval->d)."日";
		$day["h"] 	= sprintf("%02d",$interval->h)."時間";
		$day["i"]	= sprintf("%02d",$interval->i)."分";
		$day["s"]	= sprintf("%02d",$interval->s)."秒";

		return($day);
	}

	function StoreDenryoku() {

		$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
		$filename = '/etc/zabbix/externalscripts/power_prefs.xml';
		for ($j = 0; $j < 10; $j++) {
			$xml = @simplexml_load_file($filename);
			if ($xml) {

				$Y = date("Y",filemtime($filename));
				$M = date("m",filemtime($filename));
				$D = date("d",filemtime($filename));
				$H = date("H",filemtime($filename));
				$I = date("i",filemtime($filename));
				$S = date("s",filemtime($filename));
				$day = rekijitsu_3($Y,$M,$D,$H,$I,$S);

				
				for ($i = 0; $i < 10; $i++){
					$Power[$power_company[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
					$Power[$power_company[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
					$Power[$power_company[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
					$Power[$power_company[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
					$Power[$power_company[$i]]["den_alive"] = ($xml->pref[$i]->den_alive);
					$Power[$power_company[$i]]["den_date"]  = ($xml->pref[$i]->den_date);
					$Power[$power_company[$i]]["den_time"]  = ($xml->pref[$i]->den_time);
					$Power[$power_company[$i]]["den_updown"]= ($xml->pref[$i]->den_updown);

					if ($day["days"] > 0 || $day["h"] >= 1) {
						$Power[$power_company[$i]]["den_alive"] = "bad";
					}
       				}
				return($Power);

			} else {
				sleep(10);
			}
		}
		return(NULL);	
	
	}
$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
$PowerCom_JP   = array("北海道電力","東北電力　","東京電力　","中部電力　","北陸電力　","関西電力　","中国電力　","四国電力　","九州電力　","沖縄電力　");

$Power = StoreDenryoku();

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

foreach($DenHigh3 as $key => $val) {
	$image[$key] = $val["image"];
}
print('<br><div align="center">');
for ($i = 0; $i < 3; $i++){
	print('<img src='.$image[$i].'><br /><br /><br />');
}
print('</div>');
?>

      <div style="text-align: center; font-size: 14px;">感謝：大手電力会社、<a target="_blank"
          href="https://linuxmint.com/">Linux Mint</a>、<a target="_blank"
          href="http://www.asial.co.jp/jpgraph/">JpGraph</a>、<a target="_blank" href="https://www.zabbix.com/jp/">ZABBIX</a> ／ 【<a href="https://linuxparadise.net/%E3%83%9F%E3%83%B3%E3%83%88%E3%81%AE%E3%81%8A%E5%A4%A9%E6%B0%97-bot-%E2%98%BE%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6%E3%80%82"
          target="_blank">このグラフのソースや、詳細はこちらまで。</a>】 ／ 【<a target="_blank"
          href="http://linuxparadise.net/">HOME</a>】<br><br></div>
  </body>
</html>
