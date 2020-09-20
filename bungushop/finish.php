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

// 正しいページ遷移でない場合（直接アクセス）、カートページへリダイレクト
if (get_session('permission') !== 'ok') {
    redirect_to(CART_URL);
}

// ページ遷移判断リセット
set_session_empty('permission');

// 初期化
$total = 0;
$errors[] = '';

$carts = get_user_carts($db, $user_id);
$total_price = get_total_price($db, $user_id);

if (regist_order($db, $carts, $user_id) === false) {
    redirect_to(CART_URL);
}


include_once VIEW_PATH . 'finish_view.php';