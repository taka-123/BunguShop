<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';

// セッションスタート
session_start();
// セッション変数からログイン済みか確認
if (!isset($_SESSION['user_id'])) {
    redirect_to(NEW_ACCOUNT_URL);
} else {
    $top_user_name = $_SESSION['user_name'];
}

$user_id = $_SESSION['user_id'];

// 初期化
$errors = [];
    
// 入力データがPOSTで送信された場合の処理開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = get_post_data('mail');

    // 「メールアドレス」について
    // 形式check
    if (filter_var($mail, FILTER_VALIDATE_EMAIL) === FALSE) {
        $errors[] = '不正な形式のメールアドレスです';
    }
    
    // エラーが無かった場合、
    if(count($errors) === 0) {
        // DB接続
        try {
            $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
            // 入力データをDBに登録
            try {
                // ユーザ情報テーブルに追加
                $sql = 'UPDATE bungu_users SET mail = ? WHERE user_id = ?';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1, $mail, PDO::PARAM_STR);
                $stmt->bindValue(2, $user_id, PDO::PARAM_STR);
                $stmt->execute();
                echo 'メールアドレスを変更しました';
            } catch (PDOException $e) {
                throw $e;
            }       
        
        } catch (PDOException $e) {
        $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
        }
        // DB接続終了
        
    }
    // エラーがなかった場合の処理終了
 
}
// POST送信時の処理終了

// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    try {
        // 既に登録されているユーザ名一覧の取得
        $sql = 'SELECT user_name, mail, sex, birthdate 
        FROM bungu_users WHERE user_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
    }
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}

if ($data[0]['sex'] === 0) {
    $sex = '男性';
} else {
    $sex = '女性';
}

// ユーザ登録ページテンプレートファイル読み込み
include_once VIEW_PATH . 'user_info_view.php';