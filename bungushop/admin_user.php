<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

$db = get_db_connect();

// ログイン中のユーザ情報を取得
$login_user = get_login_user($db);

// 管理者としてログインしていない場合、ログインページへ
if (is_admin($login_user) === false){
    redirect_to(LOGIN_URL);
}

// 登録済みの全ユーザ情報を取得    
$users = get_all_users($db);

include_once VIEW_PATH . 'admin_user_view.php';
