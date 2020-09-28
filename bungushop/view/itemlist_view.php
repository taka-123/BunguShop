<?php header("X-FRAME-OPTIONS: SAMEORIGIN"); ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>一覧画面</title>
        <meta charset="UTF-8">
        <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
        <link href="./css/style.css" rel="stylesheet" type="text/css"/>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <link href="./css/itemlist.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="./js/itemlist.js"></script>
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined_view.php'; ?>
        
        <ul class="no_style red tx-center p-0">
        <?php foreach($errors as $error) { ?>
            <li>
                <?php print entity_str($error); ?>
            </li>
        <?php } ?>
        </ul>
        
        <h1 class="tx-center">商品紹介</h1>

        <!-- コンテナ開始 -->
        <div class="container">
            
            <menu class="bg-white tx-center pd-10">
                <!-- 検索機能 -->
                <h2 class="m-0">商品検索</h2>
                <div class="name_search pd-10">
                    <h3 class="tx-left search_style">商品名から</h3>
                    <form method="GET" action="./itemlist.php">
                        <input class="name fz-14" type="text" name="name">
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
            </menu>
            
            <main class="bg-white pd-10">
                <?php if (is_logined() === false) { ?>
                    <p class="attention fz-14 m-0 tx-center red">※カートに追加するには、<a href="./login.php">ログイン</a>が必要です</p>
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
                    <div class="product pd-10">
                        <div class="left">
                            <img class="productImg" src="<?php print entity_str(IMAGE_PATH . $item['item_img']); ?>">
                            <p class="comment_box"><?php print entity_str($item['comment']); ?></p>
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
                                    <select name="amount" class="add_amount">
                                    </select>
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
            </main>

            <aside class="bg-white tx-center pd-10">
                <ul class="no_style pd-6">
                    <li class="my-10">
                        <a href="http://118.27.17.227/veggieshop/index.php">
                            <img src="<?php print(STRUCTURE_PATH .  "ad1.png"); ?>">
                            <span>野菜ショップ</span>
                        </a>
                    </li>
                    <li class="my-20">
                        <a href="http://118.27.17.227/index.html">
                            <img src="<?php print(STRUCTURE_PATH .  "ad2.png"); ?>">
                            <span>
                            ポートフォリオ
                            <br>
                            トップページ
                            </span>
                        </a>
                    </li>
                </ul>
            </aside>
            
        </div>
        <!-- コンテナ終了 -->

        <!-- 人気ランキング開始 -->
        <div class="popular_item bg-white">
            <h2 class="mx-30 my-20">人気ランキング</h2>

            <div class="card-deck">
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
                        <p class="price">¥ <?php print number_format(entity_str($popular_item['price'])); ?></p>
                        <?php if ($popular_item['stock'] === 0) { ?>
                                <span class="sold_out">売り切れ</span>
                            <?php } else { ?>
                            <form method="POST">
                                <input type="hidden" name="sql_kind" value="cart">
                                <input type="hidden" name="item_id" value="<?php print entity_str($popular_item['item_id']); ?>">
                                <select name="amount" class="add_amount">
                                </select>
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
        
    </body>
</html>