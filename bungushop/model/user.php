<?php
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'db.php';

// 全ユーザー情報を取得
function get_all_users($db) {
    $sql = "
        SELECT
            user_id,
            user_name,
            passwd,
            mail,
            sex,
            birthdate,
            create_datetime
        FROM
            bungu_users
    ";
    return fetch_all_query($db, $sql);
}

// 指定のユーザIDから、ユーザー情報を取得
function get_user($db, $user_id) {
    $sql = "
        SELECT
            user_id,
            user_name,
            passwd,
            mail,
            sex,
            birthdate
        FROM
            bungu_users
        WHERE
            user_id = ?
        LIMIT 1
    ";
    $params = array($user_id);
    return fetch_query($db, $sql, $params);
}

// 指定のユーザ名から、ユーザー情報を取得
function get_user_by_name($db, $user_name) {
    $sql = "
        SELECT
            user_id,
            user_name,
            passwd,
            mail,
            sex,
            birthdate
        FROM
            bungu_users
        WHERE
            user_name = ?
        LIMIT 1
    ";
    $params = array($user_name);
    return fetch_query($db, $sql, $params);
}

// 「ログイン認証用関数」
// ユーザ名から情報を取得し、入力のパスワードと登録のそれが一致した場合、
// セッションにユーザIDを保存し、取得したユーザ情報を返す。
// ユーザ名未登録またはパスワード不一致の場合、falseを返す。
function login_as($db, $user_name, $passwd){
    $user = get_user_by_name($db, $user_name);
    if($user === false || $user['passwd'] !== $passwd){
        return false;
    }
    set_session('user_id', $user['user_id']);
    return $user;
}

// ログイン中のユーザIDから、ユーザ情報を取得
function get_login_user($db) {
    $login_user_id = get_session('user_id');
    
    return get_user($db, $login_user_id);
}

// ログイン中のユーザ名が、管理者用のユーザ名と一致するか確認
function is_admin($user) {
    return $user['user_name'] === USER_NAME_ADMIN;
}