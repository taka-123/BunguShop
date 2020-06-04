<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP 購入明細</title>
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

            .detail td {
                height: 80px;
            }
            
            td img {
                height: 100%;
            }

            .total span {
                color: red;
                font-weight: bold;
                font-size: 1.3rem;
            }

            .sub_total span {
                font-weight: bold;
                font-size: 1.1rem;
            }
        </style>
    </head> 
    
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <h1>BUNGU SHOP 購入明細</h1>
        <p><a href="./history.php">購入履歴ページへ</a></p>
        
        <div class="container">
            <table>
                <caption>購入明細</caption>
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                </tr>
                <tr>
                    <!--注文番号-->
                    <td>
                        <?php print $order_id; ?>
                    </td>
                    <!--購入日時-->
                    <td>
                        <?php print entity_str($order['create_datetime']); ?>
                    </td>
                    <!--合計金額-->
                    <td class="total">
                        <span>
                        <?php print number_format(entity_str($order['total_price'])); ?> 円
                        </span>
                    </td>
                </tr>
            </table>        
            <table class="detail">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>購入価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
                <!--商品の繰り返し表示開始-->
                <?php foreach ($details as $detail) { ?>
                <tr>
                    <!--商品画像-->
                    <td>
                        <img src="<?php print entity_str(IMAGE_PATH . $detail['item_img']) ?>">
                    </td>
                    <!--商品名-->
                    <td>
                        <?php print entity_str($detail['name']); ?>
                    </td>
                    <!--購入時の商品価格-->
                    <td>
                        <?php print number_format(entity_str($detail['purchase_price'])); ?> 円
                    </td>
                    <!--購入数-->
                    <td>
                        <?php print number_format(entity_str($detail['quantity'])); ?> 個
                    </td>
                    <!--小計-->
                    <td class="sub_total">
                        <span>
                        <?php print number_format(entity_str($detail['sub_total'])); ?> 円
                        </span>
                    </td>
                </tr>
                <?php } ?>
                <!--商品の繰り返し表示終了-->
            </table>        
        </div>
    </body>
</html>
