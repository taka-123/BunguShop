<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';

// セッションスタート
session_start();

// ログインしていない場合、ログインページへ
if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

// ログイン中のユーザIDを取得
$user_id = (int)get_session('user_id');

// ログイン中のユーザ名を取得
$login_name = get_login_name($db);

// 正しいページ遷移でない場合（直接アクセス）、ユーザ新規登録ページへリダイレクト
if (get_session('permission') !== 'ok') {
    redirect_to(NEW_ACCOUNT_URL);
}

// ページ遷移判断リセット
set_session_empty('permission');

include_once VIEW_PATH . 'completion_view.php';