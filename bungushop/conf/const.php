<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bungushop/model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bungushop/view/');
define('IMAGE_PATH', 'img/');

define('DB_HOST', 'mysql');
define('DB_USER', 'testuser');
define('DB_PASSWD', 'password');
define('DB_NAME', 'sample');
define('DB_CHARSET', 'utf8');
define('DBH_CHARSET', 'SET NAMES utf8mb4');
define('DSN', 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.DB_CHARSET);
define('HTML_CHARACTER_SET', 'UTF-8');

define('LOGIN_URL', 'login.php');
define('HOME_URL', 'itemlist.php');
define('ADMIN_URL', 'admin_item.php');

//管理者用ユーザ名を設定
define('USER_NAME_ADMIN', 'admin');

