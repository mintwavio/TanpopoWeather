<?php
	$pub = "/home/kouji/public_html/";
	$FILE1 = $pub."html/linuxmint/jpgraph/jpgraph.php";
	$FILE2 = $pub."html/linuxmint/jpgraph/jpgraph_bar.php";
	$FILE3 = $pub."html/AutoTweet/setting_awb.php";

	if (file_exists($FILE1) && file_exists($FILE2) && file_exists($FILE3)){
		require_once ($FILE1);
		require_once ($FILE2);
		require_once ($FILE3);
	} else {
		echo "JpGraphをインターネットからダウンロードし、\"".$FILE1."\",\"".$FILE2."\"を配置して下さい。\n";
		exit;
	}

function get_holidays_org() {
            global $GOOGLE_CALENDAR_API_KEY;

	    $year = date("Y");
	    $apiKey = $GOOGLE_CALENDAR_API_KEY;
	    $holidays = array();
	
	    // カレンダーID 
	    $calendar_id = urlencode('japanese__ja@holiday.calendar.google.com');
	
	    // 取得期間
	    $start  = date($year."-01-01\T00:00:00\Z");
	    $finish = date($year."-12-31\T00:00:00\Z");
	
	    $url = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events?key={$apiKey}&timeMin={$start}&timeMax={$finish}&maxResults=50&orderBy=startTime&singleEvents=true";
	//pr($url);
	
	    if ($results = file_get_contents($url, true)) {
	        // JSON形式で取得した情報を配列に格納
	        $results = json_decode($results);
	
	        // 年月日をキー、祝日名を配列に格納
	        foreach ($results->items as $item) {
	            $date = strtotime((string) $item->start->date);
	            $title = (string) $item->summary;
	            $holidays[date('Y-m-d', $date)] = $title;
	        }
	
	        // 祝日の配列を並び替え
	        ksort($holidays);
	    }
//var_dump($holidays);exit;
	    return $holidays; 
}

	
	$power_company = array("Hokkaido","Tohoku","Tokyo","Chubu","Hokuriku","Kansai","Chugoku","Shikoku","Kyushu","Okinawa");
	$power_company_JP = array("北海道電力"," 東北電力 "," 東京電力 "," 中部電力 "," 北陸電力 "," 関西電力 "," 中国電力 "," 四国電力 "," 九州電力 "," 沖縄電力 ");

	$filename = '/etc/zabbix/externalscripts/power_prefs.xml';
	$xml = @simplexml_load_file($filename);
	if ($xml) {
		for ($i = 0; $i < 10; $i++){
			$Power[$power_company[$i]]["den_per"]   = ($xml->pref[$i]->den_per);
			$Power[$power_company[$i]]["den_now"]   = ($xml->pref[$i]->den_now);
			$Power[$power_company[$i]]["den_max"]   = ($xml->pref[$i]->den_max);
			$Power[$power_company[$i]]["den_yosou"] = ($xml->pref[$i]->den_yosou);
			$Power[$power_company[$i]]["den_time"]  = ($xml->pref[$i]->den_time);
			$Power[$power_company[$i]]["den_date"]  = ($xml->pref[$i]->den_date);
			$Power[$power_company[$i]]["den_updown"]= ($xml->pref[$i]->den_updown);
			$da 					= ($xml->pref[$i]->den_alive);
			if ($da == "good"){
				$da_x = "　";
			} else {
				$da_x = "✕";
			}
			$Power[$power_company[$i]]["den_alive"] = $da_x;
		}
		$data_time = ($xml->pref[10]->time);
		$time_Ymd  = ($xml->pref[10]->time_Ymd);
		$data_w    = mb_substr($data_time,12,1,"UTF-8");		
		$data_time = str_replace("〔".$data_w."〕","〔　〕",$data_time);

	} else {
		exit;
	}
//var_dump($Power);exit;

	for ($i = 0; $i < 10; $i++){
		$DEN[$power_company[$i]] = $Power[$power_company[$i]];
		$DenDen[] = array("ID" => $i,"dpercent" => $DEN[$power_company[$i]]["den_per"]);
	}

	foreach($DenDen as $key => $value){
		$ID[$key] = $value["ID"];
		$dpercent[$key] = $value["dpercent"];
	}
	array_multisort($dpercent, SORT_DESC, SORT_NUMERIC, $ID, SORT_ASC, SORT_STRING, $DenDen);
	
	$DenHigh3 = array_slice($DenDen , 0, 3);
	
	for ($i = 0; $i < 10; $i++){
		$PD[$i] = (float)$Power[$power_company[$i]]["den_per"];
	}
	$datay=array($PD[0],$PD[1],$PD[2],$PD[3],$PD[4],$PD[5],$PD[6],$PD[7],$PD[8],$PD[9]);

	for ($i = 0; $i < 10; $i++){
		switch ($i) {
			case $DenHigh3[0]["ID"]:
				$num[$i] = "①";
				break;
			case $DenHigh3[1]["ID"]:
				$num[$i] = "②";
				break;
			case $DenHigh3[2]["ID"]:
				$num[$i] = "③";
				break;
			default:
				$num[$i] = "　";
		}
		if ($Power[$power_company[$i]]["den_date"] == "休止中"){
			$PD[$i] = sprintf("%1s %5s %1s 【%5d／%5d万kW】\n 　　    　　 %s［休止中]          ",$num[$i],$power_company_JP[$i],$Power[$power_company[$i]]["den_updown"],$Power[$power_company[$i]]["den_now"],$Power[$power_company[$i]]["den_max"],$Power[$power_company[$i]]["den_alive"],$Power[$power_company[$i]]["den_date"],$Power[$power_company[$i]]["den_time"]);
		}else{
			$PD[$i] = sprintf("%1s %5s %1s【%5d／%5d万kW】\n 　　    　　 %s［%s %s］",$num[$i],$power_company_JP[$i],$Power[$power_company[$i]]["den_updown"],$Power[$power_company[$i]]["den_now"],$Power[$power_company[$i]]["den_max"],$Power[$power_company[$i]]["den_alive"],$Power[$power_company[$i]]["den_date"],$Power[$power_company[$i]]["den_time"]);
		}
	}
	$datax=array($PD[0],$PD[1],$PD[2],$PD[3],$PD[4],$PD[5],$PD[6],$PD[7],$PD[8],$PD[9]);
	
//	$graph = new Graph(960,528,'auto');    
	$graph = new Graph(960,528,'auto');    
	$graph->SetScale("textlin",0,110);
	$graph->yaxis->SetLabelFormat("%3d.0%%");
	$graph->SetShadow();
	
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetFont(FF_GOTHIC,FS_NORMAL,10);
	
	$tcol=array(0,0,255);
	$fcol=array(255,0,0);	
	for ($i = 0; $i < 10; $i++) {
		if ($datay[$i] >= 90 && $datay[$i] < 95) {
			$c[$i] = "yellow@0.3";
		} elseif ($datay[$i] >= 95 && $datay[$i] < 100) {
			$c[$i] = "orangered@0.3";
		} elseif ($datay[$i] >= 100) {
			$c[$i] = "red@0.3";
		} else {
			$c[$i] = "blue@0.3";
		}
	}
	$bplot = new BarPlot($datay);
	/*
	$bplot->SetFillColor($c);
		$bplot->SetWidth(0.6);
		$bplot->SetShadow();
		$bplot->value->SetFormat("%.1f%%");
		$bplot->value->Show();
	
	$M = date("m");
	if ($M >= 1 && $M <= 3){
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_pink5.png',BGIMG_FILLFRAME);
	} elseif ($M >= 4 && $M <= 6) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_bx.png',BGIMG_FILLFRAME);
	} elseif ($M >= 7 && $M <= 8) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_bx.png',BGIMG_FILLFRAME);
	} elseif ($M >= 9 && $M <= 12) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_pink5.png',BGIMG_FILLFRAME);
	}
	$graph->SetBackgroundImageMix(100);
*/
	$graph->title->SetFont(FF_PGOTHIC,FS_NORMAL,20);
//	$graph->title->Set("\n《大手電力会社 電力使用率》");
	$graph->title->Set("\n");
	
	$z = date("H") % 9;

	if ($z == 8) {
			$message  = "　平成30年北海道胆振東部地震の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　　　一日も早く、復興ができますように、お祈り申し上げます。\n";
	} elseif ($z == 7) {
			$message  = "　　 　平成30年7月豪雨の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　　　一日も早く、復興ができますように、お祈り申し上げます。\n";
	} elseif ($z == 6) {
			$message  = "　　 　大阪府北部地震の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　　　一日も早く、復興ができますように、お祈り申し上げます。\n";
	} elseif ($z == 5) {
			$message  = "　 平成29年7月九州北部豪雨の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　　　一日も早く、復興ができますように、お祈り申し上げます。\n";
	} elseif ($z == 4) {
			$message  = "　　　阪神・淡路大震災の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　被災地の皆様が、益々元気になりますように、お祈り申し上げます。\n";
	} elseif ($z == 3) {
			$message  = "　　　　　熊本地震の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　　　一日も早く、復興が出来ますように、お祈り申し上げます。\n";
	} elseif ($z == 2) {
			$message  = "　　　 東日本大震災の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　 　一日も早く、復興が出来ますように、お祈り申し上げます。\n";
	} elseif ($z == 1) {
			$message  = "　　　 鳥取県中部地震の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　　　  　一日も早く、復興が出来ますように、お祈り申し上げます。\n";
	} elseif ($z == 0) {
			$message  = "　　 糸魚川市大規模火災の被災地の皆様に、心よりお見舞い申し上げます。\n";
			$message .= "　 　　　一日も早く、復興が出来ますように、お祈り申し上げます。\n";
	}

	$message .= "　　　　　　　　                                      　　　　".$data_time."更新";

	$graph->xaxis->title->Set($message);
	$graph->xaxis->title->SetFont(FF_GOTHIC,FS_NORMAL,10);
	$graph->xaxis->title->SetMargin(-596);


	$graph->Set90AndMargin(312,89,90,90);
	$graph->SetBackgroundGradient('white','green:1.5',GRAD_HOR,BGRAD_PLOT);

	$txt=new Text($data_w);
	$txt->SetPos(686,-16);

	$array = get_holidays_org();
	foreach($array as $key => $value){
		$array[$key] = $value;
	}

	if (isset($array["$time_Ymd"]) || $data_w == "日"){
//	if ((( isset($array[date("Y-m-d")])) && (date("Y-m-d") == $time_Ymd ))  || $data_w == "日"){
		$txt->SetColor("#FF0000");
	} elseif ($data_w == "土"){
		$txt->SetColor("#0000FF");
 	} else {
		$txt->SetColor("#000000");
	}

	$txt->SetFont(FF_GOTHIC,FS_NORMAL,10);
	$graph->AddText($txt);

	$M = date("m");

	if ($M >= 1 && $M <= 3){
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_pink5.png',BGIMG_FILLFRAME);
	} elseif ($M >= 4 && $M <= 6) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_bx.png',BGIMG_FILLFRAME);
	} elseif ($M >= 7 && $M <= 8) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_bx.png',BGIMG_FILLFRAME);
	} elseif ($M >= 9 && $M <= 12) {
		$graph->SetBackgroundImage('/home/kouji/public_html/html/AutoTweet/image/denryoku_bg_new_pink5.png',BGIMG_FILLFRAME);
	}
	$graph->SetBackgroundImageMix(100);

	$graph->add($bplot);

	$bplot->SetFillColor($c);
		$bplot->SetWidth(0.6);
		$bplot->SetShadow();
		$bplot->value->SetFormat("%.1f%%");
		$bplot->value->Show();

	$graph->Stroke("output_denryoku.png");

	$img = ImageCreateFromPNG("/home/kouji/public_html/html/linuxmint/output_denryoku.png");
	$width = ImageSx($img);
	$height = ImageSy($img);
	
	$out = ImageCreateTrueColor(600,330);
	ImageCopyResampled($out, $img,
	    0,0,0,0, 600, 330, $width, $height);
	
	header('Content-Type: image/png');
	ImagePNG($out);
?>
