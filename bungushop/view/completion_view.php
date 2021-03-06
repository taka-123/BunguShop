<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ登録完了画面</title>
        <meta charset="UTF-8">
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header.css" rel="stylesheet" type="text/css"/>
        <link href="./css/guest.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_view.php'; ?>
        
        <h1>ユーザ登録完了</h1>
        <p>
            アカウント作成に成功しました！
            <br>ログインページからログインしてください
        </p>
        <p>
            <a href="./login.php">ログインページへ</a>
        </p>
    </body>
</html>