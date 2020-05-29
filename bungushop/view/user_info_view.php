<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザアカウント情報</title>
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
            
            
            dt {
                display: inline-block;
                width: 140px;
                padding: 7px;
                margin: 5px;
                text-align: right;
                text-align-last: justify;
                float:left;
                clear:both;
            }
            
            dd {
                display: inline-block;
                width: 200px;
            	padding: 7px;
            	margin: 5px;
            }
            
            .mail_change {
                display: flex;
            }
            
            input.text {
                flex: 4;
            	width: 170px;
            	padding: 0 5px;
            	border-radius: 3px;
            	font-size: 90%;
        	}
        	
        	input[type="submit"] {
        	    flex: 1;
        	    font-size: 70%;
                background-color: orange;
            	padding: 3px 7px;
            	margin-left: 5px;
            	border-style:none;
            	border-radius: 3px;
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
        
        <h1>ユーザアカウント情報</h1>
        <p><a href="./itemlist.php">商品一覧ページへ</a></p>
        
        <form method="POST">
            <dl>
                <dt>ユ ー ザ 名:</dt>
                <dd><?php print $user['user_name']; ?></dd>
               
                <dt>パスワード:</dt>
                <dd>**********</dd>
               
                <dt><label for="mail">メールアドレス :</label></dt>
                <dd class="mail_change">
                    <input type="mail" name="mail" id="mail" class="text" value="<?php print $user['mail']; ?>" required>
                    <input type="submit" value="変更"> 
                </dd>

                <dt>性 　 　別:</dt>
                <dd><?php print $sex; ?></dd>

                <dt>生 年 月 日:</dt>
                <dd><?php print $user['birthdate']; ?></dd>
            </dl>
        </form>
    </body>
</html>