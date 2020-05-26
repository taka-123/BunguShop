<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ログアウト</title>
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
                flex: 5;
                color: white;
                border-left: 1px solid black;
                text-align: center;
            }
            
            .logo img {
                height: 100%;
            }
            
            .welcome p {
                margin: 12px 0;
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
                <p>Welcome to  " BUNGU ONLINE SHOP " !!</p>
            </div>
            <a class="logo" href="#">
                <img src="img/structure/logo1.png">
            </a>
        </header>
        
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