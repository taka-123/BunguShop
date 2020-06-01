<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザ新規登録画面</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
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
                <input type="hidden" name="token" value="<?php print $token; ?>">
                <input type="submit" value="登録">
            </p>
        </form>
    </body>
</html>