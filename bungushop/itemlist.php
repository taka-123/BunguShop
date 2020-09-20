<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'page_link.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

// ログイン中のユーザIDを取得
$user_id = (int)get_session('user_id');

// 初期化
$refine = '';
$errors = [];
$name = '';
$page_id = 1;
$rank = 1;

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など

// ジャンル一覧（ジャンルID/ジャンル名）取得（プルダウン表示のため）
$genres = get_genres($db);

// ジャンルID一覧取得（エラーチェックの為）
$genre_ids = get_genre_ids($db);

// GET送信データの取得
// 商品名
$name = get_get_data('name');
// ジャンル（未選択なら0→全て）
$genre_id = (int)get_get_data('genre_id');
// ソート（未選択ならNEW_ARRIVAL→新着順）
$sort_key = get_get_data('sort_key');
if ($sort_key === ''){
    $sort_key = NEW_ARRIVAL;
}

// GET送信されたジャンルIDから、ジャンル名を取得
$genre_name = get_genre_name($db, $genre_id);

if ($name !== '') {
    $refine = '商品名：『' . $name . '』を含む';
}
if ($genre_id !== 0) {
    $refine = 'ジャンル：「' . $genre_name . '」';
}

// ページネーションのための情報取得
// 公開商品全件数
$total_items = get_open_items_num($db, $name, $genre_id);
// トータルページ数
$max_page = ceil($total_items / MAX_NUM_PER_PAGE);
// 現在ページ番号
$page_id = (int)get_now_page();
// 開始配列
$start_array_num = MAX_NUM_PER_PAGE * ($page_id - 1);
// 開始件数
$start_num = MAX_NUM_PER_PAGE * ($page_id - 1) + 1;
// 終了件数
if (($start_num + MAX_NUM_PER_PAGE - 1) < $total_items) {
    $finish_num = $start_num + MAX_NUM_PER_PAGE - 1;
} else {
    $finish_num = $total_items;
}

// POST送信時の処理 開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = get_post_data('token');
    if (is_valid_csrf_token($token) === false) {
        $errors[] = '不正な操作です';
    }
    
    $sql_kind = get_post_data('sql_kind');
    $item_id = (int)get_post_data('item_id');
    $amount = get_post_data('amount');   
    
    // 「カートに追加」時の処理 開始
    if ($sql_kind === 'cart') {
        
        // 「追加数量」について
        // ログインしていない場合、
        if (is_guest()) {
            $errors[] = '「ログイン」してください';
        }
        // 未入力の場合
        elseif ($amount === '') {
            $errors[] = '「追加数量」を入力してください'; 
        }
        // 半角数字以外を入力した場合
        elseif (preg_match($non_num, $amount)) {
            $errors[] = '「追加数量」は半角数字で入力してください';
        }
        // 複数桁の数字で頭が０の場合、
        elseif (preg_match($zero_start, $amount)) {
            $errors[] = '「追加数量」は複数桁の場合、頭は0以外の半角数字にしてください';
        }
        // 10,000個より多い場合
        elseif ($amount > 10000) {
            $errors[] = '「追加数量」は1万個以下にしてください';
        }
        
        // 上記エラーに１つも該当しない場合、
        if (count($errors) === 0) {
            
            // 追加前の購入予定数を取得
            $before_amount = get_cart_amount($db, $user_id, $item_id);
            
            // 既にカートに入っている数量（無ければ0）に、「カートに追加」時に指定した値を加え、その合計を$amountとして更新
            $amount = $before_amount + $amount;
            
            // (1) 既にその商品がカートに入っている場合
            if ($before_amount !== 0) {              
                update_cart_amount($db, $amount, $user_id, $item_id);
            }
            
            // (2) その商品がカートに入っていない場合、
            else {
                insert_cart($db, $user_id, $item_id, $amount);
            }

            
        }
        // エラーが無かった場合の処理 終了
        
    }
    // 「カートに追加」時の処理 終了

}
// POST送信時の処理 終了


// 全公開設定商品の情報を取得
$items = get_open_items($db, $name, $genre_id, $sort_key, $start_array_num);

// 売上数の多い商品の情報を取得
$popular_items = get_popular_items($db, RANK_DISPLAY_NUM);

// カート内購入予定数合計取得
$total_amount = get_total_amount($db, $user_id);

$token = get_csrf_token();

include_once VIEW_PATH . 'itemlist_view.php';