<?php 
require_once './conf/const.php';

// セッション開始
session_start();

// セッション変数からログイン済みか確認
if (!isset($_SESSION['user_id'])) {
    // ログインしていない場合、ログインページへリダイレクト
    redirect_to(LOGIN_URL);
}

// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();
// セッション変数を全て削除
$_SESSION = array();
 
// ユーザのCookieに保存されているセッションIDを削除
if (isset($_COOKIE[$session_name])) {
  // sessionに関連する設定を取得
  $params = session_get_cookie_params();
 
}
// セッションIDを無効化
session_destroy();

include_once VIEW_PATH . 'logout_view.php';