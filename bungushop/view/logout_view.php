<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ログアウト</title>
        <meta charset="UTF-8">
        <link href="./css/header.css" rel="stylesheet" type="text/css"/>
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
        <?php include VIEW_PATH . 'templates/header.php'; ?>
        
        <h1>ログアウト</h1>
        <p>
            ログアウトしました
            <br>
            またのご利用をお待ちしております
        </p>
        <p>
            <a href="./login.php">ログインページ</a>
        </p>
    </body>
</html>