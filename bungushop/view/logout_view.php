<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ログアウト</title>
        <meta charset="UTF-8">
        <style>
            /* headerテンプレート用CSS  */
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

            .nav-item {
                flex: 1;
                text-align: center;
                border-left: 1px solid black;
                position: relative;
            }
            
            .logo img {
                height: 100%;
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
            /* headerテンプレート用CSS終了 */            
            

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