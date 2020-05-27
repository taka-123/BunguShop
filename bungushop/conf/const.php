<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bungushop/model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bungushop/view/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/bungushop/img/' );
define('IMAGE_PATH', 'img/');

define('DB_HOST', 'mysql');
define('DB_USER', 'testuser');
define('DB_PASSWD', 'password');
define('DB_NAME', 'sample');
define('DB_CHARSET', 'utf8');
define('DBH_CHARSET', 'SET NAMES utf8mb4');
define('DSN', 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.DB_CHARSET);
define('HTML_CHARACTER_SET', 'UTF-8');

define('ADMIN_ITEM_URL', 'admin_item.php');
define('ADMIN_USER_URL', 'admin_user.php');
define('CART_URL', 'cart.php');
define('COMPLETION_URL', 'completion.php');
define('FINISH_URL', 'finish.php');
define('HOME_URL', 'itemlist.php');
define('LOGIN_URL', 'login.php');
define('LOGOUT_URL', 'logout.php');
define('NEW_ACCOUNT_URL', 'new_account.php');
define('USER_INFO_URL', 'user_info.php');

define('MIN_AGE', 20);
define('MAX_AGE', 100);

//管理者用ユーザ名を設定
define('USER_NAME_ADMIN', 'admin');

