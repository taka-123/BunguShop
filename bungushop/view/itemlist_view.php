<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>一覧画面</title>
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
            
            h2 {
                margin: 0;
                text-align: center;
            }
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            
            .serch_form {
                width: 70%;
                border: 2px solid;
                padding: 15px 0 5px;
                margin: 10px auto 20px;
                text-align: center;
            }
            
            #insert {
            	font-size: 90%;
            	width: 120px;
                background-color: orange;
            	margin: 5px 10px;
            	border-style:none;
            	border-radius: 5px;
        	}
            
            .refine {
                text-align: center;
            }
            
            
            .empty {
                text-align: center;
                color: red;
            }
            
            .product {
                background: #f0faf0;
                height: 130px;
                padding: 20px 15px;
                margin: 5px 10px;
                display:flex;
            }
            
            span {
                padding-right: 10px;
            }
            
            .left {
                flex: 1;
                text-align: center;
            }
            
            .left img {
                height: 100%;
            }
            
            .right {
                flex: 1;
                text-align: left
            }
            
            .right p {
                margin: 7px 0;
            }
            
            .sold_out {
                color: red;
            }
                        
            .add_amount {
                width: 45px;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <header>
            <a class="logo" href="#">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($login_name); ?>さん</a>！</p>
            </div>
            <div class="in_cart">
                <a href="./cart.php">
                    <img src="img/structure/cart.png">
                    <?php print $total_amount; ?>
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
        
        <h1>商品紹介</h1>
        
        <!--<h2>あなたへのおすすめ文具</h2>-->
        
        <h2>商品検索</h2>
        
        <div class="serch_form">
            <form method="POST">
                <span>
                    <label for="name">名前:</label>
                    <input type="text" name="name" id="name">
                </span>
                <span>
                    <label for="genre">ジャンル:</label>
                    <select name="genre_id" id="genre">
                    <?php foreach($genres as $genre) { ?>
                        <option value="<?php print $genre['genre_id']; ?>"><?php print $genre['genre_name']; ?></option>
                    <?php } ?>  
                    </select>
                </span>
                <!--<span>-->
                <!--    <label for="price">価格帯:</label>-->
                <!--    <input type="text" name="price" id="price">-->
                <!--</span>-->
                <span class="serch">
                    <input type="hidden" name="sql_kind" value="serch">
                    <input type="submit" id="serch" value="検索">
                </span>
            </form>   
        </div>
        
        <p class="refine"><?php print entity_str($refine); ?></p>
        <!--検索条件に一致する商品が無かった場合の表示-->
        <?php if ($items === []) { ?>
            <p class="empty">条件に一致する商品はありませんでした</p>
        
        <!--検索条件に一致する商品が有った場合の表示（初期表示）-->
        <?php } else { ?>
            <!--商品の繰り返し表示開始-->
            <?php foreach ($items as $item) { ?>
            <div class="product">
                <div class="left">
                    <img src="<?php print entity_str($img_dir . $item['item_img']); ?>" title="<?php print entity_str($item['comment']); ?>">
                </div>
                <div class="right">
                    <p>商品名: <?php print entity_str($item['name']); ?></p>
                    <p>ジャンル: <?php print entity_str($item['genre_name']); ?></p>
                    <p>¥ <?php print entity_str($item['price']); ?></p>
                    <p>
                        <?php if ($item['stock'] === 0) { ?>
                            <span class="sold_out">売り切れ</span>
                        <?php } else { ?>
                        <form method="POST">
                            <input type="hidden" name="sql_kind" value="cart">
                            <input type="hidden" name="item_id" value="<?php print entity_str($item['item_id']); ?>">
                            <input class="add_amount" type="text" name="amount" value=1>個
                            <input type="submit" id="insert" value="カートに追加">
                        </form>
                        <?php } ?>
                    </p>
                </div>
            </div>
            <?php } ?>
            <!--商品の繰り返し表示終了-->
        <?php } ?>
        <!--検索条件に一致する商品が有った場合の表示終了-->
    </body>
</html>