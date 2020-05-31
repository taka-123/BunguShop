<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'item.php';
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

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など


// 入力データがPOSTで送信された場合の処理開始Ⅰ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = get_post_data('token');
    if (is_valid_csrf_token($token) === false) {
        $errors[] = '不正な操作です';
    }

    $sql_kind = get_post_data('sql_kind');
    $cart_id = (int)get_post_data('cart_id');
    $item_id = (int)get_post_data('item_id');
    $amount = (int)get_post_data('amount');
    
    // ①在庫数変更時の処理開始
    if ($sql_kind === 'amount_update') {
        
        // 「数量」について
        // 未入力の場合
        if ($amount === '') {
            $errors[] = '「数量」を入力してください'; 
        }
        // 半角数字以外を入力した場合
        elseif (preg_match($non_num, $amount)) {
            $errors[] = '「数量」は半角数字で入力してください';
        }
        // 複数桁の数字で頭が０の場合、
        elseif (preg_match($zero_start, $amount)) {
            $errors[] = '「数量」は複数桁の場合、頭は0以外の半角数字にしてください';
        }
        // 10,000個より多い場合
        elseif ($amount > 10000) {
            $errors[] = '「数量」は1万個以下にしてください';
        }
        
        // 上記エラーに１つも該当しない場合、
        if (count($errors) === 0) {
            
            update_cart_amount($db, $amount, $user_id, $item_id);
            echo '在庫数を更新しました';
            
        }
        // エラーが無かった場合の処理終了                
        
    }
    // ①在庫数変更時の処理終了
    
    // ②削除ボタン実行時の処理開始
    elseif ($sql_kind === 'delete') {
        
        delete_cart($db, $cart_id);
        echo 'カートから削除しました';
        
    }
    // ②削除ボタン実行時の処理終了
    
    // ③購入処理開始
    elseif ($sql_kind === 'purchase') {

        // テーブルの結合参照(カート内の商品情報を取得)
        $carts = get_user_carts($db, $user_id);
        
        // 購入商品毎に、SQL確認
        foreach($carts as $cart) {
            
            // 購入予定商品の現在在庫数を取得
            $name = $cart['name'];
            $stock = $cart['stock'];
            $amount = $cart['amount'];
            if ($amount > $stock) {
                $errors[] = '「'.$name.'」の在庫がありません。 残り:'.$stock.'個';
            }
        
        }
        // 購入商品毎の更新処理終了
            
        // 上記エラーに１つも該当しない場合、
        if (count($errors) === 0) {
            // 正しいページ遷移判断のためにセッション定義
            set_session('permission', 'ok');
            // 購入完了ページへ移動
            redirect_to(FINISH_URL);
        }
        // エラーが無い場合の処理終了
    
    }
    // ③購入処理終了
    
}
// 入力データがPOSTで送信された場合の処理終了Ⅰ

// テーブルの結合参照(カート内の商品情報を取得)
$carts = get_user_carts($db, $user_id);

// カート内購入予定数取得
$total_amount = get_total_amount($db, $user_id);

$total_price = get_total_price($db, $user_id);

$token = get_csrf_token();

include_once VIEW_PATH . 'cart_view.php';