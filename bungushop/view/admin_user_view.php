<!DOCYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP ユーザ管理ページ</title>
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
            
            
            table, th, td {
                border: 1px solid;
                border-collapse: collapse;
            }
            
            td {
                height: 40px;
                padding: 0 10px;
                text-align: center;
            }
            
            caption {
                text-align: left;
                font-size: 1.2em;
                padding: 10px 0;
            }
        </style>
    </head> 
    
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <h1>BUNGU SHOP ユーザ管理ページ</h1>
        <p><a href="./admin_item.php">商品管理ページへ</a></p>
        
        <table>
            <caption>ユーザ情報一覧</caption>
            <tr>
                <th>ユーザ名</th>
                <th>パスワード</th>
                <th>メールアドレス</th>
                <th>性別</th>
                <th>生年月日</th>
                <th>登録日時</th>
            </tr>
            <!--ユーザ情報の繰り返し表示開始-->
            <?php foreach ($users as $user) { ?>
            <tr>
                <!--ユーザ名-->
                <td>
                    <?php print entity_str($user['user_name']); ?>
                </td>
                <!--パスワード-->
                <td>
                    <?php print entity_str($user['passwd']); ?>
                </td>
                <!--メールアドレス-->
                <td>
                    <?php print entity_str($user['mail']); ?>
                </td>
                <!--性別-->
                <td>
                <?php if ($user['sex'] === 0) { ?>
                    <p>男性</p>
                <?php } else { ?>
                    <p>女性</p>
                <?php } ?>
                </td>
                <!--生年月日-->
                <td>
                    <?php print entity_str($user['birthdate']); ?>
                </td>
                <!--登録日-->
                <td>
                    <?php print entity_str($user['create_datetime']); ?>
                </td>
            </tr>
            <?php } ?>
            <!--ユーザ情報の繰り返し表示終了-->
        </table>        
    </body>
</html>
