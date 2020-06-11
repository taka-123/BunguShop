<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'user.php';

session_start();

$db = get_db_connect();

// ログインしている場合、商品一覧ページへ
if (is_logined() === true) {
    redirect_to(HOME_URL);
}

// 登録済みの全ユーザ名を取得
$user_names = get_user_names($db);

// 初期化
$errors = [];

// 日付取得（生年月日範囲指定の為）
$min_date = date("Y-m-d",strtotime("-" . MAX_AGE . "year"));
$max_date = date("Y-m-d",strtotime("-" . MIN_AGE . "year"));

// 正規表現
$non_alphanum = '/[^a-zA-Z0-9]/';  // 「半角英数字」以外を含む

// 入力データがPOSTで送信された場合の処理開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = get_post_data('token');
    if (is_valid_csrf_token($token) === false) {
        $errors[] = '不正な操作です';
    }

    $user_name = get_post_data('user_name');
    $passwd = get_post_data('passwd');
    $mail = get_post_data('mail');
    $sex = (int)get_post_data('sex');
    $birthdate = get_post_data('birth');

    //
    /// エラーチェック
    //
    // 「ユーザ名」について
    // 文字数、種類check
    if (mb_strlen($user_name) < 6 || mb_strlen($user_name) > 15 || preg_match($non_alphanum, $user_name)) {
        $errors[] = '「ユーザ名」は半角英数字6～15文字にしてください'; 
    }
    // 希望user_nameは、既に登録されているものと重複しないかcheck
    elseif(in_array($user_name, $user_names)) {
        $errors[] = '既に使用されているユーザ名です';
    }
    
    // 「パスワード」について
    // 文字数、種類check
    if (mb_strlen($passwd) < 6 || mb_strlen($passwd) > 15 || preg_match($non_alphanum, $passwd)) {
        $errors[] = '「パスワード」は半角英数字6～15文字にしてください'; 
    }
    
    // 「メールアドレス」について
    // 形式check
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === FALSE) {
        $errors[] = '不正な形式のメールアドレスです';
    }
    
    // 「性別」について
    // 未選択
    if ($sex === '') {
        $errors[] = '「性別」を選択してください';
    } 
    // 不正入力対処
    elseif (preg_match('/\A[01]\z/', $sex) !== 1) {
        $errors[] = '「性別」は選択肢から正しく選択してください';
    }
    
    // 「生年月日」について
    // 未入力
    if ($birthdate === '') {
        $errors[] = '「生年月日」を入力してください';
    }
    // 形式check
    elseif( !strptime( $birthdate, '%Y-%m-%d' ) ){
        $errors[] = '「生年月日」が正しくない形式です';
    }
    
    // 上記エラーに該当しない場合、
    if(count($errors) === 0) {

        // 入力のパスワードをハッシュ化
        $hash = password_hash($passwd, PASSWORD_DEFAULT); 
        
        insert_user($db, $user_name, $hash, $mail, $sex, $birthdate);

        //正しいページ遷移判断のためにセッション定義
        set_session('permission', 'ok');
        
        redirect_to(COMPLETION_URL);
    }
 
}
// POST送信時の処理終了

$token = get_csrf_token();

// ユーザ登録ページテンプレートファイル読み込み
include_once VIEW_PATH . 'new_account_view.php';