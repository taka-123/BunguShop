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
    $order_ids = get_all_order_ids($db);
} else {
    $orders = get_user_orders($db, $user_id);
    $order_ids = get_user_order_ids($db, $user_id);
}

// POST送信時の処理 開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = get_post_data('token');
    $order_id = (int)get_post_data('order_id');
    
    if (is_valid_csrf_token($token) === false) {
        $errors[] = '不正な操作です';
    }
    
    // 不正入力対処
    if (in_array($order_id, $order_ids) === false) {
        $errors[] = '不正な選択です';
    }        
    
    // 上記エラーに該当しない場合、
    if (count($errors) === 0) {
        set_session('order_id', $order_id);
        redirect_to(HISTORY_DETAIL_URL);
    }
            
}
// POST送信時の処理 終了

// カート内購入予定数合計取得
$total_amount = get_total_amount($db, $user_id);

$token = get_csrf_token();

include_once VIEW_PATH . 'history_view.php';