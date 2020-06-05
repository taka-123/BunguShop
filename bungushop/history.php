<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

// ログインしていない場合、ログインページへ
if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

// ログイン中のユーザIDを取得
$user_id = (int)get_session('user_id');

// ログイン中のユーザ名を取得
$login_name = get_session('user_name');

// 初期化
$errors = [];

// 購入履歴と注文番号の候補を取得
if (is_admin()) {
    $orders = get_all_orders($db);
} else {
    $orders = get_user_orders($db, $user_id);
}

// カート内購入予定数合計取得
$total_amount = get_total_amount($db, $user_id);

$token = get_csrf_token();

include_once VIEW_PATH . 'history_view.php';