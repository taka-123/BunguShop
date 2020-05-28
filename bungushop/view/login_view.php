<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ログイン画面</title>
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
                padding: 0 10px;
                border-right: 1px solid black;
            }

            .welcome {
                flex: 5;
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
            
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }

            
            h1 {
                padding: 20px 0;
                text-align: center;
            } 
            
            p {
                padding: 10px 0;
                text-align: center;
            }

            form {
            	width: 400px;
            	border:1px solid #ccc;
                padding: 10px 0;
            	margin: 10px auto;
            	background: #f0faf0;
            	text-align: center;
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
            
            input.text {
            	width: 190px;
            	padding: 3px 5px;
            	margin: 0;
            	border-radius: 3px;
        	}
        	
            .omit {
                display: block;
                margin-bottom: 10px;
                font-size: 0.8em;
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
                <li class="omit">
                    <input type="checkbox" name="cookie_check" value="checked">次回からユーザ名の入力を省略
                </li>
            </ul>
            <input type=submit value="ログイン">
        </form>

        <p>未登録の方は以下から<br>アカウントの作成をお願いします</p>
        <p><a href="<?php print NEW_ACCOUNT_URL; ?>">ユーザ登録ページへ</a></p>
    </body>
</html>