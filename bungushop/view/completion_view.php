<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ登録完了画面</title>
        <meta charset="UTF-8">
        <style>
            /* header_loginedテンプレート用  */
            header {
                display: flex;
                height: 75px;
                border-bottom: 1px solid;
                background-color: rgb(16,45,40);
            }
            
            .logo {
                flex: 1;
                text-align: center;
                border-right: 1px solid black;
            }
            
            .welcome {
                flex: 4;
                color: white;
                text-align: center;
            }
            
            .in_cart {
                flex: 1;
                color: white;
                text-align: center;
                position: relative;
            }

            .nav-item {
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
            
            .in_cart a {
                display: block;
                height: 100%;
                width: 100%;
                position: absolute;
                top: 0;
                left: 0;
                line-height: 10px;
                color: #f06704;
                font-weight: bold;
                text-decoration: none;
            }
            
            .in_cart img {
                height: 60%;
                padding: 14px 0;
            }
            
            .nav-item a {
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
            /* header_loginedテンプレート用終了 */

            
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