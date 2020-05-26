<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

if (is_logined()){
    redirect_to(HOME_URL);
}

// Cookie情報からユーザ名を取得
$cookie_name = get_cookie('user_name');

// 初期化
$errors = [];

// 入力データがPOSTで送信された場合の処理開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_name = get_post_data('user_name');
    $passwd = get_post_data('passwd');
    $cookie_check = get_post_data('cookie_check');

    // ユーザ名の入力を省略のチェックがONの場合、Cookieを利用する。OFFの場合、Cookieを削除する
    $now = time();
    if ($cookie_check === 'checked') {
        // Cookieへ保存する
        setcookie('user_name', $user_name, $now + 60 * 60 * 24 * 365);
    } else {
        // Cookieを削除する
        setcookie('user_name', '', $now - 3600);
    }    
    
    
    // 「ユーザ名」のエラーチェック 
    if ($user_name === '') {
        $errors[] = '「ユーザ名」を入力してください';
    }
    
    // 「パスワード」のエラーチェック 
    if ($passwd === '') {
        $errors[] = '「パスワード」を入力してください';
    }
    
        
    // 上記エラーに1つも該当しない場合、
    if (count($errors) === 0) {
        
        // ログイン認証 成功なら、セッションにユーザID保存
        $user = login_as($db, $user_name, $passwd);
        
        // (1)認証失敗
        if ($user === false) {
            $errors[] = 'ログインに失敗しました。';
        }
        
        // (2)管理者用ユーザ名で認証成功
        elseif ($user['user_name'] === USER_NAME_ADMIN){
            redirect_to(ADMIN_ITEM_URL);
        } 
        
        // (3)一般ユーザ名で認証成功
        else {
            redirect_to(HOME_URL);
        }
        
    }
            
}
//POST送信時の処理終了



// ログインページテンプレートファイル読み込み
include_once VIEW_PATH . 'login_view.php';
