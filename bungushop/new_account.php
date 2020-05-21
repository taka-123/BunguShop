<?php
// セッションスタート
session_start();
// セッション変数からログイン済みか確認
if (!isset($_SESSION['user_id'])) {
    $top_user_name = 'ゲスト';
} else {
    $top_user_name = $_SESSION['user_name'];
}

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';

// 初期化
$errors = [];

// 日付取得（生年月日範囲指定の為）
$twenty = date("Y-m-d",strtotime("-20 year")); // 20歳
$hundred_twenty = date("Y-m-d",strtotime("-120 year")); // 120歳

// 正規表現
$non_alphanum = '/[^a-zA-Z0-9]/';  // 「半角英数字」以外を含む

// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    try {
        // 既に登録されているユーザ名一覧の取得
        $sql = 'SELECT user_name FROM bungu_users';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $user_name_list[] = $row['user_name'];
        }
    } catch (PDOException $e) {
        throw $e;
    }
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}

// 入力データがPOSTで送信された場合の処理開始
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $user_name = get_post_data('user_name');
    $passwd = get_post_data('passwd');
    $mail = get_post_data('mail');
    $sex = get_post_data('sex');
    $birth = get_post_data('birth');

    //
    /// エラーチェック
    //
    // 「ユーザ名」について
    // 文字数、種類check
    if (mb_strlen($user_name) < 6 || mb_strlen($user_name) > 15 || preg_match($non_alphanum, $user_name)) {
        $errors[] = '「ユーザ名」は半角英数字6～15文字にしてください'; 
    }
    // 希望user_nameは、既に登録されているものと重複しないかcheck
    elseif(in_array($user_name, $user_name_list)) {
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
    if ($birth === '') {
        $errors[] = '「生年月日」を入力してください';
    }
    // 形式check
    elseif( !strptime( $birth, '%Y-%m-%d' ) ){
        $errors[] = '「生年月日」が正しくない形式です';
    }
    
    // エラーが無かった場合、
    if(count($errors) === 0) {
        // 正しいページ遷移判断のためにセッション定義
        $_SESSION['permission'] = 'ok';
        // DB接続
        try {
            $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
            // 入力データをDBに登録
            try {
                // ユーザ情報テーブルに追加
                $sql = 'INSERT INTO bungu_users(user_name, passwd, mail, sex, birthdate, create_datetime, update_datetime) VALUES(?, ?, ?, ?, ?, now(), now())';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
                $stmt->bindValue(2, $passwd, PDO::PARAM_STR);
                $stmt->bindValue(3, $mail, PDO::PARAM_STR);
                $stmt->bindValue(4, $sex, PDO::PARAM_INT);
                $stmt->bindValue(5, $birth, PDO::PARAM_STR);
                $stmt->execute();
                // 登録成功 → 登録完了画面へ移動 
                header('Location: completion.php');
                exit;
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



// ユーザ登録ページテンプレートファイル読み込み
include_once './view/login_view.php';

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ新規登録画面</title>
        <meta charset="UTF-8">
        <style>
            header {
                display: flex;
                height: 70px;
                border-bottom: 1px solid;
                background-color: rgb(16,45,40);
            }
            
            .logo {
                flex: 1;
                text-align: center;
                border-right: 1px solid black;
            }
            
            .welcome {
                flex: 5;
                color: white;
                text-align: center;
            }
            
            .log_out {
                flex: 1.1;
                text-align: center;
                border-left: 1px solid black;
                position: relative;
            }
            
            .logo img {
                height: 100%;
            }
            
            .welcome p {
                margin: 23px 0;
            }
            
            .welcome a {
                color: white;
            }
            
            .log_out a {
                display: block;
                height: 100%;
                width:100%;
                position: absolute;
                top: 0;
                left: 0;
                line-height: 70px;
                color: white;
                text-decoration: none;
            }
            
            
            h1 {
                padding: 20px 0;
                text-align: center;
            } 
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            p {
                text-align: center;
            }
            
            
            form {
            	width: 400px;
            	border:1px solid #ccc;
                padding: 10px 0;
            	margin: 10px auto;
            	background: #f0faf0;
            }
            
            ul {
            	padding:0;
            	margin:0;
        	}
        	
        	li {
            	list-style:none;
            	padding: 7px;
        	}
            
            label {
                display: inline-block;
                width: 140px;
                margin: 5px 10px;
                text-align: right;
                text-align-last: justify;
            }
            
            label.select {
                width: 70px;
            }
            
            input.text {
            	width: 190px;
            	padding: 3px 5px;
            	margin: 0;
            	border-radius: 3px;
        	}
            
            input[type="submit"] {
            	font-size: 100%;
            	font-weight: bold;
            	width: 140px;
                background-color: orange;
            	padding: 7px 0;
            	margin: 5px 0;
            	border-style:none;
            	border-radius: 5px;
        	}
        </style>
    </head>
    <body>
        <header>
            <a class="logo" href="./itemlist.php">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($top_user_name); ?>さん</a>！</p>
            </div>
            <div class="log_out">
                <a href="./logout.php">ログアウト</a>
            </div>
        </header>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>新規登録</h1>
        <p><a href="./itemlist.php">商品一覧ページへ</a></p>
        
        <form method="POST">
            <ul>
                <li>
                    <label for="user_name">ユ ー ザ 名:</label>
                    <input type="text" name="user_name" id="user_name" class="text" placeholder="6~15文字の半角英数字" minlength="6" maxlength="15" pattern="^[0-9A-Za-z]+$" required>
                </li>
                <li>
                    <label for="passwd">パスワード:</label>
                    <input type="password" name="passwd" id="passwd" class="text" placeholder="6~15文字の半角英数字" minlength="6" maxlength="15" pattern="^[0-9A-Za-z]+$" required>
                </li>
                <li>
                    <label for="mail">メールアドレス :</label>
                    <input type="mail" name="mail" id="mail" class="text" required>
                </li>
                <li>
                    <label for="sex">性 　 　別:</label>
                    <label class="select">
                        <input type="radio" name="sex" value=0 id="sex" required>男性
                    </label>
                    <label class="select">
                        <input type="radio" name="sex" value=1>女性
                    </label>
                </li>
                <li>
                    <label for="birth">生 年 月 日:</label>
                    <input type="date" name="birth" id="birth" min=<?php print $hundred_twenty; ?> max=<?php print $twenty; ?> class="text" required>
                </li>
            </ul>
            <p>
                <input type="submit" value="登録">
            </p>
        </form>
    </body>
</html>