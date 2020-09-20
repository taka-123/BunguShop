<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ショッピングカート</title>
        <meta charset="UTF-8">
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <link href="./css/finish.css" rel="stylesheet" type="text/css"/>
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

        <p><a href="./history.php">購入履歴ページへ</a></p>
    </body>
</html>