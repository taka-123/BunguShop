<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>一覧画面</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            h1 {
                text-align: center;
            }
            
            .note {
                text-align: center;
                font-size: 0.9em;
                margin: 0;
                color: red;
            }

            h2 {
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

            .search {
                text-align: center;
            }

            .search span {
                margin : 10px;
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
            
            nav {
                position: relative; 
                overflow: hidden;
            }

            .pagination {
                position: relative;
                left: 50%;
                float: left;
                padding: 0;
            }

            .page-item {
                position: relative;
                left: -50%;
                float: left;
                list-style: none;
                border: 1px solid #5c6b80;
                margin: 5px;
                color: rgb(16,45,40);
            }
            
            .active {
                color: white;
                background-color: rgb(16,45,40);
            }

            .page-link {
                padding: 5px 8px;
                text-decoration: none;
                font-size: 1.1em;
            }

            .item_num {
                text-align: center;
                margin-top: 0;
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
                display: flex;
            }
            
            span {
                padding-right: 10px;
            }
            
            .blank {
                flex: 1;
            }

            .left {
                flex: 1;
                text-align: center;
                right: 10%;
            }
            
            .left img {
                height: 100%;
                max-width: 250px;
            }
            
            .right {
                flex: 2;
                text-align: left;
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

            .card-deck {
                position: relative; 
                overflow: hidden;
                width: 100%;
            }

            .card {
                position: relative;
                float: left;
                height: 100%;
                width: 25%;
                text-align: center;
                border: 1px solid #eee;
                margin: 0 4%;
            }

            .rank {
                font-weight: bold;
                text-align: center;
            }

            .card-header {
                background: #f0faf0;
                padding: 5px;
            }

            .card-body {
                padding: 10px;
                text-align: center;
                vertical-align: middle;
            }

            .card-body p {
                margin: 5px;
            }

            .card-img {
                height: 120px;

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
        <?php 
            if (is_logined()) {
                include VIEW_PATH . 'templates/header_logined.php';
            } else {  
                include VIEW_PATH . 'templates/header.php';
            }
        ?>
        
        <ul class="error">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1>商品紹介</h1>
        
        <?php if (is_logined() === false) { ?>
            <p class="note">※カートに追加するには、<a href="./login.php">ログイン</a>が必要です</p>
        <?php } ?>

        <!--<h2>あなたへのおすすめ文具</h2>-->
        
        <h2>商品検索</h2>
        
        <!-- 検索機能 -->
        <div class="text-center">
            <form method="POST">
                <div class="search">
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
                    <span>
                        <input type="hidden" name="sql_kind" value="search">
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                        <input type="submit" id="search" value="検索">
                    </span>
                </div>
            </form>   
        </div>

        <!-- ソート機能 -->
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

        <!-- ページネーション -->
        <?php print_page_link($now_page, $sort_key, $max_page); ?>
        <p class="item_num">
            「<?php  print $total_items; ?>件中 <?php print $start_num; ?>-<?php print $finish_num; ?>件目の商品」
        </p>

        
        <div class="refine"><?php print entity_str($refine); ?></div>
        <!--検索条件に一致する商品が無かった場合の表示-->
        <?php if ($items === []) { ?>
            <p class="empty">条件に一致する商品はありませんでした</p>
        
        <!--検索条件に一致する商品が有った場合の表示（初期表示）-->
        <?php } else { ?>
            <!--商品の繰り返し表示開始-->
            <?php foreach ($items as $item) { ?>
            <div class="product">
                <div class="blank"></div>
                <div class="left">
                    <img src="<?php print entity_str(IMAGE_PATH . $item['item_img']); ?>" title="<?php print entity_str($item['comment']); ?>">
                </div>
                <div class="right">
                    <p>商品名: <?php print entity_str($item['name']); ?></p>
                    <p>ジャンル: <?php print entity_str($item['genre_name']); ?></p>
                    <p>¥ <?php print number_format(entity_str($item['price'])); ?></p>
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

        <!-- 人気ランキング -->
        <h2 class="ranking">人気ランキング</h2>

        <div class="card-deck">
        <?php foreach ($popular_items as $popular_item) { ?>
            <div class="card">
                <div class="rank">  
                    <?php print $rank++ . ' 位'; ?>
                </div>  
                <div class="card-header">
                    <?php print entity_str($popular_item['name']); ?>
                </div>
                <div class="card-body">
                    <img class="card-img" src="<?php print(IMAGE_PATH . $popular_item['item_img']); ?>">
                    <p>¥ <?php print number_format(entity_str($popular_item['price'])); ?></p>
                    <?php if ($item['stock'] === 0) { ?>
                            <span class="sold_out">売り切れ</span>
                        <?php } else { ?>
                        <form method="POST">
                            <input type="hidden" name="sql_kind" value="cart">
                            <input type="hidden" name="item_id" value="<?php print entity_str($popular_item['item_id']); ?>">
                            <input class="add_amount" type="text" name="amount" value=1>個
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                            <input type="submit" id="insert" value="カートに追加">
                        </form>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>
    </body>
</html>