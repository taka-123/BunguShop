<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BUNGU SHOP 商品管理ページ</title>
        <link href="./css/header_logined.css" rel="stylesheet" type="text/css"/>
        <style>
            .register, .change {
                border-top: solid 1px;
            }
            
            .error {
                list-style: none;
                color: red;
                padding: 0;
            }
            
            label {
                margin: 5px 10px;
                width: 120px;
                display: inline-block;
                text-align-last: justify;
            }
            
            caption {
                text-align: left;
                font-size: 1.2em;
                padding: 10px 0;
            }
            
            input#insert {
                display: block;
                padding: 3px 15px;
                margin: 10px;
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
            
            td {
                height: 100px;
                text-align: center;
                padding: 5px;
            }
            
            td img {
                height: 100%;
            }
            
            .stock_change {
                width: 45px;
                text-align: right;
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
        
        <h1>BUNGU SHOP 商品管理ページ</h1>
        <p><a href="./admin_user.php">ユーザ管理ページへ</a></p>
        
        <div class="register">
            <h2>商品新規登録</h2>
            <form method="POST" enctype="multipart/form-data">
                <div>
                    <label for="name">商 品 名:</label>
                    <input type="text" name="name" id="name">
                </div>
                <div>
                    <label for="genre">ジャンル:</label>
                    <select name="genre_id" id="genre">
                    <?php foreach($genres as $genre) { ?>
                        <option value="<?php print entity_str($genre['genre_id']); ?>"><?php print entity_str($genre['genre_name']); ?></option>
                    <?php } ?>  
    
                    </select>
                </div>
                <div>
                    <label for="price">値　　段:</label>
                    <input type="text" name="price" id="price">
                </div>
                <div>
                    <label for="stock">在 庫 数:</label>
                    <input type="text" name="stock" id="stock">
                </div>
                <div>
                    <label for="item_img">商品画像:</label>
                    <input type="file" name="item_img" id="item_img">
                </div>
                <div>
                    <label for="comment">コ メ ン ト:</label>
                    <textarea name="comment" id="comment"></textarea>
                </div>
                <div>
                    <label for="status">公開ステータス:</label>
                    <select name="status" id="status">
                        <option value=0>非公開</option>
                        <option value=1>公開</option>
                    </select>
                </div>
                <div>
                    <input type="hidden" name="sql_kind" value="insert">
                    <input type="submit" id="insert" value="商品を追加する">
                </div>
            </form>   
        </div>
        
        <div class="change">
            <h2>商品情報変更</h2>
            <table>
                <caption>商品情報の一覧</caption>
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>ジャンル</th>
                    <th>値段</th>
                    <th>在庫数</th>
                    <th>公開ステータス</th>
                    <th>操作</th>
                </tr>
                <!--商品の繰り返し表示開始-->
                <?php foreach ($items as $item) { ?>
                <tr>
                    <!--商品画像-->
                    <td>
                        <img src="<?php print entity_str(IMAGE_PATH . $item['item_img']) ?>">
                    </td>
                    <!--商品名-->
                    <td>
                        <?php print entity_str($item['name']); ?>
                    </td>
                    <!--ジャンル-->
                    <td>
                        <?php print entity_str($item['genre_name']); ?>
                    </td>
                    <!--値段-->
                    <td>
                        <?php print entity_str($item['price']); ?>円
                    </td>
                    <!--在庫数-->
                    <form method="POST">
                        <td>
                            <input class="stock_change" type="text" name="stock" value="<?php print entity_str($item['stock']); ?>">個
                            <input type="hidden" name="sql_kind" value="stock_update">
                            <input type="hidden" name="item_id" value="<?php print entity_str($item['item_id']); ?>">
                            <input type="submit" value="変更">                        
                        </td>
                    </form>
                    <!--公開ステータス-->
                    <form method="post">
                        <td>
                            <input type="hidden" name="sql_kind" value="status_change">
                            <input type="hidden" name="item_id" value="<?php print entity_str($item['item_id']); ?>">
                        <!--現在、公開(1)の場合-->
                        <?php if ($item['status'] === 1) { ?>
                            <input type="submit" value="公開 → 非公開">
                            <input type="hidden" name="status" value="0">
                        <!--現在、非公開(0)の場合-->
                        <?php } else { ?>
                            <input type="submit" value="非公開 → 公開">
                            <input type="hidden" name="status" value="1">
                        <?php } ?>
                        </td>
                    </form>
                    <!--削除-->
                    <form method="POST">
                        <td>
                            <input type="submit" value="削除">
                            <input type="hidden" name="sql_kind" value="delete">
                            <input type="hidden" name="item_id" value="<?php print entity_str($item['item_id']); ?>">
                        </td>
                    </form>
                </tr>
                <?php } ?>
                <!--商品の繰り返し表示終了-->
            </table>        
        </div>
    </body>
</html>
