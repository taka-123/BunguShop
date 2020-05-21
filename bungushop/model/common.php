<?php
// sessionの引数指定nameのvalueを取得
function get_session($name) {
    if (isset($_SESSION[$name])) {
        return $_SESSION[$name];
    };
    return '';
}

function get_cookie($name) {
    // Cookie情報からユーザ名を取得
    if (isset($_COOKIE[$name])) {
        return $_COOKIE[$name];
    } 
    return '';
}

// sessionのuser_idに値が有るか => ログインしているかの確認
function is_logined() {
    return get_session('user_id') !== '';
}

// 引数指定のURLへリダイレクト
function redirect_to($url) {
    header('Location: ' . $url);
    exit;
}

// セッションの引数1指定のnameに、引数2指定のvalueを保存
function set_session($name, $value) {
    $_SESSION[$name] = $value;
}

// 特殊文字をHTMLエンティティに変換する
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

// POST送信データの取得
function get_post_data($name) {
    if (isset($_POST[$name]) === TRUE) {
        return $_POST[$name];
    }
    return '';
} 

// FILE送信データの取得
function get_file_data($name){
    if(isset($_FILES[$name]) === true){
        return $_FILES[$name];
    };
    return array();
}

// 引数指定の文字数のランダムな文字列を生成
function get_random_string($length = 20) {
    return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

// トークンの生成
function get_csrf_token() {
    // get_random_string()はユーザー定義関数。
    $token = get_random_string(30);
    // set_session()はユーザー定義関数。
    set_session('csrf_token', $token);
    return $token;
}

// トークンのチェック
function is_valid_csrf_token($token) {
    if ($token === '') {
    return false;
    }
    // get_session()はユーザー定義関数
    return $token === get_session('csrf_token');
}