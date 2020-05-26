<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>ショッピングカート</title>
        <meta charset="UTF-8">
        <style>
            header {
                display: flex;
                height: 70px;
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

            .log_out {
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
            
            .log_out a {
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
            
            
            h1 {
                padding: 20px 0;
                text-align: center;
            }
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            .empty {
                text-align: center;
            }
            
            caption {
                text-align: left;
                font-size: 1.2em;
                padding: 10px 0;
            }
            
            table {
                width: 90%;
                margin: 0 auto;
            }
            
            td {
                height: 100px;
                text-align: center;
                border-bottom: 1px solid blue;
                padding: 10px 0;
            }
            
            td img {
                height: 100%;
            }
            
            .amount_change {
                width: 45px;
                text-align: right;
            }
            
            .buy {
                display: flex;
                margin-bottom: 20px;
            }
            
            .total {
                flex: 1;
                text-align: right;
                font-size: 1.2rem;
            }
            
            .total span {
                color: red;
                font-weight: bold;
                font-size: 1.3rem;
            }
            
            .purchase {
                flex: 1;
                text-align: left;
                margin-top: 10px;
                margin-left: 40px;
            }
            
            input#purchase {
            	font-size: 100%;
            	font-weight: bold;
            	width: 140px;
                background-color: orange;
            	padding: 7px 0;
            	margin: 5px 0;
            	border-style:none;
            	border-radius: 5px;
        	}
        </style>
    </head>
    
    <body>
        <header>
            <a class="logo" href="./itemlist.php">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($login_name); ?>さん</a>！</p>
            </div>
            <div class="in_cart">
                <a href="./cart.php">
                    <img src="img/structure/cart.png">
                    <?php print $total_amount ?>
                </a>
            </div>
            <div class="log_out">
                <a href="./logout.php">ログアウト</a>
            </div>
        </header>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>ショッピングカート</h1>
        
    <!--$data配列が空だった場合、-->
    <?php if (empty($carts)) { ?>
        
        <p class="empty">商品はありません</p>
        
    <!--$data配列が空でなければ、-->
    <?php } else { ?>
        
        <table>
            <tr>
                <th class="item_img"></th>
                <th></th>
                <th>値段</th>
                <th>数量</th>
                <th class="delete_form"></th>
            </tr>

            <!--商品の繰り返し表示開始-->
            <?php foreach ($carts as $cart) { ?>
            <tr>
                <!--商品画像-->
                <td>
                    <img src="<?php print entity_str($img_dir . $cart['item_img']) ?>">
                </td>
                <!--商品名-->
                <td>
                    <?php print entity_str($cart['name']); ?>
                </td>
                <!--値段-->
                <td>
                    <?php print number_format(entity_str($cart['price'])); ?>円
                </td>
                <!--数量-->
                <form method="POST">
                    <td>
                        <input class="amount_change" type="text" name="amount" value="<?php print entity_str($cart['amount']); ?>">個
                        <input type="hidden" name="sql_kind" value="amount_update">
                        <input type="hidden" name="item_id" value="<?php print entity_str($cart['item_id']); ?>">
                        <input type="submit" value="変更">                        
                    </td>
                </form>
                <!--小計を計算＆配列に格納（表示はしない）-->
                <?php $sub_total_list[] = $cart['price'] * $cart['amount']; ?>
                <!--削除-->
                <form method="POST">
                    <td>
                        <input type="hidden" name="sql_kind" value="delete">
                        <input type="hidden" name="cart_id" value="<?php print entity_str($cart['cart_id']); ?>">
                        <input type="hidden" name="item_id" value="<?php print entity_str($cart['item_id']); ?>">
                        <input type="submit" value="削除">                        
                    </td>
                </form>
            </tr>
            <?php } ?>
            <!--商品の繰り返し表示終了-->
            
            <!--合計金額-->
            <?php foreach ($sub_total_list as $sub_total) {
                $total += $sub_total; 
            } ?>
            <div class ="buy">
                <p class="total">合計金額: <span>¥ <?php print number_format($total); ?></span></p>
                <div class="purchase">
                    <form method="POST">
                        <input type="hidden" name="sql_kind" value="purchase">
                        <input type="submit" value="購入する" id="purchase">
                    </form>
                </div>
            </div>
            
        </table>
    
    <?php } ?>
    <!--$data配列が空でなかった場合の表示終了、-->
    
    </body>
</html>