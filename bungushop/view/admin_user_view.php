<!DOCYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP ユーザ管理ページ</title>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
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
