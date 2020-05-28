<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ新規登録画面</title>
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
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>新規登録</h1>
        <p><a href="<?php print HOME_URL; ?>">商品一覧ページへ</a></p>
        
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
                    <input type="date" name="birth" id="birth" min=<?php print $min_date; ?> max=<?php print $max_date; ?> class="text" required>
                </li>
            </ul>
            <p>
                <input type="submit" value="登録">
            </p>
        </form>
    </body>
</html>