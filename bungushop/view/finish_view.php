<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ショッピングカート</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            h1 {
                padding: 10px 0;
                text-align: center;
            }
            
            .finish {
                text-align: center;
                font-size: 1.1em;
            }
            
            .total {
                color
                font-weight: bold;
                text-align: center;
                font-size: 1.3em;
            }
            
            .total span {
                color: red;
            }

            .empty {
                text-align: center;
                color: red;
            }
            
            .serch_form {
                border: 2px solid;
                padding: 10px 15px;
                margin: 5px 15px;
            }
            
            .product {
                background-color: #f0faf0;
                height: 130px;
                padding: 10px 15px;
                margin: 5px 10px;
                display:flex;
            }
            
            span {
                padding-right: 10px;
            }
            
            .left {
                flex: 1;
                text-align: center
            }
            
            .left img {
                height: 95%;
            }
            
            .right {
                flex: 1;
                text-align: left
            }
            
            .right p{
                margin: 7px 0;
                text-align: left
            }
            
            .sold_out {
                color: red;
            }
                        
            .amount {
                width: 45px;
                text-align: right;
            }
            
            .sub_total {
                color: orange;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
        <h1>購入完了</h1>
        <p class="finish">
            ありがとうございます！！
            <br>
            注文が確定されました！
        </p>
        
        <p class="total">
            合計金額: <span>¥ <?php print number_format($total_price); ?></span>
        </p>

        <form method="POST">
        <!--商品の繰り返し表示開始-->
        <?php foreach ($carts as $cart) { ?>
            <div class="product">
                <div class="left">
                    <img src="<?php print entity_str(IMAGE_PATH . $cart['item_img']) ?>">
                </div>
                <div class="right">
                    <p>商品名: <?php print entity_str($cart['name']); ?></p>
                    <p>
                        <span class="price">
                            ¥ <?php print number_format(entity_str($cart['price'])); ?>
                        </span>
                        <span>
                            ×
                        </span>
                        <span>
                            <?php print entity_str($cart['amount']); ?> 個
                        </span>
                    </p>
                    <p>
                        =
                        <span class="sub_total">
                            <!--小計を表示-->
                            ¥ <?php print number_format(entity_str($cart['sub_total'])) ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php } ?>
        <!--商品の繰り返し表示終了-->
        
                


    </body>
</html>