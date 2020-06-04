<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP 購入履歴</title>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            h1, p {
                text-align: center;
            } 
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            .container {
                width: 70%;
                margin: 0 auto;
            }
            
            table {
                width: 80%;
                margin: 10px auto;
            }
            
            caption {
                text-align: center;
                font-size: 1.2em;
                padding: 10px 0;
            }
            
            input {
                display: block;
                padding: 3px 15px;
                margin: 0 auto;
                font-size: 0.9em;
                background-color: orange;
                color: black;
                border-style: 2px solid black;
                border-radius: 5px;
            }
            
            table, th, td {
                border: 1px solid;
                border-collapse: collapse;
            }

            th {
                height: 10px;
                text-align: center;
                padding: 5px;
            }
            
            td {
                height: 35px;
                text-align: center;
                padding: 5px;
            }

            .total {
                font-weight: bold;
                text-align: right;
                padding-right: 5%;
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
                    <form method="POST">
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
