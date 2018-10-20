<?php


$file = 'https://xml.meteoservice.ru/export/gismeteo/point/69.xml';
$cache_file = 'weather_69.xml'; //Кэш файл

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

if(file_exists($cache_file) && (filemtime($cache_file) > (time() - 10800 ))) { //Если время кэша еще не истекло, то загружаем из кэшированого файла 
    $xml = simplexml_load_file("weather_69.xml"); //превращаем xml в объект
	
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
	
	forecasts('spb', 0, $xml);
    forecasts('spb', 1, $xml);
    forecasts('spb', 2, $xml);
	forecasts('spb', 3, $xml);
	   
} else {
	copy($file, $cache_file); //Если время кэша прошло, то загружаем его заново
}

function forecasts($towns, $count, $xml){ //Функция для прогноза погоды на утро, день, вечер
  foreach ($xml->REPORT->TOWN->FORECAST as $forecast ){
     lol($forecast);
    if ($forecast["tod"] == $count){
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

function lol($forecast){ //Функция для подстановки картинок
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

//Функци получения погоды на город  
function get_weather(){
	$sql = "SELECT * FROM `forecast`";
	$weather = getAssocResult($sql);
		switch($weather[0]['precipitation']){
		  case 3: $weather[0]['precipitation'] = str_replace(3, "/images/sunno.png", $weather[0]['precipitation']); break;
		  case 4: $weather[0]['precipitation'] = str_replace(4, "/images/run.png", $weather[0]['precipitation']); break;
		  case 5: $weather[0]['precipitation'] = str_replace(5, "/images/run.png", $weather[0]['precipitation']); break;
		  case 6: $weather[0]['precipitation'] = str_replace(6, "/images/snow.png", $weather[0]['precipitation']); break;
		  case 7: $weather[0]['precipitation'] = str_replace(7, "/images/snow.png", $weather[0]['precipitation']); break;
		  case 8: $weather[0]['precipitation'] = str_replace(8, "/images/funder.png", $weather[0]['precipitation']); break;
		  case 10: $weather[0]['precipitation'] = str_replace(10, "/images/sun.png", $weather[0]['precipitation']); break;
     	}
	    switch($weather[0]['cloudiness']){
		  case -1: $weather[0]['cloudiness'] = str_replace(-1, "туман", $weather[0]['cloudiness']);  break;
		  case 0: $weather[0]['cloudiness'] = str_replace(0, "ясно", $weather[0]['cloudiness']);  break;
		  case 1: $weather[0]['cloudiness'] = str_replace(1, "малооблачно", $weather[0]['cloudiness']);  break;
		  case 2: $weather[0]['cloudiness'] = str_replace(2, "облачно", $weather[0]['cloudiness']);  break;
		  case 3: $weather[0]['cloudiness'] = str_replace(3, "пасмурно", $weather[0]['cloudiness']);  break;
     	}
	
    return $weather;
}

