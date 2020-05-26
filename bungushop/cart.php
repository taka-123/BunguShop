<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'cart.php';

session_start();

$db = get_db_connect();

// ログインしていない場合、ログインページへ
if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

// ログイン中のユーザIDを取得
$user_id = (int)get_session('user_id');

// ログイン中のユーザ名を取得
$login_name = get_login_name($db);

$img_dir = './img/';

// 初期化
$sub_total_list = [];
$errors = [];
$sub_total = 0;
$total = 0;

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など

// カート内購入予定数取得
$total_amount = get_total_amount($db, $user_id);

// 入力データがPOSTで送信された場合の処理開始Ⅰ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    
    // 購入予定数の合計取得
    $total_amount = get_total_amount($db, $user_id);

}
// 入力データがPOSTで送信された場合の処理終了Ⅰ

// テーブルの結合参照(カート内の商品情報を取得)
$carts = get_user_carts($db, $user_id);

// 入力データがPOSTで送信された場合の処理開始Ⅱ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    
    // ③購入処理開始
    if ($sql_kind === 'purchase') {
        
        // 購入商品毎に、SQL確認
        foreach($data as $value) {
            
            // 購入予定商品の現在在庫数を取得
            $sql = 'SELECT name, stock, amount 
            FROM bungu_carts INNER JOIN bungu_item_stock 
            ON bungu_carts.item_id = bungu_item_stock.item_id
            INNER JOIN bungu_item_master 
            ON bungu_item_stock.item_id = bungu_item_master.item_id
            AND user_id = ?
            AND bungu_carts.item_id = ?';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $value['item_id'], PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $name = $row['name'];
                $stock = $row['stock'];
                $amount = $row['amount'];
                if ($amount > $stock) {
                    $errors[] = '「'.$name.'」の在庫が足りません。 残り:'.$stock.'個';
                }
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
// 入力データがPOSTで送信された場合の処理終了Ⅱ

include_once VIEW_PATH . 'cart_view.php';