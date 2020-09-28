<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP 購入履歴</title>
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <link href="./css/history.css" rel="stylesheet" type="text/css"/>
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
        
        <h1>BUNGU SHOP 購入履歴</h1>
        <p><a href="./itemlist.php">商品一覧ページへ</a></p>
        
        <div class="container">
            <table>
                <caption>購入履歴一覧</caption>
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                    <th>操作</th>
                </tr>
                <!--商品の繰り返し表示開始-->
                <?php foreach ($orders as $order) { ?>
                <tr>
                    <!--注文番号-->
                    <td>
                        <?php print entity_str($order['order_id']); ?>
                    </td>
                    <!--購入日時-->
                    <td>
                        <?php print entity_str($order['create_datetime']); ?>
                    </td>
                    <!--合計金額-->
                    <td class="total">
                        <?php print number_format(entity_str($order['total_price'])); ?> 円
                    </td>
                    <!--明細-->
                    <form method="POST" action="./history_detail.php">
                        <td>
                            <input type="hidden" name="order_id" value="<?php print entity_str($order['order_id']); ?>">
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="submit" value="明細">
                        </td>
                    </form>
                </tr>
                <?php } ?>
                <!--商品の繰り返し表示終了-->
            </table>        
        </div>
    </body>
</html>
