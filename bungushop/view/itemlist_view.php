<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>一覧画面</title>
        <meta charset="UTF-8">
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            /* 共通 */
            body {
                background-color: #f0faf0;
            }

            .no_style {
                list-style: none;
                padding: 0;
            }

            .fz-14 {
                font-size: 14px;
            } 

            .fz-16 {
                font-size: 16px;
            } 
            
            .red {
                color: red;
            }

            .bg-white {
                background-color: white;
            }

            .bb-dot {
                border-bottom: 1px dotted #ccc;
            }

            .h-28 {
                height: 28px;
            }

            .lh-32 {
                line-height: 32px;
            }

            .w-130 {
                width: 130px;
            }

            .p-0 {
                padding: 0;
            }

            .pd-6 {
                padding: 6px;
            }
            
            .pd-10 {
                padding: 10px;
            }
            
            .pd-20 {
                padding: 20px;
            }

            .m-0 {
                margin: 0;
            }

            .m-6 {
                margin: 6px;
            }

            .mx-30 {
                margin-left: 30px;
                margin-right: 30px;
            }

            .my-20 {
                margin-top: 20px;
                margin-bottom: 20px;
            }
            
            .tx-center {
                text-align: center;
            }
            
            .tx-left {
                text-align: left;
            }
            
            .tx-right {
                text-align: right;
            }
            
            .flex {
                display: flex;
            }

            /* ページネーション用 */
            nav {
                position: relative; 
                overflow: hidden;
            }
            
            .pagination {
                position: relative;
                left: 50%;
                float: left;
            }

            .page-item {
                position: relative;
                left: -50%;
                float: left;
                list-style: none;
                border: 1px solid #5c6b80;
                margin: 6px;
            }
            
            .page-link {
                display: block;
                color: rgb(16,45,40);
                text-decoration: none;
                font-size: 18px;
                padding: 2px 8px;
            }
            
            .disabled {
                color: #ccc;
            }

            .active {
                color: white;
                background-color: rgb(16,45,40);
            }
            
            .item_num {
                text-align: center;
            }

            /* 独自 */
            .side_menu {
                flex: 1;
            }

            .main {
                flex: 3;
            }

            .aside {
                flex: 1; 
            }
            
            .search_style {
                color: white;
                background-color: rgb(16,45,40);
                line-height: 32px;
                border-radius: 16px;
                padding-left: 20px
            }
            
            .genre-link {
                display: block;
                color: black;
                font-weight: bold;
                background-image: url(img/structure/arrow.png);
                background-repeat: no-repeat;
                background-position: 20px center;
                text-decoration: none;
                line-height: 30px;
                padding-left: 40px;
            }

            .refine {
                text-align: center;
                color: white;
                background-color: red;
                line-height: 32px;
                width: 400px;
                border-radius: 16px;
                margin: 10px auto;
            }
            
            .product {
                height: 130px;
                display: flex;
                border-bottom: 1px solid #ccc;
            }
            
            .insert {
                font-size: 16px;
                background-color: orange;
                width: 130px;
                border-style: none;
                border-radius: 5px;
                margin: 6px 10px;
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
                flex: 1;
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
                display: flex;
                width: 100%;
            }

            .card {
                flex: 1;
                text-align: center;
                border: 1px solid #ccc;
            }

            .rank {
                font-weight: bold;
                text-align: center;
            }

            .card-header {
                color: white;
                background-color: rgb(16,45,40);
            }

            .card-body {
                padding: 10px;
                text-align: center;
                vertical-align: middle;
            }

            .card-img {
                height: 130px;
                padding: 10px;
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
        
        <ul class="no_style red tx-center p-0">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1 class="tx-center">商品紹介</h1>

        <!-- コンテナ開始 -->
        <div class="container mx-30">
            
            <!-- フレックス開始 -->
            <div class="flex">
                
                <!-- サイドメニュー開始 -->
                <div class="side_menu bg-white tx-center pd-10 m-6">
                    <!-- 検索機能 -->
                    <h2 class="m-0">商品検索</h2>
                    <div class="name_search pd-10">
                        <h3 class="tx-left search_style">商品名から</h3>
                        <form method="GET" action="./itemlist.php">
                            <input class="fz-14" type="text" name="name">
                            <input type="submit" id="search" value="検索">
                        </form>
                    </div>
                    <div class="genre_search pd-10">
                        <h3 class="tx-left search_style">ジャンルから</h3>
                        <ul class="no_style m-0">
                        <?php foreach($genres as $genre) { ?>
                            <li class="pd-6 tx-left bb-dot">
                                <a class="genre-link" 
                                href=<?php print itemlist_url(1, NEW_ARRIVAL, $genre['genre_id'], ''); ?>>
                                    <?php if ($genre['genre_id'] === 0) { ?>
                                        全て
                                    <?php } else { ?>
                                        <?php print entity_str($genre['genre_name']); ?>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- サイドメニュー終了 -->
                
                <!-- メイン開始 -->
                <div class="main bg-white pd-10 m-6">
                    <?php if (is_logined() === false) { ?>
                        <p class="fz-14 m-0 tx-center red">※カートに追加するには、<a href="./login.php">ログイン</a>が必要です</p>
                    <?php } ?>

                    <!--<h2>あなたへのおすすめ文具</h2>-->
                    
                    <!-- ソート機能 -->
                    <div class="tx-right">
                        <form method="GET" action="./itemlist.php" id="sort_form">
                            <input type="hidden" name="genre_id" value=<?php print $genre_id; ?>>
                            <input type="hidden" name="name" value=<?php print $name; ?>>
                            <select name="sort_key" class="fz-16 h-28 w-130" id="sort_select">
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
                    <?php if ($total_items > 0) { ?>
                        <p class="item_num">
                            「<?php  print $total_items; ?>件中 <?php print $start_num; ?>-<?php print $finish_num; ?>件目の商品」
                        </p>
                        <?php print_page_link($page_id, $sort_key, $genre_id, $name, $max_page); ?>
                    <?php } ?>
                    
                    <div class="refine m-6"><?php print entity_str($refine); ?></div>
                    <!--検索条件に一致する商品が無かった場合の表示-->
                    <?php if ($items === []) { ?>
                        <p class="tx-center">条件に一致する商品はありませんでした</p>
                    
                    <!--検索条件に一致する商品が有った場合の表示（初期表示）-->
                    <?php } else { ?>
                        <!--商品の繰り返し表示開始-->
                        <?php foreach ($items as $item) { ?>
                        <div class="product pd-20">
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
                                        <input type="submit" class="insert" value="カートに追加">
                                    </form>
                                    <?php } ?>
                                </p>
                            </div>
                        </div>
                        <?php } ?>
                        <!--商品の繰り返し表示終了-->
                    <?php } ?>
                    <!--検索条件に一致する商品が有った場合の表示終了-->

                    <!-- ページネーション -->
                    <?php if ($total_items > 0) { ?>
                        <p class="item_num">
                            「<?php  print $total_items; ?>件中 <?php print $start_num; ?>-<?php print $finish_num; ?>件目の商品」
                        </p>
                        <?php print_page_link($page_id, $sort_key, $genre_id, $name, $max_page); ?>
                    <?php } ?>

                </div>
                <!-- メイン終了 -->

                <!-- アサイド開始 -->
                <div class="aside bg-white tx-center pd-10 m-6">
                    <ul class="no_style pd-6">
                        <li class="my-10">
                            <a href="http://118.27.17.227/veggieshop/index.php">
                                野菜ショップへ
                                <br>
                                （※Bootstrap使用）
                            </a>
                        </li>
                        <li class="my-10">
                            <a href="https://codecamp.jp/">
                                <img src="<?php print(STRUCTURE_PATH .  "ad1.png"); ?>">
                            </a>
                            <p class="fz-14 m-0">お世話になった学習サイトです</p>
                        </li>
                        <li class="my-20">
                            <a href="https://codecamp.jp/">
                                <img src="<?php print(STRUCTURE_PATH .  "ad2.png"); ?>">
                            </a>
                            <p class="fz-14 m-0">素晴らしい講師がいらっしゃいます</p>
                        </li>
                    </ul>
                </div>
                <!-- アサイド終了 -->
                
            </div>
            <!-- フレックス終了 -->

            <!-- 人気ランキング開始 -->
            <div class="popular_item bg-white pd-10 m-6">
                <h2 class="mx-30 my-20">人気ランキング</h2>

                <div class="card-deck m-6">
                <?php foreach ($popular_items as $popular_item) { ?>
                    <div class="card m-6">
                        <div class="rank lh-32">  
                            <?php print $rank++ . ' 位'; ?>
                        </div>  
                        <div class="card-header lh-32">
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
                                    <input type="submit" class="insert" value="カートに追加">
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <!-- 人気ランキング終了 -->
        
        </div>
        <!-- コンテナ終了 -->

    </body>
</html>