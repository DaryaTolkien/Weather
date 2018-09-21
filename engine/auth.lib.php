<?php


//Функция входа в админку
function vhodadmin(){
	$username = $_POST['login'];
    $password = $_POST['password'];
    $link = getConnection();
    $sql = "SELECT login, password FROM users WHERE login = '".mysqli_real_escape_string($link, $username)."'";
    $user_data = getRowResult($sql, $link);
	$lol = '0';
	if($user_data['password'] === $password){
	$lol = '1';
		
		// если стояла галка, то запоминаем пользователя на сутки
	if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
        setcookie("id_user", $user_data['id_user'], time()+86400);
        setcookie("cookie_hash", $user_data['user_password'], time()+86400);
    }
    $_SESSION['user'] = $user_data;
	}
	return $lol;
  }


//валидация пользовательского куки, проверяет, есть ли куки или нет
function checkAuthWithCookie(){
    $result = false;

    if(isset($_COOKIE['id_user']) && isset($_COOKIE['cookie_hash'])){
        // получаем данные пользователя по id
        $link = getConnection();
        $sql = "SELECT id, login, password FROM users WHERE id = '".mysqli_real_escape_string($link, $_COOKIE['user_id'])."'";
        $user_data = getRowResult($sql, $link);
        if(($user_data['user_password'] !== $_COOKIE['user_hash']) || ($user_data['id_user'] !== $_COOKIE['id_user'])){
            setcookie("id", "", time() - 3600*24*30*12, "/");
            setcookie("hash", "", time() - 3600*24*30*12, "/");
        }
        else{
            header("Location: /");
        }

    }

    return $result;
}

/**
 авторизация через логин и пароль
 */
function authWithCredentials(){
    $username = $_POST['login'];
    $password = $_POST['password'];
    // получаем данные пользователя по логину
    $link = getConnection();
    $sql = "SELECT id, login, password FROM users WHERE login = '".mysqli_real_escape_string($link, $username)."'";
    $user_data = getRowResult($sql, $link);
    $isAuth = 0;
    // проверяем соответствие логина и пароля
    if ($user_data) {
        if(checkPassword($password, $user_data['password'])){
            $isAuth = 1;
        }
    }
     //print_r($isAuth = 1); die();
    // если стояла галка, то запоминаем пользователя на сутки
    if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
        setcookie("id_user", $user_data['id_user'], time()+86400);
        setcookie("cookie_hash", $user_data['user_password'], time()+86400);
    }

    // сохраним данные в сессию
    $_SESSION['user'] = $user_data;
    return $isAuth;
}

function hashPassword($password)
{
    $salt = md5(uniqid(SALT2, true));
    $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
    return crypt($password, '$2a$08$' . $salt);
}



//Сверяем введённый пароль и хэш
function checkPassword($password, $hash){
    return crypt($password, $hash) === $hash;
}

//Проверка на залогиненность
function alreadyLoggedIn(){
    return isset($_SESSION['user']);
}
