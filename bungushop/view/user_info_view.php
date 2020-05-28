<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザアカウント情報</title>
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
                <dd><?php print $data[0]['user_name']; ?></dd>
               
                <dt>パスワード:</dt>
                <dd>**********</dd>
               
                <dt><label for="mail">メールアドレス :</label></dt>
                <dd class="mail_change">
                    <input type="mail" name="mail" id="mail" class="text" value="<?php print $data[0]['mail']; ?>" required>
                    <input type="submit" value="変更"> 
                </dd>

                <dt>性 　 　別:</dt>
                <dd><?php print $sex; ?></dd>

                <dt>生 年 月 日:</dt>
                <dd><?php print $data[0]['birthdate']; ?></dd>
            </dl>
        </form>
    </body>
</html>