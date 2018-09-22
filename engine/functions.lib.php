<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);
/*
	Так называемый роутер, навигатор, главное место в движке,
	где определяется какая страница вызвана и выполняются
	необходимые действия для нее, а именно
	присваиваются, получаются, вычисляются значения
	для подстановки в шаблон, формируется переменная vars
	На входе имя запрашиваемой страницы

*/
function prepareVariables($page_name, $action = ""){
    $vars = [
		    "title" => SITE_TITLE
	        ];
	
	if(isset($_SESSION['user'])) {
        $vars["logout"] = renderPage("logout_block"); //Выйти
    }
    else{
        $vars["logout"] = renderPage("login_block"); //Войти
    }
	//print_r($_SESSION);die();
	//в зависимости от того, какую страницу вызываем
	//такой блок кода для нее и выполняем
    switch ($page_name){
        case "index":
			$vars["forecast"] = get_weather();
			$vars["future"] = get_future();
			$vars["wind"] = get_future();
            break;
        case "ekb_archiv":
            $vars["table"] = get_archiv('ekb_archiv');
			$vars["norma"] = get_norma('ekb_archiv');
			$vars["means"] = get_mean('ekb_archiv');
			$vars["extr"] = get_archiv('ekb_archiv');
			
			if(isset($_SESSION['user'])){ //Позволяет самому менять значения в таблице погоды
				$vars["update"] = renderPage("update_block"); 
			}
			else{
                $vars["update"] = null; 
            }
			
			if(isset($_POST["submit"])){
				$vars["update"] = renderPage("update_form_block");
			}
            break;
		case "update":
			//update($)
			break;
		/*case "login":
            // если уже залогинен, то выбрасываем на главную
            if(alreadyLoggedIn()){
                header("Location: /");
            }

            // если есть куки, то авторизуем сразу
            if(checkAuthWithCookie()){
                header("Location: /");
            }
            if(!empty($_POST['login']) && !empty($_POST["password"])){
			   $vars["autherror"] = "";
			   if(vhodadmin() == 1){
				 header("Location: /adminka/");
				 } else{
				   header("Location: /");
				   $vars["autherror"] = "Неправильный логин/пароль";
				   }	      
				 } else {
				   header("Location: /");
			   }				   
            break;*/
		case "logins":
			// если уже залогинен, то выбрасываем на главную
            if(alreadyLoggedIn()){
                header("Location: /");
            }

            // если есть куки, то авторизуем сразу
            if(checkAuthWithCookie()){
                header("Location: /");
            }
            if(!empty($_POST['login']) && !empty($_POST["password"])){
			   $vars["autherror"] = "";
			   if(vhodadmin() == 1){
				 header("Location: /");
				 } else{
				   header("Location: /");
				   $vars["autherror"] = "Неправильный логин/пароль";
				   }	      
				 } else {
				   header("Location: /");
			   }				   
            break;
		case "logout": //Функция выхода из профиля
            unset($_SESSION["user"]);
            session_destroy();
            header("Location: /");
            break;
    }
	
	$clear_vars = $vars;
    $clear_vars["menu"] = get_menu();
	$clear_vars["datetime"] = date(' H:i ');
	$clear_vars["calendar"] = get_datetime();
	
	
	$vars["header"] = renderPage("header_block", $clear_vars);
    $vars["time_block"] = renderPage("time_block", $clear_vars);

    return $vars;
}


//Функция считывания среднего итога за все месяцы
function get_norma($town){
	$sql = "SELECT ROUND(AVG(January),1) AS Jan, ROUND(AVG(February),1) AS Feb, ROUND(AVG(March),1) AS Mar, ROUND(AVG(April),1) AS Apr, ROUND(AVG(May),1) AS May, ROUND(AVG(June),1) AS Jun, ROUND(AVG(July),1) AS Jul, ROUND(AVG(August),1) AS Aug, ROUND(AVG(September),1) AS Sep, ROUND(AVG(October),1) AS Oct, ROUND(AVG(November),1) AS Nov, ROUND(AVG(December),1) AS Dek FROM `$town`";
	$weather = getAssocResult($sql);
	return $weather;
}

//Функция получения среднего годового числа
function get_mean($town){
	$weather = get_norma($town);
	foreach($weather as $key => $values){
	$middle = round(array_sum($values)/count($values),1);
	return $middle;
	}
}

//Функция получения архивных данных
function get_archiv($town){
	$sql = "SELECT * FROM `$town` ORDER BY `earth`";
	$weather = getAssocResult($sql);
	return $weather;
}

//Функция получения прогноза погоды на день
function get_future(){
	$sql = "SELECT * FROM `spb`";
	$weather = getAssocResult($sql);
	return $weather;
}

//Функци получения погоды на город  //print_r($weather[0]['precipitation']); die();
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


//функция возвращает меню
function get_menu(){
    $sql = "SELECT * FROM `menu_bd` ORDER BY `id`";
    $menu = getAssocResult($sql);
    return $menu;
}

//функция вывода месяца и времени
function get_datetime(){
    $monthes = array(
    1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
	5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
	9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
	$month = "<br>";
	return $month . (date('d ') . $monthes[(date('n'))] . date(' Y '));
}



//Функция вывода топ 3 новости
function get_topnews(){
	$sql = "SELECT IF(SUBSTR(text, 1, 250)=text, text, CONCAT(SUBSTR(text, 1, 250), '...')) text, image, title, id FROM `articles` ORDER BY `views` DESC LIMIT 3";
    $topnews = getAssocResult($sql);
    return $topnews;
}

//Функция вывода новостей
function get_news(){
    $sql = "SELECT IF(SUBSTR(text, 1, 100)=text, text, CONCAT(SUBSTR(text, 1, 100), '...')) text, image, title, id FROM `articles` ORDER BY `id` DESC LIMIT 4";
    $news = getAssocResult($sql);
    return $news;
}








