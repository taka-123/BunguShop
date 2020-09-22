<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

// ログインしていない場合、ログインページへ
if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

// ログイン中のユーザIDを取得
$user_id = (int)get_session('user_id');

// 初期化
$errors = [];
    
// 入力データがPOSTで送信された場合の処理開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = get_post_data('token');
    if (is_valid_csrf_token($token) === false) {
        $errors[] = '不正な操作です';
    }

    $mail = get_post_data('mail');

    // 「メールアドレス」について
    // 形式check
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === FALSE) {
        $errors[] = '不正な形式のメールアドレスです';
    }
    
    // エラーが無かった場合、
    if(count($errors) === 0) {
        
        update_user_mail($db, $mail, $user_id);
        echo 'メールアドレスを変更しました';
        
    }
    // エラーがなかった場合の処理終了
 
}
// POST送信時の処理終了

$user = get_user($db, $user_id);

if ($user['sex'] === 0) {
    $sex = '男性';
}
if ($user['sex'] === 1) {
    $sex = '女性';
}

// カート内購入予定数取得
$total_amount = get_total_amount($db, $user_id);

$token = get_csrf_token();

include_once VIEW_PATH . 'user_info_view.php';