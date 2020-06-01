<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ登録完了画面</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            h1 {
                padding: 20px 0;
                text-align: center;
            } 
            
            p {
                padding: 10px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
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