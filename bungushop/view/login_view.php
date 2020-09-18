<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ログイン画面</title>
        <meta charset="UTF-8">
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header.css" rel="stylesheet" type="text/css"/>
        <link href="./css/guest.css" rel="stylesheet" type="text/css"/>
        <link href="./css/login.css" rel="stylesheet" type="text/css"/>
    <style>
        .special {
            color: rgb(220, 2, 10);
            font-size: 0.9rem;
        }
    </style>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header.php'; ?>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>ログインページ</h1>
        <p>登録済みの方はログインしてください</p>
        <form method="POST">
            <ul>
                <li>
                    <label for="user_name">ユーザ名:</label>
                        <input type="text" name="user_name" id="user_name" value="<?php print $cookie_name; ?>">
                </li>
                <li>
                    <label for="passwd">パスワード:</label>
                        <input type="password" name="passwd" id="passwd" value="">
                </li>
                <!-- ポートフォリオ用特別設定 -->
                <span class="special">※admin / admin で管理者としてログインできます。<br>（全ページ閲覧可）</span>
                <!--  -->
                <li class="omit">
                    <input type="checkbox" name="cookie_check" value="checked">次回からユーザ名の入力を省略
                </li>
            </ul>
            <input type="hidden" name="token" value="<?php print $token; ?>">
            <input type=submit value="ログイン">
        </form>

        <p>未登録の方は以下から<br>アカウントの作成をお願いします</p>
        <p><a href="<?php print NEW_ACCOUNT_URL; ?>">ユーザ登録ページへ</a></p>
    </body>
</html>