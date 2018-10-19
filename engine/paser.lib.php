<?php

$city_id = 69; //id нужного города
$cache_lifetime = 7200; //время кэша файла в секундах, 3600=1 час
$cache_file = 'weather_'.$city_id.'.xml'; // временный файл-кэш 


@$xml = simplexml_load_file("https://xml.meteoservice.ru/export/gismeteo/point/69.xml"); //превращаем xml в объект

$cur_time=date('G');
$cur_date=date('d.m.Y');

switch($cur_time){ //Делаем переменную для сверки времени и даты с xml
  case  ($cur_time>=3 && $cur_time<9):
     $tod=1;
     break;
  case  ($cur_time>=9 && $cur_time<15):
    $tod=2;
    break;
  case  ($cur_time>=15 && $cur_time<21):
    $tod=3;
    break;
  case  (($cur_time>=21  &&  $cur_time<23) || 
         ($cur_time>=0   && $cur_time<3)):
    $tod=0;
    break;
		
 default: $tod=0;
}


foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){ //Прогноз реального времени
   $xml_date=$forecast["day"].'.'.$forecast["month"].'.'.$forecast["year"];
  if ($forecast["tod"] == $tod  && $cur_date == $xml_date){
	  $sql = "UPDATE `forecast` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
        `wind_min` = '{$forecast->WIND['min']}',
        `wind_max` = '{$forecast->WIND['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`heat` = '{$forecast->HEAT['min']}',
		`pressure` = '{$forecast->PRESSURE['min']}',
		`relwet` = '{$forecast->RELWET['min']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `forecast`.`id` = 69
         LIMIT 1";
	  $bla = executeQuery($sql);
	  break; }
 }

/*function forecasts($towns, $count){ //Функция для прогноза погоды на утро, день, вечер
  foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
     lol($forecast);
	  print_r($towns);die();
    if ($forecast["tod"] == 0){
	   $sql = "UPDATE `{$towns}` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`wind_min` = '{$forecast->WIND['min']}',
		`wind_max` = '{$forecast->WIND['max']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `{$towns}`.`tod` = {$count}
         LIMIT 1";
	  $bla = executeQuery($sql);
	  break; }
    }
}
	
    forecasts('spb', 0);
    forecasts('spb', 1);
    forecasts('spb', 2);
	forecasts('spb', 3);
*/
function lol($forecast){
	switch($forecast->PHENOMENA['precipitation']){
		  case 3: $forecast->PHENOMENA['precipitation'] = str_replace(3, "/images/sunno.png", $forecast->PHENOMENA['precipitation']); break;
		  case 4: $forecast->PHENOMENA['precipitation'] = str_replace(4, "/images/run.png", $forecast->PHENOMENA['precipitation']); break;
		  case 5: $forecast->PHENOMENA['precipitation'] = str_replace(5, "/images/strong_run.png", $forecast->PHENOMENA['precipitation']); break;
		  case 6: $forecast->PHENOMENA['precipitation'] = str_replace(6, "/images/snow.png", $forecast->PHENOMENA['precipitation']); break;
		  case 7: $forecast->PHENOMENA['precipitation'] = str_replace(7, "/images/snow.png", $forecast->PHENOMENA['precipitation']); break;
		  case 8: $forecast->PHENOMENA['precipitation'] = str_replace(8, "/images/funder.png", $forecast->PHENOMENA['precipitation']); break;
		  case 10: $forecast->PHENOMENA['precipitation'] = str_replace(10, "/images/sunnoi.png", $forecast->PHENOMENA['precipitation']); break;
     	}
	return $forecast;
}
	
foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
   lol($forecast);
  if ($forecast["tod"] == 0){
	  $sql = "UPDATE `spb` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`wind_min` = '{$forecast->WIND['min']}',
		`wind_max` = '{$forecast->WIND['max']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `spb`.`tod` = 0
         LIMIT 1";
	  
	  $bla = executeQuery($sql);
	  
	  break; }
 }

foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
	lol($forecast);
  if ($forecast["tod"] == 1){
	  $sql = "UPDATE `spb` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`wind_min` = '{$forecast->WIND['min']}',
		`wind_max` = '{$forecast->WIND['max']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `spb`.`tod` = 1
         LIMIT 1";
	  
	  $bla = executeQuery($sql);
	  break;
  }
 }

foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
	lol($forecast);
  if ($forecast["tod"] == 2){
	  $sql = "UPDATE `spb` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
		`wind_min` = '{$forecast->WIND['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
		`wind_max` = '{$forecast->WIND['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `spb`.`tod` = 2
         LIMIT 1";
	  
	  $bla = executeQuery($sql);
	  
	  break; }
 }

foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
	lol($forecast);
  if ($forecast["tod"] == 3){
	  $sql = "UPDATE `spb` 
        SET `temperature_min` = '{$forecast->TEMPERATURE['min']}',
        `temperature_max` = '{$forecast->TEMPERATURE['max']}',
        `precipitation` = '{$forecast->PHENOMENA['precipitation']}',
		`wind_min` = '{$forecast->WIND['min']}',
		`wind_max` = '{$forecast->WIND['max']}',
		`cloudiness` = '{$forecast->PHENOMENA['cloudiness']}'
         WHERE `spb`.`tod` = 3
         LIMIT 1";
	  
	  $bla = executeQuery($sql);
	  
	  break; }
 }
 

/*
$city_id = 69; //id нужного города
$cache_lifetime = 7200; //время кэша файла в секундах, 3600=1 час
$cache_file = 'weather_'.$city_id.'.xml'; // временный файл-кэш 


function load_xml($city_id){
	$url = "xml.meteoservice.ru/export/gismeteo/point/' . $city_id . '.xml";
	$userAgent = "Googlebot/2.1 (+http://www.google.com/bot.html)";
	$xml = "weather_'.$city_id.'.xml";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    $output = curl_exec($ch);
    $fh = fopen($xml, 'w');
	fwrite($fh, $output);
    fclose($fh);
}

//if ( file_exists($cache_file) ) {
           // $cache_modified = time() - @filemtime($cache_file);
           // if ( $cache_modified > $cache_lifetime ) {  
            //обновляем файл погоды, если время файла кэша устарело
           //loadxml($city_id);
           //  }
          //    }
      //   else {
           //если нет файла погоды вообще, закачиваем его
       //   load_xml($city_id);
        //  }

        //  if(file_exists($cache_file)){
       //  $data = simplexml_load_file($cache_file); }

function get_forecast(&$city_id, $temperature, $pressure){
	$sql = "INSERT INTO `forecast` (`temperature`, `pressure`) VALUES ('$temperature', '$pressure')";
	$bla = executeQuery($sql);
	loadxml($city_id);
}

*/