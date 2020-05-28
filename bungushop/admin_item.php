<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

$db = get_db_connect();

// ログイン中のユーザ情報を取得
$login_user = get_login_user($db);

// ログイン中のユーザ名を取得
$login_name = get_login_name($db);

// 管理者としてログインしていない場合、ログインページへ
if (is_admin($user) === false){
    redirect_to(LOGIN_URL);
}



// 初期化
$sql_kind = '';
$errors = [];
$data = [];

// ジャンル一覧取得
$genres = get_genres($db);

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など
    

// POST送信された場合の処理 開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 各種送信データ取得    
    $sql_kind = get_post_data('sql_kind');
    $item_id = (int)get_post_data('item_id');
    $name = get_post_data('name');
    $genre_id = (int)get_post_data('genre_id');
    $price = (int)get_post_data('price');
    $stock = (int)get_post_data('stock');
    $comment = get_post_data('comment');
    $status = (int)get_post_data('status');
    $item_img = get_file_data('item_img');
    
    
    // ①「商品新規登録」時のエラーチェック
    if ($sql_kind === 'insert') {
    
        // 「商品名」について
        // 未入力の場合
        if ($name === '') {
            $errors[] = '「商品名」を入力してください';
        }
        // 全角スペースや空白文字のみで入力した場合、
        elseif (preg_match('/\A[　|\s]+\z/', $name)) {
            $errors[] = '「商品名」は空白のみの入力はできません'; 
        }
        // 30文字を超える入力の場合
        elseif (mb_strlen($name) > 30) {
            $errors[] = '「商品名」は30文字以内にしてください'; 
        }
        
        // 「ジャンル」について
        // 「選択してください」のままになっている場合
        if ($genre_id === "0") {
            $errors[] = '「ジャンル」を選択してください';
        }
        // 不正入力対処
        elseif (preg_match('/\A[1-8]\z/', $genre_id) !== 1 ) {
            $errors[] = '「ジャンル」は選択肢の中から正しく選んでください';
        }
        
        // 「値段」について
        // 未入力の場合
        if ($price === '') {
            $errors[] = '「値段」を入力してください'; 
        }
        // 半角数字以外を入力した場合
        elseif (preg_match($non_num, $price)) {
            $errors[] = '「値段」は半角数字を入力してください';
        }
        // 複数桁の数字で頭が０の場合、
        elseif (preg_match($zero_start, $price)) {
            $errors[] = '「値段」は複数桁の場合、頭は0以外の数字にしてください';
        }
        // 10,000円より多い場合
        elseif ($price > 10000) {
            $errors[] = '「値段」は1万円以下にしてください';
        }
        
        // 「在庫数」について
        // 未入力の場合
        if ($stock === '') {
            $errors[] = '「在庫数」を入力してください'; 
        }
        // 半角数字以外を入力した場合
        elseif (preg_match($non_num, $stock)) {
            $errors[] = '「在庫数」は半角数字で入力してください';
        }
        // 複数桁の数字で頭が０の場合、
        elseif (preg_match($zero_start, $stock)) {
            $errors[] = '「在庫数」は複数桁の場合、頭は0以外の半角数字にしてください';
        }
        // 10,000個より多い場合
        elseif ($stock > 10000) {
            $errors[] = '「在庫数」は1万個以下にしてください';
        }
        
        // 「画像ファイル」について
        //  HTTP POST でファイルがアップロードされたか確認
        if (is_uploaded_file($_FILES['item_img']['tmp_name']) === TRUE) {
            
            // 画像の拡張子取得
            $extension = pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION);
            
            // 指定の拡張子であるかどうかをチェック
            if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
                
                // 保存する新しいユニークなファイル名の生成
                $item_img = sha1(uniqid(mt_rand(), true)). '.' . $extension;
                
                // 同名ファイルが存在しないかチェック
                if (is_file(IMAGE_PATH . $item_img) !== TRUE) {
                    
                    // アップロードされたファイルを指定ディレクトリに移動して保存
                    if (move_uploaded_file($_FILES['item_img']['tmp_name'], IMAGE_PATH . $item_img) !== TRUE) {
                        $errors[] = 'ファイルアップロードに失敗しました';
                    }
                    
                } else {
                    $errors[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                }
            
            } else {
                $errors[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です。';
            }
            
        } else {
            $errors[] = '「ファイル」を選択してください';
        }
        
        // 「ステータス」について
        // 不正入力対処
        if (preg_match('/\A[01]\z/', $status) !== 1) {
            $errors[] = '「公開ステータス」は公開または非公開のいずれかを選択してください';
        }
        
        // 「コメント」について
        // 30文字を超える入力の場合
        if (mb_strlen($name) > 200) {
            $errors[] = '「コメント」は200文字以内にしてください'; 
        }
        
    }
    
    // ②「在庫数更新」時のエラーチェック
    elseif ($sql_kind === 'stock_update') {
        
        // 「在庫数」について
        // 未入力の場合
        if ($stock === '') {
            $errors[] = '「在庫数」を入力してください'; 
        }
        // 半角数字以外を入力した場合
        elseif (preg_match($non_num, $stock)) {
            $errors[] = '「在庫数」は半角数字で入力してください';
        }
        // 複数桁の数字で頭が０の場合、
        elseif (preg_match($zero_start, $stock)) {
            $errors[] = '「在庫数」は複数桁の場合、頭は0以外の数字にしてください';
        }
        // 10,000個より多い場合
        elseif ($stock > 10000) {
            $errors[] = '「在庫数」は1万個以下にしてください';
        }
    
    }
    
    // ③「ステータス変更」時のエラーチェック
    elseif ($sql_kind === "status_change") {
        
        // 「ステータス」について
        // 不正入力対処
        if (preg_match('/\A[01]\z/', $status) !== 1) {
            $errors[] = '「ステータス」は公開または非公開のいずれかを選択してください';
        }
    
    }

    
    // 上記エラーに１つも該当しない場合、
    if (count($errors) === 0) {

        // ①商品新規登録
        if ($sql_kind === 'insert') {
            if (regist_item($db, $name, $genre_id, $price, $item_img, $comment, $status, $stock)){
                echo '商品を登録しました。';
            } else {
                $errors[] = '商品の登録に失敗しました。';
            }
        }
            
        // ②在庫数更新
        elseif ($sql_kind === 'stock_update') {
            if (update_item_stock($db, $item_id, $stock)) {
                echo '在庫数を変更しました。';
            } else {
                $errors[] = '在庫数の変更に失敗しました。';
            }
        }

        // ③ステータス変更
        elseif ($sql_kind === "status_change") {
            if (update_item_status($db, $item_id, $status)) {
                echo 'ステータスを変更しました。';
            } else {
                $errors[] = 'ステータスの変更に失敗しました。';
            }    
        }
        
        // ④削除
        elseif ($sql_kind === "delete") {
            if (delete_item($db, $item_id)) {
                echo '商品を削除しました。';
            } else {
                $errors[] = '商品の削除に失敗しました。';
            }
        }    

    }    
    // エラーに１つも該当しない場合の処理終了

}
// POST送信された場合の処理 終了

// テーブルを結合し、商品一覧表示のために必要な情報を取得
$items = get_items($db);

include_once VIEW_PATH . 'admin_item_view.php';
