<?php
require_once './conf/const.php';
require_once MODEL_PATH . 'common.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';

session_start();

// ログインしていない場合、ログインページへ
if (is_logined() === false) {
    header('Location: ' . LOGIN_URL);
    exit;
}

$img_dir = './img/';

$user_id = $_SESSION['user_id'];
$top_user_name = $_SESSION['user_name'];

// 初期化
$refine = '';
$total_amount = 0;
$before_amount = 0;
$errors = [];
$data = [];

// 正規表現
$non_num = '/[^0-9]/';  // 「半角数字」以外を含む
$zero_start = '/\A[0][0-9]+\z/'; // 0から始まる数字 08,023,006など

// ジャンル定義
$genres = array("全て", 
                "ペン・マーカー", 
                "鉛筆・シャーペン",
                "消しゴム",
                "ノート",
                "その他");


// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // カート購入予定数取得
    try {
        $sql = 'SELECT amount FROM bungu_carts WHERE user_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $amount_list[] = $row['amount'];
        }
    } catch (PDOException $e) {
        throw $e;
    }
    // 合計購入予定数量を計算
    if (isset($amount_list)) {
        foreach ($amount_list as $each_amount) {
            $total_amount += $each_amount;
        }
    }
    
    try {
        // テーブルの結合参照(ステータス公開の商品のみ全て表示)
        $sql = 'SELECT bungu_item_master.item_id, name, genre, price, item_img, comment, stock 
        FROM bungu_item_master INNER JOIN bungu_item_stock 
        ON bungu_item_master.item_id = bungu_item_stock.item_id AND status = 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
    }
    // データの取得終了
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql_kind = get_post_data('sql_kind');
        
        // 「検索」時の処理
        if ($sql_kind === 'serch') {
            $name = get_post_data('name');
            $genre = get_post_data('genre');
            

            // 不正入力対処
            if (preg_match('/\A[0-8]\z/', $genre) !== 1 ) {
                $errors[] = '「ジャンル」は選択肢の中から正しく選んでください';
            }        
            
            // 上記エラーに該当しない場合、
            if (count($errors) === 0) {
                
                
                // A.ジャンル全て 且つ 名前指定無し
                if ($genre === "0" && $name === '') {
                    $refine = '[検索条件] 全商品';
                    try {
                        // テーブルの結合参照(検索結果表示)
                        $sql = 'SELECT bungu_item_master.item_id, name, genre, price, item_img, comment, stock 
                        FROM bungu_item_master INNER JOIN bungu_item_stock 
                        ON bungu_item_master.item_id = bungu_item_stock.item_id 
                        AND status = 1';
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = $row;
                        }
                    } catch (PDOException $e) {
                        throw $e;
                    }
                }  
                // Aパターン終了
                
                // B.ジャンル全て 且つ 名前指定有り
                elseif ($genre === "0" && $name !== '') {
                    $refine = '[検索条件] 「全ジャンル」から、名前に『'.$name.'』を含む商品';
                    try {
                        // テーブルの結合参照(検索結果表示)
                        $sql = 'SELECT bungu_item_master.item_id, name, genre, price, item_img, comment, stock 
                        FROM bungu_item_master INNER JOIN bungu_item_stock 
                        ON bungu_item_master.item_id = bungu_item_stock.item_id 
                        AND status = 1
                        AND name LIKE ?';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindValue(1, "%".$name."%", PDO::PARAM_STR);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = $row;
                        }
                    } catch (PDOException $e) {
                        throw $e;
                    }
                }
                // Bパターン終了
                
                // C.ジャンル全て以外 且つ 名前指定無し
                elseif ($genre !== "0" && $name === '') {
                    $refine = '[検索条件] 「'.$genres[$genre].'」ジャンルの商品全て';
                    try {
                        // テーブルの結合参照(検索結果表示)
                        $sql = 'SELECT bungu_item_master.item_id, name, genre, price, item_img, comment, stock 
                        FROM bungu_item_master INNER JOIN bungu_item_stock 
                        ON bungu_item_master.item_id = bungu_item_stock.item_id 
                        AND status = 1
                        AND genre = ?';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindValue(1, (int)$genre, PDO::PARAM_INT);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = $row;
                        }
                    } catch (PDOException $e) {
                        throw $e;
                    }
                }
                // Cパターン終了
                
                // D.ジャンル全て以外 且つ 名前指定有り
                elseif ($genre !== "0" && $name !== '') {
                    $refine = '[検索条件] 「'.$genres[$genre].'」ジャンルから、名前に『'.$name.'』を含む商品';
                    try {
                        // テーブルの結合参照(検索結果表示)
                        $sql = 'SELECT bungu_item_master.item_id, name, genre, price, item_img, comment, stock 
                        FROM bungu_item_master INNER JOIN bungu_item_stock 
                        ON bungu_item_master.item_id = bungu_item_stock.item_id 
                        AND status = 1
                        AND name LIKE ?
                        AND genre = ?';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindValue(1, "%".$name."%", PDO::PARAM_STR);
                        $stmt->bindValue(2, (int)$genre, PDO::PARAM_INT);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $data = [];
                        foreach ($rows as $row) {
                            $data[] = $row;
                        }
                    } catch (PDOException $e) {
                        throw $e;
                    }
                }
                // Dパターン終了
            
            }
            // エラーがない場合の処理終了   
                
        }
        // 「検索」時の処理終了
        
        // 「カートに追加」時の処理
        if ($sql_kind === 'cart') {
        
            $item_id = get_post_data('item_id');
            $add_amount = get_post_data('add_amount');
            
            // 「追加数量」について
            // 未入力の場合
            if ($add_amount === '') {
                $errors[] = '「追加数量」を入力してください'; 
            }
            // 半角数字以外を入力した場合
            elseif (preg_match($non_num, $add_amount)) {
                $errors[] = '「追加数量」は半角数字で入力してください';
            }
            // 複数桁の数字で頭が０の場合、
            elseif (preg_match($zero_start, $add_amount)) {
                $errors[] = '「追加数量」は複数桁の場合、頭は0以外の半角数字にしてください';
            }
            // 10,000個より多い場合
            elseif ($add_amount > 10000) {
                $errors[] = '「追加数量」は1万個以下にしてください';
            }
            
            // 上記エラーに１つも該当しない場合、
            if (count($errors) === 0) {
                
                try {
                    // カートに追加する商品の情報を取得
                    $sql = 'SELECT amount FROM bungu_carts 
                    WHERE user_id = ? AND item_id = ?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
                    $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        $before_amount = $row['amount'];
                    }
                } catch (PDOException $e) {
                    throw $e;
                }
                
                // 既にその商品がカートに入っている場合、
                if ($before_amount > 0) {
                    
                    $after_amount = $before_amount + (int)$add_amount;
                    
                    try {
                        // カートの数量を更新
                        $sql = 'UPDATE bungu_carts 
                        SET amount = ?, update_datetime = now() 
                        WHERE user_id = ? AND item_id = ?';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindValue(1, $after_amount, PDO::PARAM_INT);
                        $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
                        $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        throw $e;
                    }
                    echo 'カート内の数量を更新しました';
                    // データの更新終了
                }
                
                // その商品がカートに入っていない場合、
                else {
                    try {
                        // カートに情報を登録
                        $sql = 'INSERT INTO bungu_carts 
                        (user_id, item_id, amount, create_datetime, update_datetime) 
                        VALUE (?, ?, ?, now(), now())';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
                        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
                        $stmt->bindValue(3, $add_amount, PDO::PARAM_INT);
                        $stmt->execute();
                    } catch (PDOException $e) {
                        throw $e;
                    }
                    echo 'カートに追加しました';
                    
                    // データの追加終了
                }
            
            // カート横の値を追加分増加
            $total_amount += $add_amount;
            }
            // エラーが無かった場合の処理終了
                
        }
        // 「カートに追加」時の処理終了
    
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}
// DB接続終了

?>

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
                <p>ようこそ、<a href=./user_info.php><?php print entity_str($top_user_name); ?>さん</a>！</p>
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
                    <select name="genre" id="genre">
                    <?php foreach($genres as $key => $genre) { ?>
                        <option value="<?php print $key; ?>"><?php print $genre; ?></option>
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
        <?php if ($data === []) { ?>
            <p class="empty">条件に一致する商品はありませんでした</p>
        
        <!--検索条件に一致する商品が有った場合の表示（初期表示）-->
        <?php } else { ?>
            <!--商品の繰り返し表示開始-->
            <?php foreach ($data as $value) { ?>
            <div class="product">
                <div class="left">
                    <img src="<?php print entity_str($img_dir . $value['item_img']); ?>" title="<?php print entity_str($value['comment']); ?>">
                </div>
                <div class="right">
                    <p>商品名: <?php print entity_str($value['name']); ?></p>
                    <p>ジャンル: <?php print entity_str($genres[$value['genre']]); ?></p>
                    <p>¥ <?php print entity_str($value['price']); ?></p>
                    <p>
                        <?php if ($value['stock'] === 0) { ?>
                            <span class="sold_out">売り切れ</span>
                        <?php } else { ?>
                        <form method="POST">
                            <input type="hidden" name="sql_kind" value="cart">
                            <input type="hidden" name="item_id" value="<?php print entity_str($value['item_id']); ?>">
                            <input class="add_amount" type="text" name="add_amount" value=1>個
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