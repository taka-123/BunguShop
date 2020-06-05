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

// 直接アクセスの禁止
if (get_session('order_id') !== '') {
    $order_id = get_session('order_id');
    // ページ遷移判断リセット
    set_session_empty('order_id');
} else {
    redirect_to(HISTORY_URL);
}

$order = get_order($db, $order_id);
$details = get_order_details($db, $order_id);


// カート内購入予定数合計取得
$total_amount = get_total_amount($db, $user_id);

include_once VIEW_PATH . 'history_detail_view.php';