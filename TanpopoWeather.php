<?php
/*

	author: Kouji Sugibayashi

*/
require_once('tmhOAuth/tmhOAuth.php');
require_once('setting_awb.php');

class Date_Class {
	public function week_now(){
		//配列を使用し、要素順に(日:0〜土:6)を設定する
		$week = [
		  '日', //0
		  '月', //1
		  '火', //2
		  '水', //3
		  '木', //4
		  '金', //5
		  '土', //6
		];
		
		$date = date('w');
		
		//日本語で曜日を出力
		$answer = $week[$date];
		return($answer);
	}
}


class Moon_Class  {
//---------------------------------------------------------------------------------------------------------------
	public function moon_phase_time(){
		$kurikaeshi = 10;
		$sleeptime = 60;
		$FLAG_NM = false;
		$FLAG_FM = false;
		$FLAG_WAX = false;
		$FLAG_WAN = false;
		$MFX = 0;
		for ($count = 0; $count < $kurikaeshi; $count++){
		    for ($i = 31; $i >= 0; $i--){
			$YY = date('Y',strtotime("+".$i." day"));
			$MM = date('m',strtotime("+".$i." day"));
			$DD = date('d',strtotime("+".$i." day"));
			$HH = "00";
			$II = "00";
			$SS = "00";
			$req = "http://labs.bitmeister.jp/ohakon/api/?mode=moon_phase&year=".$YY."&month=".$MM."&day=".$DD."&hour=".$HH;
                        $xml = @simplexml_load_file($req);
                        if ($xml) {
				(float)$OhaKon["moon_phase"] = ($xml->moon_phase);
				$MF = (float)$OhaKon["moon_phase"];
				if ($MF > 180 && $MF < 360){
					$MFX = $MF;
				}
/*				echo "MFX=".$MFX."    MF=".$MF;
				echo "          FLAG_FM=";
				var_export($FLAG_FM);
				echo "          FLAG_NM=";
				var_export($FLAG_NM);
				echo "          FLAG_WAX=";
				var_export($FLAG_WAX);
				echo "          FLAG_WAN=";
				var_export($FLAG_WAN);
				echo "\n";
*/				if ((float)($MF - $MFX) < 0 && $FLAG_FM == false) {
					$YY = date('Y',strtotime("+".($i)." day"));
					$MM = date('m',strtotime("+".($i)." day"));
					$DD = date('d',strtotime("+".($i)." day"));
					$fullmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo "FM ".$OhaKon["moon_phase"]."\n";
					$FLAG_FM = true;
					$FLAG_NM = false;
				} elseif ((float)($MF - 180) > 0  && $FLAG_NM == false) {
					$YY = date('Y',strtotime("+".($i)." day"));
					$MM = date('m',strtotime("+".($i)." day"));
					$DD = date('d',strtotime("+".($i)." day"));
					$newmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo "NM ".$OhaKon["moon_phase"]."\n";
					$FLAG_FM = false;
					$FLAG_NM = true;
				}
				if  ((float)($MF - 90) <= 0 && $FLAG_WAX == false) {
					$YY = date('Y',strtotime("+".($i)." day"));
					$MM = date('m',strtotime("+".($i)." day"));
					$DD = date('d',strtotime("+".($i)." day"));
					$waxingmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo "WAXING ".$OhaKon["moon_phase"]."\n";
					$FLAG_WAX = true;
					$FLAG_WAN = false;
				} elseif ((float)($MF - 270) <= 0 && $MF > 180 && $FLAG_WAN == false) {
					$YY = date('Y',strtotime("+".($i)." day"));
					$MM = date('m',strtotime("+".($i)." day"));
					$DD = date('d',strtotime("+".($i)." day"));
					$waningmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo "WANING ".$OhaKon["moon_phase"]."\n";
					$FLAG_WAN = true;
					$FLAG_WAX = false;
				}
			} else {
				sleep($sleeptime);
			}
			sleep(0.1);
		}
		return array($newmoon,$waxingmoon,$fullmoon,$waningmoon);
	     }
	     return(NULL);
	}
//---------------------------------------------------------------------------------------------------------
	public function moon_phase_time_org(){
		$kurikaeshi = 10;
		$sleeptime = 60;
		$FLAG_NM = false;
		$FLAG_FM = false;
		for ($count = 0; $count < $kurikaeshi; $count++){
		    for ($i = 0; $i <= 24 * 31; $i++){
			$YY = date('Y',strtotime("+".$i." hour"));
			$MM = date('m',strtotime("+".$i." hour"));
			$DD = date('d',strtotime("+".$i." hour"));
			$HH = date('H',strtotime("+".$i." hour"));
			$II = "00";
			$SS = "00";
			$req = "http://labs.bitmeister.jp/ohakon/api/?mode=moon_phase&year=".$YY."&month=".$MM."&day=".$DD."&hour=".$HH;
                        $xml = @simplexml_load_file($req);
                        if ($xml) {
				(float)$OhaKon["moon_phase"] = ($xml->moon_phase);
				$MF = round((float)$OhaKon["moon_phase"]);
			//	break;
				if (($MF == 0 OR $MF == 360) && $FLAG_NM == false) {
					$newmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo $OhaKon["moon_phase"]."\n";
					$FLAG_NM = true;
				} elseif ($MF == 180 && $FLAG_FM == false) {
					$fullmoon = array($YY,$MM,$DD,$HH,$II,$SS,$MF);
					echo $YY."-".$MM."-".$DD." ".$HH.":00\n";
					echo $OhaKon["moon_phase"]."\n";
					$FLAG_FM = true;
				}
			} else {
				sleep($sleeptime);
			}
			sleep(0.1);
		}
		return array($newmoon,$fullmoon);
	     }
	     return(NULL);
	}

	public function newmoon_time(){
		for ($j = 60 * 24 * 31 * 0 ; $j <= 60 * 24 * 31 * 1; $j++){
			$YY = date('Y',strtotime("+".($j + 540)." minute"));
			$MM = date('m',strtotime("+".($j + 540)." minute"));
			$DD = date('d',strtotime("+".($j + 540)." minute"));
			$HH = date('H',strtotime("+".($j + 540)." minute"));
			$II = date('i',strtotime("+".($j + 540)." minute"));
			$SS = "00";
		
			$YYTM = date('Y',strtotime("+".($j + 1440 + 540)." minute"));
			$MMTM = date('m',strtotime("+".($j + 1440 + 540)." minute"));
			$DDTM = date('d',strtotime("+".($j + 1440 + 540)." minute"));
			$HHTM = date('H',strtotime("+".($j + 1440 + 540)." minute"));
			$IITM = date('i',strtotime("+".($j + 1440 + 540)." minute"));
			$SSTM = "00";
		
		//	echo $YY."-".$MM."-".$DD." ".$HH.":".$II.":".$SS."\n";
			
			$MATD = MoonAge($YY,$MM,$DD,$HH,$II,$SS);
			$MATM = MoonAge($YYTM,$MMTM,$DDTM,$HHTM,$IITM,$SSTM);
			$SA   = $MATM - $MATD;
		
		//	echo "MoonAgeToday =".$MATD."\n";
		//     echo "MoonAgeTomorrow =".$MATM."\n";
		// 	echo "差=".$SA."\n";
		
			if ($SA < -20){
				$YYN = date('Y',strtotime("+".($j + 1440)." minute"));
				$MMN = date('m',strtotime("+".($j + 1440)." minute"));
				$DDN = date('d',strtotime("+".($j + 1440)." minute"));
				$HHN = date('H',strtotime("+".($j + 1440)." minute"));
				$IIN = date('i',strtotime("+".($j + 1440)." minute"));
				$SSN = "00";
	//			echo "新月＝".$YYN."-".$MMN."-".$DDN." ".$HHN.":".$IIN.":".$SSN."\n";
				return array($YYN,$MMN,$DDN,$HHN,$IIN,$SSN);
			}
		}
	}
//-----------------------------------------------------------------------
	public function fullmoon_time(){
		for ($j = 60 * 24 * 31 * 0; $j <= 60 * 24 * 31 * 5; $j++){
			$YY = date('Y',strtotime("+".($j + 540)." minute"));
			$MM = date('m',strtotime("+".($j + 540)." minute"));
			$DD = date('d',strtotime("+".($j + 540)." minute"));
			$HH = date('H',strtotime("+".($j + 540)." minute"));
			$II = date('i',strtotime("+".($j + 540)." minute"));
			$SS = "00";
		
			echo "[".$j."]".$YY."-".$MM."-".$DD." ".$HH.":".$II.":".$SS."\n";
			
			$MATD = MoonAge($YY,$MM,$DD,$HH,$II,$SS);
			$SAF  = 14 - $MATD;
		
			echo "MoonAgeToday =".$MATD."\n";
			echo "差=".$SAF."\n";
			if ($SAF <= 0 && $SAF > -0.97) {
				$YYF = date('Y',strtotime("+".($j + 540)." minute"));
				$MMF = date('m',strtotime("+".($j + 540)." minute"));
				$DDF = date('d',strtotime("+".($j + 540)." minute"));
				$HHF = date('H',strtotime("+".($j + 540)." minute"));
				$IIF = date('i',strtotime("+".($j + 540)." minute"));
				$SSF = "00";
				echo "満月＝".$YYF."-".$MMF."-".$DDF." ".$HHF.":".$IIF.":".$SSF."\n";
//				echo "満月＝".$YY."-".$MM."-".$DD." ".$HH.":".$II.":".$SS."\n";
				return array($YYF,$MMF,$DDF,$HHF,$IIF,$SSF);
//				return array($YY,$MM,$DD,$HH,$II,$SS);
			}
		}
	}
//----------------------------------------------------------------------
	public function SunHatena(){
		$OhaKon = $this->StoreSunMoon("ise_sunmoon_prefs.xml");
		$time["sunrise_h"] = mb_substr($OhaKon["sunrise_hm"],0,2,"UTF-8");
		$time["sunrise_m"] = mb_substr($OhaKon["sunrise_hm"],3,2,"UTF-8");
		$time["sunset_h"]  = mb_substr($OhaKon["sunset_hm"],0,2,"UTF-8");
		$time["sunset_m"]  = mb_substr($OhaKon["sunset_hm"],3,2,"UTF-8");

		$dt = new DateTime();
		$dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
		$current_time = $dt->format('Y-m-d H:i:s');
			
		$target_time["sunrise"] = date('Y-m-d ').$time["sunrise_h"].":".$time["sunrise_m"].":00";
		$target_time["sunset"] = date('Y-m-d ').$time["sunset_h"].":".$time["sunset_m"].":00";

//	$target_time["sunrise"] = "2016-03-04 01:10:00";	
//	echo $current_time."\n".$target_time["sunrise"]."\n".$target_time["sunset"]."\n";
	
		if (strtotime($target_time["sunrise"]) <= strtotime($current_time) && strtotime($target_time["sunset"]) >= strtotime($current_time)) {
			return(1);
		} else {
			return(0);
		}
	}
//-----------------------------------------------------------------------
	public function StoreFullNewMoon() {

		$filename = '/etc/zabbix/externalscripts/F_N_Moon.xml';
		for ($j = 0; $j < 10; $j++) {
			$xml = @simplexml_load_file($filename);
			if ($xml) {
				$Moon["NewMoon_Date"]		= ($xml->pref[0]->Date);
				$Moon["NewMoon_Time"]		= ($xml->pref[0]->Time);
				$Moon["NewMoon_Wait"]		= ($xml->pref[0]->Wait);
				$Moon["WaxingMoon_Date"]	= ($xml->pref[1]->Date);
				$Moon["WaxingMoon_Time"]	= ($xml->pref[1]->Time);
				$Moon["WaxingMoon_Wait"]	= ($xml->pref[1]->Wait);
				$Moon["FullMoon_Date"]		= ($xml->pref[2]->Date);
				$Moon["FullMoon_Time"]		= ($xml->pref[2]->Time);
				$Moon["FullMoon_Wait"]		= ($xml->pref[2]->Wait);
				$Moon["WaningMoon_Date"]	= ($xml->pref[3]->Date);
				$Moon["WaningMoon_Time"]	= ($xml->pref[3]->Time);
				$Moon["WaningMoon_Wait"]	= ($xml->pref[3]->Wait);
				$Moon["time"]			= ($xml->pref[4]->time);
				return($Moon);

			} else {
				sleep(10);
			}
		}
		return(NULL);	
	
	}
//-------------------------------------------------------------------------------
	public function StoreSunMoon($place) {

		$filename = '/etc/zabbix/externalscripts/'.$place;
		for ($j = 0; $j < 10; $j++) {
			$xml = @simplexml_load_file($filename);
			if ($xml) {
				$OhaKon["sunrise_hm"]	= ($xml->pref[0]->sunrise_hm);
				$OhaKon["sunset_hm"]	= ($xml->pref[0]->sunset_hm);
				$OhaKon["moonrise_hm"]	= ($xml->pref[0]->moonrise_hm);
				$OhaKon["moonset_hm"]   = ($xml->pref[0]->moonset_hm);
				$OhaKon["moon_age"]	= ($xml->pref[0]->moon_age);
				$OhaKon["moon_phase"]   = ($xml->pref[0]->moon_phase);
//				$OhaKon["fullmoon_wait"]= ($xml->pref[0]->fullmoon_wait);
//				$OhaKon["newmoon_wait"]	= ($xml->pref[0]->newmoon_wait);
				$OhaKon["tide_name"]	= ($xml->pref[0]->tide_name);
				$OhaKon["time"]		= ($xml->pref[1]->time);
				return($OhaKon);

			} else {
				sleep(10);
			}
		}
		return(NULL);	
	
	}
//-------------------------------------------------------------------------------
	public function shiomei($moonage) {

		if ($moonage == "--"){
			return("--");
		}
		$moonage = round((float)($moonage),0);
	//	$moonage = floor((float)($moonage));

		if       ($moonage >= 29 || $moonage <= 2) {
			$shio = "大潮";
		} elseif ($moonage >= 3  && $moonage <= 6) {
			$shio = "中潮";
		} elseif ($moonage >= 7  && $moonage <= 9) {
			$shio = "小潮";
		} elseif ($moonage == 10) {
			$shio = "長潮";
		} elseif ($moonage == 11) {
			$shio = "若潮";
		} elseif ($moonage >= 12 && $moonage <= 13) {
			$shio = "中潮";
		} elseif ($moonage >= 14 && $moonage <= 17) {
			$shio = "大潮";
		} elseif ($moonage >= 18 && $moonage <= 21) {
			$shio = "中潮";
		} elseif ($moonage >= 22 && $moonage <= 24) {
			$shio = "小潮";
		} elseif ($moonage == 25) {
			$shio = "長潮";
		} elseif ($moonage == 26) {
			$shio = "若潮";
		} elseif ($moonage >= 27 && $moonage <= 28) {
			$shio = "中潮";
		} else {
			$shio = "？潮";
		}

		return ($shio);	
	}

//---------------------------------------------------------------------
	public function ohakon($LAT,$LON) {

		$kurikaeshi = 10;
		$sleeptime = 30;
		$now_hour = (int)date('H') + (float)round((date('i') / 60),2);

		for ($count = 0; $count < $kurikaeshi; $count++){
			$req = "http://labs.bitmeister.jp/ohakon/api/?mode=sun_moon_rise_set&year=".date('Y')."&month=".date('n')."&day=".date('j')."&lat=".$LAT."&lng=".$LON;
                        $xml = @simplexml_load_file($req);
                        if ($xml) {
//				var_dump($xml);
				$OhaKon["sunrise"]	= ($xml->rise_and_set->sunrise);
				$OhaKon["sunset"]	= ($xml->rise_and_set->sunset);
				$OhaKon["sunrise_hm"]	= ($xml->rise_and_set->sunrise_hm);
				$OhaKon["sunset_hm"]	= ($xml->rise_and_set->sunset_hm);
				$OhaKon["moonrise"]	= ($xml->rise_and_set->moonrise);
				$OhaKon["moonset"]	= ($xml->rise_and_set->moonset);
				$OhaKon["moonrise_hm"]	= ($xml->rise_and_set->moonrise_hm);
				$OhaKon["moonset_hm"]	= ($xml->rise_and_set->moonset_hm);
				$OhaKon["moon_age"]	= ($xml->moon_age);
				break;
				
			} else {
				sleep($sleeptime);
			}
		}
		if ($count == $kurikaeshi) {
			return (NULL);
		}

		for ($count = 0; $count < $kurikaeshi; $count++){
			$req = "http://labs.bitmeister.jp/ohakon/api/?mode=moon_phase&year=".date('Y')."&month=".date('n')."&day=".date('j')."&hour=".$now_hour;
                        $xml = @simplexml_load_file($req);
                        if ($xml) {
				$OhaKon["moon_phase"] = ($xml->moon_phase);
				break;
			} else {
				sleep($sleeptime);
			}
		}
		if ($count == $kurikaeshi) {
			return (NULL);
		}
		/*
	
		$days = 31;
		for ($count = 0; $count < $kurikaeshi; $count++){
			$req = "http://labs.bitmeister.jp/ohakon/api/?mode=moon_age&year=".date('Y')."&month=".date('n')."&day=".date('j')."&days=".$days;
                        $xml = @simplexml_load_file($req);
                        if ($xml) {
//				var_dump($xml);
//				$OhaKon["fullmoon_wait"] = NULL;
//				$OhaKon["newmoon_wait"] = NULL;

				for ($j = 0; $j < $days; $j++){
					$moon_data = ($xml->moon_age[$j]);
					$MOON = (float)($moon_data);
	//				echo $moon_data."\n";
//			echo $moon_data.",".$MOON."\n";
					if ($MOON >= 13.8 && $MOON <= 15.8) {
						$OhaKon["fullmoon_wait"] = $j;
					} elseif ($MOON >= 29 || ($MOON > 0 && $MOON <= 0.5)) {
						$OhaKon["newmoon_wait"]  = $j;
					}
				}
				if ($OhaKon["fullmoon_wait"] == NULL){
					$OhaKon["fullmoon_wait"] = "--";
				}
				if ($OhaKon["newmoon_wait"] == NULL){
					$OhaKon["newmoon_wait"] = "--";
				}

				if ($OhaKon["moon_age"] < 0 || $OhaKon["moon_age"] > 30) {
					$OhaKon["moon_age"] = "--";
				}
				return($OhaKon);
			} else {
				sleep($sleeptime);
			}
		}
*/
		return ($OhaKon);

	}
}


class Denryoku_Class {

	public function get_holidays() {
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
	
	    return $holidays; 
}

	public function compare_time() {
		$filename = '/etc/zabbix/externalscripts/power_prefs.xml';
		$xml = @simplexml_load_file($filename);
		if ($xml) {
			$time_Ymd = ($xml->pref[10]->time_Ymd);
			$time_His = ($xml->pref[10]->time_His);
		}	
	}


	public function StoreDenryoku() {

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
				$TT = new Twitter_Class;	
				$day = $TT->rekijitsu_3($Y,$M,$D,$H,$I,$S);

				
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
//----------------------------------------------------------------------------------
	public function Denryoku($power_company) {
	
		$data = NULL;
		$kurikaeshi = 3;
		$sleeptime = 60;
		for ($count = 0; $count < $kurikaeshi; $count++){
			setlocale(LC_ALL, 'ja_JP.UTF-8');
			switch ($power_company) {
				case "Hokkaido":
					$file = "http://denkiyoho.hepco.co.jp/area/data/juyo_01_".date("Ymd").".csv";
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Tohoku":
					$file = 'https://setsuden.nw.tohoku-epco.co.jp/common/demand/juyo_02_'.date("Ymd").'.csv';
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Tokyo":
					$file = 'https://www.tepco.co.jp/forecast/html/images/juyo-d-j.csv';
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Chubu":
					$file = 'http://powergrid.chuden.co.jp/denki_yoho_content_data/juyo_cepco003.csv';
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Hokuriku":
					$file = 'http://www.rikuden.co.jp/nw/denki-yoho/csv/juyo_05_'.date("Ymd").'.csv';
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Kansai":
					$file = "https://www.kansai-td.co.jp/yamasou/juyo1_kansai.csv";
					$start_dat = 58;
					$end_dat = 345;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Chugoku":
					$file = "https://www.energia.co.jp/nw/jukyuu/sys/juyo_07_".date("Ymd").".csv";
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Shikoku":
					$file = "https://www.yonden.co.jp/nw/denkiyoho/juyo_shikoku.csv";
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Kyushu":
					$file = "https://www.kyuden.co.jp/td_power_usages/csv/juyo-hourly-".date("Ymd").".csv";
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				case "Okinawa":
					$file = 'https://www.okiden.co.jp/denki2/juyo_10_'.date("Ymd").".csv";
					$start_dat = 55;
					$end_dat = 342;
					$capacity_place = 2;
					$yosou_place = 5;
					break;
				default:
					echo "ERROR-0";
					exit;
			}
					
			if ($data = @file_get_contents($file)){
				$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
				$temp = tmpfile();
				$csv  = array();
				 
				fwrite($temp, $data);
				rewind($temp);
				 
				while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
				    $csv[] = $data;
				}
				fclose($temp);
//		var_dump($csv);
					for($i = $start_dat; $i <= $end_dat; $i++){
						if ($csv[$i][2] != False ){
							$recent["NUM"]  = $i;
							$recent["DATE"] = $csv[$i][0];
							$recent["TIME"] = $csv[$i][1];
							$recent["DATA"] = $csv[$i][2];
							if ($i <> $start_dat) {
								$recent["OLD_DATA"] = $csv[$i-1][2];
							}
						}
					}
	
				if ($csv[$capacity_place][0] && $csv[$yosou_place][0] && $recent["DATA"]){
					$den["capacity"] = $csv[$capacity_place][0];
					$den["yosou"]    = $csv[$yosou_place][0];
					$den["usage"]    = $recent["DATA"];
					$den["den_per"]  = round((float)($den["usage"] / $den["capacity"]) * 100,1);
					$den["DATE"] 	 = $recent["DATE"];
					$den["OLD_DATA"] = $recent["OLD_DATA"];
							
					if ($recent["OLD_DATA"] == $recent["DATA"]) {
						$den["up_down"] = "＝";
					} elseif ($recent["OLD_DATA"] < $recent["DATA"]) {
						$den["up_down"] = "△";
						if (($recent["OLD_DATA"] - $recent["DATA"]) <= -10) {
							$den["up_down"] = "▲";
						}
					} else {
						$den["up_down"] = "▽";
						if (($recent["OLD_DATA"] - $recent["DATA"]) >= 10) {
							$den["up_down"] = "▼";
						}
					}
					
					$den["time"]     = sprintf("%05s",$recent["TIME"]);
					$den["now"]	 = date("Y-m-d H:i"); 

					$pieces = explode("/",$den["DATE"]);
					$den["DATE"] 	 = sprintf("%4d",$pieces[0])."-".sprintf("%02d",$pieces[1])."-".sprintf("%02d",$pieces[2]);
					$den["timeX"]	 = $den["DATE"]." ".$den["time"];
					$from = $den["timeX"];
					$to   = $den["now"];
					// 日時からタイムスタンプを作成
					$fromSec = strtotime($from);
					$toSec   = strtotime($to);
					 
					// 秒数の差分を求める
					$differences = $toSec - $fromSec;
					 
					// フォーマットする
					$result = gmdate("H", $differences);
					if ($result >= 1) {
						$den["alive"] = "bad";
					} else {
						$den["alive"] = "good";
					}

				} else {
					echo "Falseのデータがありました。（Error)\n\n";
					echo $power_company."\n";
					$den["alive"] = "bad";

					exit;
				} 
				
				if ($den["den_per"] > 110 || $den["den_per"] == 0) {
					echo "電力使用率が110％より大きいか、0％です。（Error）";
					$den["alive"] = "bad";
					exit;
				}

				foreach ($den as $key => $value){
					if ($den[$key] == NULL || $den[$key] == false || strlen($den[$key]) == 0){
						echo "Falseのデータがありましたので、何もせず終了します。（Error）\n\n";
						echo $power_company."\n";
						var_dump($den);
						echo "\n\n".$den[$key];
						$den["alive"] = "bad";
						exit;
					}
				}
				return($den);
			} else {
				if(count($http_response_header) > 0){
				        //「$http_response_header[0]」にはステータスコードがセットされているのでそれを取得
				        $status_code = explode(' ', $http_response_header[0]);  //「$status_code[1]」にステータスコードの数字のみが入る
				 
				        //エラーの判別
				        switch($status_code[1]){
				            //404エラーの場合
				            case 404:
					        echo "指定したページが見つかりませんでした";
						$den["capacity"] = 0;
						$den["yosou"]    = 0;
						$den["usage"]    = 0;
						$den["den_per"]  = 0;
						$den["DATE"] 	 = "休止中";
						$den["OLD_DATA"] = "";
						$den["alive"]	 = "bad";
						$den["time"]     = "";
						$den["now"]	 = date("Y-m-d H:i"); 
						$den["timeX"]	 = "";
						$den["up_down"] = "";
					 	return($den);
				                break;
				 
				            //500エラーの場合
				            case 500:
				                echo "指定したページがあるサーバーにエラーがあります";
				                break;
				 
				            //その他のエラーの場合
				            default:
				                echo "何らかのエラーによって指定したページのデータを取得できませんでした";
				        }
				    }else{
				        //タイムアウトの場合 or 存在しないドメインだった場合
				        echo "タイムエラー or URLが間違っています";
				    }
				sleep($sleeptime);
			}
		}
		echo "ERROR EXIT";
		exit;	
	}	
//------------------------------------------------------------------------------------------------
	public function Denryoku9($D_place2) {
		$xml = NULL;
		$kurikaeshi = 10;
		$sleeptime = 30;
	
		for ($count = 0; $count < $kurikaeshi; $count++){
			$json = @file_get_contents("https://elecwarn.kuropen.org/json");
			if ($json) {
				$obj = json_decode($json);
				for ($place = 0;$place < 9;$place++){
					if ($obj[$place]->company == $D_place2){
						$den = array();
						$den["usage"] = $obj[$place]->consume;
						$den["capacity"] = $obj[$place]->capacity;
						$den["den_per"] = round((float)($den["usage"] / $den["capacity"]) * 100,1);
						return($den);
					}
				}
				return(NULL);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}
	
}
//-------------------------------------------------------------------------

class Twitter_Class {
	public function file_read($url){
		$fp = fopen($url,'r');
		if($fp == false) return("");
		while (!feof($fp)) {
			$mojiget = fgets($fp);
		}
		fclose($fp);
		return($mojiget);
	}
	
	public function file_read_2($url2){
		$fp = fopen($url2,'r');
		if($fp == false) return("");
		while (!feof($fp)) {
			$mojiget2 = fgets($fp);
			$pos = strpos($mojiget2,'日');
			if ($pos <> ""){
				$p = strpos($mojiget2,'秒');
				$hozon_moji = substr($mojiget2,0,$p + 3);
			}
		}
		fclose($fp);
		return ($hozon_moji);
	}

	public function get_uptime_raspi($host){
		$kurikaeshi = 10;
		$sleeptime = 30;
		$hozon = NULL; 
		for ($count = 0; $count < $kurikaeshi; $count++){
			$hozon = @$this->file_read_2("http://$host/get-uptime.php");
			if ($hozon){
				return($hozon);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}
	public function get_uptime_me(){
		$kurikaeshi = 10;
		$sleeptime = 30;
		$hozon = NULL; 
		for ($count = 0; $count < $kurikaeshi; $count++){
			$hozon = @$this->file_read_2("http://localhost/~kouji/html/uptime/get-uptime.php");
			if ($hozon){
				return($hozon);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}

	public function get_uptime(){
		$kurikaeshi = 10;
		$sleeptime = 30;
		$hozon = NULL; 
		for ($count = 0; $count < $kurikaeshi; $count++){
			$hozon = @$this->file_read_2("http://linuxparadise.net/uptime/get-uptime.php");
			if ($hozon){
				return($hozon);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}
	public function rekijitsu_3($Y,$M,$D,$H,$I,$S){
	
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
	public function rekijitsu($Y,$M,$D,$H,$I,$S){
	
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

		$A = $this->rekijitsu_2($Y,$M,$D,$H,$I,$S);

		if ($A < 0) {
			$day["days"]	= "0000日";
			$day["y"]	= "00年";
			$day["m"]	= "00ヶ月";
			$day["d"] 	= "00日";
			$day["h"] 	= "00時間";
			$day["i"]	= "00分";
			$day["s"]	= "00秒";
		}
		
		return($day);
	}

	public function rekijitsu_2($Y,$M,$D,$H,$I,$S){
		$date1 = date("Y/m/d h:i:s");
		$date2 = $Y."/".$M."/".$D." ".$H.":".$I.":".$S; 
		
		$daydiff1 = (strtotime($date2)-strtotime($date1))/(3600*24);
		
		return($daydiff1);
}

	public function upload_image(&$twitter,$image) {
	
		foreach ($image as $key => $value) {
		
			$kurikaeshi = 10;
			$sleeptime = 10;
		
			for ($count = 0; $count < $kurikaeshi; $count++){
				$GETS = @file_get_contents($value);
				if ($GETS) {
					break;
				} else {
					sleep($sleeptime);
				}			
			}
			if ($GETS) {
				$upload_image = base64_encode($GETS);
				$update_img_params = array(
					'media_data'    =>  $upload_image,
				);
				$code_img = $twitter->request( 'POST', "https://upload.twitter.com/1.1/media/upload.json", $update_img_params, true, true);
				$result_img = json_decode($twitter->response["response"], true,512,JSON_BIGINT_AS_STRING);
				$result_media_id[$key] = $result_img['media_id'];
			} else {
				return(NULL);
			}
		}
		return($result_media_id);
	}
}
//-------------------------------------------------------------------------------------
class Weather_Class {
function rain_yahoo($LAT,$LON,$z,$w,$h){

	global $Yahoo_Dev_Key;
	$rain_yahoo_img = "https://map.yahooapis.jp/map/V1/static?appid=".$Yahoo_Dev_Key."&lat=".$LAT."&lon=".$LON."&z=".$z."&width=".$w."&height=".$h."&overlay=type:rainfall|date:".date("YmdHi")."00|datelabel:on";

	return($rain_yahoo_img);

}

	public function discomfort_index ($fukai) {
		if ($fukai == NULL || $fukai == 0 || strlen($fukai) == 0) {
			$data = "?";
		} elseif ($fukai <= 55) {
			$data = "寒い";
		} elseif ($fukai > 55 && $fukai <= 60) {
			$data = "肌寒い";
		} elseif ($fukai > 60 && $fukai <= 65) {
			$data = "何も感じない";
		} elseif ($fukai > 65 && $fukai <= 70) {
			$data = "快い";
		} elseif ($fukai > 70 && $fukai <= 75) {
			$data = "暑くない";
		} elseif ($fukai > 75 && $fukai <= 80) {
			$data = "やや暑い";
		} elseif ($fukai > 80 && $fukai <= 85) {
			$data = "暑くて汗が出る";
		} elseif ($fukai > 85) {
			$data = "暑くてたまらない";
		} else {
			$data = "?";
		}
		return($data);
	} 
		
	public function openweathermap_forecast() {
//------------------------------------------------------------------------
		$today = strtotime("now");
		$tomorrow = strtotime("+1 day");
		$dayaftertomorrow = strtotime("+2 day");
//------------------------------------------------------------------------
		$xml = NULL;
		$kurikaeshi = 10;
		$sleeptime = 30;
		global $LAT,$LON,$APPID_OWM,$DAYNAME;
		for ($count = 0; $count < $kurikaeshi; $count++){
			$req = "http://api.openweathermap.org/data/2.5/forecast/daily?lat=".$LAT."&lon=".$LON."&mode=xml&units=metric&cnt=7&appid=".$APPID_OWM;
			$xml = @simplexml_load_file($req);
			if ($xml) {
				$owm = array();
				for ($dd = 0; $dd <= 4; $dd++){
					$owm["day"] = ($xml->forecast->time->{$dd}->attributes()->day);
					$owm["day_y"] = mb_strcut($owm["day"],0,4,"UTF-8");
					$owm["day_m"] = mb_strcut($owm["day"],5,2,"UTF-8");
					$owm["day_d"] = mb_strcut($owm["day"],8,2,"UTF-8");
			
					if ((date('d',$today) == $owm["day_d"]) AND ($DAYNAME == "TODAY")) {
						$day_set = $dd;
						break;
					} elseif ((date('d',$tomorrow) == $owm["day_d"]) AND ($DAYNAME == "TOMORROW")) {
						$day_set = $dd;
						break;
					} elseif ((date('d',$dayaftertomorrow) == $owm["day_d"]) AND ($DAYNAME == "DAY_AFTER_TOMORROW")) {
						$day_set = $dd;
						break;
					} elseif ($dd == 4){
						echo "ERROR OWM!";
						exit;
					}
				}
				if ($owm["day_d"] == date('d',$dayaftertomorrow)) {
					$owm["day_moji"] = "明後日";
				} elseif ($owm["day_d"] == date('d',$tomorrow)){
					$owm["day_moji"] = "明日";
				} elseif ($owm["day_d"] == date('d',$today)) {
					$owm["day_moji"] = "今日";
				} else {
					$owm["day_moji"] = "";
				} 
			
				$datetime = new DateTime();
				$datetime->setDate($owm["day_y"], $owm["day_m"], $owm["day_d"]);
				$week = array("日", "月", "火", "水", "木", "金", "土");
				$w = (int)$datetime->format('w');
				$owm["day_jp"] = $owm["day_y"]."年".$owm["day_m"]."月".$owm["day_d"]."日〔".$week[$w]."〕";
		
				$owm["place"] = ($xml->location->name);
				echo "OpenWeatherMap Place = ".$owm["place"];	
				$owm["temp_min"] = round((float)($xml->forecast->time->{$day_set}->temperature->attributes()->min),1);
				$owm["temp_max"] = round((float)($xml->forecast->time->{$day_set}->temperature->attributes()->max),1);

					if ($owm["temp_min"] == "-0"){
					        $owm["temp_min"] = "0";
					}
			        	if ($owm["temp_max"] == "-0"){
						$owm["temp_max"] = "0";
                                	}

				$owm["humidity"] = ($xml->forecast->time->{$day_set}->humidity->attributes()->value);
		
				$owm["pressure"] = round((float)($xml->forecast->time->{$day_set}->pressure->attributes()->value),1);
		
				$owm["clouds_value"] = ($xml->forecast->time->{$day_set}->clouds->attributes()->all);
		
				$owm["windSpeed"] = round((float)($xml->forecast->time->{$day_set}->windSpeed->attributes()->mps),1);
				$owm["precipitation"] = round((float)($xml->forecast->time->{$day_set}->precipitation->attributes()->value) / 3,1);
				$owm["description"] = $this->ryusWheatherDescription((int)($xml->forecast->time->{$day_set}->symbol->attributes()->number));
				$owm["kazamuki"] = $this->ryusWehatherWindDigree($xml->forecast->time->{$day_set}->windDirection->attributes()->deg);
				$owm["precipitation_data"] = ($xml->forecast->time->{$day_set}->precipitation->attributes()->type);
				$owm_icon = str_replace('n','d',($xml->forecast->time->{$day_set}->symbol->attributes()->var));
			/*	$SunMoon = new Moon_Class;
				$Sun = $SunMoon->SunHatena();
				if (!$Sun) {
					$owm_icon = str_replace('d','n',$owm_icon);
				}*/
				$owm["weather_icon"] = $this->trans_weather_icon($owm_icon);
				return($owm);
			} else {
				sleep($sleeptime);
			}	
		}
		return(NULL);
	}
//----------------------------------------------------------------------------------
	public function wunderground_forecast() {
		$dayaftertomorrow = strtotime("+2 day");
		$tomorrow = strtotime("+1 day");
		$today = strtotime("now");
		$xml = NULL;
		$kurikaeshi = 10;
		$sleeptime = 90;
		global $LAT,$LON,$APPID_WG,$DAYNAME;
		for ($count = 0; $count < $kurikaeshi; $count++){
			$req = "http://api.wunderground.com/api/".$APPID_WG."/forecast/lang:JP/q/".$LAT.",".$LON.".xml";
			$xml = @simplexml_load_file($req);
			if ($xml) {
				$wg = array();
				for ($dd = 0; $dd <= 4; $dd++){
					$wg["day_y_wg"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$dd}->date->year);
					$wg["day_m_wg"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$dd}->date->month);
					$wg["day_d_wg"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$dd}->date->day);
					if (strlen($wg["day_m_wg"]) == 1) {
						$wg["day_m_wg"] = "0".$wg["day_m_wg"];
					}
					if (strlen($wg["day_d_wg"]) == 1) {
						$wg["day_d_wg"] = "0".$wg["day_d_wg"];
					}
					if       ((date('d',$today) == $wg["day_d_wg"]) AND ($DAYNAME == "TODAY")) {
						$day_set = $dd;
						break;
					} elseif ((date('d',$tomorrow) == $wg["day_d_wg"]) AND ($DAYNAME == "TOMORROW")) {
						$day_set = $dd;
						break;
					} elseif ((date('d',$dayaftertomorrow) == $wg["day_d_wg"]) AND ($DAYNAME == "DAY_AFTER_TOMORROW")) {
						$day_set = $dd;
						break;
					} elseif ($dd == 4) {
						echo "ERROR WG!";
						exit;
					}	
				}
//			var_dump($xml);
				$wg["temp_high_wg"] = round((float)(5 / 9) * (($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->high->fahrenheit) - 32) ,1);
				$wg["temp_low_wg"] =  round((float)(5 / 9) * (($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->low->fahrenheit) - 32) ,1);
				$wg["humidity_wg"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->avehumidity);
				$wg["conditions_wg"] = $this->trans_condition($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->conditions);
	
				$wg["kazamuki"] = $this->ryusWehatherWindDigree($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->avewind->degrees);
				$wg["windspeed"] =  round(0.44704 * (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->avewind->mph),1);

				$wg["max_kazamuki"] = $this->ryusWehatherWindDigree($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->maxwind->degrees);
				$wg["max_windspeed"] =  round(0.44704 * (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->maxwind->mph),1);

				$wg["qpf_allday"] =  (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->qpf_allday->mm);
				$wg["snow_allday"] =  (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->snow_allday->cm);

				$wg["icon_url"] =  $this->trans_weather_icon_7($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->icon_url);
				if ($wg["icon_url"] == "http://icons.wxug.com/i/c/k/.gif" || $wg["icon_url"] == "http://icons.wxug.com/i/c/k/nt_.gif"){
			//		$wg["icon_url"] = "http://linuxparadise.net/wp-content/uploads/2016/10/question_blue.jpg";
					$wg["icon_url"] = NULL;
				}
				$wg["pop"] =  ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->pop);

				$wg["temp_high_wg_X"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->high->fahrenheit);
				$wg["temp_low_wg_X"] =  ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->low->fahrenheit);
				$wg["windspeed_X"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->avewind->mph);
				$wg["max_windspeed_X"] = ($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->maxwind->mph);
				$wg["qpf_allday_X"] =  (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->qpf_allday->mm);
				$wg["snow_allday_X"] =  (float)($xml->forecast->simpleforecast->forecastdays->forecastday->{$day_set}->snow_allday->cm);

				if ($wg["day_d_wg"] == date('d',$dayaftertomorrow)) {
					$wg["day_moji"] = "明後日";
				} elseif ($wg["day_d_wg"] == date('d',$tomorrow)){
					$wg["day_moji"] = "明日";
				} elseif ($wg["day_d_wg"] == date('d',$today)) {
					$wg["day_moji"] = "今日";
				} else {
					$wg["day_moji"] = "";
				} 
			
				$datetime2 = new DateTime();
				$datetime2->setDate((int)$wg["day_y_wg"],(int)$wg["day_m_wg"],(int)$wg["day_d_wg"]);
				$week = array("日", "月", "火", "水", "木", "金", "土");
				$w = (int)$datetime2->format('w');
				$wg["day_jp"] = $wg["day_m_wg"]."月".$wg["day_d_wg"]."日〔".$week[$w]."〕";
//				var_dump($wg);

				if (strlen($wg["temp_high_wg_X"]) == 0) {
					$wg["temp_high_wg"] = "?";
				} 
				if (strlen($wg["temp_low_wg_X"]) == 0) {
					$wg["temp_low_wg"] = "?";
				} 
				if (strlen($wg["windspeed_X"]) == 0) {
					$wg["windspeed"] = "?";
				} 
				if (strlen($wg["max_windspeed_X"]) == 0) {
					$wg["max_windspeed"] = "?";
				} 
				if (strlen($wg["qpf_allday_X"]) == 0) {
					$wg["qpf_allday"] = "?";
				} 
				if (strlen($wg["snow_allday_X"]) == 0) {
					$wg["snow_allday"] = "?";
				}
				if ($wg["humidity_wg"] == 0) {
					$wg["humidity_wg"] = "?";
				}
				return($wg);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}
//---------------------------------------------------------------------------------
	public function ryusWheatherDescription($id){
	    $description = array();
	    $description[200] = '小雨と雷雨';
	    $description[201] = '雨と雷雨';
	    $description[202] = '大雨と雷雨';
	    $description[210] = '雷雨【弱】';
	    $description[211] = '雷雨';
	    $description[212] = '雷雨【強】';
	    $description[221] = 'ぼろぼろの雷雨';
	    $description[230] = '霧雨と雷雨【弱】';
	    $description[231] = '霧雨と雷雨';
	    $description[232] = '霧雨と雷雨【強】';
	    $description[300] = '霧雨【弱】';
	    $description[301] = '霧雨';
	    $description[302] = '霧雨【強】';
	    $description[310] = '霧雨の雨【弱】';
	    $description[311] = '霧雨の雨';
	    $description[312] = '霧雨の雨【強】';
	    $description[313] = 'にわか雨と霧雨';
	    $description[314] = 'にわか雨と霧雨【強】';
	    $description[321] = 'にわか霧雨';
	    $description[500] = '小雨';
	    $description[501] = '適度な雨';
	    $description[502] = '雨【強】';
	    $description[503] = '非常に激しい雨';
	    $description[504] = '極端な雨';
	    $description[511] = '雨氷';
	    $description[520] = 'にわか雨【弱】';
	    $description[521] = 'にわか雨';
	    $description[522] = 'にわか雨【強】';
	    $description[531] = '不規則なにわか雨';
	    $description[600] = '小雪';
	    $description[601] = '雪';
	    $description[602] = '大雪';
	    $description[611] = 'みぞれ';
	    $description[612] = 'にわかみぞれ【弱】';
	    $description[613] = 'にわかみぞれ';
	    $description[615] = '雨や雪【弱】';
	    $description[616] = '雨や雪';
	    $description[620] = 'にわか雪【弱】';
	    $description[621] = 'にわか雪';
	    $description[622] = 'にわか雪【強】';
	    $description[701] = 'ミスト';
	    $description[711] = '煙';
	    $description[721] = 'ヘイズ';
	    $description[731] = '砂、ほこり旋回する';
	    $description[741] = '霧';
	    $description[751] = '砂';
	    $description[761] = 'ほこり';
	    $description[762] = '火山灰';
	    $description[771] = 'スコール';
	    $description[781] = '竜巻';
	    $description[800] = '晴天';
	    $description[801] = '薄い雲';
	    $description[802] = '千切れ雲';
	    $description[803] = '曇りがち';
	    $description[804] = '厚い雲';
	    if (array_key_exists($id, $description) === false) {
	        return $id;
	    }
	    return $description[$id];
	}

	public function trans_weather_icon($icon){

		$icon_img_owm = "http://openweathermap.org/img/w/";
		$icon_img_thanks = "http://linuxparadise.net/weather_png/";

		switch ($icon) {
			case "01d":
				$icon = "weather004.png";
				break;
			case "02d":
				$icon = "weather001.png";
				break;
			case "03d":
				$icon = "weather015.png";
				break;
			case "04d":
				$icon = "weather016.png";	
				break;
			case "09d":
				$icon = "weather018.png";	
				break;
			case "10d":
				$icon = "weather018.png";	
				break;
			case "11d":
				$icon = "weather006.png";	
				break;
			case "13d":
				$icon = "weather007.png";	
				break;
			case "50d":
				$icon = "weather009.png";	
				break;
			case "01n":
				$icon = "weather027.png";	
				break;
			case "02n":
				$icon = "weather029.png";	
				break;
			case "03n":
				$icon = "weather015.png";	
				break;
			case "04n":
				$icon = "weather016.png";	
				break;
			case "09n":
				$icon = "weather018.png";	
				break;
			case "10n":
				$icon = "weather018.png";	
				break;
			case "11n":
				$icon = "weather006.png";	
				break;
			case "13n":
				$icon = "weather007.png";	
				break;
			case "50n":
				$icon = "weather009.png";
				break;
			default:
				$icon = $icon_img_owm.$icon.".png";
				return($icon);
		}
	
		$icon = $icon_img_thanks.$icon;
		return($icon);
	}
//------------------------------------------------------------------------
	public function trans_condition($condition){
		switch ($condition){
			case "晴":
				$con = "晴天";
				break;
			case "曇":
				$con = "曇天";
				break;
			case "雨":
				$con = "雨天";
				break;
			default:
				$con = $condition;
		}

		$con = str_replace("（","【",$con);
		$con = str_replace("）","】",$con);

		return($con);
	}
//-----------------------------------------------------------------------	
	
	public function trans_weather_icon_7($icon_url){

		$filename = strrchr($icon_url, "/");
		$filename = substr($filename, 1);

		$icon_img_wg = "http://icons.wxug.com/i/c/k/";
		$icon_img_thanks = "http://linuxparadise.net/weather_png/";

		switch ($filename) {
			case "clear.gif":
				$icon = "weather004.png";
				break;
			case "sunny.gif":
				$icon = "weather004.png";
				break;
			case "mostlycloudy.gif":
				$icon = "weather001.png";
				break;
			case "mostlysunny.gif":
				$icon = "weather001.png";
				break;
			case "partlycloudy.gif":
				$icon = "weather001.png";
				break;
			case "partlysunny.gif":
				$icon = "weather001.png";
				break;
			case "chancerain.gif":
				$icon = "weather018.png";
				break;
			case "rain.gif":
				$icon = "weather018.png";
				break;
			case "fog.gif":
				$icon = "weather009.png";
				break;
			case "hazy.gif":
				$icon = "weather009.png";
				break;
			case "cloudy.gif":
				$icon = "weather015.png";	
				break;
			case "chancesleet.gif":
				$icon = "weather008.png";	
				break;
			case "sleet.gif":
				$icon = "weather008.png";	
				break;
			case "chancetstorms.gif":
				$icon = "weather006.png";	
				break;
			case "tstorms.gif":
				$icon = "weather006.png";	
				break;
			case "chanceflurries.gif":
				$icon = "weather007.png";	
				break;
			case "chancesnow.gif":
				$icon = "weather007.png";	
				break;
			case "flurries.gif":
				$icon = "weather007.png";	
				break;
			case "snow.gif":
				$icon = "weather007.png";	
				break;
//------------------------------------------------------------------------
			case "nt_clear.gif":
				$icon = "weather027.png";	
				break;
			case "nt_sunny.gif":
				$icon = "weather027.png";	
				break;
			case "nt_mostlycloudy.gif":
				$icon = "weather029.png";	
				break;
			case "nt_mostlysunny.gif":
				$icon = "weather029.png";	
				break;
			case "nt_partlycloudy.gif":
				$icon = "weather029.png";	
				break;
			case "nt_partlysunny.gif":
				$icon = "weather029.png";	
				break;
			case "nt_chancerain.gif":
				$icon = "weather018.png";
				break;
			case "nt_rain.gif":
				$icon = "weather018.png";
				break;
			case "nt_fog.gif":
				$icon = "weather009.png";
				break;
			case "nt_hazy.gif":
				$icon = "weather009.png";
				break;
			case "nt_cloudy.gif":
				$icon = "weather015.png";	
				break;
			case "nt_chancesleet.gif":
				$icon = "weather008.png";	
				break;
			case "nt_sleet.gif":
				$icon = "weather008.png";	
				break;
			case "nt_chancetstorms.gif":
				$icon = "weather006.png";	
				break;
			case "nt_tstorms.gif":
				$icon = "weather006.png";	
				break;
			case "nt_chanceflurries.gif":
                                $icon = "weather007.png";
                                break;
                        case "nt_chancesnow.gif":
                                $icon = "weather007.png";
                                break;
                        case "nt_flurries.gif":
                                $icon = "weather007.png";
                                break;
                        case "nt_snow.gif":
                                $icon = "weather007.png";
                                break;

			default:
				$icon = $icon_img_wg.$filename;
				return($icon);
		}
	
		$icon = $icon_img_thanks.$icon;
		return($icon);
	}

	public function trans_weather_icon_2($icon){
		
		$icon_img_owm = "http://openweathermap.org/img/w/";
		$icon_img_thanks = "http://linuxparadise.net/weather_png/";
	
		switch ($icon) {
			case "01n":
				$icon = $icon_img_thanks."weather026.png";	
				break;
			case "02n":
				$icon = $icon_img_thanks."weather028.png";	
				break;
			case "10d":
				$icon = $icon_img_thanks."weather008.png";	
				break;
			case "10n":
				$icon = $icon_img_thanks."weather008.png";	
				break;
			default:
				$icon = $icon_img_owm.$icon.".png";
		}
		return($icon);
	}
	
	public function trans_weather_icon_5($icon_url){
		
		$filename = strrchr($icon_url, "/");
		$filename = substr($filename, 1);
	
		$icon_img_thanks = "http://linuxparadise.net/weather_png/";
		switch ($filename) {
			case "clear.gif":
				$icon = $icon_img_thanks."weather004.png";	
				break;
			default:
				$icon = $icon_url;
		}
		return($icon);
	}
	//-------------------------------------------------------------------------
	public function openweathermap(){
		global $LAT,$LON,$APPID_OWM;
		$xml = NULL;
		$kurikaeshi = 10;
		$sleeptime = 30;
		for ($count=0; $count < $kurikaeshi; $count++){
			$req1 = "http://api.openweathermap.org/data/2.5/weather?lat=".$LAT."&lon=".$LON."&mode=xml&appid=".$APPID_OWM;
			$xml = @simplexml_load_file($req1);
			if ($xml) {
				$owm = array();
				$owm["place"] = ($xml->city->attributes()->name);
				echo "OpenWeatherMap Place = ".$owm["place"]."\n";
				$owm["temp_now"] = round((float)($xml->temperature->attributes()->value) - 273.15,1);
				if ($owm["temp_now"] == "-0"){
					$owm["temp_now"] = "0";
				}

				$owm["humidity_now"] = ($xml->humidity->attributes()->value);
				$owm["clouds_value"] = ($xml->clouds->attributes()->value);
				$owm["pressure"] = round((float)($xml->pressure->attributes()->value),1);
				$owm["wind_speed"] = round((float)($xml->wind->speed->attributes()->value),1);
				$owm["wind_dir_value"] = ($xml->wind->direction->attributes()->value);
				$owm["wind_dir"] = $this->ryusWehatherWindDigree($xml->wind->direction->attributes()->value);

				$owm["w_time"] = ($xml->lastupdate->attributes()->value);
				$pos = strpos($owm["w_time"],"T");
				$owm["w_time_h"] = mb_strcut($owm["w_time"],$pos + 1,2,"UTF-8");
				$owm["w_time_m"] = mb_strcut($owm["w_time"],$pos + 4,2,"UTF-8");
				$owm["fukai"] = round((float)(0.81 * $owm["temp_now"] + 0.01 * $owm["humidity_now"] * (0.99 * $owm["temp_now"] - 14.3) + 46.3),1);
	
				$owm["description"] = $this->ryusWheatherDescription((int)($xml->weather->attributes()->number));
				$owm_icon = str_replace('n','d',($xml->weather->attributes()->icon));
				$SunMoon = new Moon_Class;
				$Sun = $SunMoon->SunHatena();
				if (!$Sun) {
					$owm_icon = str_replace('d','n',$owm_icon);
				}
				$owm["weather_icon"] = $this->trans_weather_icon($owm_icon);
// 	var_dump($owm);exit;
				return($owm);
			} else {
				sleep($sleeptime);
			}
		}
		return(NULL);
	}
	//----------------------------------------------------------------------------------------
	public function wunderground() {
		global $LAT,$LON,$APPID_WG;
		$xml = NULL;
		$kurikaeshi = 10;
		$sleeptime = 90;
		for ($count=0; $count < $kurikaeshi; $count++){ 
			$req2 = "http://api.wunderground.com/api/".$APPID_WG."/conditions/lang:JP/q/".$LAT.",".$LON.".xml";
			$xml = @simplexml_load_file($req2);
			if ($xml) {
//		var_dump($xml);
				$wg = array();
				$wg["city"] = ($xml->current_observation->display_location->city);
				echo "Wunderground City = ".$wg["city"]."\n";
				$wg["temp_now2"] = round((float)(5 / 9) * (($xml->current_observation->temp_f) - 32),1);
				$wg["temp_now_f"] = ($xml->current_observation->temp_f);
				$wg["humidity_now2"] = str_replace("%","％",($xml->current_observation->relative_humidity));
				$wg["windspeed_now2"]= round( 0.44704 * (float)($xml->current_observation->wind_mph),1) ;
				$wg["pressure_now2"] = round((float)($xml->current_observation->pressure_in) * 33.8639,1);
				$wg["kazamuki2"] = $this->ryusWehatherWindDigree($xml->current_observation->wind_degrees);
				$wg["kazamuki_degrees"] = ($xml->current_observation->wind_degrees);
				$wg["feelslike"] = round((float)(5 / 9) *(($xml->current_observation->feelslike_f) - 32),1);
				$wg["fukai"] = round((float)(0.81 * $wg["temp_now2"] + 0.01 * rtrim($wg["humidity_now2"],"％") * (0.99 * $wg["temp_now2"] - 14.3) + 46.3),1);
				$wg["weather"] = $this->trans_condition($xml->current_observation->weather);
				if ($wg["weather"] == ""){
					$owm = $this->openweathermap(); 
					$wg["weather"] = $owm["description"];
				}
				$wg["icon_url"] = ($xml->current_observation->icon_url);

				$wg["humidity_now2_X"] 	= ($xml->current_observation->relative_humidity);
				$wg["windspeed_now2_X"]	= ($xml->current_observation->wind_mph);
				$wg["pressure_now2_X"] 	= ($xml->current_observation->pressure_in);
				$wg["feelslike_X"] 	= ($xml->current_observation->feelslike_f);

				$wg["rfc822"]		= ($xml->current_observation->observation_time_rfc822);
				$wg["rfc"] = explode(" ",$wg["rfc822"]);
				$timestamp = strtotime($wg["rfc"][1]." ".$wg["rfc"][2]." ".$wg["rfc"][3]." ".$wg["rfc"][4]);
				$now_rfc = date("Y/m/d H:i:s",$timestamp);

                                        $from = $now_rfc;
                                        $to   = date("Y/m/d H:i:s");
                                        // 日時からタイムスタンプを作成
                                        $fromSec = strtotime($from);
                                        $toSec   = strtotime($to);

                                        // 秒数の差分を求める
                                        $differences = $toSec - $fromSec;

                                        // フォーマットする
                                        $result_day  = gmdate("d", $differences);
                                        $result_hour = gmdate("H", $differences);
                                        if ($result_day > 1 || $result_hour >= 3) {
                                                $wg["rfc_alive"] = "bad";
                                        } else {
                                                $wg["rfc_alive"] = "good";
                                        }                         
	
				if (strlen($wg["humidity_now2_X"]) == 0) {
					$wg["humidity_now2"] = "?";
					$wg["fukai"] = "?";
				} 
				if (strlen($wg["windspeed_now2_X"]) == 0) {
					$wg["windspeed_now2"] = "?";
				}
				if (strlen($wg["pressure_now2_X"]) == 0) {
					$wg["pressure_now2"] = "?";
				} 
				if (strlen($wg["feelslike_X"]) == 0) {
					$wg["feelslike"] = "?";
				}
				if (strlen($wg["temp_now_f"]) == 0) {
					$wg["temp_now2"] = "?";
					$wg["fukai"] = "?";
				}
				if (strlen($wg["kazamuki2"]) == 0) {
					$wg["kazamuki2"] = "?";
				}
				if (strlen($wg["weather"]) == 0) {
					$wg["weather"] = "?";
				}

				$filename = strrchr($wg["icon_url"], "/");
				$filename = substr($filename, 1);
		//		$wg_icon = str_replace('nt_','',$filename);
				$wg_icon = $filename;
				if ($wg_icon == ".gif" || $wg_icon == "nt_.gif") {
			//		$wg["icon_url"] = "http://linuxparadise.net/wp-content/uploads/2016/10/question_blue.jpg";
					$wg["icon_url"] = NULL;
					return($wg);
				}
//				$SunMoon = new Moon_Class;
//				$Sun = $SunMoon->SunHatena();
//				if (!$Sun) {
//					$wg_icon = "nt_".$wg_icon;
//				}
				$wg["icon_url"] = $this->trans_weather_icon_7("http://icons.wxug.com/i/c/k/".$wg_icon);
				return($wg);
			} else {
				sleep($sleeptime);
			}	
		}
		return(NULL);
	}
	
	public function time_diff($time_from, $time_to) 
	{	
   		 // 日時差を秒数で取得
   		 $dif = $time_to - $time_from;
   		 // 時間単位の差
   		 $dif_time = date("H:i:s", $dif);
   		 // 日付単位の差
   		 $dif_days = (strtotime(date("Y-m-d", $dif)) - strtotime("1970-01-01")) / 86400;
   		 return "{$dif_days}days {$dif_time}";
	}

	public function ryusWehatherWindDigree($digree){
	    $windDigree = array();
	    $windDigree['北'] = array(337.5, 382.5);
	    $windDigree['北東'] = array(22.5, 67.5);
	    $windDigree['東'] = array(67.5, 112.5);
	    $windDigree['南東'] = array(112.5, 157.5);
	    $windDigree['南'] = array(157.5, 202.5);
	    $windDigree['南西'] = array(202.5, 247.5);
	    $windDigree['西'] = array(247.5, 292.5);
	    $windDigree['北西'] = array(292.5, 337.5);
	    if($digree < 22.5){
	        $digree += 360;
	    }
	    foreach($windDigree as $kazamuki => $fromTo){
	        if(($fromTo[0] <= $digree) && ($digree < $fromTo[1])){
	            return $kazamuki;
	            break;
	        }
	    }
	    return '';
	}
}
?>
