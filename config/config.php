<?php
define('SITE_ROOT', "../");
define('WWW_ROOT', SITE_ROOT . '/public');

/* DB config */
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB', 'weather');

define('DATA_DIR', SITE_ROOT . 'data');
define('LIB_DIR', SITE_ROOT . 'engine');
define('TPL_DIR', SITE_ROOT . 'templates');

define('SITE_TITLE', 'Погода в России');
//подгружаем основные функции
define('SALT2', 'awOIHO@EN@Oine q2enq2kbkb');

require_once(LIB_DIR . '/lib_autoload.php');


