<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ登録完了画面</title>
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
            }
            
            .welcome {
                flex: 3;
                color: white;
                border-left: 1px solid black;
                text-align: right;
            }
            
            .in_cart {
                flex: 1;
                color: white;
                text-align: center;
                border-right: 1px solid black;
            }

            .log_out {
                flex: 1;
                text-align: center;
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
            
            .in_cart img {
                height: 60%;
                margin: 14px 0;
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
            
            p {
                padding: 10px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <header>
            <a class="logo" href="./login.php">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($login_name); ?>さん</a>！</p>
            </div>
            <div class="in_cart">
                <a href="./cart.php"><img src="img/structure/cart.png"></a>
                
            </div>
            <div class="log_out">
                <a href="./logout.php">ログアウト</a>
            </div>
        </header>
        
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