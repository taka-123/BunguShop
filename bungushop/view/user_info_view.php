<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ユーザアカウント情報</title>
        <meta charset="UTF-8">
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <link href="./css/user_info.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined_view.php'; ?>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>ユーザアカウント情報</h1>
        <p><a href="./itemlist.php">商品一覧ページへ</a></p>
        
        <form method="POST" onSubmit="return checkSubmit()">
            <dl>
                <dt>ユ ー ザ 名:</dt>
                <dd><?php print $user['user_name']; ?></dd>
               
                <dt>パスワード:</dt>
                <dd>**********</dd>
               
                <dt><label for="mail">メールアドレス :</label></dt>
                <dd class="mail_change">
                    <input type="mail" name="mail" id="mail" class="text" value="<?php print $user['mail']; ?>" required>
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                    <input type="submit" value="変更"> 
                </dd>

                <dt>性 　 　別:</dt>
                <dd><?php print $sex; ?></dd>

                <dt>生 年 月 日:</dt>
                <dd><?php print $user['birthdate']; ?></dd>
            </dl>
        </form>
        <script type="text/javascript">
            function checkSubmit() {
                return confirm("変更しても良いですか？");
            }
        </script>
    </body>
</html>