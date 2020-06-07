<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>一覧画面</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
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
            
            
            .text-center {
                width: 70%;
                border: 2px solid;
                padding: 15px 0;
                margin: 10px auto;
                text-align: center;
            }

            .text-right {
                text-align: right;
                margin-right: 10px;
            }
            
            .select {
                width: 150px;
                height: 25px;
                font-size: 15px;
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <script>
            $(function(){
                $("#sort_select").change(function(){
                    $("#sort_form").submit();
                });
            });
        </script>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        
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
        
        <div class="text-center">
            <form method="POST">
                <span>
                    <label for="name">名前:</label>
                    <input type="text" name="name" id="name">
                </span>
                <span>
                    <label for="genre">ジャンル:</label>
                    <select name="genre_id" class="select" id="genre">
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
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                    <input type="submit" id="serch" value="検索">
                </span>
            </form>   
        </div>

        <div class="text-right">
            <form method="GET" action="./itemlist.php" id="sort_form">
                <select name="sort_key" class="select" id="sort_select">
                <?php foreach(SORT_TYPES as $key => $sort){ ?>
                    <!-- 現在のソート条件のものを固定表示('selected') -->
                    <option <?php if ($sort_key === $key){ print 'selected'; }?> value=<?php print $key; ?>>
                        <?php print $sort; ?>
                    </option>
                <?php } ?>
                </select>
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
                    <img src="<?php print entity_str(IMAGE_PATH . $item['item_img']); ?>" title="<?php print entity_str($item['comment']); ?>">
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
                            <input type="hidden" name="token" value="<?php print $token; ?>">
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