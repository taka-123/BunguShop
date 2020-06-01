<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

// ログイン中のユーザ名を取得
$login_name = get_session('user_name');
if ($login_name === '') {
    $login_name = 'ゲスト';
}

// 正しいページ遷移でない場合（直接アクセス）、カートページへリダイレクト
if (get_session('permission') !== 'ok') {
    redirect_to(NEW_ACCOUNT_URL);
}

// ページ遷移判断リセット
set_session_empty('permission');

include_once VIEW_PATH . 'completion_view.php';