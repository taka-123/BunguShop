<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
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
$login_name = get_login_name($db);

// 正しいページ遷移でない場合（直接アクセス）、カートページへリダイレクト
if (get_session('permission') !== 'ok') {
    redirect_to(CART_URL);
}

// ページ遷移判断リセット
set_session_empty('permission');

$img_dir = './img/';

$user_id = $_SESSION['user_id'];
$top_user_name = $_SESSION['user_name'];

// 初期化
$data = [];
$sub_total_list = [];
$sub_total = 0;
$total = 0;
$total_amount = 0;

// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // データの取得開始
    try {
        // テーブルの結合参照(ステータス公開の商品のみ全て表示)
        $sql = 'SELECT bungu_item_master.item_id, name, price, item_img, user_id, amount 
        FROM bungu_item_master INNER JOIN bungu_carts 
        ON bungu_item_master.item_id = bungu_carts.item_id AND amount > 0 AND user_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
    }
    // データの取得終了
    
    // カートの中身が空だった場合、
    if (empty($data)) {
        // ログインページへリダイレクト
        redirect_to(LOGIN_URL);
    }
    
    // 小計を定義・表示
    foreach ($data as $value) {
        $sub_total = entity_str($value['price'] * $value['amount']);
    // 小計をlist配列に格納
    $sub_total_list[] = $sub_total;
    }
    // 合計金額を定義
    foreach ($sub_total_list as $sub_total) {
        $total += $sub_total; 
    }

    // $after_stock = $stock - $amount;
    // トランザクション開始
    $dbh->beginTransaction();
    try {
        // 購入商品毎に、SQL更新
        foreach($data as $value) {
            // 在庫数の更新
            $sql = 'UPDATE bungu_item_stock 
            SET stock = stock - ? 
            WHERE item_id = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $value['amount'], PDO::PARAM_STR);
            $stmt->bindValue(2, $value['item_id'], PDO::PARAM_STR);
            $stmt->execute();
        }
        // 購入商品毎の更新処理終了
            // 同ユーザのカート情報全削除
            $sql = 'DELETE FROM bungu_carts 
            WHERE user_id =?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
            $stmt->execute();
        // コミット処理
        $dbh->commit();
    } catch (PDOException $e) {
        // ロールバック処理
        $dbh->rollback();
        throw $e;
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}
// DB接続終了

include_once VIEW_PATH . 'finish_view.php';