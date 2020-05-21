<?php
// セッションスタート
session_start();
// ログインしていない場合、ログインページへリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// 正しいページ遷移でない場合（直接アクセス）、カートページへリダイレクト
elseif ($_SESSION['permission'] !== 'ok') {
    header('Location: cart.php');
    exit;
}


// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/common.php';

$img_dir = './img/';

$user_id = $_SESSION['user_id'];
$top_user_name = $_SESSION['user_name'];

// 初期化
$data = [];
$sub_total_list = [];
$sub_total = 0;
$total = 0;
$total_amount = 0;
$_SESSION['permission'] = ''; // ページ遷移判断リセット


// DB接続
try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // データの取得開始
    try {
        // テーブルの結合参照(ステータス公開の商品のみ全て表示)
        $sql = 'SELECT bungu_item_master.item_id, name, price, item_img, user_id, amount 
        FROM bungu_item_master INNER JOIN bungu_carts 
        ON bungu_item_master.item_id = bungu_carts.item_id AND amount > 0 AND user_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $data[] = $row;
        }
    } catch (PDOException $e) {
        throw $e;
    }
    // データの取得終了
    
    // カートの中身が空だった場合、
    if (empty($data)) {
        // ログインページへリダイレクト
        header('Location: login.php');
        exit;
    }
    
    // 小計を定義・表示
    foreach ($data as $value) {
        $sub_total = entity_str($value['price'] * $value['amount']);
    // 小計をlist配列に格納
    $sub_total_list[] = $sub_total;
    }
    // 合計金額を定義
    foreach ($sub_total_list as $sub_total) {
        $total += $sub_total; 
    }

    // $after_stock = $stock - $amount;
    // トランザクション開始
    $dbh->beginTransaction();
    try {
        // 購入商品毎に、SQL更新
        foreach($data as $value) {
            // 在庫数の更新
            $sql = 'UPDATE bungu_item_stock 
            SET stock = stock - ? 
            WHERE item_id = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $value['amount'], PDO::PARAM_STR);
            $stmt->bindValue(2, $value['item_id'], PDO::PARAM_STR);
            $stmt->execute();
        }
        // 購入商品毎の更新処理終了
            // 同ユーザのカート情報全削除
            $sql = 'DELETE FROM bungu_carts 
            WHERE user_id =?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
            $stmt->execute();
        // コミット処理
        $dbh->commit();
    } catch (PDOException $e) {
        // ロールバック処理
        $dbh->rollback();
        throw $e;
    }
    
} catch (PDOException $e) {
    $err_msg[] = '接続できませんでした。理由:'.$e->getMessage();
}
// DB接続終了

?>

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
        <header>
            <a class="logo" href="./itemlist.php">
                <img src="img/structure/logo1.png">
            </a>
            <div class="welcome">
                <p>ようこそ<a href=./user_info.php><?php print entity_str($top_user_name); ?>さん</a>！</p>
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
        
        <h1>購入完了</h1>
        <p class="finish">
            ありがとうございます！！
            <br>
            注文が確定されました！
        </p>
        
        <p class="total">
            合計金額: <span>¥ <?php print number_format($total); ?></span>
        </p>

        <form method="POST">
        <!--商品の繰り返し表示開始-->
        <?php foreach ($data as $value) { ?>
            <div class="product">
                <div class="left">
                    <img src="<?php print entity_str($img_dir . $value['item_img']) ?>">
                </div>
                <div class="right">
                    <p>商品名: <?php print entity_str($value['name']); ?></p>
                    <p>
                        <span class="price">
                            ¥ <?php print number_format(entity_str($value['price'])); ?>
                        </span>
                        <span>
                            ×
                        </span>
                        <span>
                            <?php print entity_str($value['amount']); ?> 個
                        </span>
                    </p>
                    <p>
                        =
                        <span class="sub_total">
                            <!--小計を表示-->
                            ¥ <?php print number_format(entity_str($value['price'] * $value['amount'])) ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php } ?>
        <!--商品の繰り返し表示終了-->
        
                


    </body>
</html>